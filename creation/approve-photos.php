<?php
// https://edensnake.com/creation/approve-photos.php

	header('Content-Type: text/html; charset=utf-8');
	include('config.php');
	include('functions.php');



	echo "<!DOCTYPE html>\n";
	echo "<html lang='en'><head><title>Approve Photos</title>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
	echo '<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">' . "\n";
	echo '<link rel="stylesheet" href="edensnake.css">' . "\n";
	echo '<link rel="icon" type="image/png" href="resources/favicon.png">' . "\n";
	echo '</head><body>' . "\n";


	echo '<h1>Unapproved Photos</h1>' . "\n";



	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);
	
	$photoToApprove = '';
	$photoToReject = '';
	
	if (isset($_GET['approvePhoto'])) {
		$photoToApprove = $_GET['approvePhoto'];
	}
	if (isset($_GET['rejectPhoto'])) {
		$photoToReject = $_GET['rejectPhoto'];
	}

	if ($photoToApprove != '') {
		$sql = 'UPDATE photos SET approved="1" WHERE id="' . $photoToApprove . '";';
	}
	if ($photoToReject != '') {
		$sql = 'UPDATE photos SET approved="2" WHERE id="' . $photoToReject . '";';
	}
	echo "<h3>DEBUG: " . $sql . "</h3>\n";
	
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));


	$sql = "SELECT 
				photos.filename
				, photos.id AS photo_id
				, photos.day
				, users.first_name
				, users.last_name
				, riddles.photo_long
			FROM 
				photos
				, users
				, riddles
			WHERE
				photos.active = '1'
				AND photos.approved = '0'
				AND photos.user_id = users.id
				AND riddles.day = photos.day
			ORDER BY
				photos.day asc
				, users.last_name asc
				, users.first_name asc
				;";
	
	
			//	echo "<pre>$sql</pre>\n";
				
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

	$theCurrentDay = '0';
	
	echo "<table cellpadding='3'>";
	while($row = mysqli_fetch_array($result)){
		echo "<tr valign='top'>";
		// echo $row['filename'];
		if ($row['day'] != $theCurrentDay) {
			echo '<td>' . $row['day'] . "</td>\n";
			echo '<td>' . $row['photo_long'] . "</td>\n";
			$theCurrentDay = $row['day'];
		}
		else {
			echo '<td></td><td></td>' . "\n";
		}
		echo '<td>' . $row['first_name'];
			echo ' ' . $row['last_name'] . "</td>\n";
		echo "<td><img src='/creation/sent-images/" . $row['filename'] . "' width='200'/></td>";
		echo "<td>";
		echo "<form method='get' action='./approve-photos.php'>\n";
		echo "<input type='hidden' name='approvePhoto' value='" . $row['photo_id'] . "'/>";
		echo "<input type='submit' value='approve'/>";
		echo "</form>\n";
		echo "</td>";

		echo "<td>";
		echo "<form method='get' action='./approve-photos.php'>\n";
		echo "<input type='hidden' name='rejectPhoto' value='" . $row['photo_id'] . "'/>";
		echo "<input type='submit' value='reject'/>";
		echo "</form>\n";
		echo "</td>";

		echo "</tr>";
		echo "\n";
	}
	echo "</table>\n";
	
	dbClose($conn);

?>