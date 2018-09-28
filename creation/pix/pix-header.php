<?php
	

$cookie_name = "edenuser";
if (isset($_POST['myUserId'])) {
	session_start();
	$latestMatchingUserID = $_POST['myUserId'];
	
	$_SESSION['userID'] = $latestMatchingUserID;
	// $currentUserID = '1';
	$currentUserID = $latestMatchingUserID;
	
	$cookie_value = $latestMatchingUserID;
	setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
	
}
else if(!isset($_COOKIE[$cookie_name])) {
	header('Location: ../login.php');
}
else {
    $currentUserID = $_COOKIE[$cookie_name];
}
	
	
?>