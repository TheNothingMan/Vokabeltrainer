<!DOCTYPE html>
<?php
	session_start();
?>
<html>
	<head>
		<?php include 'head_tag.html';?>
		<title>Vokabeltrainer</title>
	</head>
	<body>
	<div class='page'>
		<div id="header">
			<?php include 'header.php';?>
		</div>
		<div class="content wrapper">
			<iframe  class='content' id='content_frame' name='content_frame' src='welcome.php'></iframe>
		</div>
	</div>
	</body>
</html>