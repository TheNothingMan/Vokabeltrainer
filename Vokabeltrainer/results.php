<!DOCTYPE html>
<?php
	session_start();
?>
<html>
	<head>
		<link rel="stylesheet" href="style/main.css" type="text/css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta charset="utf-8">
		<title>Ergebnisse</title>
	</head>
	<body>
		<div id="results">
			<a>Die Lerneinheit ist abgeschlossen.</a><br>
			<table id="result_table">
				<tbody>
					<tr><td>Richtig</td><td>Falsch</td><td>Gemeistert</td></tr>
					<?php echo("<tr><td>".$_GET['right']."</td><td>".$_GET['wrong']."</td><td>".$_GET['mastered']."</td></tr>")?>
				</tbody>				
			</table>
			
			<p><a href='index.php' target='_top'>Zurück zum Start</a> oder <a href='learn.php' target='_top'>weiterlernen.</a></p>
		</div>
	</body>
</html>