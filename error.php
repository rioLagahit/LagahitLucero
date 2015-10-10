<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>

<?php
	
	$required_values = array("errors");
	check_required_values($required_values,"get");
	$errors = explode(",", $_GET['errors']);

?>

<div class="outerBox noLowerBox">
	<div class="titleBox">Error</div>

	<div class="innerBox">
		
		<p>
			The following errors occured:

			<ul>
			<?php
				foreach ($errors as $error) {
					echo "<li>{$error}</li>";
				}
			?>
			</ul>

			Please click back to try again. 
		</p>
		
	</div>
</div>


<?php require_once("shared/footer.php"); ?>