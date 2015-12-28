var videoPlayer;
var commentForm;
inVideoComments = {
	setup: function () {
		var playerElement = $('#videoPlayer');
		videoPlayer = {
			el: playerElement,
			id: playerElement.attr('id'),
			width: playerElement.width(),
			height: playerElement.outerHeight(),
			offset: {
				x: playerElement.offset().left,
				y: playerElement.offset().top
			}
		};

		var commentElement = $('.inlineCommentForm');
		commentForm = {
			el: commentElement,
			enabled: 'false'
		};

		inVideoComments.listen();
		inVideoComments.watchCommentForm();
	},
	listen: function () {
		console.log('Listening for click on videoplayer');
		document.getElementById(videoPlayer.id).addEventListener("click", inVideoComments.processPosition);

	},
	unlisten: function() {
		console.log('Stopped listening for click on videoplayer');
		document.getElementById(videoPlayer.id).removeEventListener("click", inVideoComments.processPosition);
	},
	processPosition: function(e) {
		var clickPosition = inVideoComments.cursorPos(e);

		var positionInPlayer = {
			x: clickPosition.x - videoPlayer.offset.x,
			y: clickPosition.y - videoPlayer.offset.y
		};

		var percentageLocation = inVideoComments.positionPXtoPercentage(positionInPlayer);
		console.log('Checking if form is disabled');
		console.log(playerStarted);
		if (commentForm.el.data('disabled') && playerStarted) {
			console.log('Form is not disabled, create it');
			inVideoComments.createCommentForm(percentageLocation);
		}

	},
	cursorPos: function (e) {
		var cursorX = e.clientX;
		var cursorY = e.clientY;

		cursorX += $(window).scrollLeft();
		cursorY += $(window).scrollTop();

		return {x: cursorX, y: cursorY};
	},
	positionPXtoPercentage: function(positionInPlayer) {
		var x = positionInPlayer.x * 100 / videoPlayer.width;
		var y = positionInPlayer.y * 100 / videoPlayer.height;
		return {x: x, y: y};
	},
	commentData: {
		method	: 'push',
		type	: 'inVideo',
		videoID : videoID
	},
	createCommentForm: function(positionInPlayer) {
		console.log('Creating comment form');
		inVideoComments.unlisten();
		//youtubePlayer.loop(4, true);

		commentForm.el.find('input[name="comment"]').val('');
		commentForm.el.css({
			display:   'block',
			left: 	   positionInPlayer.x + '%',
			top: 	   positionInPlayer.y + '%',
			'z-index': '10'
		}).find('input:first-child').focus();

		commentForm.el.attr('data-disabled', 'false');

		inVideoComments.commentData.location  = JSON.stringify(positionInPlayer);
		inVideoComments.commentData.videoTime = youtubePlayer.playerTime().current;

	},
	watchCommentForm: function() {
		console.log('Watching comment form');
		commentForm.el.find('input:first-child').bind("keyup", function() {
			var comment = commentForm.el.find('input:first-child').val();
			if (comment.length > 0){
				commentForm.el.find('input[type="submit"]').removeAttr('disabled');
			}
		});
		commentForm.el.submit(function(e) {
			e.preventDefault();

			inVideoComments.commentData.comment = $(this).find('input[name="comment"]').val();
			inVideoComments.commentData.userID  = $(this).find('input[name="userID"]').val();

			inVideoComments.postCommentForm();
		});
		commentForm.enabled = 'true';
		$('#closeComments').click(inVideoComments.closeCommentForm);
	},
	postCommentForm: function() {
		var formData = inVideoComments.commentData;

		$.post('/api/index.php', formData, function(data, status){
			if (status == 'success'){
				inVideoComments.closeCommentForm();
				inVideoComments.renderComments([formData]);
			}
		});
	},
	closeCommentForm: function() {
		console.log('Closing comment form');
		commentForm.el.removeAttr('style');
		commentForm.el.attr('data-disabled', 'true');
		setTimeout(function() {
			inVideoComments.listen();
		}, 100);
	},
	loadComments: function(videoID) {
		var request = {
			method  : 'pull',
			videoID : videoID,
			type    : 'inVideo'
		};
		$.post('/api/index.php', request, function(data, status){
			if (status == 'success'){
				var comments = $.parseJSON(data);

				inVideoComments.renderComments(comments);
				inVideoComments.comments = comments;
			}
		});
	},
	comments: {},
	renderComments: function(comments) {
		$(comments).each(function() {
			var commentData = $(this)[0];
			var offset = JSON.parse(commentData.location);
			$('#commentOverlay').append('<article class="comment" id="comment-'+commentData.id+'" style="top:'+ offset.y +'%;left:'+ offset.x+ '%;";" data-showtime="'+commentData.videoTime+'"><p>'+commentData.comment+'</p><small>By @<a href="//twitter.com/'+commentData.userHandle+'">'+commentData.userHandle+'</a></small></article>');
		});
	},
	timeComments: function(event) {
		if (event.data == YT.PlayerState.PLAYING) {

			youCommentTimer = setInterval(function () {
				var currentPlaytime = youtubePlayer.playerTime().current;
				for (var i = 0; i < inVideoComments.comments.length; i++){
					var thisShowtime = inVideoComments.comments[i].videoTime;
					var thisID = '#comment-'+inVideoComments.comments[i].id;
					if (thisShowtime >= currentPlaytime && thisShowtime <= currentPlaytime + 3){
						$(thisID).fadeIn('fast');
						console.log(thisID + ' is showing');
					} else {
						$(thisID).fadeOut('fast');
					}
					//console.log(thisID);
				}
			}, 1000);

		} else {
			clearTimeout(youCommentTimer);
		}
	}
};

inVideoComments.setup();