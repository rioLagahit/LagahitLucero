<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/countries.php"); ?>

<?php

	connect_to_database();
	select_database();

	if ( !logged_in() || $_SESSION['acess_rank'] != "administrator") {
		redirect_to("no_permission.php");
	}
?>

<div class="centerBox">

	<div class="title">
		Admin Settings
		<hr />
	</div>

	<?php require_once("shared/admin_settings_nav.php"); ?>

	<div class="settings_content">

		<fieldset>
			<legend>Create New Section</legend>

			<form method="post" action="validation.php">
			<table>

				<tr>
					<td>Name</td>
					<td>
						<input type="text" name="section_name" class="text">
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<input type="hidden" name="action" value="create_section">
						<input type="submit" name="submit" value="Create" class="button">
					</td>
				</tr>

			</table>
			</form>
		</fieldset>

	</div>
</div>

<?php require_once("shared/footer.php"); ?>