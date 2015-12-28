<?php
include('../db-connection.php');
/*
 * Should it push or pull?
 * ?method=push or ?method=pull
 */
$method = $_POST['method'];
/*
 * What type of data
 * ?type=timed or ?type=inVideo
 */
$type = $_POST['type'];

/*
 * If it's a comment or video we need these to
 * ?videoID=videoID and
 */
$videoID = $_POST['videoID'];

if ($method == 'pull'):

	$query = $db->prepare('
		SELECT
			comments.id,
			comments.comment,
			comments.videoTime,
			comments.created,
			comments.videoID,
			comments.type,
			comments.location,
			users.userName,
			users.userHandle,
			users.userImageURL
		FROM
			comments,
			users
		WHERE
			comments.videoID = ? AND
			comments.type = ?
	');

	// voeg parameters toe aan je statement
	$params = array($videoID, $type);
	// voer de statement met de parameters uit
	$query->execute($params);
	// sla het resultaat op in een array
	$comments = $query->fetchAll(PDO::FETCH_ASSOC);

	// vertaal het resultaat naar een json object
	$json = json_encode($comments, JSON_PRETTY_PRINT);
	// toon het json object
	echo $json;

elseif ($method == 'push'):
	$comment = $_POST['comment'];
	$videoTime = $_POST['videoTime'];
	$userID = $_POST['userID'];
	$location = $_POST['location'];

	if (empty($comment) || empty($videoID) || empty($videoTime) || empty($userID) || empty($type)):
		echo 'nodata';
		return;
	endif;
	if ($type == 'inVideo' && empty($location)):
		echo 'no location';
		return;
	endif;
	$sql = "INSERT IGNORE INTO comments (comment, videoID, videoTime, userID, type, location)
VALUES ('$comment', '$videoID', '$videoTime', '$userID', '$type', '$location')";
// use exec() because no results are returned
	$db->exec($sql);

	echo 'pushed';
else:
	echo 'error';
endif;