<?php
	include 'pix-header.php';
?><html>
<head>
  <meta charset="utf-8">
  <title>Quiz</title>
  <xlink rel="stylesheet" type="text/css" href="style.css">
	  <style>
		  .optionlistQUESTION td {
			  height: 100px;
			  text-align: center;
			  font-weight: bold;
			  cursor: pointer;
		  }
		  .optionlistRESULTS td {
			  height: 100px;
			  text-align: center;
			  font-weight: bold;
		  }
		  .option_a {
			  background: yellow;
		  }
		  .option_b {
			  background: lightblue;
		  }
		  .option_c {
			  background: pink;
		  }
		  .option_d {
			  background: lightgreen;
		  }
		  #seconds {
			  font-size: 48px;
			  background: lightyellow;
			  font-weight: bold;
		      margin: auto;
		      border: 3px solid #73AD21;
			  width: 200px;
			  padding: 6px;
			  color: red;
		  }
		  
		  </style>
		  <script>
			  function countdown(endDate) {
			    let days, hours, minutes, seconds;
  
			    endDate = new Date(endDate).getTime();
  
			    if (isNaN(endDate)) {
			  	return;
			    }
  
			    setInterval(calculate, 1000);
  
			    function calculate() {
			      let startDate = new Date();
			      startDate = startDate.getTime();
    
			      let timeRemaining = parseInt((endDate - startDate) / 1000);
    
			      if (timeRemaining >= 0) {
			        days = parseInt(timeRemaining / 86400);
			        timeRemaining = (timeRemaining % 86400);
      
			        hours = parseInt(timeRemaining / 3600);
			        timeRemaining = (timeRemaining % 3600);
      
			        minutes = parseInt(timeRemaining / 60);
			        timeRemaining = (timeRemaining % 60);
      
			        seconds = parseInt(timeRemaining);
      
			       // document.getElementById("days").innerHTML = parseInt(days, 10);
			       // document.getElementById("hours").innerHTML = ("0" + hours).slice(-2);
			      //  document.getElementById("minutes").innerHTML = ("0" + minutes).slice(-2);
			        document.getElementById("seconds").innerHTML = ("0" + seconds).slice(-2);
			      } else {
					$waitOver == 'TRUE';
					<?php echo '// quiz.php?current_question=' . ($theCurrentQuestion + 1) . ';'; ?>

					// todo - refresh this page with a parameter sayinng:
						// showResults
						// or... showNextQuestion
			        return;
			      }
			    }
			  }

			  (function () { 
			    countdown('<?php 
				date_default_timezone_set('America/Los_Angeles');
				$time = date("m/d/Y h:i:s a", time() + 32);
				echo $time;
				?>');
			  }());
			  </script>
  <?php
  // todo
  // counnt up questionns
  
if (isset($_GET['debug'])) {
    $debug = 'TRUE';
}
	include('config.php');
	include('functions.php');

	function updateScore($theUserResponseID, $theScore) {
		global $conn;
		global $debug;
		$sql = "UPDATE user_quiz_questions SET answer_score = $theScore WHERE id = $theUserResponseID;";
		if ($debug == 'TRUE') {
			echo "<h3>Here is SQL to update a correct score: $sql</h3>\n";
		}
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		return TRUE; 
	}

	// todo - find the spot to refresh everyone via websockets
	
	function calculateScoresAndPercentages($theQuestionID) {
		global $conn;
		global $debug;
		
		$sql = "SELECT 
				user_quiz_questions.id AS user_quiz_response_id
				, user_quiz_questions.question_id
				, user_quiz_questions.answer_value
				, quiz_questions.answer
			FROM user_quiz_questions
				, quiz_questions
			WHERE user_quiz_questions.question_id = $theQuestionID
				AND user_quiz_questions.question_id = quiz_questions.id
				AND user_quiz_questions.answer_value IS NOT NULL
			ORDER BY answer_timestamp asc
			;";
		if ($debug == 'TRUE') {
			echo "<h3>Here is SQL to get all results submitted by users: $sql</h3>\n";
		}
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		$correctAnswerCount = 0;
		$a_count = 0;
		$b_count = 0;
		$c_count = 0;
		$d_count = 0;
		while($row = mysqli_fetch_array($result)){
			// check if it's the correct answer
			if ($row['answer_value'] == $row['answer']) {
				$correctAnswerCount++;
				if ($correctAnswerCount < 4) {
					updateScore($row['user_quiz_response_id'],'20');
				}
				else {
					updateScore($row['user_quiz_response_id'],'10');
				}
			}
			switch ($row['answer_value']) {
			    case 'a':
					$a_count++;
			        break;
			    case 'b':
					$b_count++;
			        break;
			    case 'c':
					$c_count++;
			        break;
			    case 'd':
					$d_count++;
			        break;
			}
		}
		$total_count = $a_count + $b_count + $c_count + $d_count;
		if ($total_count > 0) {
			$sql = 'UPDATE quiz_questions SET pct_a = "' . ($a_count/$total_count)*100 . '", pct_b = "' . ($b_count/$total_count)*100 . '", pct_c = "' . ($c_count/$total_count)*100 . '", pct_d = "' . ($d_count/$total_count)*100 . '" WHERE quiz_questions.id = ' . $theQuestionID . ';';
			if ($debug == 'TRUE') {
				echo "<h3>Here is SQL to update the percentages: $sql</h3>\n";
			}
			$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		}
		
		
		
		
		
	    return TRUE; 
	}
	
	echo "</head><body><h1>Quiz - Broadcast Screen</h1>\n";
	?>
	<div class="countdown">
	    <p class="timer">
	        <span id="seconds">&nbsp;&nbsp;</span>
	    </p>
	</div>
				
		<?php		
		if (isset($_GET['debug'])) {
		    $debug = 'TRUE';
		}

	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);

	$sql = "SELECT quiz_question_id, quiz_question_state FROM quiz_admin;";
	if ($debug == 'TRUE') {
		echo "<h3>Here is SQL to see if they have submitted already: $sql</h3>\n";
	}
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($result)){
		$theQuizAdminCurrentQuestionNumber = $row['quiz_question_id'];
		$theQuizAdminCurrentQuestionState = $row['quiz_question_state'];
	}
	if ($debug == 'TRUE') {
		echo "<h1>Here is current state: $theQuizAdminCurrentQuestionNumber and $theQuizAdminCurrentQuestionState</h1>\n";
	}
	
	if ($theQuizAdminCurrentQuestionState == 'question') {
		$sql_update_state = "UPDATE quiz_admin SET quiz_question_id = " . ($theQuizAdminCurrentQuestionNumber) . ", quiz_question_state = '";
		$sql_update_state .= 'results';
		$theQuizAdminCurrentQuestionNumber = $theQuizAdminCurrentQuestionNumber;
		$theQuizAdminCurrentQuestionState = 'results';
	}
	else {
		$sql_update_state = "UPDATE quiz_admin SET quiz_question_id = " . ($theQuizAdminCurrentQuestionNumber + 1) . ", quiz_question_state = '";
		$sql_update_state .= 'question';
		$theQuizAdminCurrentQuestionNumber = $theQuizAdminCurrentQuestionNumber + 1;
		$theQuizAdminCurrentQuestionState = 'question';
	}
	$sql_update_state .= "';";
	if ($debug == 'TRUE') {
		echo "<h3>Here is SQL to iterate the quiz state: $sql</h3>\n";
	}
	$result = mysqli_query($conn,$sql_update_state) or die(mysqli_error($conn));


	// get current quiz question

	$sql = "SELECT id FROM quiz_questions ORDER BY id DESC LIMIT 1;";
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($result)){
		$totalQuestions = $row['id'];
	}
	

	if ($theQuizAdminCurrentQuestionState == 'results') {
		calculateScoresAndPercentages($theQuizAdminCurrentQuestionNumber);
	}

	$sql = "SELECT quiz_questions.*, quiz_admin.quiz_question_state
		FROM quiz_questions, quiz_admin
		WHERE quiz_admin.quiz_question_id = quiz_questions.id
	;";
	if ($debug == 'TRUE') {
		echo "<h3>Here is Question-asking SQL: $sql</h3>\n";
	}
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($result)){
		$theQuestionID = $row['id'];
		$theQuestion = $row['question'];
		$theState = $row['quiz_question_state'];
		$option_a = $row['option_a'];
		$option_b = $row['option_b'];
		$option_c = $row['option_c'];
		$option_d = $row['option_d'];
		$theAnswer = $row['answer'];
		if ($row['pct_a'] != '') {
			$pct_a = $row['pct_a'];
		}
		else {
			$pct_a = 0;
		}
		if ($row['pct_b'] != '') {
			$pct_b = $row['pct_b'];
		}
		else {
			$pct_b = 0;
		}
		if ($row['pct_c'] != '') {
			$pct_c = $row['pct_c'];
		}
		else {
			$pct_c = 0;
		}
		if ($row['pct_d'] != '') {
			$pct_d = $row['pct_d'];
		}
		else {
			$pct_d = 0;
		}
	}
	/*
	$sql = "SELECT * FROM quiz_admin;";
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($result)){
		$currentQuestion = $row['quiz_question_id'];
		$currentQuestionState = $row['quiz_question_state'];
	}
	*/
	// todo - is this inn the right spot?
	
	if ($theState == 'results') {

		$theContents.= "<h3>$theQuestionID) $theQuestion</h3>";

		$theContents.= "<div id='answerSpace' style='hidden'>";

		$theContents.= "<table class='optionlistRESULTS' cellpadding='3' cellspacing='0' width='100%' border>";

		$theContents.= "<tr><td width='50%' class='option_a'>$option_a<br/><span class='answer_pct'>${pct_a}%</span></td>";
			$theContents.= "<td width='50%' class='option_b'>$option_b<br/><span class='answer_pct'>${pct_b}%</span></td></tr>";

		$theContents.= "<tr><td width='50%' class='option_c'>$option_c<br/><span class='answer_pct'>${pct_c}%</span></td>";
			$theContents.= "<td width='50%' class='option_d'>$option_d<br/><span class='answer_pct'>${pct_d}%</span></td></tr>";

		$theContents.= "</table>";

		$theContents.= "</div>";
		
		echo $theContents;
	}
	else {
		if ($priorQuestion < $totalQuestions) {

			echo "<h3>$theQuestionID) $theQuestion</h3>\n";
	
			echo "<table class='optionlistQUESTION' cellpadding='3' cellspacing='0' width='100%' border>\n";
	
			echo "<tr><td width='50%' class='option_a'>$option_a</td>\n";
				echo "<td width='50%' class='option_b'>$option_b</td></tr>\n";

			echo "<tr><td width='50%' class='option_c'>$option_c</td>\n";
				echo "<td width='50%' class='option_d'>$option_d</td></tr>\n";

			echo '</table>' . "\n";
		
		}
		else {
			echo "<h1>Game is now OVER!</h1>";
		}
			
	}
	
	dbClose($conn);

	
  ?>






  
</body>
</html>