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
<!DOCTYPE html>
<html>
	<head>
		<?php include 'head_tag.html';?>
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