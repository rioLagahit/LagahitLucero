<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/countries.php"); ?>

<?php
	if ( !logged_in() || $_SESSION['acess_rank'] != "administrator") {
		redirect_to("no_permission.php");
	}
?>

<div class="centerBox adminSettings">

	<div class="title">
		Admin Settings
		<hr />
	</div>

	<?php require_once("shared/admin_settings_nav.php"); ?>
	
</div>

<?php require_once("shared/footer.php"); ?>