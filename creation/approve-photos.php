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


	echo '<h1>The Photos Approval Dashboard ("The PAD")</h1>' . "\n";



	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);
	
	$photoToApprove = '';
	$photoToReject = '';
	$sql = '';
	
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
	
	if ($sql != '') {
		//	echo "<h3>DEBUG: " . $sql . "</h3>\n";
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	}


	if (isset($_GET['approveStatus'])) {
		$theApproveStatus = $_GET['approveStatus'];
	}
	else {
		$theApproveStatus = 'unapproved';
	}
	
	
	if ($theApproveStatus == 'approved') {
		$sqlApproveFragment = "AND photos.approved = '1'";
	}
	else if ($theApproveStatus == 'rejected') {
		$sqlApproveFragment = "AND photos.approved = '2'";
	}
	else if ($theApproveStatus == 'reviewed') {
		$sqlApproveFragment = "AND photos.approved > 0";
	}
	else if ($theApproveStatus == 'all') {
		$sqlApproveFragment = "";
	}
	else {
		$sqlApproveFragment = "AND photos.approved = '0'";
	}

	$sql = "SELECT 
				photos.filename
				, photos.id AS photo_id
				, photos.day
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
				$sqlApproveFragment
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
	
	echo "<h3>";
	echo "<a href='./approve-photos.php'>Unapproved</a>";
	echo " | <a href='./approve-photos.php?approveStatus=approved'>Approved</a>";
	echo " | <a href='./approve-photos.php?approveStatus=rejected'>Rejected</a>";
	echo " | <a href='./approve-photos.php?approveStatus=reviewed'>(Approved or Rejected)</a>";
	echo " | <a href='./approve-photos.php?approveStatus=all'>All</a>";
	echo "</h3>";
	
	$rowCount = '0';
	echo "<table cellpadding='3' width='100%'>";
	echo "<tr bgcolor='#cccccc'><th>Day</th><th>Photo Assignment</th><th>User</th><th>Photo</th><th>Current<br/>Status</th><th>Approve?</th><th>Reject?</th></tr>";
	while($row = mysqli_fetch_array($result)){
		echo "<tr valign='top'>";
		// echo $row['filename'];
		if ($row['day'] != $theCurrentDay) {
			echo '<td>' . $row['day'] . "</td>\n";
			echo '<td><b>' . $row['photo_long'] . "</b></td>\n";
			$theCurrentDay = $row['day'];
		}
		else {
			echo '<td></td><td></td>' . "\n";
		}
		echo '<td>' . $row['first_name'];
			echo ' ' . $row['last_name'] . "</td>\n";
			
		echo "<td><img src='/creation/sent-images/" . $row['filename'] . "' width='200'/></td>";

		echo "<td>";
		if ($row['approved'] == '0') {
			echo "unapproved";
		}
		else if ($row['approved'] == '1') {
			echo "approved";
		}
		else if ($row['approved'] == '2') {
			echo "rejected";
		}
		echo "</td>\n";

		echo "<td>";
		if ($row['approved'] != '1') {
			echo "<form method='get' action='./approve-photos.php'>\n";
			echo "<input type='hidden' name='approveStatus' value='" . $theApproveStatus . "'/>";
			echo "<input type='hidden' name='approvePhoto' value='" . $row['photo_id'] . "'/>";
			echo "<input type='submit' value='approve'/>";
			echo "</form>\n";
		}
		echo "</td>";

		echo "<td>";
		if ($row['approved'] != '2') {
			echo "<form method='get' action='./approve-photos.php'>\n";
			echo "<input type='hidden' name='approveStatus' value='" . $theApproveStatus . "'/>";
			echo "<input type='hidden' name='rejectPhoto' value='" . $row['photo_id'] . "'/>";
			echo "<input type='submit' value='reject'/>";
			echo "</form>\n";
		}
		echo "</td>";

		echo "</tr>";
		echo "\n";
		$rowCount++;
	}
	echo "</table>\n";
	
	if ($rowCount == '0') {
		echo "<h3>No results for this particular report.</h3>\n";
	}
	
	dbClose($conn);

?>