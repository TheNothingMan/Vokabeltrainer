<?php
	session_start();
	require_once 'models/database.php';
	require_once 'models/vocable.php';
	require_once 'models/lesson.php';
	if (!isset($_SESSION['user_id'])){
		//TODO: Update urls
		$_SESSION['origin_url']="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		header("Location: login.php");
	}
	$db = new DatabaseConnector($_SESSION['user_id']);
	$options = $db->getOptions();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include 'head_tag.html';?>
		<title>Lernen</title>
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
				var plan = $("#plan").prop('checked');
				var direction = $("input:radio[name='direction']:checked").val();
				var steps=[];
				$("input:checkbox[id^=step-]").each(function(){
					 if ($(this).prop("checked")){
					 	steps.push($(this).prop("value"));
					 }
				 });
				steps_json = JSON.stringify(steps);
				
				$.ajax({url:"script/prepare_learning.php", type:"POST", data:{plan:plan, direction: direction, steps:steps_json}, success:function(data){
					console.log(data);
					window.location = "learn_step.php";
				}});
			}

			
			$(document).ready(function(){
				$('#check_all').click(function(){
					$('#lesson_box input[type="checkbox"]').prop('checked', this.checked);
					$('#lesson_box input[type="checkbox"]').change();
				});
				
				$("#plan").click(function(){
					if ($(this).prop('checked')){
						$("#steps :input").prop('disabled', true);
					}else{
						$("#steps :input").prop('disabled', false);
					}
				}); 				
			});
		</script>
		<title>Lernen</title>
	</head>
	<body>
		<div id="options">
			<form action="learn_step.php" method="post">
				<b><a>Lektionen:</a></b><br>
				<div style="display:flex; justify-content: space-between;">
					<div id="lesson_box">
					<input type="checkbox" id='check_all'>Alle wählen<br>
					<?php
						foreach ($db->getAllLessons() as $row){
							echo("<input type=\"checkbox\" value=\"".$row->getId()."\" name=\"lesson_".$row->getName()."\" onchange=\"saveLesson(this)\"".($row->getActive()==1?" checked":"")."><a>".$row->getName()." (".$db->countLessonVocs($row->getId()).")</a><br>");			
						}
					?>
					</div>
					<div id="other_options">
						<input type="checkbox" id='plan' name='plan' <?php echo($options['plan']?"checked":"");?>><a>Zeitplan verwenden</a>
						<div class="tooltip">Was heißt das?
							<span class="tooltiptext">Hinweis: Bei deaktiviertem Zeitplan werden gewusste Vokabeln nicht hochgestuft, nicht gewusste aber auf Stufe 1 zurückgesetzt.<br>
							Damit eignet sich das Lernen ohne Zeitplan für kurzfristiges Lernen, aber auch für die Kontrolle von <b>gemeisterten</b> Wörtern.</span>
						</div><br>
						<a>Aktive Stufen: </a><div id="steps">
							<a>1</a><input type="checkbox" id='step-1' value=1 <?php echo(!$options['plan']?"":"disabled "); echo(in_array('1',$options['steps'])?"checked":"");?>>
							<a>2</a><input type="checkbox" id='step-2' value=2 <?php echo(!$options['plan']?"":"disabled "); echo(in_array('2',$options['steps'])?"checked":"");?>>
							<a>3</a><input type="checkbox" id='step-3' value=3 <?php echo(!$options['plan']?"":"disabled "); echo(in_array('3',$options['steps'])?"checked":"");?>>
							<a>4</a><input type="checkbox" id='step-4' value=4 <?php echo(!$options['plan']?"":"disabled "); echo(in_array('4',$options['steps'])?"checked":"");?>>
							<a>5</a><input type="checkbox" id='step-5' value=5 <?php echo(!$options['plan']?"":"disabled "); echo(in_array('5',$options['steps'])?"checked":"");?>>
							<a>M</a><input type="checkbox" id='step-6' value=6 <?php echo(!$options['plan']?"":"disabled "); echo(in_array('6',$options['steps'])?"checked":"");?>>
						</div>
						<p>Richtung der Abfrage:</p>
						<div class='radio_wrapper'><input type="radio" id='foreign-own' name="direction" value="fo" <?php echo($options['direction']=="fo"?"checked":"");?>><a>Fremdsprache-Muttersprache</a></div>
						<div class='radio_wrapper'><input type="radio" id='own-foreign' name="direction" value="of" <?php echo($options['direction']=="of"?"checked":"");?>><a>Muttersprache-Fremdsprache</a></div>
						<div class='radio_wrapper'><input type="radio" id='random' name="direction" value="ra" <?php echo($options['direction']=="ra"?"checked":"");?>><a>Zufällig in beide Richtungen</a></div>
					</div>
				</div>
				<?php
					if ($db->getNextVocable()){
						echo("<input type='submit' name='continue' value='Alte Abfrage fortsetzen'>");
					}
				?>
			</form>
			<div class='bottom_line'>
				<button onclick="start()">Beginnen</button>
			</div>
		</div>
	</body>
</html>