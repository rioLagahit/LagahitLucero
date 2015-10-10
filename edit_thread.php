<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/bbcodeParser.php"); ?>

<?php
	
	connect_to_database();
	select_database();

	if ( !logged_in() || $_SESSION['acess_rank'] == "member") {
		redirect_to("no_permission.php");
	}

	if ( !isset($_GET['thread']) || $_GET['thread'] == "" || get_num_rows("threads", "thread_id", $_GET['thread']) == 0) {
		
		redirect_to("not_found.php");
	}

	$thread_id = $_GET['thread'];

	$query = query_select("threads");
	$query .= query_select_where("thread_id", $thread_id);

	$thread = get_array_from_db($query);
	$thread = $thread[0];
?>

<div class="centerBox">

	<div class="title">
		Edit Thread: <?php echo $thread['thread_name']; ?> 
		<hr />
	</div>

	<fieldset>
		<legend>Thread Settings</legend>

		<table>
			
			<form method="post" action="validation.php">
			<tr>
				<td>Forum</td>
				<td>
					<select name="forum_id" class="text">
					<?php
						$query = query_select("forums");
						$query .= query_select_order("section_id,position", "ASC");	
						$forum_list = get_array_from_db ($query);

						foreach ($forum_list as $item) {
							echo "<option value=\"{$item['forum_id']}\" ";
							if ($item['forum_id'] == $thread['forum_id']) { echo "selected"; }
							echo "> {$item['forum_name']} </option>";
						}

					?>
					</select>
				</td>
				<td>
					<input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
					<input type="hidden" name="action" value="update_thread_forum">
					<input type="submit" name="submit" value="Update" class="button">		
				</td>
			</tr>
			</form>

			<form method="post" action="validation.php">
			<tr>
				<td>Change Title</td>
				<td>
					<input type="text" name="thread_name" value=<?php echo "\"{$thread['thread_name']}\""; ?> class="text">
				</td>
				<td>
					<input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
					<input type="hidden" name="action" value="update_thread_title">
					<input type="submit" name="submit" value="Update" class="button">		
				</td>
			</tr>
			</form>

			<form method="post" action="validation.php">
			<tr>
				<td>Locked</td>
				<td>
					<select name="lock" class="text">
						<option value="1" <?php if ($thread['locked']) echo "selected"; ?> >Yes</option>
						<option value="0" <?php if (!$thread['locked']) echo "selected"; ?> >No</option>
					</select>
				</td>
				<td>
					<input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
					<input type="hidden" name="action" value="update_thread_lock">
					<input type="submit" name="submit" value="Update" class="button">		
				</td>
			</tr>
			</form>

			<form method="post" action="validation.php">
			<tr>
				<td colspan="3">
					<input type="hidden" name="forum_id" value="<?php echo $thread['forum_id']; ?>">
					<input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>">
					<input type="submit" name="submit" value="Delete Thread" class="button">		
				</td>
			</tr>
			</form>
		</table>
	</fieldset>
	
</div>


<?php require_once("shared/footer.php"); ?>