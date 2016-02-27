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
<!DOCTYPE html>
<html>
	<head>
		<?php include 'head_tag.html';?>
		<title>Lernen</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script type="text/javascript">
		var checked=false;
		var right=0;
		var wrong=0;
		var mastered=0;
		var solution="";
		var cur_step=0;

		function removeActive(element){
			$(element).removeClass("active");
		}
		
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
					window.location.href='results.php?right='+right+'&wrong='+wrong+'&mastered='+mastered;	
				}else{
					var voc = JSON.parse(data);
					$("#index").text(voc.learn_index+" ");
					if (voc.step != cur_step){
						$("#step-"+cur_step).removeClass("current");
						cur_step = voc.step;
						$("#step-"+cur_step).addClass("current");
					}
					$("#ownCard").addClass("hidden");
					$("#ownLanguage").addClass("hidden");
					solution = voc.own_language;
					$("#foreignLanguage").animate({opacity:'0'}, 100, function(){
						$("#foreignLanguage").text(voc.foreign_language);
						$("#foreignLanguage").animate({opacity:'1'}, 100);
					});
					$("#foreignCard").animate({opacity:'0.5'},150);
					$("#foreignCard").animate({opacity:'1'},150);
					$("#right").prop("disabled", true);
					$("#wrong").prop("disabled", true);					
				}
			});
		}

		function check(button) {
			if (!checked){
				checked = true;
				$("#ownLanguage").text(solution);
				$("#ownCard").removeClass("hidden");
				$("#ownLanguage").removeClass("hidden");
				$("#right").prop("disabled", false);
				$("#wrong").prop("disabled", false);
			}
		}
		$(document).ready(function(){
			$.post("script/step.php", function(data){
				var voc = JSON.parse(data);
				solution = voc.own_language;
				$("#index").text(voc.learn_index);
				$("#foreignLanguage").text(voc.foreign_language);
				$("#right").prop("disabled", true);
				$("#wrong").prop("disabled", true);
				$("#step-"+voc.step).addClass("current");
				cur_step = voc.step;
			});
			$("button").click(function(){
				$(this).addClass("active");
				window.setTimeout(function(element){$(element).removeClass("active");}, 100, $(this));
			});

			$(window).bind('input keyup',function(e){
			    switch (e.keyCode){
			    	case 39:
			        	$("#check").trigger("click");
			        	//$("#check").trigger("hover");
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
			<a class='step' id="step-1">1</a>
			<a class='step' id="step-2">2</a>
			<a class='step' id="step-3">3</a>
			<a class='step' id="step-4">4</a>
			<a class='step' id="step-5">5</a>
			<div style="float:right">
				<a id="index"><?php echo $voc->getLearnIndex()?></a>
				<a> von <?php echo $_SESSION['count']?></a>
			</div>
		</div>
		<div class='card_wrapper'>
			<div class='card foreign' id='foreignCard'>
				<span id="foreignLanguage">
				<?php
					//echo $voc->getForeignLang();
				?>			
				</span>
			</div>
			<div class='card own hidden' id="ownCard">
				<span id="ownLanguage">
				<?php 
					//echo $voc->getOwnLang();
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