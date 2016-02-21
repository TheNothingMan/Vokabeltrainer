<?php
	session_start();
?>
<html>
	<head>
		<link rel="shortcut icon" href="favicon.png" type="image/png">
		<link rel="stylesheet" href="style/main.css" type="text/css">
		<meta charset="utf-8">
		<title>Vokabeltrainer</title>
	</head>
	<body>
	<div class='page'>
		<div id="header">
			<?php include 'header.php';?>
		</div>
		<div class="content wrapper">
			<iframe  class='content' id='content_frame' name='content_frame' src='welcome.php'></iframe>
		</div>
	</div>
	</body>
</html>