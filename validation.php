<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/validation_functions.php"); ?>
<?php require_once("shared/encryption.php"); ?>
<?php
	
	//checks for page requirements
	if ( (!isset($_POST['submit']) || $_POST['submit'] == "") && (!isset($_GET['ban_id']) || $_GET['ban_id'] == "")) {
		redirect_to("not_found.php");
	}

	//Validation for register
	if ( $_POST['submit'] == "Register" ) {
		
		$username = mysql_prep($_POST['username']);
		$password = $_POST['password'];
		$confirm = $_POST['confirm'];
		$email = mysql_prep($_POST['email']);
		$location = mysql_prep($_POST['location']);
		$gender = mysql_prep($_POST['gender']);
		$month = mysql_prep($_POST['month']);
		$day = mysql_prep($_POST['day']);
		$year = mysql_prep($_POST['year']);
		$errors = array();

		//username validation
		foreach ( username_validation ($username) as $error ) {
			$errors[] = $error;
		}

		//checks database if username is already in use
		connect_to_database();
		select_database();
		if (get_num_rows("users", "username", $username) != 0) {
			$errors[] = "Desired username is already in use. Please pick another one. NOTE: Username is not case sensitive.";
		}
		close_connection($connection);
				

		//password validation
		foreach ( password_validation ($password,$confirm) as $error ) {
			$errors[] = $error;
		}
			

		//email validation
		foreach ( email_validation ($email) as $error ) {
			$errors[] = $error;
		}

		//checks database if email is already in use
		connect_to_database();
		select_database();
		if (get_num_rows("users", "email", $email, true) != 0) {
			$errors[] = "Email already used by another member.";
		}
		close_connection($connection);
			

		//birthday validation
		foreach ( birthday_validation ($month,$day,$year) as $error ) {
			$errors[] = $error;
		}
			

		//checks if the user ticked the agreement checkbox
		if ( !isset($_POST['agree']) ) {
			$errors[] = "The registration cannot continue if you chose not to accept the Forum Rules.";
		}

		if (!empty($errors)) {
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		} else {

			//Register user

			connect_to_database();
			select_database();

			$hashed_password = encrypt($password);
			$birthday = "{$year}-{$month}-{$day}";

			$query = "INSERT INTO users ";
			$query .= "(username, password, birthday, location, email, gender) ";
			$query .= "VALUES ('{$username}', '{$hashed_password}', '{$birthday}', '{$location}', '{$email}', '{$gender}') ";

			$result = mysql_query($query, $connection);
			if (!$result) {
				die("Database query failed: " . mysql_error());
			}else {
				
				$message = "Registration successful. You may now login.";
				redirect_to("message.php?message=" . $message);
			}

			close_connection($connection);
		}
	}

	//Validation for login
	if ( $_POST['submit'] == "Login" ) {

		$username = mysql_prep($_POST['username']);
		$password = $_POST['password'];

		connect_to_database();
		select_database();

		$query = query_select("users");
		$query .= query_select_where("username", $username);
		$query .= query_select_and("password", encrypt($password));

		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		}

		if (mysql_num_rows($result) == 1) {
			
			$user = mysql_fetch_array($result);

			if ( $user['banned'] == 1) {
				close_connection($connection);
				redirect_to("message.php?message=You area banned from this forum.");				
			} else {
				
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['acess_rank'] = $user['acess_rank'];
				close_connection($connection);
				redirect_to("profile.php");
			}

		}else {
			$error = "Incorrect Username/Password";
			close_connection($connection);
			redirect_to("error.php?errors={$error}");
		}
	}

	//updating password
	if ( $_POST['submit'] == "Update Password" ) {

		$user_id = $_SESSION['user_id'];
		$current_password = $_POST['current_password'];
		$new_password = $_POST['new_password'];
		$confirm_password = $_POST['confirm_password'];
		$errors = array();

		connect_to_database();
		select_database();

		foreach ( password_validation ($new_password, $confirm_password, $current_password) as $error ) {
			$errors[] = $error;
		}

		$query = query_select("users");
		$query .= query_select_where("user_id", $user_id);
		$query .= query_select_and("password", encrypt($current_password));

		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		}

		if (mysql_num_rows($result) == 0) {
			
			$errors[] = "Incorrect Password";
		}

		if ( empty($errors) ) {
			
			$query = " UPDATE users";
			$query .= " SET password='" . encrypt($new_password) . "' "; 
			$query .= " WHERE user_id='{$user_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				redirect_to("message.php?message=Password updated.");
			}

		} else {
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//updating email
	if ( $_POST['submit'] == "Update Email" ) {

		connect_to_database();
		select_database();

		$user_id = $_SESSION['user_id'];
		$email = mysql_prep($_POST['email']);
		$current_email = get_value_from_db ("users", "email", "user_id", $user_id);
		$errors = array();

		if ( $current_email == $email ) {
			redirect_to("message.php?message=No changes made.");
		}

		foreach ( email_validation ($email) as $error ) {
			$errors[] = $error;
		}

		//checks if email is already in the database
		if (get_num_rows("users", "email", $email, true) != 0) {
			$errors[] = "Email already used by another member.";
		}

		if ( empty($errors) ) {
			
			$query = " UPDATE users";
			$query .= " SET email='{$email}'"; 
			$query .= " WHERE user_id='{$user_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("message.php?message=Email updated.");
			}

		} else {
			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//updating location
	if ( $_POST['submit'] == "Update Location" ) {

		connect_to_database();
		select_database();

		$user_id = $_SESSION['user_id'];
		$location = mysql_prep($_POST['location']);

		$query = " UPDATE users";
		$query .= " SET location='{$location}'"; 
		$query .= " WHERE user_id='{$user_id}' ";
		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		} else {
			close_connection($connection);
			redirect_to("message.php?message=Location updated.");
		}
	}

	//updating about me
	if ( $_POST['submit'] == "Update About Me" ) {

		connect_to_database();
		select_database();

		$user_id = $_SESSION['user_id'];
		$about_me = mysql_prep($_POST['about_me']);
		$errors = array();

		if ( strlen($about_me) > 2000 ) {
			$errors[] = "Character limit for about me section is 500";
		}

		if ( empty($errors) ) {
			
			$query = " UPDATE users";
			$query .= " SET about_me='{$about_me}'"; 
			$query .= " WHERE user_id='{$user_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("message.php?message=About Me updated.");
			}

		} else {
			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//updating signature
	if ( $_POST['submit'] == "Update Signature" ) {

		connect_to_database();
		select_database();

		$user_id = $_SESSION['user_id'];
		$signature = mysql_prep($_POST['signature']);
		$errors = array();

		if ( strlen($signature) > 250 ) {
			$errors[] = "Character limit for about me section is 250";
		}

		if ( empty($errors) ) {
			
			$query = " UPDATE users";
			$query .= " SET signature='{$signature}'"; 
			$query .= " WHERE user_id='{$user_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("message.php?message=Signature updated.");
			}

		} else {
			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//updating profile image
	if ( $_POST['submit'] == "Update Picture" ) {

		$user_id = $_SESSION['user_id'];
		$type = $_FILES['img']['type'];
		$name = $_SESSION['username'];
		$image_directory = "images/users/";
		$file_type;
		$errors = array();

		connect_to_database();
		select_database();

		switch ($type) {
			case 'image/jpeg':
				$file_type = ".jpg";
				break;

			case 'image/png':
				$file_type = ".png";
				break;

			case 'image/gif':
				$file_type = ".gif";
				break;
			
			default:
				$file_type = "invalid";
				break;
		}

		if ($_FILES['img']['error'] == 0 && $file_type != "invalid") {

			$name .= $file_type;
			$current_image = get_value_from_db ("users", "image_name", "user_id", $user_id);

			//checks if the user already have an image uploaded,
			//then deletes it if it exists
			if ( $current_image != null) {
				
				if ( file_exists( $image_directory . $current_image ) ) {
					unlink($image_directory . $current_image);
				}
			}
			
			move_uploaded_file($_FILES['img']['tmp_name'], $image_directory . $name);

			$query = " UPDATE users";
			$query .= " SET image_name='{$name}'"; 
			$query .= " WHERE user_id='{$user_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("message.php?message=Image updated.");
			}						

		} else {

			switch ($_FILES['img']['error']) {
				
				case 1:
					$errors[] = "File size too large.";
					break;
				
				case 2:
					$errors[] = "File size too large.";
					break;

				case 4:
					$errors[] = "Please choose an image to upload.";
					break;

				case 8:
					$errors[] = "Invalid file extension.";
					break;

				default:
					$errors[] = "Cannot upload file.";
					break;
			}

			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//unban user
	if ( isset($_GET['ban_id']) ) {

		connect_to_database();
		select_database();

		$user_id = $_GET['ban_id'];
		$current_banned = get_value_from_db ("users", "banned", "user_id", $user_id);
		$banned = ($current_banned == 0) ? 1 : 0;
		$action = ($current_banned == 0) ? "Banned." : "Unbanned.";

		$query = " UPDATE users";
		$query .= " SET banned='{$banned}'"; 
		$query .= " WHERE user_id='{$user_id}' ";
		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		} else {
			close_connection($connection);
			redirect_to("message.php?message=User {$action}");
		}
	}

	//change rank acess
	if ( $_POST['submit'] == "Change User Rank" ) {
		
		connect_to_database();
		select_database();

		$user_id = $_POST['user_id'];
		$acess_rank = $_POST['acess_rank'];

		$query = " UPDATE users";
		$query .= " SET acess_rank='{$acess_rank}'"; 
		$query .= " WHERE user_id='{$user_id}' ";
		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		} else {
			close_connection($connection);
			redirect_to("message.php?message=User rank updated.");
		}
	}

	//validation for posting a thread
	if ( $_POST['submit'] == "Post") {
	
		$errors = array();

		if ( logged_in() ) {
			$title = mysql_prep($_POST['title']);
			$content = mysql_prep($_POST['content']);
			$forum_id = $_POST['forum_id'];
			$user_id = $_SESSION['user_id'];
		} else {
			$errors[] = "You are not logged in.";
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}

		if ( $title == "" ) {
			$errors[] = "Title empty.";
		}

		if ( $content == "" ) {
			$errors[] = "Content empty.";
		}

		if (!empty($errors)) {
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		} else {

			//add thread to database

			connect_to_database();
			select_database();

			$query = "INSERT INTO threads ";
			$query .= " (forum_id, thread_name, author_id) ";
			$query .= "VALUES ('{$forum_id}', '{$title}', '{$user_id}') ";

			$result = mysql_query($query, $connection);
			if (!$result) {
				die("Database query failed: " . mysql_error());
			}

			//add post to database
			$thread_id = mysql_insert_id();
			$query = "INSERT INTO posts ";
			$query .= " (thread_id, posted_by, content) ";
			$query .= "VALUES ('{$thread_id}', '{$user_id}', '{$content}') ";

			$result = mysql_query($query, $connection);
			if (!$result) {
				die("Database query failed: " . mysql_error());
			}

			//updates thread's last posts
			$last_post_id = mysql_insert_id();
			$query = "UPDATE threads ";
			$query .= " SET last_post_id = {$last_post_id} ";
			$query .= " WHERE thread_id = {$thread_id} ";

			$result = mysql_query($query, $connection);
			if (!$result) {
				die("Database query failed: " . mysql_error());
			}

			$message = "Thread Created.";
			redirect_to("thread.php?thread={$thread_id}");	

			close_connection($connection);
		}
	}

	//validation for posting a reply
	if ( $_POST['submit'] == "Reply") {
	
		$errors = array();

		if ( logged_in() ) {
			$content = mysql_prep($_POST['content']);
			$thread_id = $_POST['thread_id'];
			$user_id = $_SESSION['user_id'];
		} else {
			$errors[] = "You are not logged in.";
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}

		if ( $content == "" ) {
			$errors[] = "Content empty.";
		}

		if (!empty($errors)) {
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		} else {

			//add post to database

			connect_to_database();
			select_database();

			$query = "INSERT INTO posts ";
			$query .= " (thread_id, posted_by, content) ";
			$query .= "VALUES ('{$thread_id}', '{$user_id}', '{$content}') ";

			$result = mysql_query($query, $connection);
			if (!$result) {
				die("Database query failed: " . mysql_error());
			}

			//update thread's last post
			$last_post_id = mysql_insert_id();
			$query = "UPDATE threads ";
			$query .= " SET last_post_id = {$last_post_id} ";
			$query .= " WHERE thread_id = {$thread_id} ";

			$result = mysql_query($query, $connection);
			if (!$result) {
				die("Database query failed: " . mysql_error());
			}

			$message = "Reply Posted.";
			redirect_to("thread.php?thread={$thread_id}");

			close_connection($connection);
		}
	}

	//updating section name
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_section_name") {

		$section_id = $_POST['section_id'];
		$name = mysql_prep($_POST['section_name']);

		connect_to_database();
		select_database();

		if ( strlen($name) > 20 ) {
			$errors[] = "Character limit for Section Name is 20";
		}

		if ( $name == "" ) {
			$errors[] = "Name cannot be empty.";
		}

		if ( empty($errors) ) {
			
			$query = " UPDATE sections";
			$query .= " SET section_name='{$name}'"; 
			$query .= " WHERE section_id='{$section_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("index_.php");
			}

		} else {
			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//updating section position
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_section_position" ) {

		$section_id = $_POST['section_id'];
		$initial_position = mysql_prep($_POST['initial_position']);
		$new_position = mysql_prep($_POST['new_position']);

		connect_to_database();
		select_database();

		if ( $initial_position != $new_position ) {
			
			 if ( $initial_position > $new_position ) {
				$query = " UPDATE sections";
				$query .= " SET position=position+1 "; 
				$query .= " WHERE position < '{$initial_position}' ";
				$query .= " AND position >= '{$new_position}' ";
				$result = mysql_query($query, $connection);
				if ( !$result ) {
					die("Error executing query: " . mysql_error() );
				}
			} else if ( $initial_position < $new_position ) {
				$query = " UPDATE sections";
				$query .= " SET position=position-1 "; 
				$query .= " WHERE position > '{$initial_position}' ";
				$query .= " AND position <= '{$new_position}' ";
				$result = mysql_query($query, $connection);
				if ( !$result ) {
					die("Error executing query: " . mysql_error() );
				}
			}

			$query = " UPDATE sections";
			$query .= " SET position='{$new_position}'"; 
			$query .= " WHERE section_id='{$section_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			}
		}

		close_connection($connection);
		redirect_to("index_.php");
	}

	//updating forum section
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_forum_section" ) {

		connect_to_database();
		select_database();

		$forum_id = $_POST['forum_id'];
		$initial_section = get_value_from_db("forums", "section_id", "forum_id", $forum_id);
		$new_section = $_POST['new_section'];
		$position = get_num_rows("forums", "section_id", $new_section) + 1;

		$query = " UPDATE forums";
		$query .= " SET section_id='{$new_section}', ";
		$query .= " position='{$position}' ";
		$query .= " WHERE forum_id='{$forum_id}' ";
		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		}

		$query = "SET @pos := 0 ";
		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		}
 		$query = " UPDATE forums SET position = ( SELECT @pos := @pos + 1 ) WHERE section_id = {$initial_section} ORDER BY position ASC ";
 		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		}

		close_connection($connection);
		redirect_to("index_.php");
	}

	//updating forum name
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_forum_name" ) {

		$forum_id = $_POST['forum_id'];
		$name = mysql_prep($_POST['forum_name']);

		connect_to_database();
		select_database();

		if ( strlen($name) > 20 ) {
			$errors[] = "Character limit for Forum Name is 20";
		}

		if ( $name == "" ) {
			$errors[] = "Name cannot be empty.";
		}

		if ( empty($errors) ) {
			
			$query = " UPDATE forums";
			$query .= " SET forum_name='{$name}'"; 
			$query .= " WHERE forum_id='{$forum_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("index_.php");
			}

		} else {
			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//updating forum description
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_forum_description" ) {

		$forum_id = $_POST['forum_id'];
		$description = mysql_prep($_POST['forum_description']);

		connect_to_database();
		select_database();

		if ( strlen($description) > 255 ) {
			$errors[] = "Character limit for Forum Description is 255";
		}

		if ( $description == "" ) {
			$errors[] = "Description cannot be empty.";
		}

		if ( empty($errors) ) {
			
			$query = " UPDATE forums";
			$query .= " SET description='{$description}'"; 
			$query .= " WHERE forum_id='{$forum_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("index_.php");
			}

		} else {
			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}	

	//updating forum position
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_forum_position" ) {

		connect_to_database();
		select_database();

		$forum_id = $_POST['forum_id'];
		$section_id = get_value_from_db("forums","section_id","forum_id",$forum_id);
		$initial_position = mysql_prep($_POST['initial_position']);
		$new_position = mysql_prep($_POST['new_position']);

		if ( $initial_position != $new_position ) {
			
			 if ( $initial_position > $new_position ) {
				$query = " UPDATE forums";
				$query .= " SET position=position+1 ";
				$query .= " WHERE section_id = '{$section_id}' ";
				$query .= " AND position < '{$initial_position}' ";
				$query .= " AND position >= '{$new_position}' ";
				$result = mysql_query($query, $connection);
				if ( !$result ) {
					die("Error executing query: " . mysql_error() );
				}
			} else if ( $initial_position < $new_position ) {
				$query = " UPDATE forums";
				$query .= " SET position=position-1 "; 
				$query .= " WHERE section_id = '{$section_id}' ";
				$query .= " AND position > '{$initial_position}' ";
				$query .= " AND position <= '{$new_position}' ";
				$result = mysql_query($query, $connection);
				if ( !$result ) {
					die("Error executing query: " . mysql_error() );
				}
			}

			$query = " UPDATE forums";
			$query .= " SET position='{$new_position}'"; 
			$query .= " WHERE forum_id='{$forum_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			}
		}

		close_connection($connection);
		redirect_to("index_.php");
	}

	//updating forum lock
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_forum_lock") {

		connect_to_database();
		select_database();

		$lock = $_POST['lock'];
		$forum_id = $_POST['forum_id'];

		$query = " UPDATE forums";
		$query .= " SET locked='{$lock}' ";
		$query .= " WHERE forum_id='{$forum_id}' ";
		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		} 

		close_connection($connection);
		redirect_to("index_.php");
	}

	//create new section
	if ( $_POST['submit'] == "Create" && $_POST['action'] == "create_section") {

		connect_to_database();
		select_database();

		$name = mysql_prep($_POST['section_name']);
		$position = get_all_rows("sections") + 1;

		if ( strlen($name) > 20 ) {
			$errors[] = "Character limit for Section Name is 20";
		}

		if ( $name == "" ) {
			$errors[] = "Name cannot be empty.";
		}

		if ( empty($errors) ) {
			
			$query = " INSERT INTO sections";
			$query .= " (section_name, position) "; 
			$query .= " VALUES ('{$name}', '{$position} ') ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("index_.php");
			}

		} else {
			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//create new forum
	if ( $_POST['submit'] == "Create" && $_POST['action'] == "create_forum") {

		connect_to_database();
		select_database();

		$section_id = mysql_prep($_POST['section_id']);
		$name = mysql_prep($_POST['forum_name']);
		$description = mysql_prep($_POST['forum_description']);
		$position = get_num_rows("forums","section_id",$section_id) + 1;

		if ( strlen($name) > 20 ) {
			$errors[] = "Character limit for Forum Name is 20";
		}

		if ( $name == "" ) {
			$errors[] = "Name cannot be empty.";
		}

		if ( strlen($description) > 255 ) {
			$errors[] = "Character limit for Description is 255";
		}

		if ( $description == "" ) {
			$errors[] = "Description cannot be empty.";
		}

		if ( empty($errors) ) {
			
			$query = " INSERT INTO forums";
			$query .= " (section_id, forum_name, description, position) "; 
			$query .= " VALUES ('{$section_id}', '{$name}', '{$description}', '{$position}' ) ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("index_.php");
			}

		} else {
			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//delete forum
	if ( $_POST['submit'] == "Delete" && $_POST['action'] == "delete_forum") {

		connect_to_database();
		select_database();

		$forum_id = $_POST['forum_id'];
		$section_id = get_value_from_db("forums", "section_id", "forum_id", $forum_id);

		if ( get_num_rows ("threads", "forum_id", $forum_id) == 0) {
			
			//means that the forum is empty
			$query = " DELETE FROM forums
						WHERE forum_id = {$forum_id}
			";
		} else {

			$query = 
			"
				DELETE FROM 
				  forums, threads, posts 
				USING 
				  forums
				JOIN 
				  threads ON forums.forum_id = threads.forum_id
				JOIN 
				  posts ON threads.thread_id = posts.thread_id
				WHERE 
				  forums.forum_id = '{$forum_id}'
			";
		}

		$result = mysql_query($query, $connection);
		if (!$result) {
			die("Database query failed: " . mysql_error());
		}

		//arange position order
		$query = "SET @pos := 0 ";
		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		}

 		$query = " UPDATE forums SET position = ( SELECT @pos := @pos + 1 ) WHERE section_id = {$section_id} ORDER BY position ASC ";
 		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		}

		close_connection($connection);
		redirect_to("index_.php");
	}

	//delete section
	if ( $_POST['submit'] == "Delete" && $_POST['action'] == "delete_section") {

		connect_to_database();
		select_database();

		$section_id = $_POST['section_id'];

		if ( get_num_rows ("forums", "section_id", $section_id) == 0) {
			
			//means that the section is empty
			$query = " DELETE FROM sections
						WHERE section_id = {$section_id}
			";
			$result = mysql_query($query, $connection);
			if (!$result) {
				die("Database query failed: " . mysql_error());
			}

		} else {

			$query = 
			"
				DELETE FROM 
				  sections, forums, threads, posts 
				USING 
				  sections
				JOIN 
				  forums ON sections.section_id = forums.section_id
				JOIN 
				  threads ON forums.forum_id = threads.forum_id
				JOIN 
				  posts ON threads.thread_id = posts.thread_id
				WHERE 
				  sections.section_id = {$section_id}
			";

			$result = mysql_query($query, $connection);
			if (!$result) {
				die("Database query failed: " . mysql_error());
			}

			if ( mysql_affected_rows($connection) == 0) {
				
				//means that all forums inside this section is empty
				//deletes section manually
				$query = " DELETE FROM sections
							WHERE section_id = {$section_id}
				";
				$result = mysql_query($query, $connection);
				if (!$result) {
					die("Database query failed: " . mysql_error());
				}
			}

			//makes sure that empty forums gets deleted
			$query = 
			"
				DELETE FROM 
					forums
				WHERE 
				  	section_id = {$section_id}
			";

			$result = mysql_query($query, $connection);
			if (!$result) {
				die("Database query failed: " . mysql_error());
			}
		}
		

		//arange position order
		$query = "SET @pos := 0 ";
		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		}

 		$query = " UPDATE sections SET position = ( SELECT @pos := @pos + 1 ) ORDER BY position ASC ";
 		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		}

		close_connection($connection);
		redirect_to("index_.php");

	}

	//updating thread forum
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_thread_forum" ) {

		connect_to_database();
		select_database();

		$thread_id = $_POST['thread_id'];
		$forum_id = $_POST['forum_id'];

		$query = " UPDATE threads";
		$query .= " SET forum_id='{$forum_id}' ";
		$query .= " WHERE thread_id='{$thread_id}' ";
		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		}

		close_connection($connection);
		redirect_to("index_.php");
	}

	//updating thread title
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_thread_title" ) {

		$thread_id = $_POST['thread_id'];
		$name = mysql_prep($_POST['thread_name']);

		connect_to_database();
		select_database();

		if ( strlen($name) > 50 ) {
			$errors[] = "Character limit for Thread title is 50";
		}

		if ( $name == "" ) {
			$errors[] = "Title cannot be empty.";
		}

		if ( empty($errors) ) {
			
			$query = " UPDATE threads";
			$query .= " SET thread_name='{$name}'"; 
			$query .= " WHERE thread_id='{$thread_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("thread.php?thread={$thread_id}");
			}

		} else {
			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//updating thread lock
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_thread_lock") {

		connect_to_database();
		select_database();

		$lock = $_POST['lock'];
		$thread_id = $_POST['thread_id'];
		$editor = $_SESSION['user_id'];
		$thread_id = $_POST['thread_id'];

		$query = " UPDATE threads";
		$query .= " SET locked='{$lock}' ";
		$query .= " WHERE thread_id='{$thread_id}' ";
		$result = mysql_query($query, $connection);
		if ( !$result ) {
			die("Error executing query: " . mysql_error() );
		} 

		close_connection($connection);
		redirect_to("thread.php?thread={$thread_id}");
	}

	//updating post content
	if ( $_POST['submit'] == "Update" && $_POST['action'] == "update_post_content" ) {

		$post_id = $_POST['post_id'];
		$editor = $_SESSION['user_id'];
		$thread_id = $_POST['thread_id'];
		$content = mysql_prep($_POST['post_content']);

		connect_to_database();
		select_database();

		if ( $content == "" ) {
			$errors[] = "Content cannot be empty.";
		}

		if ( empty($errors) ) {
			
			$query = " UPDATE posts";
			$query .= " SET content='{$content}', "; 
			$query .= " edited='1', "; 
			$query .= " edited_by='{$editor}' "; 
			$query .= " WHERE post_id='{$post_id}' ";
			$result = mysql_query($query, $connection);
			if ( !$result ) {
				die("Error executing query: " . mysql_error() );
			} else {
				close_connection($connection);
				redirect_to("thread.php?thread={$thread_id}");
			}

		} else {
			close_connection($connection);
			redirect_to("error.php?errors=" . urlencode( implode(",", $errors) ));
		}
	}

	//delete thread
	if ( $_POST['submit'] == "Delete Thread") {

		connect_to_database();
		select_database();

		$thread_id = $_POST['thread_id'];
		$forum_id = $_POST['forum_id'];

		$query = 
			"
				DELETE FROM 
				  threads, posts 
				USING 
				  threads
				JOIN 
				  posts ON threads.thread_id = posts.thread_id
				WHERE 
				  threads.thread_id = '{$thread_id}'
			";

		$result = mysql_query($query, $connection);
		if (!$result) {
			die("Database query failed: " . mysql_error());
		}

		close_connection($connection);
		redirect_to("forum.php?forum={$forum_id}");
	}

	//delete post
	if ( $_POST['submit'] == "Delete Post") {

		connect_to_database();
		select_database();

		$post_id = $_POST['post_id'];
		$thread_id = $_POST['thread_id'];

		$query = 
			"
				DELETE FROM 
				  posts 
				WHERE 
				  post_id = '{$post_id}'
			";

		$result = mysql_query($query, $connection);
		if (!$result) {
			die("Database query failed: " . mysql_error());
		}

		close_connection($connection);
		redirect_to("thread.php?thread={$thread_id}");
	}
?>