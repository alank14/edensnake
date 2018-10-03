<html>
<head>
  <meta charset="utf-8">
  <title>Quiz - Client</title>
  <xlink rel="stylesheet" type="text/css" href="style.css">
	  <style>
		  .optionlist td {
			  height: 100px;
			  text-align: center;
			  font-weight: bold;
			  cursor: pointer;
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
			 function submitAnswer($theAnswer) {
				//  alert ("hi there " + $theAnswer);
		// todo: make it beep ?
				  document.getElementById("submitted_question_answer").value = $theAnswer;
				  document.getElementById("submitForm").submit();
			  }
			  
			  </script>
  <?php
  // todo
  // count up questions
  
	if (isset($_GET['debug'])) {
	    $debug = 'TRUE';
	}
  
	include 'pix-header.php';
	include('config.php');
	include('functions.php');

	// todo - dump this variable!!
	if (isset($_GET['current_question'])) {
		$theCurrentQuestion = $_GET['current_question'];
	}
	else {
		$theCurrentQuestion = '1';
	}
	
	if (isset($_GET['submitted_question_number'])) {
		$submitted_question_number = $_GET['submitted_question_number'];
	}
	else {
		$submitted_question_number = '';
	}
	if (isset($_GET['submitted_question_answer'])) {
		$submitted_question_answer = $_GET['submitted_question_answer'];
	}
	else {
		$submitted_question_answer = '';
	}
	
	echo "</head><body><h1>Quiz - Client App</h1>\n";


	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);

	// first check if they have an answer in there already.
	// if NOT, then insert it.
	$sql = "SELECT count(user_quiz_questions.id) FROM user_quiz_questions, quiz_admin where user_quiz_questions.user_id = $currentUserID AND quiz_admin.quiz_question_id = user_quiz_questions.question_id;";
	if ($debug == 'TRUE') {
		echo "<h3>Here is SQL to see if they have submitted already: $sql</h3>\n";
	}
	
	$rows = mysqli_query($conn,$sql);
	$count = mysqli_fetch_array($rows);
	if ($debug == 'TRUE') {
		echo "<h1>here is cool count: " . $count[0] . "</h1>";
	}

	if ($count[0] == 0) {
		$answerSubmissionState = "not_submitted";
	}
	else {
		$answerSubmissionState = "already_submitted";
	}

	$sql = "SELECT quiz_question_id, quiz_question_state FROM quiz_admin;";
	if ($debug == 'TRUE') {
		echo "<h3>Here is SQL to see if they have submitted already: $sql</h3>\n";
	}
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($result)){
		$theQuizAdminCurrentQuestionNumber = $row['quiz_question_id'];
		$theQuizAdminCurrentQuestionState = $row['quiz_question_state'];
	}
	
	if ($submitted_question_answer != '') {
		// todo: check to confirm that $submitted_question_number is equal to the very question-number that is LIVE in quiz_admin!
		if (
			($answerSubmissionState == "not_submitted")
				&& ($submitted_question_number == $theQuizAdminCurrentQuestionNumber)
					&& ($theQuizAdminCurrentQuestionState == 'question')
			) {
	
				// todo - do NOT insert... if the time is up! if the quiz-admin state is not on this very question and the state is in "question" state
			
			$sql = "INSERT INTO user_quiz_questions (user_id, question_id, answer_value, answer_timestamp) VALUES ($currentUserID,$submitted_question_number,'$submitted_question_answer',NOW())
			;";
			if ($debug == 'TRUE') {
				echo "<h3>Here is SQL: $sql</h3>\n";
			}
			$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
			if ($debug == 'TRUE') {
				echo "<h3>Here is result: $result - a number 1 means successfully inserted</h3>\n";
			}
			$answerSubmissionState = "already_submitted";
		}
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
		$pct_a = $row['pct_a'];
		$pct_b = $row['pct_b'];
		$pct_c = $row['pct_c'];
		$pct_d = $row['pct_d'];
	}


	// if they submitted an answer
	// or if the time is up... 
	// show a HOLD screen... and do a Refresh every 5 seconds until DB says: time to load the next answer-screen.
	
	$theContents = '';

	if ($debug == 'TRUE') {
		echo "<h3>Here is the ADMIN state: $theState and here is the USER submissionState: $answerSubmissionState</h3>";
	}
	
	if (($theState == 'question') && ($answerSubmissionState == "not_submitted")) {

		$theContents.= "<h3>$theQuestionID) $theQuestion</h3>";
	
		$theContents.= "<div id='answerSpace'>";
	
		$theContents.= "<table class='optionlist' cellpadding='3' cellspacing='0' width='100%' border>";
	
		$theContents.= "<tr><td width='50%' class='option_a' onClick='" . 'submitAnswer(\"a\");' . "'>$option_a</td>";
			$theContents.= "<td width='50%' class='option_b' onClick='" . 'submitAnswer(\"b\");' . "'>$option_b</td></tr>";

		$theContents.= "<tr><td width='50%' class='option_c' onClick='" . 'submitAnswer(\"c\");' . "'>$option_c</td>";
			$theContents.= "<td width='50%' class='option_d' onClick='" . 'submitAnswer(\"d\");' . "'>$option_d</td></tr>";

		$theContents.= "</table>";
	
		$theContents.= "</div>";
	}

	else if (($theState == 'question') && ($answerSubmissionState == "already_submitted")) {
		$theContents.= "<h3>Results are coming soon!</h3>";
		$theContents.= "<h3><a href='./quiz-client.php'>Click to refresh this page, cleanly.</a></h3>";
	}

	else if ($theState == 'results') {

		$theContents.= "<h3>$theQuestionID) $theQuestion - RESULTS</h3>";

		$theContents.= "<div id='answerSpace' style='hidden'>";

		$theContents.= "<table class='optionlist' cellpadding='3' cellspacing='0' width='100%' border>";

		$theContents.= "<tr><td width='50%' class='option_a'>$option_a<br/><span class='answer_pct'>$pct_a</span></td>";
			$theContents.= "<td width='50%' class='option_b'>$option_b<br/><span class='answer_pct'>$pct_b</span></td></tr>";

		$theContents.= "<tr><td width='50%' class='option_c'>$option_c<br/><span class='answer_pct'>$pct_c</span></td>";
			$theContents.= "<td width='50%' class='option_d'>$option_d<br/><span class='answer_pct'>$pct_d</span></td></tr>";

		$theContents.= "</table>";

		$theContents.= "</div>";
		
	}
	else if ($theState == 'pause') {
		$theContents.= "<h3>Quiz is in PAUSE mode!</h3>";
	}
	
	echo "<div id='question_or_results'>\n";
	

	echo "</div>\n";
	
	echo "<script>\n";
	echo 'document.getElementById("question_or_results").innerHTML = "' . $theContents . '";';
	echo "</script>\n";

	dbClose($conn);

	


	echo '<form method="get" action="./quiz-client.php" id="submitForm">' . "\n";
	echo '<input type="hidden" name="submitted_question_number" value="' . $theQuizAdminCurrentQuestionNumber . '"/>' . "\n";
	echo '<input type="hidden" id="submitted_question_answer" name="submitted_question_answer" value=""/>' . "\n";
//	echo '<input type="submit">' . "\n";
	echo '</form>' . "\n";
		?>

  
</body>
</html>