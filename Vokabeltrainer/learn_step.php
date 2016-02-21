<?php
	session_start();
	require_once 'models/database.php';
	require_once 'models/vocable.php';
	require_once 'models/lesson.php';
	$db = new DatabaseConnector($_SESSION['user_id']);
	$voc = $db->getNextVocable();
	if (!$voc){
		die("Keine Vokabeln für heute übrig. Zurück zur <a href='index.php' target='_top'>Startseite</>.");
	}
	$_SESSION['current_id']=$voc->getId();
	if (isset($_POST['continue'])){
		$_SESSION['count']=$db->getCount();
	}
?>
<html>
	<head>
		<link rel="stylesheet" href="style/main.css" type="text/css">
		<meta charset="utf-8">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript">
		var checked=false;
		var right=0;
		var wrong=0;
		var mastered=0;
		function step(button){
			if (!checked) {
				return 0;
			}
			checked=false;
			var result = $(button).attr('id');
			if (result=="right"){
					right += 1;
					if ($(step).text()==5){
						mastered+=1;
					}
			}else if (result=="wrong"){
					wrong += 1;
			}

			$.post("script/step.php", {result:result}, function(data){
				console.log(data);
				if (data == "end"){
					//TODO: Status screen and link to homepage here
					window.location.href='results.php?right='+right+'&wrong='+wrong+'&mastered='+mastered;
					$("#foreignLanguage").html("<a href='index.php' target='_top'>Fertig. Zurück zum Start. Richtig: "+right+", falsch: "+wrong+"</a>");
				}else{
					var voc = JSON.parse(data);
					$("#index").text(voc.learn_index+" ");
					$("#step").text(voc.step);
					$("#ownLanguage").animate({width:'0', opacity:'0', minWidth:'0'},"fast", function(){
						$("#ownLanguage").css({"visibility": "collapse", "width":"0"});
						$("#ownLanguage").text(voc.own_language);
					});
					//TODO: Make this somewhat more sophisticated
					$("#foreignLanguage").animate({opacity:'0'}, 100, function(){
						$("#foreignLanguage").text(voc.foreign_language);
						$("#foreignLanguage").animate({opacity:'1'}, 100);
					});
					$("#foreignCard").animate({opacity:'0.3'},150);
					$("#foreignCard").animate({opacity:'1'},150);
					$("#right").prop("disabled", true);
					$("#wrong").prop("disabled", true);					
				}
			});
		}

		function check(button) {
			if (!checked){
				checked = true;
				$("#ownLanguage").css({"visibility": "visible", "width":"0", "min-width":"200"});
				$("#ownLanguage").animate({width:'30%', opacity:'1'},"fast");
				//TODO: Make this somewhat more sophisticated
				$("#right").prop("disabled", false);
				$("#wrong").prop("disabled", false);
			}
		}
		$(document).ready(function(){
			$("#ownLanguage").css("visibility", "collapse");
			$(window).keypress(function(e){
			    switch (e.keyCode){
			    	case 39:
			        	$("#check").trigger("click");
			    		break;
			    	case 38:
				    	$("#right").click();
				    	break;
			    	case 40:
				    	$("#wrong").click();
				    	break;
			    }
			});
		});
		</script>
	</head>
	<body class="learn_body">
		<?php
			if ($voc == false) {
				die("Keine Vokabeln für heute übrig. Zurück zur <a href='index.php' target='_top'>Startseite</a>.");
			}
		?>
		<div class='status_bar'>
			<a>Stufe: </a>
			<a id="step"><?php echo $voc->getStep()?></a>
			<a style="float:right"> von <?php echo $_SESSION['count']?></a>
			<a style="float:right" id="index"><?php echo $voc->getLearnIndex()?></a>
		</div>
		<div class='card_wrapper'>
			<div class='card foreign' id='foreignCard'>
				<span id="foreignLanguage">
				<?php
					echo $voc->getForeignLang();
				?>			
				</span>
			</div>
			<div class='card own' id="ownLanguage">
				<span>
				<?php 
					echo $voc->getOwnLang();
				?>
				</span>
			</div>
		</div>
		<br>
		<div class='button-wrapper'>
			<button class='learn' id="right" onclick="step(this)" disabled>Gewusst</button><br>
			<button class='learn' id="wrong" onclick="step(this)" disabled>Nicht gewusst</button>
			<button class='learn' id="check" onclick="check(this)">Weiter</button>			
		</div>
	</body>
</html>