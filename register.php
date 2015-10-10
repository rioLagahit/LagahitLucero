<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/countries.php"); ?>
<?php
	$yearStart = 1970;
	$yearEnd = 2014;
	$month = 31;
?>
<?php
	if ( logged_in() ) {
		
		redirect_to("message.php?message=You cannot register if you're logged in.");
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
				<tr>
					<td>Confirm</td>
					<td><input type="password" name="confirm" class="text"></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input type="text" name="email" class="text"></td>
				</tr>
				<tr>
					<td>Location</td>
					<td>
						<select name="location" class="text">
							<?php foreach ($countries as $country) {
								echo "<option value=\"{$country}\">{$country}</option>";
							}?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Gender</td>
					<td>
						<select name="gender" class="text">
							<option value="m">Male</option>
							<option value="f">Female</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Birthday</td>
					<td>
						<select name="month" class="text" style="width:120px;">
							<option value="01">January</option>
							<option value="02">February</option>
							<option value="03">March</option>
							<option value="04">April</option>
							<option value="05">May</option>
							<option value="06">June</option>
							<option value="07">July</option>
							<option value="08">August</option>
							<option value="09">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						</select>
						<select name="day" class="text" style="width:70px;">
							<?php
								for ($i=1; $i <= $month; $i++) { 
									echo "<option value=\"";
									if ($i < 10) { echo "0"; }
									echo "{$i}\">$i</option>";
								}
							?>
						</select>
						<select name="year" class="text" style="width:80px;">
							<?php
								for ($i=$yearEnd; $i >= $yearStart; $i--) { 
									echo "<option value=\"{$i}\">$i</option>";
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="font-size: 0.95em;">
						<label>
						<input type="checkbox" name="agree" value="checked">
						I have read and agree to abide by the <a href="forum_rules.php" target="_blank">InfiniteA Forum rules</a>.
						</label>
					</td>
					<td></td>
				</tr>
			</table>
			</div>

			<div class="lowerBox">
				<input type="submit" name="submit" value="Register" class="buttonMain">
			</div>
			</form>
	</div>


<?php require_once("shared/footer.php"); ?>