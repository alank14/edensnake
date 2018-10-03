<html>
<head>
  <meta charset="utf-8">
  <title>Quiz</title>
  <xlink rel="stylesheet" type="text/css" href="style.css">
	  <style>
		  .optionlist td {
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
      
			        document.getElementById("days").innerHTML = parseInt(days, 10);
			        document.getElementById("hours").innerHTML = ("0" + hours).slice(-2);
			        document.getElementById("minutes").innerHTML = ("0" + minutes).slice(-2);
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
				$time = date("m/d/Y h:i:s a", time() + 8);
				echo $time;
				?>');
			  }());
			  </script>
  <?php
  // todo
  // counnt up questionns
  
	include 'pix-header.php';
	include('config.php');
	include('functions.php');
	
	
	echo "</head><body><h1>Quiz - Main Common Screen</h1>\n";
	?>
	<div class="countdown">
	    <p class="timer">
	        <span id="days"></span> Day(s)
	        <span id="hours"></span> Hour(s)
	        <span id="minutes"></span> Minute(s)
	        <span id="seconds"></span> Second(s)
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
	}
	else {
		$sql_update_state = "UPDATE quiz_admin SET quiz_question_id = " . ($theQuizAdminCurrentQuestionNumber + 1) . ", quiz_question_state = '";
		$sql_update_state .= 'question';
	}
	$sql_update_state .= "';";
	if ($debug == 'TRUE') {
		echo "<h3>Here is SQL to iterate the quiz state: $sql</h3>\n";
	}
	$result = mysqli_query($conn,$sql_update_state) or die(mysqli_error($conn));

	if (isset($_GET['showResults'])) {
		$showResults = $_GET['showResults'];
	}
	else {
		$showResults = '';
	}

	// get current quiz question

	$sql = "SELECT id FROM quiz_questions ORDER BY id DESC LIMIT 1;";
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($result)){
		$totalQuestions = $row['id'];
	}
	
	$sql = "SELECT * FROM quiz_admin;";
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($result)){
		$currentQuestion = $row['quiz_question_id'];
	}
	
	if ($showResults == 'TRUE') {
		echo '<h3>Here is RESULTS for question ' . $currentQuestion . '</h3>';
		echo "<h4>After 30 seconds, redirect this page to <a href='./quiz.php?showResults=FALSE'>this link</a></h4>\n";
	}
	else {
		if ($priorQuestion < $totalQuestions) {
			$sql = "UPDATE quiz_admin SET quiz_question_id=$currentQuestion;";
			$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	
			$sql = "SELECT quiz_questions.*, quiz_admin.quiz_question_state
				FROM quiz_questions, quiz_admin
				WHERE quiz_admin.quiz_question_id = quiz_questions.id
			;";
			$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
			while($row = mysqli_fetch_array($result)){
				$theQuestion = $row['question'];
				$theQuestionState = $row['quiz_question_state'];
				$option_a = $row['option_a'];
				$option_b = $row['option_b'];
				$option_c = $row['option_c'];
				$option_d = $row['option_d'];
			}

			echo "<h3>$currentQuestion) $theQuestion - $theQuestionState</h3>\n";
	
			echo "<table class='optionlist' cellpadding='3' cellspacing='0' width='100%' border>\n";
	
			echo "<tr><td width='50%' class='option_a'>$option_a</td>\n";
				echo "<td width='50%' class='option_b'>$option_b</td></tr>\n";

			echo "<tr><td width='50%' class='option_c'>$option_c</td>\n";
				echo "<td width='50%' class='option_d'>$option_d</td></tr>\n";

			echo '</table>' . "\n";
		
			// echo "<h4>After 30 seconds, redirect this page to <a href='./quiz.php?showResults=TRUE'>this link</a></h4>\n";
		}
		else {
			echo "<h1>Game is now OVER!</h1>";
		}
			
	}


	// todo: count down 30 seconds
	// then show answers
	// then show next question
	
	dbClose($conn);

	
  ?>






  
</body>
</html>