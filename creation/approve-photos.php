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
	echo '</head><body><h1>Approve Photos</h1>' . "\n";


	echo '<h1>Unapproved Photos</h1>' . "\n";



	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);

	$sql = "SELECT 
				photos.filename
				, photos.day
				, users.first_name
				, users.last_name
			FROM 
				photos
				, users
			WHERE
				photos.active = '1'
				AND photos.approved IS NULL
				AND photos.user_id = users.id
			ORDER BY
				photos.day asc
				, users.last_name asc
				, users.first_name asc
				;";
	
	
				// echo "<pre>$sql</pre>\n";
				
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

	while($row = mysqli_fetch_array($result)){
		echo "<li>";
	 echo $row['filename'];
	 echo ' - ' . $row['first_name'];
	 echo ' - ' . $row['last_name'];
	 echo ' - ' . $row['day'];
	 echo " - <img src='/creation/sent-images/" . $row['filename'] . "'/>";
			 echo "\n";
		
	}

	dbClose($conn);

?>