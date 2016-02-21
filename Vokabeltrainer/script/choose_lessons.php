<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/lesson.php';
	$db = new DatabaseConnector($_SESSION['user_id']);
	$lesson = $db->getLessonById($_POST['id']);
	$lesson->setActive($_POST['state']=="true"?1:0);
	$db->updateLesson($lesson);
	
	/*$pdo = new PDO("mysql:host=localhost;dbname=vokabeltrainer", "root", "123");
	$lesson["id"]=$_POST['id'];
	$lesson["state"]=($_POST['state']=="true"?1:0);
	$statement=$pdo->prepare("UPDATE lessons SET active = :state WHERE id = :id");
	$statement->execute($lesson);*/
	echo("Set lesson with id ".$lesson->getId()." to ".$lesson->getActive());
?>