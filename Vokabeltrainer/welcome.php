<?php
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include 'head_tag.html';?>
		<title>Vokabeltrainer</title>
	</head>
	<body>
	<div>
		<h1><?php echo(isset($_SESSION['user_id'])?"Willkommen zurück!":"Herzlich willkommen!")?></h1>
		<p>Auf dieser Website können beliebige Inhalte im Karteikarten-Prinzip gelernt werden. Die Karten sind in Lektionen gegliedert. 
		Besonders geeignet ist die Seite für Vokabeln. Lektionen können auch importiert werden.</p>
		<?php echo(isset($_SESSION['user_id'])?"<p style='text-align: center'><a href='learn.php' target='_top'>Leg gleich los!</a></p>"
				:"<p>Zur Nutzung ist eine kostenlose <a href='register.php' target='_top'>Registrierung</a> nötig.</p>");?>
	</div>
	</body>
</html>