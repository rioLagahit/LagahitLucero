<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php

	if ( !isset($_POST['thread_id']) || $_POST['thread_id'] == "" ) {
		
		redirect_to("not_found.php");
	}

	if ( !logged_in() ) {
		redirect_to("no_permission.php");
	}

?>
<?php $thread_id = $_POST['thread_id']; ?>

<div class="outerBox">
	<div class="titleBox">Reply to thread</div>

	<form method="post" action="validation.php">
		<div class="innerBox">
			<?php 
				connect_to_database();
				select_database();
			?>
			Reply To: <?php echo get_value_from_db("threads", "thread_name", "thread_id", $thread_id); ?>
			<textarea name="content" cols="10" rows="10" class="postText"></textarea>
		</div>

		<div class="lowerBox">
			<input type="hidden" name="thread_id" value="<?php echo $thread_id; ?>" >
			<input type="submit" name="submit" value="Reply" class="buttonMain">
		</div>
	</form>
</div>

<?php require_once("shared/footer.php"); ?>