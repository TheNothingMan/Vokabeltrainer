<!DOCTYPE html>
<?php
	session_start();
	require_once 'models/database.php';
	require_once 'models/vocable.php';
	require_once 'models/lesson.php';
	if (!isset($_SESSION['user_id'])){
		//TODO: Update urls
		$_SESSION['origin_url']="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		header("Location: login_content.php");
	}
?>
<html>
	<head>
		<?php include 'head_tag.html';?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript">
			function change(button){
				var name = $(button).prop('value');
				window.location.href="change_lesson.inc.php?name="+name;
			}

			$(document).ready(function(){
				$('#check_all').click(function(){
				    $('input[type="checkbox"]').prop('checked', this.checked);
				    $('input[type="checkbox"]').change();
				  });

				 $("#delete").click(function(){
					 var r=confirm("Ausgewählte Lektionen löschen?");
					 if (r){
						 var names = [];
						 $("input:checkbox[id^=l-]").each(function(){
							 if ($(this).prop("checked")){
							 	names.push($(this).prop("value"));
							 }
						 });
						 var keep = $("#keep_vocs").prop("checked");
						 names_json = JSON.stringify(names);
						 $.post("script/delete_lessons.php", {keep:keep, names: names_json},function(data){
							 location.reload(true);
						 });
					 }
				 });
			});
		</script>
		<title>Lektionen</title>
	</head>
	<body>
		<table class='manage' id='lesson_table'>
			<caption>Lektionen verwalten</caption>
			<thead>
				<tr>
					<th><input type="checkbox" id="check_all"></th>
					<th>Name</th>
					<th>Vokabeln</th>
					<th></th>
				</tr>
			</thead>
			<?php
				$db=new DatabaseConnector($_SESSION['user_id']);
				$lesson_list=$db->getAllLessons(); 
				foreach ($lesson_list as $lesson){
					if ($lesson->getName()=="Unsortiert"){
						echo("<tr><td></td><td><a href='manage_vocables.inc.php?name=".$lesson->getName()."'>".$lesson->getName()."</a></td><td>".$db->countLessonVocs($lesson->getId())."</td><td></tr>");
					}else{
						echo("<tr><td><input type='checkbox' value='".$lesson->getName()."' id='l-".$lesson->getName()."'/></td><td><a href='manage_vocables.inc.php?name=".$lesson->getName()."'>".$lesson->getName()."</a></td><td>".$db->countLessonVocs($lesson->getId())."</td><td><button onclick='change(this)' value='".$lesson->getName()."'>Umbenennen</button></td></tr>");
					}					
				}
			?>
		</table>
		<div class='bottom_line'>
			<form action='new_lesson.inc.php' method="post" style='display:inline;'>
				<input type='submit' id='new' value='Neue Lektion/Import'>
				<input type='hidden' name='origin' value='manage_lessons.php'>
			</form>
			<button id='delete'>Löschen</button><input type='checkbox' id='keep_vocs'>Vokabeln behalten und nach "Unsortiert" verschieben.
		</div>
	</body>
</html>