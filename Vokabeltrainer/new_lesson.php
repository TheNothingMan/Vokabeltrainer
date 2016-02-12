<html>
	<head>
		<meta charset="utf-8">
		<title>Lektion erstellen</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#submit").click(function(){
					var lesson_name = $("#newLesson").val();
					$.post("save_new_lesson.php",{newLesson: lesson_name}, function(data){
						if (data) {
							$("#result").text("Lektion " + lesson_name + " gespeichert.");
							$("#result").fadeIn(200);
							window.location.href("index.php");	
						}else
						{
							$("#result").text("Lektion existiert schon, anderen Namen w�hlen!");
							$("#result").fadeIn(200);
						}
					});
				});
			});
		</script>
	</head>
	<body>
		<form>
			<input type="text" id="newLesson" name="newLesson">
			<input type="button" id="submit" name="submit" value="Speichern">
		</form>
		<p id="result" hidden="true">Lesson saved</p>
	</body>
</html>
