<?php
// https://edensnake.com/creation/scoreboard.php

	header('Content-Type: text/html; charset=utf-8');
	include('config.php');
	include('functions.php');



	echo "<!DOCTYPE html>\n";
	echo "<html lang='en'><head><title>Scoreboard</title>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
	echo '<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">' . "\n";
	echo '<link rel="stylesheet" href="edensnake.css">' . "\n";
	echo '<link rel="icon" type="image/png" href="resources/favicon.png">' . "\n";
	echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
	echo '</head><body>' . "\n";


	echo '<h1>Scoreboard</h1>' . "\n";


	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);

	$userScoreArray = array();
	$userNameArray = array();

/*
		for ($stall=0;$stall<1000;$stall++) {
		// stalling here
	}
	*/
	sleep(3);

	$sql = "SELECT 
				user_id
				, day_1_proposed_riddle_id
				, day_2_proposed_riddle_id
				, day_3_proposed_riddle_id
				, day_4_proposed_riddle_id
				, day_5_proposed_riddle_id
				, day_6_proposed_riddle_id
				, day_7_proposed_riddle_id
			FROM 
				user_riddles
				;";
	
	
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

	while($row = mysqli_fetch_array($result)){
		for ($i=1; $i<8; $i++) {
			if ($row['day_' . $i . '_proposed_riddle_id'] != '') {
				$userScoreArray[$row['user_id']] += 10;
			}
		}
	}

	$sql = "SELECT 
				users.id
				, users.first_name
				, users.last_name
			FROM 
				users
				;";
	
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

	while($row = mysqli_fetch_array($result)){
		$userNameArray[$row['id']] = $row['first_name'] . ' ' . $row['last_name'];
	}

	$sql = "SELECT 
				user_quiz_questions.user_id
				, user_quiz_questions.answer_score
			FROM 
				user_quiz_questions
			WHERE
				user_quiz_questions.answer_score IS NOT NULL
			ORDER BY
				user_quiz_questions.user_id asc
				;";
	
				// echo "<pre>$sql</pre>\n";
				
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

	while($row = mysqli_fetch_array($result)){
		$userScoreArray[$row['user_id']] += $row['answer_score'];
	}
	arsort($userScoreArray);
	

	dbClose($conn);



	echo '<table cellpadding="8" width="100%">';
	
			
		foreach ($userScoreArray AS $user => $score) {
			echo "<tr><td>" . $userNameArray[$user] . "</td><td align='right'>" . $score . "</td></tr>\n";
		}
			
	echo '</table>';


?>
