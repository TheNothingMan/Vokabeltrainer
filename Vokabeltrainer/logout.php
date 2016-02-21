<?php
	session_start();
	session_destroy();
	echo("<p style='text-align: center;'>Ausgeloggt. Zurück zur <a href='index.php' target='_top'>Startseite</a>.</p>")
?>