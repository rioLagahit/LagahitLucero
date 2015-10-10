<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php

	if ( !isset($_POST['forum_id']) || $_POST['forum_id'] == "" ) {
		
		redirect_to("not_found.php");
	}

	if ( !logged_in() ) {
		redirect_to("no_permission.php");
	}

?>
<?php $forum_id = $_POST['forum_id']; ?>

<div class="outerBox">
		<div class="titleBox">Create a Thread</div>

		<form method="post" action="validation.php">
			<div class="innerBox">
				<?php 
					connect_to_database();
					select_database();
				?>
				Forum: <?php echo get_value_from_db("forums", "forum_name", "forum_id", $forum_id); ?>
				<input type="text" name="title" class="postTitle" placeholder="Thread Title">
				<textarea name="content" cols="10" rows="10" class="postText"></textarea>
			</div>

			<div class="lowerBox">
				<input type="hidden" name="forum_id" value="<?php echo $forum_id; ?>" >
				<input type="submit" name="submit" value="Post" class="buttonMain">
			</div>
			</form>
	</div>

<?php require_once("shared/footer.php"); ?>