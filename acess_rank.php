<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/countries.php"); ?>

<?php
	connect_to_database();
	select_database();

	if ( get_num_rows("users", "user_id", $_GET['id']) == 0 || !isset( $_GET['id'] ) || $_GET['id'] == "") {
			
		redirect_to("not_found.php");
	}

	if ( $_SESSION['acess_rank'] != "administrator" || !logged_in()) {
		
		redirect_to("no_permission.php");
	}

	//$same will be true if the user is trying to change his/her acess rank
	if ( $_GET['id'] == $_SESSION['user_id'] ) {
		$same = true;
	} else {
		$same = false;
	}
?>

<?php
	
	$user_id = $_GET['id'];
	$username = get_value_from_db ("users", "username", "user_id", $user_id);
	$rank = get_value_from_db ("users", "acess_rank", "user_id", $user_id);

?>

	<div class="outerBox">
		<div class="titleBox">User Acess Rank</div>

		<form method="post" action="validation.php" <?php if ($same) { echo "onsubmit=\"return confirm('Are you sure you want to change your ownn acess rank?')\""; } ?> >
		<div class="innerBox">
			
			<table>
				<tr>
					<td><?php echo $username;?></td>
					<td>
						<select name="acess_rank" id="acess_rank" class="text">
							<option value="administrator" <?php if ($rank == "administrator") { echo "selected"; } ?> >administrator</option>
							<option value="moderator" <?php if ($rank == "moderator") { echo "selected"; } ?>>moderator</option>
							<option value="member" <?php if ($rank == "member") { echo "selected"; } ?>>member</option>
						</select>
					</td>
				</tr>
			</table>
		</div>

		<div class="lowerBox">
			<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
			<input type="submit" name="submit" value="Change User Rank" class="buttonMain">
		</div>
		</form>
	</div>

<?php require_once("shared/footer.php"); ?>