<?php
// https://edensnake.com/creation/approve-photos.php

	header('Content-Type: text/html; charset=utf-8');
	include('config.php');
	include('functions.php');



	echo "<!DOCTYPE html>\n";
	echo "<html lang='en'><head><title>View Photos</title>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
	echo '<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">' . "\n";
	echo '<link rel="stylesheet" href="edensnake.css">' . "\n";
	echo '<link rel="icon" type="image/png" href="resources/favicon.png">' . "\n";
	echo '</head><body>' . "\n";


	echo '<h1>Photos</h1>' . "\n";



	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);

	$sql = "SELECT 
				photos.day
				, photos.filename
				, photos.id AS photo_id
				, users.first_name
				, users.last_name
				, riddles.photo_long
				, photos.approved
			FROM 
				photos
				, users
				, riddles
			WHERE
				photos.active = '1'
				AND photos.approved = '1'
				AND photos.user_id = users.id
				AND riddles.day = photos.day
			ORDER BY
				photos.day asc
				, users.last_name asc
				, users.first_name asc
				;";
	
	
			// echo "<pre>$sql</pre>\n";
				
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

	$theCurrentDay = '0';

	while($row = mysqli_fetch_array($result)){
		
		echo '<h1>' . $row['day'] . "</h1>\n";
		
		echo '<h2>' . $row['first_name'];
		echo ' ' . $row['last_name'] . "</h2>\n";

		echo "<p><img src='/creation/sent-images/" . $row['filename'] . "' width='200'/></p>";
		echo "<hr/>";
			
		echo "\n";
	}

	
	dbClose($conn);

?>