<?php
// https://edensnake.com/creation/approve-photos.php

	header('Content-Type: text/html; charset=utf-8');
	include('../config.php');
	include('../functions.php');



	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);

	$userScoreArray = array();
	$userNameArray = array();

	$sql = "SELECT 
				user_id
				, day_1_proposed_riddle_id
				, day_2_proposed_riddle_id
				, day_3_proposed_riddle_id
				, day_4_proposed_riddle_id
				, day_5_proposed_riddle_id
				, day_6_proposed_riddle_id
				, day_7_proposed_riddle_id
			FROM 
				user_riddles
				;";
	
	
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

	while($row = mysqli_fetch_array($result)){
		for ($i=1; $i<8; $i++) {
			if ($row['day_' . $i . '_proposed_riddle_id'] != '') {
				$userScoreArray[$row['user_id']] += 10;
			}
		}
	}

	$sql = "SELECT 
				users.id
				, user_quiz_questions.answer_score
				, users.first_name
				, users.last_name
			FROM 
				user_quiz_questions
				, users
			WHERE
				user_quiz_questions.user_id = users.id
				AND user_quiz_questions.answer_score IS NOT NULL
			ORDER BY
				user_quiz_questions.user_id asc
				;";
	
				// echo "<pre>$sql</pre>\n";
				
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

	while($row = mysqli_fetch_array($result)){
		$userScoreArray[$row['id']] += $row['answer_score'];
		$userNameArray[$row['id']] = $row['first_name'] . ' ' . $row['last_name'];
	}
	arsort($userScoreArray);
	
	$sql = "SELECT 
				photos.day
				, photos.filename
				, photos.id AS photo_id
				, users.first_name
				, users.last_name
				, riddles.photo_long
				, riddles.day_title
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
				photos.id desc
				;";
	
	
			// echo "<pre>$sql</pre>\n";
				
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));

	?>
	
	
<!DOCTYPE html>
<html>
	<head>
		<title>Photos</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="css/styles.css" />
				<link rel="stylesheet" href="css/iview.css" />
				<link rel="stylesheet" href="css/skin 1/style.css" />
		<script src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/raphael-min.js"></script>
		<script type="text/javascript" src="js/jquery.easing.js"></script>
		<script src="js/iview.js"></script>
		<script>
			$(document).ready(function(){
				$('#iview').iView({
					pauseTime: 7000,
					directionNav: false,
					controlNav: false,
					tooltipY: -15
				});
			});
		</script>
	</head>

	<body>
<div id="cont">
	<div id="scoreboard">
	<h1>Scoreboard</h1>
	<table cellpadding="8" width="100%">
		<?php
			
		foreach ($userScoreArray AS $user => $score) {
			echo "<tr><td>" . $userNameArray[$user] . "</td><td align='right'>" . $score . "</td></tr>\n";
		}
			
		?>
	</table>
	</div>
	
	<div class="container">
			<div id="centerTitle">
				<h1>Visit edensnake.com on your phone to play along!</h1>
			</div>
		<div id="iview">
			

	
			<?php
			while($row = mysqli_fetch_array($result)){
		
				$photoContent = '';
				$photoContent .= '<div data-iview:image="/creation/sent-images/' . $row['filename'] . '" data-iview:transition="slice-top-fade,slice-right-fade">' . "\n";
				$photoContent .= '<div class="iview-caption caption1" data-x="550" data-y="50">Day ' . $row['day'] . ':<br/>' . $row['day_title'] . '</div>' . "\n";
				$photoContent .= '<div class="iview-caption caption3" data-x="600" data-y="150" xdata-transition="wipeRight">By ' . $row['first_name'] . ' ' . $row['last_name'] . '</div>' . "\n";
				// $photoContent .= '<div class="iview-caption" data-x="600" data-y="320" data-transition="wipeLeft"><i>THIRD by <b>Hemn Chawroka</b></i></div>' . "\n";
				$photoContent .= '</div>' . "\n";

				echo $photoContent;
			}

			?>
			
		
		</div>
	</div>

</div>

<div id="background-image">
	<img src="img/bckg.jpg" width="1820" height="1024" />
</div>
<script type="text/javascript" src="js/jquery.fullscreen.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$("#background-image").fullscreenBackground();
	});
</script>
<!--
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30854466-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	-->
	</body>
</html>


	<?php
	dbClose($conn);

?>
