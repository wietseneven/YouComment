var playerTime;

$('#commentForm').submit(function(e){
	e.preventDefault();
	var commentData = $(this).serialize();
	console.log(playerTime);
	$.get('db-new-comment.php?' + commentData + '&videoTime=' + playerTime );
});

function initComments() {
	var playerTotalTime = player.getDuration();
	var commentsWidth = $('#comments').width();

	$('#comments .comment').each(function() {
		var thisTime = $(this).data('time');
		var commentOffset = commentsWidth / playerTotalTime * thisTime;

		$(this).css({'left':commentOffset});
	});
}