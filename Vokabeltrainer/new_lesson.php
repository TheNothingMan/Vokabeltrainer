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
		<meta charset="utf-8">
		<link rel="stylesheet" href="style/main.css" type="text/css">
		<title>Lektion erstellen</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#submit").click(function(){
					var lesson_name = $("#newLesson").val();
					$.post("script/save_new_lesson.php",{newLesson: lesson_name}, function(data){
						if (data) {
							$("#result").text("Lektion " + lesson_name + " gespeichert.");
							$("#result").fadeIn(200);
							window.location.href='index.php';	
						}else
						{
							$("#result").text("Lektion existiert schon, anderen Namen wählen!");
							$("#result").fadeIn(200);
						}
					});
				});
			});
		</script>
	</head>
	<body>
	<div class='page'>
	<div id="header">
		<?php include 'header.php';?>
	</div>
	<div class="content">
		<form>
			<input type="text" id="newLesson" name="newLesson"><br>
			<input type="button" id="submit" name="submit" value="Speichern">
		</form>
		<a>Import:</a>
		<form action="script/import.php" method="post" enctype="multipart/form-data">
			<input type="file" name="file">
			<input type="submit" id="submit_upload" value="Importieren">
		</form>
		<p id="result" hidden="true">Lesson saved</p>
	</div>
	</div>
	</body>
</html>
