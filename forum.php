<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php

	connect_to_database();
	select_database();
	
	if ( !isset($_GET['forum']) || $_GET['forum'] == "") {
		redirect_to("not_found.php");
	} else {

		if (get_num_rows("forums", "forum_id", $_GET['forum']) == 0) {
			
			redirect_to("index_.php");
		}
	}

?>
<?php

	$forum_id = $_GET['forum'];

	$query = query_select("threads");
	$query .= query_select_where("forum_id", $forum_id);
	$query .= query_select_order("last_post_time", "DESC");

	$threads = get_array_from_db($query);
	$forum_locked = get_value_from_db ("forums", "locked", "forum_id", $forum_id);

	//if forum is locked, no one is allows to post threads
	if ( logged_in() && !$forum_locked ) {
		echo "<form method=\"post\" action=\"new_thread.php\">";
			echo "<input type=\"hidden\" name=\"forum_id\" value=\"{$forum_id}\" >";
			 echo "<input type=\"submit\" name=\"submit\" value=\"+ Post New Thread\" class=\"button plus\">";
		echo "</form>";
	}

	echo "<div class=\"listBoxOuter\">";
	echo "<div class=\"listBoxTitle\">";
	if ($forum_locked) {
		echo "<span class=\"locked\">";
		echo "<img src=\"images/lock.png\" width=\"20\" height=\"25\" title=\"This Forum is locked.\" style=\"vertical-align:bottom\">";
		echo "</span>";
	}
	echo get_value_from_db ("forums", "forum_name", "forum_id", $forum_id);
	echo "</div>";
	echo "<table class=\"listBoxTable\">";
	echo "<tr>";
	echo "<th style=\"width:850px\">Thread</th>";
	echo "<th style=\"width:150px\">Replies</th>";
	echo "<th style=\"width:300px\">Last Post</th>";
	echo "</tr>";

	if ( empty($threads) ) {
		echo "<tr><td colspan=\"3\" class=\"center bold\">There are currently no threads in this forum.</td></tr>";
	} else {
		foreach ($threads as $thread) {

			$poster_id = $thread['author_id'];
			$last_poster_id = get_value_from_db ("posts", "posted_by", "post_id", $thread['last_post_id']);
			$num_of_threads = get_num_rows("posts", "thread_id", $thread['thread_id']) - 1;
			$last_post = "#" . $thread['last_post_id'];

			echo "<tr>";
			echo "<td class=\"table_name\">";
				if ($thread['locked']) {
					echo "<span class=\"locked\">";
					echo "<img src=\"images/lock.png\" width=\"20\" height=\"25\" title=\"This Thread is locked.\" style=\"vertical-align:bottom\">";
					echo "</span>";
				}
				echo "<a href=\"thread.php?thread={$thread['thread_id']}\">{$thread['thread_name']}</a>";
				echo "<div class=\"description\">by <a href=\"profile.php?user={$poster_id}\">" . get_value_from_db ("users", "username", "user_id", $poster_id ) . "</a></div>";
			echo "</td>";
			echo "<td class=\"table_thread\">" . $num_of_threads . "</td>";
			echo "<td class=\"table_thread\">";
			echo "<a href=\"profile.php?user={$last_poster_id}\">" . get_value_from_db ("users", "username", "user_id", $last_poster_id) . "</a>";
			echo "<a href=\"thread.php?thread={$thread['thread_id']}{$last_post}\">>></a><br />";
			echo strftime("%m-%d-%y, %I:%M %p", strtotime($thread['last_post_time']));
			echo "</td>";
			echo "</tr>";
		}
	}

	echo "</table>";
	echo "<div class=\"listBottom\">";
	echo "</div>";
	echo "</div>";

?>
<?php require_once("shared/footer.php"); ?>