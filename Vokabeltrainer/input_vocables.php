<html>
	<head>
		<!-- HTML5 -->
		<meta charset="utf-8">
		<title>Vokabeleingabe</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				//$("#submit").click(function(){
				$("#newVoc").on('submit', function(e){
					//prevent page reload
					e.preventDefault();
					//prepare variables
					var ownLanguage = $("#ownLanguage").val();
					var foreignLanguage = $("#foreignLanguage").val();
					var lesson = $("#lesson").val();
					if (ownLanguage=="" || foreignLanguage==""){
						$("#result").text("Beide Felder müssen ausgefüllt sein");
						$("#result").fadeIn(200);
					}else{
						$.post("save_new_voc.php",{ownLanguage:ownLanguage, foreignLanguage:foreignLanguage, lesson:lesson}, function(data){
							if (data) {
								$("#result").text("Karte "+ownLanguage+" --> "+foreignLanguage+" gespeichert.");
								$("#result").fadeIn(200);
							}
						});
					}
				});
			});
		</script>
	</head>
	<body>
		<form id="newVoc">
			<label for="ownLanguage">Muttersprache:
				<input type="text" id="ownLanguage" name="ownLanguage">
			</label>
			<label for="foreignLanguage">Fremdsprache:
				<input type="text" id="foreignLanguage" name="foreignLanguage">
			</label>
			<select name="lesson" id="lesson">
				<?php
					$pdo = new PDO("mysql:host=localhost;dbname=vokabeltrainer", "root", "123");
					$query = "SELECT * FROM lessons";
					foreach ($pdo->query($query) as $row){
						echo("<option value=".$row['id'].">".$row['name']);
					}
				?>
			</select>
			<br>
			<input type="submit" id="submit" name="submit" value="Speichern">
		</form>
		
		<br>
		<a href="new_lesson.php">Lektion erstellen</a>
		<p id="result" hidden="true">Vokabel gespeichert</p>
	</body>
</html>