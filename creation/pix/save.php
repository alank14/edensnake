<html><head><title>Photo Saved!</title></head>
<body>
	<?php
	// from https://ourcodeworld.com/articles/read/76/how-to-save-a-base64-image-from-javascript-with-php
	// and https://www.kasperkamperman.com/blog/camera-template/
	
	function base64_to_jpeg($base64_string, $output_file) {
	    // open the output file for writing
	    $ifp = fopen( $output_file, 'wb' ); 

	    // split the string on commas
	    // $data[ 0 ] == "data:image/png;base64"
	    // $data[ 1 ] == <actual base64 string>
	    $data = explode( ',', $base64_string );

	    // we could add validation here with ensuring count( $data ) > 1
	    fwrite( $ifp, base64_decode( $data[ 1 ] ) );

	    // clean up the file resource
	    fclose( $ifp ); 

	    return $output_file; 
	}
	
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

	
	if (isset($_POST['base64'])) {
		$theBase64 = $_POST['base64'];
	}

	if (isset($_POST['user'])) {
		$theUser = $_POST['user'];
	}

	if (isset($_POST['creationDay'])) {
		$theCreationDay = $_POST['creationDay'];
	}
	
	$theUser = $currentUserID;
	
	date_default_timezone_set("America/Los_Angeles");

	$time = time(); 
	
	$datestamp =  date("Y-m-d_H-i-s", $time);

	$filebase = "/creation/pix/sent-images/${theUser}_${theCreationDay}_$datestamp.jpg";
	$filepath = "/var/www/edensnake" . $filebase;

	// Save the image in a defined path
	base64_to_jpeg($theBase64,$filepath);
	
	echo '<h3>File Saved: <a href="./">back to photo taker</a></h3>' . "\n";
	echo '<img src="https://edensnake.com' . $filebase . '"/>' . "\n";
?>



