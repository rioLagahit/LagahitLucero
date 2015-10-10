<?php require_once("shared/cookies.php"); ?>
<?php require_once("shared/functions.php"); ?>
<?php require_once("shared/header.php"); ?>
<?php require_once("shared/bbcodeParser.php"); ?>

<?php
	
	connect_to_database();
	select_database();

	if ( isset($_GET['user']) && $_GET['user'] != "") {
		
		if ( get_num_rows("users", "user_id", $_GET['user']) == 0) {
			
			redirect_to("not_found.php");
			
		} else {

			$user_id = $_GET['user'];

			$query = query_select("users");
			$query .= query_select_where("user_id", $user_id);

			$user = get_array_from_db($query);
			$user = $user[0];
		}

	} else if ( logged_in() ) {

		$user_id = $_SESSION['user_id'];

		$query = query_select("users");
		$query .= query_select_where("user_id", $user_id);

		$user = get_array_from_db($query);
		$user = $user[0];

	} else {
		redirect_to("not_found.php");
	}

?>

<div class="profileWrapper">
	<div class="profileLeft">

		<div id="profileName" class="center">
			<?php echo $user['username']; ?>
			<hr />
		</div>
		
		<div id="profileImage" class="center">
			<?php 
				if ( $user['image_name'] == null ) {
					$picture = "default.jpg";
				} else {
					$picture = $user['image_name'];
				}
			?>
			<img src="images/users/<?php echo $picture?>" width="240" height="320">
		</div>

		<div id="options">
			<ul>
				<?php

					if ( logged_in() ) {
						
						if ( isset($_GET['user']) && $_GET['user'] != "" && $_SESSION['user_id'] != $_GET['user']) {

							//echo "<li><a href=\"#\">Send a Private Message</a></li>";

						} else {
							echo "<li><a href=\"edit_profile.php\">Edit Profile</a></li>";
							//echo "<li><a href=\"#\">Private Messages</a></li>";
						}

						if ($_SESSION['acess_rank'] == "administrator" && $user['user_id'] != $_SESSION['user_id'] ) {
							echo "<li><a href=\"acess_rank.php?id={$user['user_id']}\">Change Acess Rank</a></li>";
						}

						if ( ($_SESSION['acess_rank'] == "administrator" || $_SESSION['acess_rank'] == "moderator") && $user['acess_rank'] != "administrator" && $user['user_id'] != $_SESSION['user_id']) {
							
							if ( $user['banned'] == 0) {
								echo "<li><a href=\"validation.php?ban_id={$user['user_id']}\">Ban User</a></li>";
							} else if ( $user['banned'] == 1) {
								echo "<li><a href=\"validation.php?ban_id={$user['user_id']}\">Unban User</a></li>";
							}
						}
					}

				?>
			</ul>
		</div>
	</div>

	<div class="profileRight">

		<div>	
			<div class="title">
				Details
				<hr />
			</div>

			<div>
				<table>
					<tr>
						<td>Gender</td>
						<td><?php echo ($user['gender'] == "m") ? "Male" : "Female"; ?></td>
					</tr>
					<tr>
						<td>Birthday</td>
						<td><?php echo strftime("%b %d, %Y", strtotime($user['birthday'])); ?></td>
					</tr>
					<tr>
						<td>Location</td>
						<td><?php echo $user['location']; ?></td>
					</tr>
					<tr>
						<td>Join Date</td>
						<td><?php echo strftime("%b %d, %Y", strtotime($user['date_joined'])); ?></td>
					</tr>
					<tr>
						<td>Acess Rank</td>
						<td><?php echo ucfirst($user['acess_rank']); if ($user['banned'] == 1) { echo " (Banned)"; } ?></td>
					</tr>
					<tr>
						<td>Email</td>
						<td><?php echo $user['email']; ?></td>
					</tr>
				</table>
			</div>
		</div>

		<div>	
			<div class="title">
				About Me
				<hr />
			</div>

			<div class="comment">
				<?php
					if ( $user['about_me'] == null ) {
						echo "User's about me is empty";
					} else {
						echo "<div  class=\"about_me\">";
						echo nl2br(bbcodeParser($user['about_me']));
						echo "</div>";
					}
				?>
			</div>

		</div>
		
	</div>
</div>

<?php require_once("shared/footer.php"); ?>