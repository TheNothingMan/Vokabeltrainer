<?php
	session_start();
	require_once 'models/database.php';
	require_once 'models/vocable.php';
	require_once 'models/lesson.php';
	if (!isset($_SESSION['user_id'])){
		//TODO: Update urls fpr frames
		$_SESSION['origin_url']="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		header("Location: login.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include 'head_tag.html';?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript">
			function change(button){
				var id = $(button).prop('value');
				var ol = $("#ol-"+id).text();
				var fl = $("#fl-"+id).text();
				var origin = "../manage_vocables.inc.php?name="+$("#lesson").text();
				console.log(id);
				//TODO: SECURITY???
				window.location.href="change_voc.inc.php?ol="+ol+"&fl="+fl+"&origin="+origin+"&id="+id;
			}
			$(document).ready(function(){
				$('#check_all').click(function(){
				    $('input[type="checkbox"]').prop('checked', this.checked);
				    $('input[type="checkbox"]').change();
				  });

				$("#delete").click(function(){
					 var r=confirm("Ausgewählte Vokabeln löschen?");
					 if (r){
						 var ids = [];
						 $("input:checkbox[id^=v-]").each(function(){
							 if ($(this).prop("checked")){
							 	ids.push($(this).prop("value"));
							 }
						 });
						 ids_json = JSON.stringify(ids);
						 $.post("script/delete_vocables.php", {ids: ids_json},function(data){
							 location.reload(true);
						 });
					 }
				 });

				 $("#move").click(function(){
					 var r=confirm("Ausgewählte Vokabeln verschieben?");
					 if (r){
						 var ids = [];
						 var lesson = $("#choose_lesson").val();
						 $("input:checkbox[id^=v-]").each(function(){
							 if ($(this).prop("checked")){
							 	ids.push($(this).prop("value"));
							 }
						 });
						 ids_json = JSON.stringify(ids);
						 $.post("script/move_vocables.php", {ids: ids_json, lesson: lesson},function(data){
							 location.reload(true);
						 });
					 }
				 });

				 $("#reset").click(function(){
					 var r=confirm("Ausgewählte Vokabeln zurücksetzen?");
					 if (r){
						 var ids = [];
						 var step = $("#choose_step").val();
						 $("input:checkbox[id^=v-]").each(function(){
							 if ($(this).prop("checked")){
							 	ids.push($(this).prop("value"));
							 }
						 });
						 ids_json = JSON.stringify(ids);
						 $.post("script/reset_vocables.php", {ids: ids_json, step: step},function(data){
							 location.reload(true);
						 });
					 }
				 });
			});
		</script>
		<title>Lektionen</title>
	</head>
	<body>
		<table class='manage' id='vocable_table'>
			<caption>Vokabeln in "<a id='lesson'><b><?php echo $_GET['name']?></b></a>" verwalten</caption>
			<thead>
				<tr>
					<th><input type="checkbox" id="check_all"></th>
					<th>Muttersprache</th>
					<th>Fremdsprache</th>
					<th>Stufe</th>
					<th></th>
				</tr>
			</thead>
			<?php
				$db=new DatabaseConnector($_SESSION['user_id']);
				$vocable_list=$db->getVocablesByLessonName($_GET['name']);
				foreach ($vocable_list as $vocable){
					echo("<tr><td><input type='checkbox' value='".$vocable->getId()."' id='v-".$vocable->getId()."'/></td>");
					echo("<td id=ol-".$vocable->getId().">".$vocable->getOwnLang()."</td><td id=fl-".$vocable->getId().">".$vocable->getForeignLang()."</td>");
					echo("<td>".$vocable->getStep()."</td><td><button onclick='change(this)' value=".$vocable->getId().">Ändern</button></td></tr>");
				}
			?>
		</table>
		<div class='bottom_line'>
			<a>Ausgewählte:</a><br>
			<div class='manage_box'>
				<button id='delete'>Löschen</button>
			</div>
			<div class='manage_box'>
				<button id='reset'>Zurücksetzen</button><br>
				<a> auf Stufe </a>
				<select id="choose_step">
					<option value=1>1
					<option value=2>2
					<option value=3>3
					<option value=4>4
					<option value=5>5
				</select><br>
				<a style='font-size:60%'>Nur Vokabeln mit höherer Stufe werden zurückgesetzt.</a>
			</div>
			<div class='manage_box'>
				<button id='move'>Verschieben</button><br>
				<a> nach </a>
				<select id="choose_lesson">
					<?php
						$db = new DatabaseConnector($_SESSION['user_id']);
						foreach ($db->getAllLessons() as $lesson){
							echo("<option value=".$lesson->getId()
									.($lesson->getLastChosen()==1?" selected":"")
									.">".$lesson->getName());
						}
					?>
				</select>
			</div>
		</div>
	</body>
</html>