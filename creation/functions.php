<?php
	
function dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb) {
	$conn = mysqli_connect($sqlhost, $sqluser, $sqlpass, $sqldb);
	if (!$conn) {
		die("Could not connect to mysql");
	}
	mysqli_query($conn,"SET NAMES 'utf8'");

	return $conn;
}

function dbClose($conn) {
	mysqli_close($conn);
}

function updateUserAnswer($currentUserID,$rday) {
    global $sqlhost, $sqluser, $sqlpass, $sqldb, $conn;
	$sql2 = 'UPDATE user_riddles SET day_' . $rday . '_proposed_riddle_id=' . $rday . ' WHERE user_id=' . $currentUserID . ';';
	$result = mysqli_query($conn,$sql2) or die(mysqli_error($conn));
}

function updateNewDay($currentUserID,$rday) {
    global $sqlhost, $sqluser, $sqlpass, $sqldb, $conn, $userRiddleArray;
	$sql2 = 'UPDATE user_riddles SET next_day=' . $rday . ' WHERE user_id=' . $currentUserID . ';';
	$result = mysqli_query($conn,$sql2) or die(mysqli_error($conn));
}



?>
