<?php
session_start();
require_once 'models/database.php';
require_once 'models/lesson.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include 'head_tag.html';?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript">
			var date = new Date().toISOString().slice(0, 10).replace('T', ' ');
			var prevent = true;

			function lastChosen(){
				var lesson = $("#lesson").val();
				$.post("script/update_last_chosen.php",{lesson:lesson});
			}
			
			function sendAjax(){
				//prepare variables
				var ownLanguage = $("#ownLanguage").val();
				var foreignLanguage = $("#foreignLanguage").val();
				var lesson = $("#lesson").val();
				if (ownLanguage=="" || foreignLanguage==""){
					$("#result").text("Beide Felder müssen ausgefüllt sein");
					$("#result").attr("class","result-negative");
					$("#result").fadeIn(200);
				}else{
					$.post("script/save_new_voc.php",{ownLanguage:ownLanguage, foreignLanguage:foreignLanguage, lesson:lesson, date:date}, function(data){
						if (data) {
							$("#result").attr("class","result-positive");
							$("#result").html("Karte <b>"+ownLanguage+" - "+foreignLanguage+"</b> gespeichert.");
							$("#result").fadeIn(200);
							//Reset fields and focus
							$("#ownLanguage").val("");
							$("#foreignLanguage").val("");
							//Timeout is necessary, focus won't work otherwise
							setTimeout(function() {
								 $("#ownLanguage").focus();
								}, 0);
						}
					});
				}
			}
		
			$(document).ready(function(){

				$("textarea").keypress(function(e){
				      if(e.which == 13){
				          sendAjax();
				       }
				});

				$("#newVoc").on('submit', function(e){
					//Prevent page reload when submitted normally
					if (prevent){
						e.preventDefault();
						sendAjax();
					}else{
						prevet=true;
					}
				});

				$("#new_lesson").click(function(e){
					prevent = false;
					$("#newvoc").submit();
				});
			});
		</script>
	</head>
	<body>
		<div>
			<!-- use new lesson as target, saving is done via ajax -->
			<form action="new_lesson.inc.php" id="newVoc" method="post" style="text-align: center">
				<div class="voc_input">
					<div>
						<label for="ownLanguage">Muttersprache:</label><br>
							<textarea id="ownLanguage" name="ownLanguage"><?php 
								if (isset($_SESSION['own_language'])){echo $_SESSION['own_language'];}?></textarea>
					</div>
					<div>
						<label for="foreignLanguage">Fremdsprache:</label><br>
							<textarea id="foreignLanguage" name="foreignLanguage"><?php
								if (isset($_SESSION['foreign_language'])){
									echo $_SESSION['foreign_language'];
									unset($_SESSION['own_language'], $_SESSION['foreign_language']);
								}?></textarea>
					</div>
				</div>
				<br>
				<input type="submit" id="submit" name="submit" value="Speichern">
				<br>
				<div style="text-align: right;">
					<label for="lesson">Lektion:</label>
					<select name="lesson" id="lesson" onchange="lastChosen()">
						<?php
							$db = new DatabaseConnector($_SESSION['user_id']);
							foreach ($db->getAllLessons() as $lesson){
								echo("<option value=".$lesson->getId()
										.($lesson->getLastChosen()==1?" selected":"")
										.">".$lesson->getName());
							}
							/*$pdo = new PDO("mysql:host=localhost;dbname=vokabeltrainer", "root", "123");
							$query = "SELECT * FROM lessons WHERE user_id IN (0,".$_SESSION['user_id'].")";
							foreach ($pdo->query($query) as $row){
								echo("<option value=".$row['id'].">".$row['name']);
							}*/
						?>
					</select><br>
<!-- 					<input type="submit" id='new_lesson' name="new_lesson" value='Lektion erstellen'> -->
					<button id='new_lesson' name="new_lesson">Lektion erstellen</button>
					<input type="hidden" name='origin' value='input_content.php'>
					<a href="new_lesson.inc.php">Lektion erstellen</a>
				</div>
			</form>
			
			<br>
			<p class="result" id="result" hidden="true">Vokabel gespeichert</p>
		</div>
	</body>
</html>