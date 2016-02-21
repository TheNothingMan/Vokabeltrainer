<?php
	session_start();
?>
<html>
	<head>
		<link rel="stylesheet" href="style/main.css" type="text/css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta charset="utf-8">
	</head>
	<body>
		<div id="results">
			<a>Die Lerneinheit ist abgeschlossen.</a><br>
			<table>
				<tbody>
					<tr><td>Richtig</td><td>Falsch</td><td>Gemeistert</td></tr>
					<?php echo("<tr><td>".$_GET['right']."</td><td>".$_GET['wrong']."</td><td>".$_GET['mastered']."</td></tr>")?>
				</tbody>				
			</table>
		</div>
	</body>
</html>