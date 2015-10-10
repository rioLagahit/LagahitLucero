<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/countries.php"); ?>

<?php
	if ( !logged_in() ) {
		redirect_to("no_permission.php");
	} else {
		connect_to_database();
		select_database();

		$user_id = $_SESSION['user_id'];

		$query = query_select("users");
		$query .= query_select_where("user_id", $user_id);

		$user = get_array_from_db($query);
		$user = $user[0];
	}
?>

<div class="centerBox">

	<div class="title">
		Edit Profile
		<hr />
	</div>

	<fieldset>
		<legend>Change Password</legend>
		<form method="post" action="validation.php">
		<table>
			<tr>
				<td>New Password</td>
				<td><input type="password" name="new_password" class="text"></td>
			</tr>
			<tr>
				<td>Confirm New Password</td>
				<td><input type="password" name="confirm_password" class="text"></td>
			</tr>
			<tr>
				<td>Current Password</td>
				<td><input type="password" name="current_password" class="text"></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" value="Update Password" class="button"></td>
			</tr>
		</table>
		</form>
	</fieldset>

	<fieldset>
		<legend>Change Email</legend>
		<form method="post" action="validation.php">
		<table>
			<tr>
				<td>Current Email</td>
				<td><input type="text" name="email" value=<?php echo "\"{$user['email']}\""; ?> class="text"></td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" value="Update Email" class="button"></td>
			</tr>
		</table>
		</form>
	</fieldset>

	<fieldset>
		<legend>Change Location</legend>
		<form method="post" action="validation.php">
		<table>
			<tr>
				<td>Location</td>
				<td>
					<select name="location" class="text">
						<?php foreach ($countries as $country) {
							echo "<option value=\"{$country}\"";
							if ($country == $user['location']) { echo " selected "; }
							echo ">{$country}</option>";
						}?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2"><input type="submit" name="submit" value="Update Location" class="button"></td>
			</tr>
		</table>
		</form>
	</fieldset>

	<fieldset>
		<legend>About Me</legend>
		<form method="post" action="validation.php">
		<table>
			<tr>
				<td>
					<textarea name="about_me" rows="10" cols="70" class="textarea"><?php if ($user['about_me'] != null) { echo $user['about_me']; } ?></textarea>
				</td>
			</tr>
			<tr>
				<td><input type="submit" name="submit" value="Update About Me" class="button"></td>
			</tr>
		</table>
		</form>
	</fieldset>

	<fieldset>
		<legend>Signature</legend>
		<form method="post" action="validation.php">
		<table>
			<tr>
				<td>
					<textarea name="signature" rows="10" cols="70" class="textarea"><?php if ($user['signature'] != null) { echo $user['signature']; } ?></textarea>
				</td>
			</tr>
			<tr>
				<td><input type="submit" name="submit" value="Update Signature" class="button"></td>
			</tr>
		</table>
		</form>
	</fieldset>

	<fieldset>
		<legend>Profile Picture</legend>
		<form method="post" action="validation.php" enctype="multipart/form-data">
		<table>
			<tr >
				<td rowspan="3">
					<img src="images/users/<?php echo ($user['image_name'] == null) ? 'default.jpg' : $user['image_name']; ?>" width="200" height="250">
				</td>
			</tr>
			<tr>
				<td style="text-align:left; vertical-align:top;">
					<p> Must be jpg, gif, or png format. No nudity allowed. No copyrighted images. 
					Maximum 2MB. </p>
					<input type="file" name="img" accept="image/*" style="margin:auto;">
					<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
					<input type="submit" name="submit" value="Update Picture" class="button" style="margin:10px;">
				</td>
			</tr>
		</table>
		</form>
	</fieldset>
	
</div>

<?php require_once("shared/footer.php"); ?>