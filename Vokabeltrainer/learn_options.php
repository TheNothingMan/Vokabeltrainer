<?php
	session_start();
	require_once 'models/database.php';
	require_once 'models/vocable.php';
	require_once 'models/lesson.php';
	if (!isset($_SESSION['user_id'])){
		//TODO: Update urls
		$_SESSION['origin_url']="http://localhost/".$_SERVER['REQUEST_URI'];
		header("Location: login.php");
	}
?>
<html>
	<head>
		<link rel="stylesheet" href="style/main.css" type="text/css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta charset="utf-8">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript">
			function saveLesson(lesson){
				var id = $(lesson).attr("value");
				var state = $(lesson).prop('checked');
				$.post("script/choose_lessons.php",{id: id, state: state},function(data){
					console.log(data);
				});
			}

			function start(){
				console.log("Test start");
				$.ajax({url:"script/prepare_learning.php",success:function(data){
					console.log(data);
					window.location = "learn_step.php";
				}});
			}

			
			$(document).ready(function(){
				$('#check_all').click(function(){
				    $('#lesson_box input[type="checkbox"]').prop('checked', this.checked);
				    $('#lesson_box input[type="checkbox"]').change();
				  });				
			});
		</script>
		<title>Lernen</title>
	</head>
	<body>
		<div id="options">
			<form action="learn_step.php" method="post">
				<a>Lektionen:</a><br>
				<div id="lesson_box">
				<input type="checkbox" id='check_all'>Alle wählen<br>
				<?php
					$db = new DatabaseConnector($_SESSION['user_id']);
					foreach ($db->getAllLessons() as $row){
						echo("<input type=\"checkbox\" value=\"".$row->getId()."\" name=\"lesson_".$row->getName()."\" onchange=\"saveLesson(this)\"".($row->getActive()==1?" checked":"").">".$row->getName()."<br>");			
					}
				?>
				</div>
				<?php
					if ($db->getNextVocable()){
						echo("<br><input type='submit' name='continue' value='Alte Abfrage fortsetzen'>");
					}
				?>
			</form>
			<div class='wrapper'>
				<button onclick="start()">Beginnen</button>
			</div>
		</div>
	</body>
</html>