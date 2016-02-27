<?php
	session_start();
	if (!isset($_SESSION['user_id'])){
		//TODO: Update urls
		$_SESSION['origin_url']="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		header("Location: login.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include 'head_tag.html';?>
		<title>Lernen</title>
	</head>
<body>
<div class='page'>
	<div id="header">
		<?php include 'header.php';?>
	</div>
	<div class="content wrapper">
		<?php
// 			if (!isset($_POST['start'])){
// 				echo("<iframe  class='content' id='content_frame' src='learn_options.php'></iframe>");
// 			}else{
// 				echo("<iframe class='content' id='content_frame' src='learn_step.php'></iframe>");
// 			}
		?>
		<iframe  class='content' name='content_frame' id='content_frame' src='learn_options.php'></iframe>
	</div>
</div>
</body>
</html>