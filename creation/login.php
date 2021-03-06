<?php

	// log out of any prior sessions
	
	setcookie("edenuser", "", time() - 3600);
	session_start();
	session_destroy();

	// populate $userArray

	include('config.php');
	include('functions.php');

	$userArray = array();
	$conn = dbOpen($sqlhost, $sqluser, $sqlpass, $sqldb);
	$sql = "SELECT id AS user_id, name_title, first_name, last_name, username, capture_style, hint_level
		FROM users
		ORDER BY last_name, first_name;";
	$result = mysqli_query($conn,$sql) or die(mysqli_error($conn));
	while($row = mysqli_fetch_array($result)){
		$userArray[$row['user_id']]["user_id"] = $row['user_id'];
		$userArray[$row['user_id']]["name_title"] = $row['name_title'];
		$userArray[$row['user_id']]["first_name"] = $row['first_name'];
		$userArray[$row['user_id']]["last_name"] = $row['last_name'];
		$userArray[$row['user_id']]["username"] = $row['username'];
		$userArray[$row['user_id']]["capture_style"] = $row['capture_style'];
		$userArray[$row['user_id']]["hint_level"] = $row['hint_level'];
		
	}
	dbClose($conn);

	// start page

	header('Content-Type: text/html; charset=utf-8');

	echo "<!DOCTYPE html>\n";
	echo "<html lang='en'><head><title>EdenSnake: Login</title>\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . "\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
	echo '<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">' . "\n";
	echo '<link rel="stylesheet" href="edensnake.css">' . "\n";
	echo '<link rel="stylesheet" href="edensnake.css">' . "\n";
	echo '<link rel="icon" type="image/png" href="resources/favicon.png">' . "\n";

	// create local javascript array of all users
	echo '<script>' . "\n";
	echo "var \$localUserArray = [];\n";
	foreach ($userArray AS $theUser) {
		echo '$localUserArray[' . $theUser["user_id"] . '] = "' . $theUser["first_name"] . '";' . "\n";
	}
	echo '</script>' . "\n";
	echo '</head>' . "\n";

	echo '<body>' . "\n";

	echo '<h1>Welcome to EdenSnake!</h1>';
	echo '	<div class="header">';
	echo '    <img src="resources/edensnake-logo.png" alt="EdenSnake" width="80"/>' . "\n";
	echo '	</div>';


	$userLastNameArray = array();

	foreach ($userArray AS $theUser) {
		if (array_key_exists($theUser["last_name"],$userLastNameArray)) {
			$userLastNameArray[$theUser["last_name"]]["first_name_list"] = $userLastNameArray[$theUser["last_name"]]["first_name_list"] . ',' . $theUser["user_id"];
		}
		else {
			$userLastNameArray[$theUser["last_name"]]["first_name_list"] = $theUser["user_id"];
		}
		$userLastNameArray[$theUser["last_name"]]["last_name"] = $theUser["last_name"];
	}

	echo "<p class='loginInstructions'>To log in, select your last name and enter the beginning of your first name.</p>\n";
	
	echo "<ul class='logins'>\n";
	
	?>
	<script>
		function parseMe($theIdSet) {
			$enteredText = document.getElementById('firstNameField').value;
//			alert("Ids: " + $theIdSet + " - text: " + $enteredText);

			$myIDArray = $theIdSet.split(/\s*,\s*/);
			$numberOfMatches = 0;
			for (let $myID of $myIDArray) {
				// test here for a match
				// Use PHP to write out a Javascript Array out of all the User IDs and First and Last Names
				// look up the First Name for this ID, in this Array
				// if it's a UNIQUE match, then we are done... log in as that User ID.
				
				$currentFirstName = $localUserArray[$myID].toLowerCase();

				
				if ($enteredText != "") {
					if ($currentFirstName.startsWith($enteredText.toLowerCase())) {
						// alert ("it is a match!");
						$numberOfMatches++;
						$latestMatchingUserID = $myID;
						$latestMatchingFirstName = $currentFirstName;
					}
					// alert("Check to see if string [" + $enteredText + "] matches the first name (" + $currentFirstName + ") - " + $currentFirstName.startsWith($enteredText) + " - for User ID " + $myID);
				}
			} // end for-loop
			if ($numberOfMatches == 1) {
				// log them in with this ID!
				// alert ("Num of matches: " + $numberOfMatches + " with last ID being: " + $latestMatchingUserID);
				// these three are PHP!!!!
			    var form = document.createElement('form');
			    form.method = "post";
			    form.action = "riddle.php";
				
		        var input = document.createElement('input');
		        input.type = 'hidden';
		        input.name = 'myUserId';
		        input.value = $latestMatchingUserID;
		        form.appendChild(input);
				
				document.body.appendChild(form);

			    form.submit();
				
				/*
				session_start();
				$_SESSION['username'] = $latestMatchingFirstName;
				$_SESSION['userID'] = $latestMatchingUserID;
				*/
			}
		}
		
		var $currentNameFormSpanID = '';
		
		function askFirst($theSpan,$theLastName,$theIdSet) {
			if ($currentNameFormSpanID != '') {
				document.getElementById($currentNameFormSpanID).innerHTML = '';
			}
			$currentNameFormSpanID = $theSpan;

			// read all the ID's from the set into a new array - the ID as key, and the first name as value
			

			// every time a character is typed, compare that with a letter in the array
			// if the first three match exactly one value, then 
			document.getElementById($theSpan).innerHTML = " First name: <input id='firstNameField' onkeyup='parseMe(\"" + $theIdSet + "\");' type='text' name='es_firstName' size='6'/> " + $theLastName;
			
		}
		</script>
	<?php
	$i = 1;
	foreach ($userLastNameArray AS $theLastName) {
		echo "<li><a href='javascript:askFirst(\"login_span_" . $i . '","' . $theLastName['last_name'] . '","' . $theLastName['first_name_list'] . '"' . ");    document.getElementById(\"firstNameField\").focus();'>" . $theLastName["last_name"] . "</a><span id='login_span_$i'></span>\n";
		$i++;
	}
	echo "</ul>\n";
	
	
?>