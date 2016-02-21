<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/vocable.php';
	require_once '../models/lesson.php';
	
	$newLesson = new Lesson($_POST["newLesson"]);
	$db = new DatabaseConnector($_SESSION['user_id']);
	$lesson=$db->saveLesson($newLesson);
	if ($lesson){
		echo TRUE;
	}else{
		/*echo $statement->queryString;
		echo $statement->errorInfo()[2];*/
		echo FALSE;
	}
?>