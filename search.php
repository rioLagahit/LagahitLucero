<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/countries.php"); ?>


<div class="outerBox">
		<div class="titleBox">Search User</div>

		<form method="post" action="search_result.php">
		<div class="innerBox">
			<table>
				<tr>
					<td><input type="text" name="search" class="text" style="width: 400px"></td>
				</tr>
				
			</table>
			</div>

			<div class="lowerBox">
				<input type="submit" name="submit" value="Search" class="buttonMain">
			</div>
			</form>
	</div>

<?php require_once("shared/footer.php"); ?>