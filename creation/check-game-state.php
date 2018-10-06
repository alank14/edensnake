<?php
include('config.php');
include('functions.php');
$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);

header('Content-Type: application/json');

$sql = "SELECT * FROM quiz_admin;";
	
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

while($row = mysqli_fetch_array($result)){
	echo '{';
	echo '"quiz_question_id": "' . $row['quiz_question_id'] . '"';
	echo ',';
	echo '"quiz_question_state": "' . $row['quiz_question_state'] . '"';
	echo '}';
}

dbClose($conn);
?>