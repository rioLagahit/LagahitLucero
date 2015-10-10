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
			<legend>Create New Forum</legend>

			<form method="post" action="validation.php">
			<table>

				<tr>
					<td>Section</td>
					<td>
						<select name="section_id" class="text" onchange="this.form.submit()">
						<?php
							$query = query_select("sections");	
							$query .= query_select_order("position", "ASC");
							$section_list = get_array_from_db ($query);

							foreach ($section_list as $item) {
								echo "<option value=\"{$item['section_id']}\"> {$item['section_name']} </option>";
							}

						?>
						</select>
					</td>
				</tr>

				<tr>
					<td>Name</td>
					<td>
						<input type="text" name="forum_name" class="text">
					</td>
				</tr>

				<tr>
					<td>Description</td>
					<td colspan="2">
						<textarea name="forum_description" rows="5" cols="40" class="textarea"></textarea>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<input type="hidden" name="action" value="create_forum">
						<input type="submit" name="submit" value="Create" class="button">
					</td>
				</tr>

			</table>
			</form>
		</fieldset>

	</div>
</div>

<?php require_once("shared/footer.php"); ?>