<?php
	include 'pix-header.php';
?><html>
<head>
  <meta charset="utf-8">
  <title>Quiz Admin</title>
  <xlink rel="stylesheet" type="text/css" href="style.css">
	  
  <?php
  // todo
  // count up questions
  
	include('config.php');
	include('functions.php');

	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);

	if (isset($_GET['newquestion'])) {
		$thenewquestion = $_GET['newquestion'];
	}
	else {
		$thenewquestion = '';
	}
	
	if (isset($_GET['newstate'])) {
		$thenewstate = $_GET['newstate'];
	}
	else {
		$thenewstate = '';
	}
	
	if (isset($_GET['wipeUserAnswers'])) {
		$wipeUserAnswers = $_GET['wipeUserAnswers'];
	}
	else {
		$wipeUserAnswers = '';
	}
	
	if ($thenewquestion != '') {
		$sql = "UPDATE quiz_admin SET quiz_question_id = '$thenewquestion', quiz_question_state = '$thenewstate';";
		// echo $sql;
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	}
	
	if ($wipeUserAnswers == 'TRUE') {
		$sql = "DELETE FROM user_quiz_questions;";
		// echo $sql;
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		$sql = "UPDATE quiz_admin SET quiz_question_id = 0, quiz_question_state = 'results';";
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
		$sql = "UPDATE quiz_questions SET pct_a = NULL, pct_b = NULL, pct_c = NULL, pct_d = NULL;";
		$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	}
	
	
	echo "</head><body><h1>Quiz Admin App</h1>\n";


	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);



	// if they submitted an answer
	// or if the time is up... 
	// show a HOLD screen... and do a Refresh every 5 seconds until DB says: time to load the next answer-screen.
	
	$sql = "SELECT quiz_admin.*
		FROM quiz_admin
	;";
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($result)){
		$theCurrentQuestion = $row['quiz_question_id'];
		$theCurrentState = $row['quiz_question_state'];
	}

	echo "<h3>CURRENT Question: $theCurrentQuestion - $theCurrentState</h3>\n";

	?>
	
	<form method="get" action="quiz-admin.php">
		<input type="radio" name="newquestion" value="1" <?php
		if ($theCurrentQuestion == '1') {
			echo 'checked';
		}
		?>> 1<br/>
		<input type="radio" name="newquestion" value="2" <?php
		if ($theCurrentQuestion == '2') {
			echo 'checked';
		}
		?>> 2<br/>
		<input type="radio" name="newquestion" value="3" <?php
		if ($theCurrentQuestion == '3') {
			echo 'checked';
		}
		?>> 3<br/>
		<input type="radio" name="newquestion" value="4" <?php
		if ($theCurrentQuestion == '4') {
			echo 'checked';
		}
		?>> 4<br/>
		<input type="radio" name="newquestion" value="5" <?php
		if ($theCurrentQuestion == '5') {
			echo 'checked';
		}
		?>> 5<br/>
		<input type="radio" name="newquestion" value="6" <?php
		if ($theCurrentQuestion == '6') {
			echo 'checked';
		}
		?>> 6<br/>
		
		<br/>
		<input type="radio" name="newstate" value="question" <?php
		if ($theCurrentState == 'question') {
			echo 'checked';
		}
		?>> question<br/>
		<input type="radio" name="newstate" value="results" <?php
		if ($theCurrentState == 'results') {
			echo 'checked';
		}
		?>> results<br/>
		
		<input type="submit"/>
	</form>
	
	<h2><a href="./quiz-admin.php?wipeUserAnswers=TRUE">Wipe All User Answers</a></h2>
	
	<h2><a href="./quiz.php">Quiz Broadcast Screen</a></h2>

	<h2><a href="./quiz-client.php">Quiz Client Screen</a></h2>

	<h2><a href="./approve-photos.php">Photo Approval</a></h2>
	
	<h2><a href=".iview/photoz.php">Photo Viewer</a></h2> 
	
	<?php
	
	dbClose($conn);

	

	?>
  
</body>
</html>