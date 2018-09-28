<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1, maximum-scale=1">
  <meta name="description" content="Smart Device Camera Template for HTML, CSS, JS and WebRTC">
  <meta name="keywords" content="HTML,CSS,JavaScript, WebRTC, Camera">
  <meta name="author" content="Kasper Kamperman">
  <title>EdenSnake Photo</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="photoQuestion">
		<h1><span class="photoKicker">Take a photo of somebody...</span> wearing black and white</h1>
	</div>
  <div id="container">
    <div id="vid_container">
        <video id="video" autoplay playsinline></video>
        <div id="video_overlay"></div>
    </div>
    <div id="gui_controls">
	<div class="photoQuestion">
		<h1><span class="photoKicker">2Take a photo of somebody...</span> wearing black and white</h1>
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
	include 'pix-header.php';

	if (isset($_GET['creationDay'])) {
		$theCreationDay = $_GET['creationDay'];
	}
	else {
		$theCreationDay = '5';
	}

	echo '<form method="POST" name="form" id="form" action="save.php">' . "\n";
	echo "\t" . '<input type="hidden" name="creationDay" value="' . $theCreationDay . '">' . "\n";
	echo "\t" . '<textarea name="base64" id="base64"></textarea>' . "\n";
	echo '</form>' . "\n";

?>





  
</body>
</html>