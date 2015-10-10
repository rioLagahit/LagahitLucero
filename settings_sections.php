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

	// if ( !isset( $_POST['section'] ) || $_POST['section'] == "" || get_num_rows("sections", "section_id", $_POST['section']) == 0) {
	// 	redirect_to("not_found.php");
	// }

	if ( !isset($_POST['section']) ) {
		$section_id = 1;
	} else {
		$section_id = $_POST['section'];
	}

	$query = query_select("sections");
	$query .= query_select_where("section_id", $section_id);

	$section = get_array_from_db($query);
	$section = $section[0];
?>

<div class="centerBox">

	<div class="title">
		Admin Settings
		<hr />
	</div>

	<?php require_once("shared/admin_settings_nav.php"); ?>

	<div class="settings_content">

		<fieldset>
			<legend>Sections Settings</legend>

			<table>
				
				<form method="post" action="">
				<tr>
					<td>Section</td>
					<td>
						<select name="section" class="text" onchange="this.form.submit()">
						<?php
							$query = query_select("sections");	
							$query .= query_select_order("position", "ASC");
							$section_list = get_array_from_db ($query);

							foreach ($section_list as $item) {
								echo "<option value=\"{$item['section_id']}\" ";
								if ($item['section_id'] == $section_id) { echo "selected"; }
								echo "> {$item['section_name']} </option>";
							}

						?>
						</select>
					</td>
				</tr>
				</form>

				<form method="post" action="validation.php">
				<tr>
					<td>Change Name</td>
					<td>
						<input type="text" name="section_name" value=<?php echo "\"{$section['section_name']}\""; ?> class="text">
					</td>
					<td>
						<input type="hidden" name="section_id" value="<?php echo $section['section_id']; ?>">
						<input type="hidden" name="action" value="update_section_name">
						<input type="submit" name="submit" value="Update" class="button">		
					</td>
				</tr>
				</form>

				<form method="post" action="validation.php">
				<tr>
					<td>Change Position</td>
					<td>
						<select name="new_position" class="text">
							<?php
								$section_num = get_all_rows("sections");

								for ($i=1; $i <= $section_num ; $i++) { 
									echo "<option value=\"{$i}\" ";
									if ($i == $section['position']) { echo "selected"; }
									echo "> {$i} </option>";
								}

							?>
						</select>
					</td>
					<td>
						<input type="hidden" name="action" value="update_section_position">
						<input type="hidden" name="section_id" value="<?php echo $section['section_id']; ?>">
						<input type="hidden" name="initial_position" value="<?php echo $section['position']; ?>">
						<input type="submit" name="submit" value="Update" class="button">
					</td>
				</tr>
				</form>

			</table>
		</fieldset>

	</div>
</div>

<?php require_once("shared/footer.php"); ?>