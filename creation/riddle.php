<?php
/*
todo:

	drop the logout feature for the kids? make it a DB flag that lets them log out? allow_logout flag

	after resetting game, you can keep hitting REFRESH and see the different questions, even though the next_question field has been set.

	create a Scoreboard

*/
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
		header('Location: login.php');
	}
	else {
	    $currentUserID = $_COOKIE[$cookie_name];
	}

	header('Content-Type: text/html; charset=utf-8');
	include('config.php');
	include('functions.php');

	// todo: replace this with a proper login system
	
	include('initialize.php');

	


	echo "<!DOCTYPE html>\n";
	echo "<html lang='en'><head><title>EdenSnake</title>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
	echo '<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">' . "\n";
	echo '<link rel="stylesheet" href="edensnake.css">' . "\n";
	echo '<link rel="icon" type="image/png" href="images/favicon.png">' . "\n";
	echo '</head><body>' . "\n";

echo '	<div class="header">';
echo '    <img src="resources/edensnake-logo.png" alt="EdenSnake" width="80"/>' . "\n";

echo '	  <div class="header-right">';
echo '	    	<span class="headerspan">';
echo $userArray[$currentUserID]["first_name"] . " " . $userArray[$currentUserID]["last_name"];
echo '</span> <a class="active" href="login.php">Logout</a>';
echo '	  </div>';
echo '	</div>';

	echo '<div class="cbody">' . "\n";

	echo '<div class="cbody-left">' . "\n";


	// feedback message, if any
	if ($feedbackMessage != '') {
		echo '<h2 class="feedback">' . $feedbackMessage . '</h2>' . "\n";
	}
	
	// answer form
	echo '<form method="get" action="./riddle.php" id="answerForm">' . "\n";

	echo "<script>\n";
	// write out javascript array here with all the B-Tag values and their English equivalents
	echo "var \$answerWords = [];\n";
	foreach ($falseAnswerArray as $theFalseAnswer) {
		echo "$" . "answerWords['" . $theFalseAnswer["riddle_answer_key"] . "'] = " . '"' . $theFalseAnswer["riddle_answer"] . '";' . "\n";
	}
	foreach ($riddleArray as $theRealAnswer) {
		echo "$" . "answerWords['" . $theRealAnswer["riddle_answer_key"] . "'] = " . '"' . $theRealAnswer["riddle_answer"] . '";' . "\n";
	}
		echo "\tfunction parseMe($" . "theValue) {\n";
		// echo "\n\t\$enteredText = document.getElementById('firstNameField').value;\n";
		echo "if (typeof \$answerWords[\$theValue] === 'undefined') {\n";
			// reset the span to BLANK!
			echo "\t\t" . 'document.getElementById("wordsForBTag").innerHTML = "&nbsp;";' . "\n";
			echo "}\n";
			echo "else {\n";
			// set that span to the helpful-feedback words
			echo "\t\t" . 'document.getElementById("wordsForBTag").innerHTML = $answerWords[$theValue];' . "\n";
			echo "\t}\n";
		echo "}\n";
		
		// Hint Display Javascript
		
		echo 'function showHideHint() {' . "\n";
		    echo 'var x = document.getElementById("myHINT");' . "\n";
		    echo 'if ((x.style.display == "none") || (x.style.display == "")) {' . "\n";
		        echo 'x.style.display = "block";' . "\n";
				echo 'document.getElementById("showHideHintButton").value = "hide hint";' . "\n";
		    echo '} else {' . "\n";
		        echo 'x.style.display = "none";' . "\n";
				echo 'document.getElementById("showHideHintButton").value = "show hint";' . "\n";
		    echo '}' . "\n";
		echo '}' . "\n";
		
		
		
	echo "</script>\n";
	
	if ($answersCorrect != 7) {
		// current riddle
		echo '<p class="instructions">Find the B-Tag that solves this riddle:</p>';
		echo '<div id="clue_div">' . $riddleArray[$rday]["riddle"] . "</div>\n";
	
		echo '<p class="instructions">Scan the QR-Code, or type in the three digits:</p>';
		echo "<div class='btag'>B-Tag Number: ";
			echo '<input onkeyup=' . "parseMe(document.getElementById(\"cam-qr-result\").value);" . ' id="cam-qr-result" name="ranswer" type="tel" size="4" minlength="3" maxlength="3" class="btag"/>' . "\n";
			echo ' <div id="wordsForBTag">&nbsp;</div>';
		echo '</div>';
		echo '<p class="instructions">Then click the Day of Creation at right!</p>';
	}
	?>

	<!-- webcam -->
	<div class="cam">
<!--	    <xvideo style="width:320px;height=180;" muted autoplay playsinline xid="qr-video"></xvideo> -->
	    <video style="width:180px;height=180px;" muted autoplay playsinline id="qr-video"></video>
	    <canvas id="debug-canvas"></canvas>
	</div>


<input id="showHideHintButton" type="button" onclick="showHideHint()" value="Show Hint"/>


	<?php
	echo '<div id="myHINT" style="display:none;">' . "\n";

	if ($answersCorrect != 7) {
		// hints for current clue
			echo '<div id="clue_hints_div">' . "\n";
			echo "<p class='hint_english'><i>" . '"' . $riddleArray[$rday]["quote_english"] . '"' . "</i></p>";
			echo "<p class='hint_hebrew'>" . $riddleArray[$rday]["quote_hebrew"] . "</p>";
			echo '</div>' . "\n";
		}

	echo '</div>' . "\n";


	
	echo '</div>' . "\n"; // end cbody-left
	
		
	echo '<div class="cbody-right">';

					// puzzle-piece table
					echo '<table xid="puzzle_pieces" cellspacing="0" cellpadding="0" xborder="border">' . "\n";
						echo "<tr>\n";
						echo "<th colspan='2' class='pieceHeader'>Select Day</th>\n";
						echo "</tr>\n";
						for ($i=1; $i<=7; $i++) {
							echo "<tr>\n";
							echo "<th class='daycell'>$i</th>\n";
							if ($userRiddleArray[$currentUserID][$i] == $riddleArray[$i]["day"]) {
								echo "<td class='snakePieceCell'>";
								echo "<img style='display:block;' src='snake-pieces-side/snake-$i.png' width='90' height='60'/>";
								echo "</td>\n";
							}
							else {
								echo "<td class='dayRadioCell'>";
								echo "<input xclass='dayRadio' type='radio' name='rqkey' value='" . $riddleArray[$i]["riddle_question_key"];
								echo "' onclick='document.getElementById(\"answerForm\").submit();'/>";
								echo "</td>\n";
							}
							echo "</tr>\n";
						}
					echo "</table>\n";
	
	
	echo "</div>\n";

	echo "</form>\n";
	






echo '<br clear="all"/><br/><br/>';

echo '<hr/>';

	if ($answersCorrect != 7) {
		// answer (for testing purposes)
			echo '<div id="clue_answer_div">' . "\n";
			echo '<p>B-Tag = ' . $riddleArray[$rday]["riddle_answer_key"] . ' and Day = ' . $riddleArray[$rday]["day"];
			echo ' (' . $riddleArray[$rday]["riddle_answer"] . ')</p>';
			// echo "<img src='sample-photos/sample-$rday.png' height='200' xalign='left'/>\n";
			echo '</div>' . "\n";
	}
?>
	<div>
	    <input type="checkbox" id="debug-checkbox">
	    <span>Show debug image</span>
	</div>
	<b>Detected QR code: </b>
	<span id="xcam-qr-result" style="font-size:36px;">None</span>
	
	
	<!-- Start Webcam Javascript -->
	<script type="module">
	    import QrScanner from "./qr-scanner.min.js";
	    const video = document.getElementById('qr-video');
	    const debugCheckbox = document.getElementById('debug-checkbox');
	    const debugCanvas = document.getElementById('debug-canvas');
	    const debugCanvasContext = debugCanvas.getContext('2d');
	    const camQrResult = document.getElementById('cam-qr-result');
	    const fileSelector = document.getElementById('file-selector');
	    const fileQrResult = document.getElementById('file-qr-result');

	    function setResult(label, result) {
//	        label.textContent = result;
	        label.value = result;
	        label.style.color = 'teal';
	        clearTimeout(label.highlightTimeout);
	        label.highlightTimeout = setTimeout(() => label.style.color = 'inherit', 100);
			parseMe(document.getElementById("cam-qr-result").value);
		//	alert('got one: ' + result);
	    }


	    // ####### Web Cam Scanning #######

	    const scanner = new QrScanner(video, result => setResult(camQrResult, result));
	    scanner.start();


	    // ####### File Scanning #######

	    fileSelector.addEventListener('change', event => {
	        const file = fileSelector.files[0];
	        if (!file) {
	            return;
	        }
	        QrScanner.scanImage(file)
	            .then(result => setResult(fileQrResult, result))
	            .catch(e => setResult(fileQrResult, e || 'No QR code found.'));
	    });


	    // ####### debug mode related code #######

	    debugCheckbox.addEventListener('change', () => setDebugMode(debugCheckbox.checked));

	    function setDebugMode(isDebug) {
	        const worker = scanner._qrWorker;
	        worker.postMessage({
	            type: 'setDebug',
	            data: isDebug
	        });
	        if (isDebug) {
	            debugCanvas.style.display = 'block';
	            worker.addEventListener('message', handleDebugImage);
	        } else {
	            debugCanvas.style.display = 'none';
	            worker.removeEventListener('message', handleDebugImage);
	        }
	    }

	    function handleDebugImage(event) {
	        const type = event.data.type;
	        if (type === 'debugImage') {
	            const imageData = event.data.data;
	            if (debugCanvas.width !== imageData.width || debugCanvas.height !== imageData.height) {
	                debugCanvas.width = imageData.width;
	                debugCanvas.height = imageData.height;
	            }
	            debugCanvasContext.putImageData(imageData, 0, 0);
	        }
	    }
	</script>
	<!-- End Webcam Javascript -->

	
	<?php
	
	
	

	// reset link (for debug purposes)
	echo '<h3><a href="./riddle.php?reset=true">reset game</a></h3>';
	
	
	
	
	echo '</body></html>' . "\n";
	
/*
	echo "<h4>" . $answersCorrect . " clues solved and " . (7 - $answersCorrect) . " unanswered" . "</h4>\n";
	echo "<img src='resources/switch-lens.png' width='80'/>\n";
	echo "<img src='resources/camera.png' width='80'/>\n";
*/
?>