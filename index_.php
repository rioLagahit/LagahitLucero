<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php

	connect_to_database();
	select_database();

	$query = query_select("sections");
	$query .= query_select_order("position", "ASC");
	$sections = get_array_from_db($query);

	foreach ($sections as $section) {

		$query = query_select("forums");
		$query .= query_select_where("section_id", $section['section_id']);
		$query .= query_select_order("position", "ASC");
		$forums = get_array_from_db($query);
		
		echo "<div class=\"listBoxOuter\">";
		echo "<div class=\"listBoxTitle\">";
		echo "{$section['section_name']}";
		echo "</div>";
		echo "<table class=\"listBoxTable\">";
		echo "<tr>";
		echo "<th style=\"width:800px\">Forum</th>";
		echo "<th style=\"width:150px\">Threads</th>";
		echo "</tr>";

		if ( empty($forums) ) {
			echo "<tr><td colspan=\"2\" class=\"center\">Section empty.</td></tr>";
		} else {

			foreach ($forums as $forum) {
				echo "<tr>";
				echo "<td class=\"table_name\">";
					if ($forum['locked']) {
						echo "<span class=\"locked\">";
						echo "<img src=\"images/lock.png\" width=\"20\" height=\"25\" title=\"This Forum is locked.\" style=\"vertical-align:bottom\">";
						echo "</span>";
					}
					echo "<a href=\"forum.php?forum={$forum['forum_id']}\">{$forum['forum_name']}</a><br />";
					echo "<div class=\"description\">{$forum['description']}</div>";
				echo "</td>";
				echo "<td class=\"table_thread\">" . get_num_rows("threads", "forum_id", $forum['forum_id']) . "</td>";
				echo "</tr>";
			}
		}

		echo "</table>";

		echo "<div class=\"listBottom\">";
		echo "</div>";

		echo "</div>";
	}

?>
<?php require_once("shared/footer.php"); ?>