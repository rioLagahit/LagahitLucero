<html>
<head>

	<?php

		$banners = array("height.jpg",
							"titan.jpg", "vocaboys.jpg", "blackrock.jpg",
							"pink.jpg", "misaka.jpg", "luka.jpg", "miku.jpg",
							"miku2.jpg", "asuna.jpg", "misaka2.jpg", "miku3.jpg",
							 "akashi.jpg", "kirito.jpg",
							"miku.png", "mikasa.jpg", "yuno.png", "noiz.jpg"
					);
		$banner = $banners[ rand(0,count($banners)-1) ];

	?>

	<title>Infinite Anime</title>
	<link href="stylesheets/main.css" rel="stylesheet" type="text/css">
	<link href="stylesheets/profile.css" rel="stylesheet" type="text/css">
	<style type="text/css">

		#header {
			background: url("images/banners/<?php echo $banner; ?>");
			background-size: cover;
			background-attachment: scroll;
			background-repeat: no-repeat;
			background-position: center;
		}

	</style>
</head>

<body>
	
	<div id="header">
		<div>
			<h1>Infinite A</h1>
			<h5>Anime Forum</h5>
		</div>
	</div>

	<?php

		if ( logged_in() ) {

			require_once("shared/nav_loggedin.php");

		} else {

			require_once("shared/nav.php");

		}

	?>

	<div id="content">
	<!-- start of content -->