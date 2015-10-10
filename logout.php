<?php
	
	require_once("shared/cookies.php");
	require_once("shared/functions.php");

	if ( logged_in() ) {
		
		$_SESSION = array();

		if ( isset($_COOLIE[session_name()]) ) {
			setcookie(session_name(), null, time() - 1000000, '/');
		}

		session_destroy();
	}

	redirect_to("index_.php");

?>