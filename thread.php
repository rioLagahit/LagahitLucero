<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/bbCodeParser.php"); ?>
<?php

	connect_to_database();
	select_database();
	
	if ( !isset($_GET['thread']) || $_GET['thread'] == "" || get_num_rows("threads", "thread_id", $_GET['thread']) == 0) {
		redirect_to("not_found.php");
	} 

?>
<?php

	$thread_id = $_GET['thread'];

	$query = query_select("posts");
	$query .= query_select_where("thread_id", $thread_id);
	$query .= query_select_order("time_posted", "ASC");

	$posts = get_array_from_db($query);
	$thread_author = get_value_from_db ("threads", "author_id", "thread_id", $thread_id);
	$thread_locked = get_value_from_db ("threads", "locked", "thread_id", $thread_id);

	//if the thread is locked, no one is allowed to post
	if ( logged_in() && !$thread_locked ) {
		echo "<form method=\"post\" action=\"new_post.php\">";
			echo "<input type=\"hidden\" name=\"thread_id\" value=\"{$thread_id}\" >";
			 echo "<input type=\"submit\" name=\"submit\" value=\"+ Reply to this thread\" class=\"button plus\">";
		echo "</form>";
	}

	echo "<div class=\"listBoxOuter\">";
	echo "<div class=\"listBoxTitle\">";
	if ($thread_locked) {
		echo "<span class=\"locked\">";
		echo "<img src=\"images/lock.png\" width=\"20\" height=\"25\" title=\"This Thread is locked.\" style=\"vertical-align:bottom\">";
		echo "</span>";
	}
	echo get_value_from_db ("threads", "thread_name", "thread_id", $thread_id);
	if ( logged_in() && ($thread_author == $_SESSION['user_id'] || $_SESSION['acess_rank'] == "administrator" || $_SESSION['acess_rank'] == "moderator") ) {
		
		echo "<span class=\"settings_icon\">";
		echo "<a href=\"edit_thread.php?thread={$thread_id}\">";
		echo "<img src=\"images/setting.png\" title=\"Thread Settings\" width=\"25\" height=\"25\" >";
		echo "</a>";
		echo "</span>";
	}
	echo "</div>";
	echo "<table class=\"listBoxTablePost\">";

	foreach ($posts as $post) {

		$poster_id = $post['posted_by'];
		$poster = get_value_from_db ("users", "username", "user_id", $poster_id);
		
		$poster_image = get_value_from_db ("users", "image_name", "user_id", $poster_id);
		if ($poster_image == null) { $poster_image = "default.jpg"; }
		$poster_image = "images/users/" . $poster_image;

		$poster_rank = get_value_from_db ("users", "acess_rank", "user_id", $poster_id);
		$poster_signature = get_value_from_db ("users", "signature", "user_id", $poster_id);

		$content = $post['content'];
		$time_posted = strftime("%m-%d-%y, %I:%M %p", strtotime($post['time_posted']));

		echo "<tr id=\"" . $post['post_id'] . "\">";
		echo "<td colspan=\"2\" class=\"post_time\" >";
		echo $time_posted;
		if ( logged_in() && ($poster_id == $_SESSION['user_id'] || $_SESSION['acess_rank'] == "administrator" || $_SESSION['acess_rank'] == "moderator") ) {
		
			echo "<span class=\"settings_icon\">";
			echo "<a href=\"edit_post.php?post={$post['post_id']}\">";
			echo "<img src=\"images/setting.png\" title=\"Post Settings\" width=\"25\" height=\"25\" >";
			echo "</a>";
			echo "</span>";
		}
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td class=\"post_user\">";
		echo"<a href=\"profile.php?user={$poster_id}\">
				<img src=\"{$poster_image}\" width=\"180\" height=\"220\">
			 	{$poster}
			 </a><br />";
		echo ucfirst($poster_rank);
		echo "</td>";

		echo "<td class=\"post_content\">";

		echo "<div class=\"post_message\" >";
		echo nl2br(bbcodeParser($content));
		echo "</div>";

		if ( $post['edited'] ) {
			
			$editor = get_value_from_db("users", "username", "user_id", $post['edited_by']);
			$time_edited = strftime("%m-%d-%y, %I:%M %p", strtotime($post['edited_time']));
			echo "<div class=\"post_edited\" >";
			echo "Last edited by ";
			echo "<a href=\"profile.php?user={$post['edited_by']}\">{$editor}</a> on {$time_edited}";
			echo "</div>";
		}

		if ( $poster_signature != null ) {
		
			echo "<div class=\"post_signature\" >";
			echo $poster_signature;
			echo "</div>";
		}

		echo "</td>";
		echo "</tr>";
	}

	echo "</table>";
	echo "<div class=\"listBottom\">";
	echo "</div>";
	echo "</div>";

?>
<?php require_once("shared/footer.php"); ?>