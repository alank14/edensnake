<?php
	
$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);

// Initialize Arrays

$riddleArray = array();
$falseAnswerArray = array();
$userRiddleArray = array();
$userArray = array();
$riddleQuestionKeyArray = array();

$feedbackMessage = '';

// reset if you want to clear user settings
if (isset($_GET['reset'])) {
	$sql2 = 'UPDATE user_riddles SET next_day=NULL, day_1_proposed_riddle_id=NULL, day_2_proposed_riddle_id=NULL, day_3_proposed_riddle_id=NULL, day_4_proposed_riddle_id=NULL, day_5_proposed_riddle_id=NULL, day_6_proposed_riddle_id=NULL, day_7_proposed_riddle_id=NULL WHERE user_id=' . $currentUserID . ';';
	$result = mysqli_query($conn,$sql2) or die(mysqli_error($conn));
}

// populate $userRiddleArray
$sql = "SELECT user_id, 
	day_1_proposed_riddle_id, day_2_proposed_riddle_id, day_3_proposed_riddle_id, 
	day_4_proposed_riddle_id, day_5_proposed_riddle_id, day_6_proposed_riddle_id, 
	day_7_proposed_riddle_id, next_day
	FROM user_riddles
	WHERE user_id = $currentUserID;";
	
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
while($row = mysqli_fetch_array($result)){
	$userRiddleArray[$row['user_id']][1] = $row['day_1_proposed_riddle_id'];
	$userRiddleArray[$row['user_id']][2] = $row['day_2_proposed_riddle_id'];
	$userRiddleArray[$row['user_id']][3] = $row['day_3_proposed_riddle_id'];
	$userRiddleArray[$row['user_id']][4] = $row['day_4_proposed_riddle_id'];
	$userRiddleArray[$row['user_id']][5] = $row['day_5_proposed_riddle_id'];
	$userRiddleArray[$row['user_id']][6] = $row['day_6_proposed_riddle_id'];
	$userRiddleArray[$row['user_id']][7] = $row['day_7_proposed_riddle_id'];
	$userRiddleArray[$row['user_id']]["next_day"] = $row['next_day'];
}

// populate $riddleArray
$sql = "SELECT day, riddle, riddle_question_key, riddle_answer_key, riddle_answer, quote_english, quote_hebrew, qr_style, photo_long FROM riddles;";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
while($row = mysqli_fetch_array($result)){
	$riddleArray[$row['day']]["day"] = $row['day'];
	$riddleArray[$row['day']]["riddle"] = $row['riddle'];
	$riddleArray[$row['day']]["riddle_question_key"] = $row['riddle_question_key'];
	$riddleArray[$row['day']]["riddle_answer_key"] = $row['riddle_answer_key'];
	$riddleArray[$row['day']]["riddle_answer"] = $row['riddle_answer'];
	$riddleArray[$row['day']]["quote_english"] = $row['quote_english'];
	$riddleArray[$row['day']]["quote_hebrew"] = $row['quote_hebrew'];
	$riddleArray[$row['day']]["qr_style"] = $row['qr_style'];
	$riddleArray[$row['day']]["photo_long"] = $row['photo_long'];

	$riddleQuestionKeyArray[$row['riddle_question_key']] = $row['day'];
}

// populate $falseAnswerArray
$sql = "SELECT riddle_answer_key, riddle_answer FROM false_answers;";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
while($row = mysqli_fetch_array($result)){
	$falseAnswerArray[$row['riddle_answer_key']]["riddle_answer_key"] = $row['riddle_answer_key'];
	$falseAnswerArray[$row['riddle_answer_key']]["riddle_answer"] = $row['riddle_answer'];
}

// populate $userArray
$sql = "SELECT id AS user_id, name_title, first_name, last_name, username, capture_style, hint_level
	FROM users;";
$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
while($row = mysqli_fetch_array($result)){
	$userArray[$row['user_id']]["name_title"] = $row['name_title'];
	$userArray[$row['user_id']]["first_name"] = $row['first_name'];
	$userArray[$row['user_id']]["last_name"] = $row['last_name'];
	$userArray[$row['user_id']]["username"] = $row['username'];
	$userArray[$row['user_id']]["capture_style"] = $row['capture_style'];
	$userArray[$row['user_id']]["hint_level"] = $row['hint_level'];
}

// if next_day is blank, then fill it in randomly
if ($userRiddleArray[$currentUserID]['next_day'] == '') {
	$rday=rand(1,7);
	$sql2 = 'UPDATE user_riddles SET next_day=' . $rday . ' WHERE user_id=' . $currentUserID . ';';
	$result = mysqli_query($conn,$sql2) or die(mysqli_error($conn));
}
else {
	$rday = $userRiddleArray[$currentUserID]['next_day'];
}

$answersCorrect = '0';
for ($i=1; $i<=7; $i++) {
	if ($userRiddleArray[$currentUserID][$i] == $i) {
		$answersCorrect++;
	}
}

// check if an answer is being submitted
if (isset($_GET['rqkey'])) {
    $rqkey = $_GET['rqkey'];
	$prior_rday = $riddleQuestionKeyArray[$rqkey];
}

// no - only initialize this at START -- then just reset ONCE PHOTO HAS BEEN SUBMITTED
// on receiving of GET variable for that photo, then you can reset it.
$riddleJustAnsweredCorrectly = '';

// Check if an answer is submitted.
if (isset($_GET['ranswer']) && ($userRiddleArray[$currentUserID][$prior_rday] != $prior_rday)) {
	// user is submitting the answer to a question they did not get right before
    $ranswer = $_GET['ranswer'];
	if ($ranswer == $riddleArray[$prior_rday]["riddle_answer_key"]) {
		// Answer is Correct!
		
		// update the database and array so that their correct answer is recorded
		updateUserAnswer($currentUserID,$rday);
		$answersCorrect++;
		
		// update the array
		$userRiddleArray[$currentUserID][$rday] = $rday;
		
		$riddleJustAnsweredCorrectly = $rday;

		// if all answers are now correct, then MOVE ON!
		if ($answersCorrect == 7) {
			$feedbackMessage = 'Congratulations! All 7 are now correct!';
		}
		// otherwise, move to a random unanswered question
		else {
			$rday=rand(1,7);
			if($userRiddleArray[$currentUserID][$rday] == $rday) {
				while($userRiddleArray[$currentUserID][$rday] == $rday) {
					$rday=rand(1,7);
				}
			}
			updateNewDay($currentUserID,$rday);
			$feedbackMessage = "CORRECT! Next riddle:";
		}
	}
	else {
		$feedbackMessage = "WRONG! Try again:";
	}
}

dbClose($conn);
?>
