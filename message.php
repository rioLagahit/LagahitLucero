<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>

<?php
	
	$required_values = array("message");
	check_required_values($required_values,"get");

?>

<div class="outerBox noLowerBox">
	<div class="titleBox">Message</div>

	<div class="innerBox center">
		
		<p>
			<?php echo $_GET['message']; ?>
		</p>
		
	</div>
</div>


<?php require_once("shared/footer.php"); ?>