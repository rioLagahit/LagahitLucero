<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/countries.php"); ?>

<?php

	if ( !isset($_POST['search']) || $_POST['search'] == "" ) {
		redirect_to("not_found.php");
	}

	$searchKey = $_POST['search'];

?>

<div class="centerBox adminSettings">

	<div class="title">
		Search Results for: <?php echo " {$searchKey}" ;?>
		<hr />
	</div>

	<table>
	<?php

		connect_to_database();
		select_database();

		$query = "SELECT * FROM users WHERE username LIKE '%{$searchKey}%' ";

		$users = get_array_from_db($query);
		$counter = 1;

		if ( empty($users) ) {
			
			echo "<p class=\"bold\">No users that matched your query were found.";
		} else {

			foreach ($users as $user) {
			
				if ($counter == 1) echo "<tr>";
				echo "<td>";

				if ( $user['image_name'] == null ) 
					$picture = "default.jpg";
				else
					$picture = $user['image_name'];
				
				echo "<a href=\"profile.php?user={$user['user_id']}\">";
				echo "<img src=images/users/{$picture} width=\"160\" height=\"200\">";
				echo "<span>{$user['username']}</span>";
				echo "</a>";
				echo "</td>";
				$counter++;

				if ($counter > 4) {
					echo "</tr>";
					$counter = 1;
				}
			}
		}
	?>
	</table>
	
</div>

<?php require_once("shared/footer.php"); ?>