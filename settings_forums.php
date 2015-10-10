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

	if ( !isset($_POST['forum']) ) {
		$forum_id = 1;
	} else {
		$forum_id = $_POST['forum'];
	}

	$query = query_select("forums");
	$query .= query_select_where("forum_id", $forum_id);

	$forum = get_array_from_db($query);
	$forum = $forum[0];
?>

<div class="centerBox">

	<div class="title">
		Admin Settings
		<hr />
	</div>

	<?php require_once("shared/admin_settings_nav.php"); ?>

	<div class="settings_content">

		<fieldset>
			<legend>Forums Settings</legend>

			<table>

				<form method="post" action="">
				<tr>
					<td>Forum</td>
					<td>
						<select name="forum" class="text" onchange="this.form.submit()">
						<?php
							$query = query_select("forums");
							$query .= query_select_order("section_id,position", "ASC");	
							$forum_list = get_array_from_db ($query);

							foreach ($forum_list as $item) {
								echo "<option value=\"{$item['forum_id']}\" ";
								if ($item['forum_id'] == $forum_id) { echo "selected"; }
								echo "> {$item['forum_name']} </option>";
							}

						?>
						</select>
					</td>
				</tr>
				</form>

				<form method="post" action="validation.php">
				<tr>
					<td>Change Section</td>
					<td>
						<select name="new_section" class="text">
							<?php
								$query = query_select("sections");
								$query .= query_select_order("position", "ASC");

								$sections = get_array_from_db ($query);

								foreach ($sections as $section) {
									echo "<option value=\"{$section['section_id']}\" ";
									if ($section['section_id'] == $forum['section_id']) { echo "selected"; }
									echo "> {$section['section_name']} </option>";
								}

							?>
						</select>
					</td>
					<td>
						<input type="hidden" name="action" value="update_forum_section">
						<input type="hidden" name="forum_id" value="<?php echo $forum['forum_id']; ?>">
						<input type="submit" name="submit" value="Update" class="button">
					</td>
				</tr>
				</form>

				<form method="post" action="validation.php">
				<tr>
					<td>Change Name</td>
					<td>
						<input type="text" name="forum_name" value=<?php echo "\"{$forum['forum_name']}\""; ?> class="text">
					</td>
					<td>
						<input type="hidden" name="action" value="update_forum_name">
						<input type="hidden" name="forum_id" value="<?php echo $forum['forum_id']; ?>">
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

								$forum_num = get_num_rows ("forums", "section_id", $forum['section_id']);

								for ($i=1; $i <= $forum_num ; $i++) { 
									echo "<option value=\"{$i}\" ";
									if ($i == $forum['position']) { echo "selected"; }
									echo "> {$i} </option>";
								}

							?>
						</select>
					</td>
					<td>
						<input type="hidden" name="action" value="update_forum_position">
						<input type="hidden" name="forum_id" value="<?php echo $forum['forum_id']; ?>">
						<input type="hidden" name="initial_position" value="<?php echo $forum['position']; ?>">
						<input type="submit" name="submit" value="Update" class="button">
					</td>
				</tr>
				</form>

				<form method="post" action="validation.php">
				<tr>
					<td>Locked</td>
					<td>
						<select name="lock" class="text">
							<option value="1" <?php if ($forum['locked']) echo "selected"; ?> >Yes</option>
							<option value="0" <?php if (!$forum['locked']) echo "selected"; ?> >No</option>
						</select>
					</td>
					<td>
						<input type="hidden" name="action" value="update_forum_lock">
						<input type="hidden" name="forum_id" value="<?php echo $forum['forum_id']; ?>">
						<input type="submit" name="submit" value="Update" class="button">
					</td>
				</tr>
				</form>

				<form method="post" action="validation.php">
				<tr>
					<td>Change Description</td>
					<td colspan="2">
						<textarea name="forum_description" rows="5" cols="40" class="textarea"><?php echo $forum['description']; ?></textarea>
					</td>
				</tr>
				<tr><td colspan="3">
					<input type="hidden" name="action" value="update_forum_description">
					<input type="hidden" name="forum_id" value="<?php echo $forum['forum_id']; ?>">
					<input type="submit" name="submit" value="Update" class="button">
				</td></tr>
				</form>

			</table>
		</fieldset>

	</div>
</div>

<?php require_once("shared/footer.php"); ?>