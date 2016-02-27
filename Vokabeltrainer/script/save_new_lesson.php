<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/vocable.php';
	require_once '../models/lesson.php';
	
	$db = new DatabaseConnector($_SESSION['user_id']);
	if (isset($_POST['change'])){
		$lesson = $db->getLessonByName($_POST['name']);
		$lesson->setName($_POST['new_name']);
		$db->updateLesson($lesson);
		header("Location: ../manage_lessons.inc.php");
		die();
	}
	
	$newLesson = new Lesson($_POST["newLesson"]);
	$lesson=$db->saveLesson($newLesson);
	if ($lesson){
		echo $_POST['origin'];
	}else{
		/*echo $statement->queryString;
		echo $statement->errorInfo()[2];*/
		echo FALSE;
	}
?>