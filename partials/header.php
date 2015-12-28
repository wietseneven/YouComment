<?php
//start session
session_start();

//just simple session reset on logout click
if($_GET["reset"]==1)
{
	session_destroy();
	header('Location: ./index.php');
}

// Include config file and twitter PHP Library by Abraham Williams (abraham@abrah.am)
include_once("login/config.php");
include_once("login/inc/twitteroauth.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags always come first -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">

	<title><?= $pageTitle ?></title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/base.css">
	<script src="assets/js/libs/jquery-2.1.4.min.js"></script>
</head>
<body>
<div class="jumbotron">
	<div class="container">
		<h1 class="display-3"><?= $pageTitle ?></h1>
		<p><?= $pageSubtitle ?></p>
	</div>
</div>
<?php
if(isset($_SESSION['status']) && $_SESSION['status']=='verified') {
	$screenname 		= $_SESSION['request_vars']['screen_name'];
	$twitterid 			= $_SESSION['request_vars']['user_id'];
	$userImageURL       = $_SESSION['userDetails']->profile_image_url;
	$oauth_token 		= $_SESSION['request_vars']['oauth_token'];
	$oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
//	print_r($_SESSION);
}