<?php
require_once('class.youtube.php');
$ytID = $_GET['ytID'];
$thisVideo = new youtube($ytID);

if ($thisVideo->validateID()){
	$videoInformation = $thisVideo->videoInfo();
	$videoTitle       = $videoInformation->title;
	$videoAuthor      = $videoInformation->author_name;
	$videoID          = $videoInformation->id;
	//print_r($videoInformation);

	$pageTitle        = $videoTitle;
	$pageSubtitle     = 'By '.$videoAuthor;
}

include_once('partials/header.php');

include_once('db-connection.php');
// bereid het SQL statement voor
$query = $db->prepare('
		SELECT
			comments.comment,
			comments.videoTime,
			comments.created,
			comments.videoID,
			users.userName,
			users.userImageURL
		FROM
			comments,
			users
		WHERE
			comments.videoID = "'.$videoID.'"

	');

// voeg parameters toe aan je statement
$params = array();
// voer de statement met de parameters uit
$query->execute($params);
// sla het resultaat op in een array
$comments = $query->fetchAll(PDO::FETCH_ASSOC);
echo '<pre>';
print_r($result);
?>
</pre>

	<div class="container">
		<div class="col-lg-10 col-lg-offset-1">
			<div class="video-container" id="videoPlayer">
				<script type="text/javascript">var videoID = '<?= $videoID; ?>';</script>
				<div id="player"></div>
				<div id="commentOverlay">
					<div class="inlineCommentForm" data-disabled="true">
						<form id="inlineCommentForm">
							<input type="text" name="comment" maxlength="42" class="form-control" cols="20" rows="3">
							<input type="hidden" name="userID" value="<?= $twitterid; ?>">
							<input type="hidden" name="videoID" value="<?= $videoID; ?>">
							<input type="submit" value="Place comment" class="form-control" disabled>
						</form>
						<button id="closeComments">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<section id="commentSection" class="jumbotron">
		<div class="container">
			<div id="comments" class="col-lg-10 col-lg-offset-1">
				<?php
					$i = 0;
					foreach ($comments as $comment):
					$i++;
				?>
					<div id="comment-<?= $i; ?>" class="comment" data-time="<?= $comment['videoTime']; ?>">
						<figure class="userImage">
							<img src="<?= $comment['userImageURL']; ?>" alt="<?= $comment['userName']; ?>">
						</figure>
						<span class="commentText"><?= $comment['comment']; ?></span>
					</div>
				<?php
					endforeach;
				?>
			</div>
		</div>
		<div class="container">
			<div class="col-sm-4">
				<h3>Go comment!</h3>
			</div>
			<div class="col-sm-8">
				<form id="commentForm">
					<label>Your comment</label>
					<input type="text" name="comment" class="form-control">
					<input type="hidden" name="userID" value="<?= $twitterid; ?>">
					<input type="hidden" name="videoID" value="<?= $videoID; ?>">
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</section>
	<script src="assets/js/youtubePlayer.js"></script>
	<script src="assets/js/inVideoComments.js"></script>
<?php

include_once('partials/footer.php');