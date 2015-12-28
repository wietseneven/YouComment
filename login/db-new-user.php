<?php
//start session
session_start();

// Include config file and twitter PHP Library by Abraham Williams (abraham@abrah.am)
include_once("config.php");
include_once("inc/twitteroauth.php");
if(isset($_SESSION['status']) && $_SESSION['status']=='verified') {
	$userDetails = $_SESSION['userDetails'];

	include('../db-connection.php');
	$userID = $userDetails->id;
	$userName = $userDetails->name;
	$userHandle = $userDetails->screen_name;
	$userLocation = $userDetails->location;
	$userFollowerCount = $userDetails->followers_count;
	$userImageURL = $userDetails->profile_image_url;
	$userDescription = $userDetails->description;

	$sql = "INSERT INTO users (userID, userName, userHandle, userLocation, userFollowerCount, userImageURL, userDescription)
		VALUES ('$userID', '$userName', '$userHandle', '$userLocation', '$userFollowerCount', '$userImageURL', '$userDescription')";
// use exec() because no results are returned
	$db->exec($sql);
}
header('Location: ./index.php');