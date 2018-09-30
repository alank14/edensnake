<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1, maximum-scale=1">
  <meta name="description" content="Smart Device Camera Template for HTML, CSS, JS and WebRTC">
  <meta name="keywords" content="HTML,CSS,JavaScript, WebRTC, Camera">
  <meta name="author" content="Kasper Kamperman">
  <title>EdenSnake Photo</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <?php
  	
	include 'pix-header.php';

	if (isset($_GET['creationDay'])) {
		$theCreationDay = $_GET['creationDay'];
	}
	else {
		$theCreationDay = '5';
	}

	switch ($theCreationDay) {
	    case 1:
	        $theCreationPhotoRequest = "someone<br/>wearing black<br/>and white";
	        break;
	    case 2:
			$theCreationPhotoRequest = "someone<br/>holding<br/>a drink";
	        break;
	    case 3:
			$theCreationPhotoRequest = "someone<br/>holding<br/>seating placecard";
	        break;
	    case 4:
			$theCreationPhotoRequest = "someone<br/>smiling<br/>as bright as<br/>the sun";
	        break;
	    case 5:
			$theCreationPhotoRequest = "someone<br/>flapping<br/>wings<br/>like a bird";
	        break;
	    case 6:
			$theCreationPhotoRequest = "someone<br/>posing with you<br/>for a Selfie";
	        break;
	    case 7:
			$theCreationPhotoRequest = "someone<br/>resting";
	        break;
	}
	
  ?>
</head>
<body>
  <div id="container">
    <div id="vid_container">
        <video id="video" autoplay playsinline></video>
        <div id="video_overlay"></div>
    </div>
    <div id="gui_controls">
		<div class="photoQuestion">
			<p><?php echo $theCreationPhotoRequest;?></p>
		</div>
        <button id="switchCameraButton" name="switch Camera" type="button" aria-pressed="false"></button>
        <button id="takePhotoButton" name="take Photo" type="button"></button>
        <button id="toggleFullScreenButton" name="toggle FullScreen" type="button" aria-pressed="false"></button>
    </div>
  </div> 
  <script src="js/DetectRTC.min.js"></script>
  <script src="js/adapter.min.js"></script>  
  <script src="js/screenfull.min.js"></script>
  <script src="js/howler.core.min.js"></script>
  <script src="js/main.js"></script>
  

<?php
	echo '<form method="POST" name="form" id="form" action="riddle.php">' . "\n";
	echo "\t" . '<input type="hidden" name="creationDay" value="' . $theCreationDay . '">' . "\n";
	echo "\t" . '<textarea name="base64" id="base64"></textarea>' . "\n";
	echo '</form>' . "\n";

?>





  
</body>
</html>