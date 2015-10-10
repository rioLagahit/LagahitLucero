<?php

	require_once("shared/constants.php");

	//global variable:
	$connection;

	function is_leapyear($year) {

		if ($year % 4 == 0) {
			
			if ($year % 100 == 0) {
				
				if ($year % 400 == 0) {
					return true;
				}

			} else {

				return true;
			}
		}

		return false;
	}

	function check_required_values($array, $method) {

		if ($method == "get") {
			foreach ($array as $val) {
				if ( !isset($_GET[$val]) || $_GET[$val] == "" ) {
					
					redirect_to("index_.php");
				}
			}
		} else if ($method == "post") {
			foreach ($array as $val) {
				if ( !isset($_POST[$val]) || $_POST[$val] == "" ) {
					
					redirect_to("index_.php");
				}
			}
		}
	}

	function connect_to_database() {

		global $connection;
		$connection = mysql_connect(SERVER,USER,PASSWORD);

		if (!$connection) {
			die("Database connection failed: " . mysql_error());
		}
	}

	function select_database($database = MAIN_DATABASE) {

		global $connection;
		$db = mysql_select_db($database,$connection);
		if (!$db) {
			die("Database selection failed: " . mysql_error());
		}
	}

	function close_connection() {
		global $connection;
		if (isset($connection)) {
			mysql_close($connection);
		}
	}

	//returns a single value
	function get_value_from_db ($table, $field, $id_field, $id) {

		global $connection;
		$query = "SELECT $field "; 
		$query .= "FROM {$table} ";
		$query .= "WHERE {$id_field} = '{$id}' ";

		$result = mysql_query($query, $connection);
		if (!$result) {
			die("Database query failed: " . mysql_error());
		}

		return mysql_fetch_array($result)[$field];
	}

	//returns an array
	function get_array_from_db ($query) {

		global $connection;
		$arr = array();

		$result = mysql_query($query, $connection);
		if (!$result) {
			die("Database query failed: " . mysql_error());
		}

		while ($row = mysql_fetch_array($result)) {
			
			$arr[] = $row;
		}

		return $arr;
	}

	function query_select($table, $fields = "*") {

		return "SELECT {$fields} FROM {$table} ";
	}

	function query_select_where($field, $value) {

		return "WHERE {$field} = '{$value}' ";
	}

	function query_select_and($field, $value) {

		return "AND {$field} = '{$value}' ";
	}

	function query_select_order($order_by, $order_value) {

		return "ORDER BY {$order_by} $order_value ";
	}

	function get_num_rows ($table, $field, $value, $case_sensitive = false) {

		global $connection;
		$arr = array();

		$query = "SELECT * "; 
		$query .= "FROM {$table} ";
		$query .= "WHERE "; 
		if ($case_sensitive == true) { 
			$query .= "BINARY "; 
		} 
		$query .= "{$field} = '{$value}' ";

		$result = mysql_query($query, $connection);
		if (!$result) {
			die("Database query failed: " . mysql_error());
		}

		return mysql_num_rows($result);
	}

	function get_all_rows ($table) {

		global $connection;
		$arr = array();

		$query = "SELECT * "; 
		$query .= "FROM {$table} ";

		$result = mysql_query($query, $connection);
		if (!$result) {
			die("Database query failed: " . mysql_error());
		}

		return mysql_num_rows($result);
	}

	function redirect_to($page) {
		header("Location: {$page}");
		die();
	}

	function print_array( $arr ) {
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}

	function mysql_prep($str) {
		return mysql_real_escape_string(trim($str));
	}

?>