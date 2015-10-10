<?php

	session_start();

	function logged_in() {

		return isset( $_SESSION['user_id']) && $_SESSION['user_id'] != "" 
			&& isset($_SESSION['username']) && $_SESSION['username'] != ""
			&& isset($_SESSION['acess_rank']) && $_SESSION['acess_rank'] != ""; 
	}

?>