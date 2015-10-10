<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/countries.php"); ?>
<?php
	if ( logged_in() ) {
		
		redirect_to("message.php?message=You're already logged in.");
	}
?>

	<div class="outerBox">
		<div class="titleBox">Welcome to Infinite A</div>

		<form method="post" action="validation.php">
		<div class="innerBox">
			
			<table>
				<tr>
					<td>Username</td>
					<td><input type="text" name="username" class="text"></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="password" class="text"></td>
				</tr>
			</table>
			</div>

			<div class="lowerBox">
				<input type="submit" name="submit" value="Login" class="buttonMain">
			</div>
			</form>
	</div>

<?php require_once("shared/footer.php"); ?>