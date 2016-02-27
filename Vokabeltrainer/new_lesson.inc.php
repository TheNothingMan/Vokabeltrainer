<!DOCTYPE html>
<?php
	session_start();
	if (!isset($_SESSION['user_id'])){
		//TODO: Update urls
		$_SESSION['origin_url']="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		header("Location: login.php");
	}
	//set session variables for voc input
	if(isset($_POST['ownLanguage']) && ($_POST['origin']=="input_content.php")){
		$_SESSION['own_language']=$_POST['ownLanguage'];
		$_SESSION['foreign_language']=$_POST['foreignLanguage'];
	}
?>
<html>
	<head>
		<?php include 'head_tag.html';?>
		<link rel="stylesheet" href="style/main.css" type="text/css">
		<title>Lektion erstellen</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#submit").click(function(){
					var lesson_name = $("#newLesson").val();
					var origin = $("#origin").val();
					console.log(origin);
					$.post("script/save_new_lesson.php",{newLesson: lesson_name, origin: origin}, function(data){
						if (data){
							$("#result").text("Lektion " + lesson_name + " gespeichert.");
							$("#result").fadeIn(200);
							window.location.href=data;	
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
		<form>
			<input type="text" id="newLesson" name="newLesson"><br>
			<input type="button" id="submit" name="submit" value="Speichern">
			<input type="hidden" id="origin" name='origin' value='<?php echo($_POST['origin']);?>'>
		</form>
		<?php
			if (isset($_POST['ownLanguage'])){
				echo "<a>Import:</a>
					<form action='script/import.php' method='post' enctype='multipart/form-data'>
						<input type='file' name='file'>
						<input type='submit' id='submit_upload' value='Importieren'>
					</form>";
			}			
		?>
		<a>Import:</a>
		<form action="script/import.php" method="post" enctype="multipart/form-data">
			<input type="file" name="file">
			<input type="submit" id="submit_upload" value="Importieren">
			<p>Hinweis: Akzeptiert werden nur .csv-Dateien. Inhalte müssen mit ';' im Format "Fremdsprache;Muttersprache" abgetrennt werden. 
			Lektionen erhalten eine eigene Zeile im Format "# Name #". Die erste Zeile muss eine Lektion enthalten.</p>
		</form>
		<p id="result" hidden="true">Lesson saved</p>
	</body>
</html>
