<?php
include('db-connection.php');
$comment = $_GET['comment'];
$videoID = $_GET['videoID'];
$videoTime = $_GET['videoTime'];
$userID = $_GET['userID'];
$userName = $_GET['screenname'];

$sql = "INSERT IGNORE INTO comments (comment, videoID, videoTime, userID)
VALUES ('$comment', '$videoID', '$videoTime', '$userID')";
// use exec() because no results are returned
$db->exec($sql);

return true;