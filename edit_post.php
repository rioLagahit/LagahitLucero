<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/bbcodeParser.php"); ?>

<?php
	
	connect_to_database();
	select_database();

	if ( isset($_GET['post']) && $_GET['post'] != "") {
		
		if ( get_num_rows("posts", "post_id", $_GET['post']) == 0) {
			
			redirect_to("not_found.php");
			
		} else {

			$post_id = $_GET['post'];

			$query = query_select("posts");
			$query .= query_select_where("post_id", $post_id);

			$post = get_array_from_db($query);
			$post = $post[0];
		}
	}

	if ( !logged_in() || ($_SESSION['acess_rank'] == "member" && $post['posted_by'] != $_SESSION['user_id'])) {
		redirect_to("no_permission.php");
	}
?>

<div class="centerBox">

	<div class="title">
		Edit Post
		<hr />
	</div>

	<fieldset>
		<legend>Post Settings</legend>

		<table >
			
			<tr>
				<td style="text-align:left; width: 50px">Thread: </td>
				<td>
					<?php echo "<a href=\"thread.php?thread={$post['thread_id']}\"> " . get_value_from_db("threads","thread_name","thread_id",$post['thread_id']) . "</a>"; ?>
				</td>
			</tr>

			<tr>
				<td style="text-align:left; width: 50px">Poster: </td>
				<td>
					<?php echo "<a href=\"profile.php?user={$post['posted_by']}\"> " . get_value_from_db("users","username","user_id",$post['posted_by']); ?>
				</td>
			</tr>

			<form method="post" action="validation.php">
			<tr>
				<td colspan="2" style="text-align:left">
					Content: <br />
					<textarea name="post_content" rows="15" cols="70" class="textarea"><?php echo $post['content']; ?></textarea>
				</td>
			</tr>
			<tr><td colspan="2">
				<input type="hidden" name="action" value="update_post_content">
				<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
				<input type="hidden" name="thread_id" value="<?php echo $post['thread_id']; ?>">
				<input type="submit" name="submit" value="Update" class="button">
			</td></tr>
			</form>

			<form method="post" action="validation.php">
			<tr><td colspan="2">
				<input type="hidden" name="thread_id" value="<?php echo $post['thread_id']; ?>">
				<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
				<input type="submit" name="submit" value="Delete Post" class="button">
			</td></tr>
			</form>
		</table>
	</fieldset>
</div>

<?php require_once("shared/footer.php"); ?>