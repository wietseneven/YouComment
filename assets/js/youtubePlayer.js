var loopTimer;
var looping = false;
var playerStarted = false;
var youtubePlayer = {
	player: {},
	setup: function() {
		var tag = document.createElement('script');
		tag.src = "https://www.youtube.com/iframe_api";
		var firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	},
	createPlayer: function() {
		youtubePlayer.player = new YT.Player('player', {
			videoId: videoID,
			//playerVars: {
			//	'controls': 0,
			//	'modestbranding': 1,
			//	'rel': 0,
			//	'showinfo': 0
			//},
			events: {
				'onReady': youtubePlayer.playerState,
				'onStateChange': youtubePlayer.onPlayerStateChange
			}
		});
	},
	playerState: function() {
		return youtubePlayer.player.B.playerState;
		//initComments();
	},
	playerTime: function() {
		return {current: youtubePlayer.player.getCurrentTime()};
	},
	onPlayerStateChange: function(event) {
		inVideoComments.timeComments(event);
	},
	loop: function(seconds){
		if (seconds !== 0 && looping === false) {
			console.log('Starting loop');

			looping = true;

			var startTime = youtubePlayer.playerTime().current;
			var currentTime = youtubePlayer.playerTime().current;
			console.log('Starttime is: ' + startTime);

			if (youtubePlayer.playerState() == YT.PlayerState.PLAYING) {
				console.log('Is playing, so start interval function')
				loopTimer = setInterval(function () {

					youtubePlayer.player.seekTo(startTime);
					console.log('Going back to starttime');

					console.log('looping');
				}, seconds * 1000);

			} else {
				clearTimeout(loopTimer);
				looping = false;
			}
		} else {
			clearTimeout(loopTimer);
			looping = false;
		}
	}
};
youtubePlayer.setup();
function onYouTubeIframeAPIReady() {
	console.log('Calling create player');
	youtubePlayer.createPlayer();
	inVideoComments.loadComments(videoID);
}

$('#commentOverlay').click(function() {
	var playerState = youtubePlayer.playerState();

	if (playerState == 5 || playerState == 2  || playerState == -1) {
		youtubePlayer.player.playVideo();
		setTimeout(function() {
			playerStarted = true;
		},1);
	} else {
		//console.log(youtubePlayer.playerTime());

	//	youtubePlayer.player.pauseVideo();
	}
});