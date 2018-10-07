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
	echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
	echo '</head><body>' . "\n";


	echo '<h1>Photos</h1>' . "\n";

	echo '<div id="photoDiv"></div>' . "\n";

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

	?>
	
	<style>
		#fader {
		    position: relative; 
		    width: 100%;
		    height: 400px;
		}

		.button {
		    background-color: green;
		    width: 50px;
		    height: 30px;
		    font-size: 20px;
		    line-height: 30px;
		    text-align: center;
		    position: absolute;
		    top: 30px;  
		}

		#next {
		    right: 100px;   
		}

		#prev {
		    left: 100px;  
		}
		
		</style>
	<script>
	// https://zeit.co/blog/async-and-await
	function sleep (time) {
	  return new Promise((resolve) => setTimeout(resolve, time));
	}

	<?php

	
	while($row = mysqli_fetch_array($result)){
		
		$photoContent = '';
		
		$photoContent .= '<h1>' . $row['day'] . "</h1>";
		
		$photoContent .= '<h2>' . $row['first_name'];
		$photoContent .= ' ' . $row['last_name'] . "</h2>";

		$photoContent .= "<p><img src='/creation/sent-images/" . $row['filename'] . "' width='200'/></p>";
		$photoContent .= "<hr/>";


			/*
		echo 'sleep(20000).then(() => {' . "\n";
		echo '	document.getElementById("photoDiv").innerHTML = "' . $photoContent . '";' . "\n";
		echo '})' . "\n";
			*/		
	
	
		// $('#someid').addClass("load");

		/*
		echo 'setTimeout(function(){' . "\n";
		echo '	document.getElementById("photoDiv").innerHTML = "' . $photoContent . '";' . "\n";
		echo '}, 200);' . "\n";
		*/
			
		/*
		echo '	document.getElementById("photoDiv").innerHTML = "' . $photoContent . '";' . "\n";
		echo 'sleep(30000);' . "\n";
	*/
	
		// echo $photoContent;
	}
	echo '</script>' . "\n";

	
	dbClose($conn);

?>


<div id="fader">
    <img src="http://dummyimage.com/600x400/000/fff.png&text=Image1"/>
    <img src="http://dummyimage.com/200x400/f00/000.jpg&text=Image2"/>
    <img src="http://dummyimage.com/100x100/0f0/000.png&text=Image3"/>
    <img src="http://dummyimage.com/400x400/0ff/000.gif&text=Image4"/>
    <img src="http://dummyimage.com/350x250/ff0/000.png&text=Image5"/>
</div>


<div class="button" id="next">Next</div>
<div class="button" id="prev">Prev</div>




<script>
	$(function() {
	    $('#fader img:not(:first)').hide();
	    $('#fader img').css('position', 'absolute');
	    $('#fader img').css('top', '0px');
	    $('#fader img').css('left', '50%');
	    $('#fader img').each(function() {
	        var img = $(this);
	        $('<img>').attr('src', $(this).attr('src')).load(function() {
	            img.css('margin-left', -this.width / 2 + 'px');
	        });
	    });

	    var pause = false;
    
	    function fadeNext() {
	        $('#fader img').first().fadeOut().appendTo($('#fader'));
	        $('#fader img').first().fadeIn();
	    }

	    function fadePrev() {
	        $('#fader img').first().fadeOut();
	        $('#fader img').last().prependTo($('#fader')).fadeIn();
	    }

	    $('#fader, #next').click(function() {
	        fadeNext();
	    });

	    $('#prev').click(function() {
	        fadePrev();
	    });

	    $('#fader, .button').hover(function() {
	        pause = true;
	    },function() {
	        pause = false;
	    });

	    function doRotate() {
	        if(!pause) {
	            fadeNext();
	        }    
	    }
    
	    var rotate = setInterval(doRotate, 2000);
	});
</script>

