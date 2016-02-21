<?php
	session_start();
	if (!isset($_SESSION['user_id'])){
		//TODO: Update urls
		$_SESSION['origin_url']="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		header("Location: login.php");
	}
?>
<html>
	<head>
		<link rel="icon" href="favicon.ong" type="image/png">
		<link rel="stylesheet" href="style/main.css" type="text/css">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Lernen</title>
	</head>
<body>
<div class='page'>
	<div id="header">
		<?php include 'header.php';?>
	</div>
	<div class="content wrapper">
		<?php
			if (!isset($_POST['start'])){
				echo("<iframe  class='content' id='content_frame' src='learn_options.php'></iframe>");
			}else{
				echo("<iframe class='content' id='content_frame' src='learn_step.php'></iframe>");
			}
		?>
	</div>
</div>
</body>
</html>