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
		$(document).ready(function(){
			$("textarea").keypress(function(e){
			      if(e.which == 13){
				      console.log("enter");
				      $("#submit").click();
			       }else{
				       console.log("not enter");
			       }
			});
		});
		</script>
	</head>
	<body>
		<div>
			<form action="script/save_new_voc.php" id="saveVoc" style="text-align: center" method="post">
				<div class="voc_input">
					<div>
						<label for="ownLanguage">Muttersprache:</label><br>
							<textarea id="ownLanguage" name="ownLanguage"><?php echo($_GET['ol'])?></textarea>
					</div>
					<div>
						<label for="foreignLanguage">Fremdsprache:</label><br>
							<textarea id="foreignLanguage" name="foreignLanguage"><?php echo($_GET['fl'])?></textarea>
					</div>
				</div>
				<input type="hidden" name="origin" value="<?php echo ($_GET['origin']);?>">
				<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
				<br>
				<input type="submit" id="submit" name="change" value="Speichern">
			</form>
			
			<br>
			<p class="result" id="result" hidden="true">Vokabel gespeichert</p>
		</div>
	</body>
</html>