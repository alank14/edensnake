<?php
// phpInfo();

	// Load source and mask
	$jpgSourceFilename = '/var/www/edensnake/test/p6/MobileCameraTemplate-master/sent-images/image3.jpg';
	$pngSourceFilename = '/var/www/edensnake/test/p6/MobileCameraTemplate-master/sent-images/image3.png';
    imagepng(imagecreatefromstring(file_get_contents($jpgSourceFilename)), $pngSourceFilename);
	


	$source = imagecreatefrompng( $pngSourceFilename );
	
	
	$mask = imagecreatefrompng( 'mask1.png' );
	// Apply mask to source
	imagealphamask( $source, $mask );
	// Output
	header( "Content-type: image/png");
	imagepng( $source );

	function imagealphamask( &$picture, $mask ) {
	    // Get sizes and set up new picture
	    $xSize = imagesx( $picture );
	    $ySize = imagesy( $picture );
	    $newPicture = imagecreatetruecolor( $xSize, $ySize );
	    imagesavealpha( $newPicture, true );
	    imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );

	    // Resize mask if necessary
	    if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
	        $tempPic = imagecreatetruecolor( $xSize, $ySize );
	        imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
	        imagedestroy( $mask );
	        $mask = $tempPic;
	    }

	    // Perform pixel-based alpha map application
	    for( $x = 0; $x < $xSize; $x++ ) {
	        for( $y = 0; $y < $ySize; $y++ ) {
	            $alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );
	            $alpha = 127 - floor( $alpha[ 'red' ] / 2 );
	            $color = imagecolorsforindex( $picture, imagecolorat( $picture, $x, $y ) );
	            imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $alpha ) );
	        }
	    }

	    // Copy back to original picture
	    imagedestroy( $picture );
	    $picture = $newPicture;
	}


	// <h3>File Saved: <a href="./">back to photo taker</a></h3>

	// png file: <img src="https://edensnake.com/test/p6/MobileCameraTemplate-master/sent-images/image3.png">
?>