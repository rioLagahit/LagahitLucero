<?php
	require_once("functions.php");
	//username validation
	function username_validation ($username) {

		$errors = array();

		if ($username == "") {

			$errors[] = "Please choose a Username.";

		} else {

			//checks for invalid characters
			foreach (str_split($username) as $letter) {
				if ( !ctype_alnum($letter) && !( $letter == "_" || $letter == "-" ) ) {
					$errors[] = "Please enter a valid username (letters/ numbers/ underscores/ dashes only).";
					break;
				}
			}

			//checks for length
			if ( strlen($username) < 4 || strlen($username) > 11) {
				$errors[] = "Username too long/ too short. Please enter a username between 4 and 11 characters long.";
			}
		}

		return $errors;
	}

	function password_validation ($password, $confirm) {

		$errors = array();

		if ($password == "" || $confirm == "") {
				
			$errors[] = "Password/Confirm field empty. Please fill in both.";

		} else {

			//checks for invalid characters
			foreach (str_split($password) as $letter) {
				if ( !ctype_alnum($letter) ) {
					$errors[] = "Please enter a password that consists of only letters and numbers.";
					break;
				}
			}

			//checks for length
			if ( strlen($password) < 4 || strlen($password) > 16) {
				$errors[] = "Please enter a password between 4 and 16 characters long.";
			}

			//checks if password and confirm password match
			if ($password != $confirm) {
				$errors[] = "Your password and confirm password do not match.";
			}
		}

		return $errors;
	}

	function email_validation ($email) {

		$errors = array();

		if ($email == "") {
			$errors[] = "Please enter an email address.";
		}else {

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors[] = "Please enter a valid email address.";
			} 
		}

		return $errors;
	}

	function birthday_validation($month,$day,$year) {

		switch ($month) {
			case '01':
			case '03':
			case '05':
			case '07':
			case '08':
			case '10':
			case '12':
				if ($day > 31) {
					$errors[] = "Invalid birthday";
				}
				break;
			case '04':
			case '06':
			case '09':
			case '11':
				if ($day > 30) {
					$errors[] = "Invalid birthday";
				}
				break;
			case '02':

				if (is_leapyear($year)) {
					
					if ($day > 29) {
						$errors[] = "Invalid birthday";
					}
				}else if (!is_leapyear($year)) {
					
					if ($day > 28) {
						$errors[] = "Invalid birthday";
					}
				}
				
				break;	
		}

		return $errors;
	}

?>