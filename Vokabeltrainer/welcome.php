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
	<div>
		<h1><?php echo(isset($_SESSION['user_id'])?"Willkommen zurück!":"Herzlich willkommen!")?></h1>
		<p>Dieser Vokabeltrainer ist an <b>Vokker</b> angelehnt. Er untersützt den Import von Lektionen aus Vokker
		und bietet eine ähnliche Abfragemethodik.</p>
		<?php echo(isset($_SESSION['user_id'])?"<p style='text-align: center'><a href='learn.php' target='_top'>Leg gleich los!</a></p>"
				:"<p>Zur Nutzung ist eine kostenlose <a href='register.php' target='_top'>Registrierung</a> nötig.</p>");?>
	</div>
	</body>
</html>