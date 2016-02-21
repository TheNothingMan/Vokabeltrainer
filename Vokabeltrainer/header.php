<!-- no session_start here because it is included in parent -->
<div id="title">
	<a id="title" href="index.php">Vokabeltrainer</a>
	<span id=login>
	<?php
		if (isset($_SESSION['user_id'])){
			//TODO: Show username here
			echo("Angemeldet als <br><b>".$_SESSION['email']."</b><br>");
			echo("<a href='logout.php' target='content_frame'>Logout</a>");
		}else{
			echo("<a href='login.php'>login</a>");
		}
	?>
	</span>
</div>
<ul id="menu">
	<li><a href="input_vocables.php">Eingeben</a></li>
	<li><a href="learn.php">Lernen</a></li>
	<li><a href="new_lesson.php">Lektion erstellen</a></li>
</ul>