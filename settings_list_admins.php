<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/countries.php"); ?>

<?php
	if ( !logged_in() || $_SESSION['acess_rank'] != "administrator") {
		redirect_to("no_permission.php");
	}
?>

<div class="centerBox adminSettings">

	<div class="title">
		Admin Settings
		<hr />
	</div>

	<?php require_once("shared/admin_settings_nav.php"); ?>

	<div class="settings_content">
		<div class="title">
			List of Admins
			<hr />
		</div>

		<table>
		<?php

			connect_to_database();
			select_database();

			$query = query_select("users");
			$query .= query_select_where("acess_rank", "administrator");

			$admins = get_array_from_db($query);
			$counter = 1;

			foreach ($admins as $admin) {
				
				if ($counter == 1) echo "<tr>";
				echo "<td>";

				if ( $admin['image_name'] == null ) 
					$picture = "default.jpg";
				else
					$picture = $admin['image_name'];
				
				echo "<a href=\"profile.php?user={$admin['user_id']}\">";
				echo "<img src=images/users/{$picture} width=\"160\" height=\"200\">";
				echo "<span>{$admin['username']}</span>";
				echo "</a>";
				echo "</td>";
				$counter++;

				if ($counter > 3) {
					echo "</tr>";
					$counter = 1;
				}
			}
		?>
		</table>

	</div>
	
</div>

<?php require_once("shared/footer.php"); ?>