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
	
	
	if (isset($_POST['base64'])) {
		$theBase64 = $_POST['base64'];
	}

	$filepath = "/var/www/edensnake/test/p6/MobileCameraTemplate-master/sent-images/image3.jpg";

	// Save the image in a defined path
	base64_to_jpeg($theBase64,$filepath);
?>
<h3>File Saved: <a href="./">back to photo taker</a></h3>

<img src="https://edensnake.com/test/p6/MobileCameraTemplate-master/sent-images/image3.jpg">
