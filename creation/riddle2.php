<?php
/*
todo
	* purchase domain?
	* Scoreboard
	* Login system
	* smart form submit - don't submit unless a b-tag is typed in AND a day is selected
*/
	header('Content-Type: text/html; charset=utf-8');
	include('config.php');
	include('functions.php');

	// todo: replace this with a proper login system
	$currentUserID = '1';
	
	include('initialize.php');

	echo "<!DOCTYPE html>\n";
	echo "<html lang='en'><head><title>EdenSnake</title>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
	echo '<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">' . "\n";
	echo '<link rel="stylesheet" href="edensnake.css">' . "\n";
	echo '<link rel="stylesheet" href="css/normalize.css">' . "\n";
	echo '<link rel="stylesheet" href="css/skeleton.css">' . "\n";
	echo '<script src="resources/creation.js"></script>' . "\n";
	echo '<link rel="icon" type="image/png" href="images/favicon.png">' . "\n";
	// echo '<script src="edensnake.js"></script>' . "\n";
	echo '</head><body>' . "\n";

	echo '<div class="container">' . "\n";
	
	// banner
	echo '<table id="banner" width="100%"><tr bgcolor="lightyellow" valign="top">';
	echo '<td>';
		// echo '<img src="resources/BK-logo-final.png" width="40"/>' . "\n";
		echo '<img src="resources/edensnake-logo.png" width="100"/>' . "\n";
	echo '</td>';
	echo '<td style="text-align:right;">';
		echo "<p>" . $userArray[$currentUserID]["first_name"] . " " . $userArray[$currentUserID]["last_name"];
		echo "<img src='resources/logout.png' width='80'/>\n";
		echo "</p>\n";
		echo '</td>';
	echo '</tr></table>';

	// feedback message, if any
	if ($feedbackMessage != '') {
		echo '<h2 class="feedback">' . $feedbackMessage . '</h2>' . "\n";
	}
	
	// answer form
	echo '<form method="get" action="./riddle2.php" id="answerForm">' . "\n";

	if ($answersCorrect != 7) {
		// current riddle
		echo '<div id="clue_div" style="color:darkgreen;">' . "\n";
		echo "<h3>" . $riddleArray[$rday]["riddle"] . "</h3>";
		echo '</div>' . "\n";
	
		echo '<p>Find the B-Tag in YICC\'s Rotunda that solves this riddle, then click the Day of Creation below!</p>';
		echo "<h3>B-Tag Number: ";
			echo '<input id="cam-qr-result" name="ranswer" type="tel" size="4" minlength="3" maxlength="3" class="btag"/>' . "\n";
		echo '</h3>';
	}

	// puzzle-piece table
	echo '<table id="puzzle_pieces" xcellspacing="0" border="border">' . "\n";
		echo "<tr>\n";
		for ($i=1; $i<=7; $i++) {
			echo "<th>Day $i</th>\n";
		}
		echo "</tr>\n";
		
		echo "<tr>\n";
		for ($i=1; $i<=7; $i++) {
			echo "<td>";
			if ($userRiddleArray[$currentUserID][$i] == $riddleArray[$i]["day"]) {
				echo "<img src='snake-pieces/snake-$i.png' width='60' height='90'/>";
			}
			else {
				echo "<input xclass='dayRadio' type='radio' name='rqkey' value='" . $riddleArray[$i]["riddle_question_key"];
				echo "' onclick='document.getElementById(\"answerForm\").submit();'/>";
			}
			echo "</td>\n";
		}
		echo "</tr>\n";
	echo "</table>\n";

	echo "</form>\n";
	
	
	?>
	
	<h1>Scan from WebCam:</h1>
	<div class="cam">
<!--	    <xvideo style="width:320px;height=180;" muted autoplay playsinline xid="qr-video"></xvideo> -->
	    <video style="width:180px;height=180px;" muted autoplay playsinline id="qr-video"></video>
	    <canvas id="debug-canvas"></canvas>
	</div>
	<div>
	    <input type="checkbox" id="debug-checkbox">
	    <span>Show debug image</span>
	</div>
	<b>Detected QR code: </b>
	<span id="xcam-qr-result" style="font-size:36px;">None</span>
	
	
	
	
	<?php
	
	
	
	

	if ($answersCorrect != 7) {
		// hints for current clue
		echo '<h3 style="color:darkgreen;">Hint:</h3>' . "\n";
			echo '<div id="clue_hints_div">' . "\n";
			echo "<h4><i>" . '"' . $riddleArray[$rday]["quote_english"] . '"' . "</i></h4>";
			echo "<h4>" . $riddleArray[$rday]["quote_hebrew"] . "</h4>";
			echo '</div>' . "\n";

		// answer (for testing purposes)
		echo '<h3 style="color:darkgreen;">Answer:</h3>' . "\n";
			echo '<div id="clue_answer_div">' . "\n";
			echo '<h4>B-Tag = ' . $riddleArray[$rday]["riddle_answer_key"] . ' and Day = ' . $riddleArray[$rday]["day"];
			echo ' (' . $riddleArray[$rday]["riddle_answer"] . ')</h4>';
			echo "<img src='sample-photos/sample-$rday.png' height='200' xalign='left'/>\n";
			echo '</div>' . "\n";
	}
	// reset link (for debug purposes)
	echo '<h3><a href="./riddle2.php?reset=true">reset game</a></h3>';
	
	
	
	
	echo '</div></body></html>' . "\n";
	
/*
	echo "<h4>" . $answersCorrect . " clues solved and " . (7 - $answersCorrect) . " unanswered" . "</h4>\n";
	echo "<img src='resources/switch-lens.png' width='80'/>\n";
	echo "<img src='resources/camera.png' width='80'/>\n";
*/
?>