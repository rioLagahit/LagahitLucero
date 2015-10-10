<div id="nav">
	<ul>
		<li><a href="index_.php">Forums</a></li>
		<li><a href="profile.php">Profile</a></li>
		<li><a href="search.php">Search</a></li>
		<?php

			if ( logged_in() && $_SESSION['acess_rank'] == "administrator") {
				echo "<li><a href=\"settings.php\">Settings</a></li>";
			}

		?>
		<li><a href="logout.php">Logout</a></li>
	</ul>
</div>