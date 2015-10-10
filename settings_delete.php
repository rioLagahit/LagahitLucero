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
			<legend>Delete Section</legend>
			<form method="post" action="validation.php">
			<table>
				<tr>
					<td>Section</td>
					<td>
						<select name="section_id" class="text">
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
					<td colspan="2">
						<input type="hidden" name="action" value="delete_section">
						<input type="submit" name="submit" value="Delete" class="button">
					</td>
				</tr>
			</table>
			</form>
		</fieldset>

		<fieldset style="margin-top: 40px;">
			<legend>Delete Forum</legend>

			<form method="post" action="validation.php">
			<table>
				<tr>
					<td>Forum</td>
					<td>
						<select name="forum_id" size="1" class="text">
						<?php
							$query = query_select("forums");	
							$query .= query_select_order("section_id,position", "ASC");
							$forums_list = get_array_from_db ($query);

							foreach ($forums_list as $item) {
								echo "<option value=\"{$item['forum_id']}\"> {$item['forum_name']} </option>";
							}

						?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="hidden" name="action" value="delete_forum">
						<input type="submit" name="submit" value="Delete" class="button">
					</td>
				</tr>
			</table>
			</form>
		</fieldset>


	</div>
		
</div>

<?php require_once("shared/footer.php"); ?>