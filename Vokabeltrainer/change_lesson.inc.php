<?php
session_start();
require_once 'models/database.php';
require_once 'models/lesson.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include 'head_tag.html';?>
	</head>
	<body>
		<div>
			<form action="script/save_new_lesson.php" id="saveLesson" style="text-align: center" method="post">
				<input type="text" id="new_name" name="new_name" value="<?php echo $_GET['name']?>"><br>
				<input type="hidden" name="name" value="<?php echo $_GET['name']?>">
				<br>
				<input type="submit" id="submit" name="change" value="Speichern">
			</form>
			
			<br>
			<p class="result" id="result" hidden="true">Vokabel gespeichert</p>
		</div>
	</body>
</html>