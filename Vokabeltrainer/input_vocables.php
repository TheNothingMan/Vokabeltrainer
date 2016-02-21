<?php
	session_start();
	require_once 'models/database.php';
	require_once 'models/lesson.php';
	if (!isset($_SESSION['user_id'])){
		//TODO: Update urls
		$_SESSION['origin_url']="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		header("Location: login.php");
		die();
	}
?>
<html>
	<head>
		<!-- HTML5 -->
		<link rel="icon" href="favicon.ong" type="image/png">
		<link rel="stylesheet" href="style/main.css" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<title>Vokabeleingabe</title>
	</head>
	<body>
	<div class='page'>
		<div id="header">
			<?php include 'header.php';?>
		</div>
		<div class="content wrapper">
			<iframe  class='content' id='content_frame' name='content_frame' src='input_content.php'></iframe>
		</div>
	</div>
	</body>
</html>