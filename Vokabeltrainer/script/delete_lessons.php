<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/vocable.php';
	require_once '../models/lesson.php';
	
	$names=json_decode($_POST['names']);
	$keep=($_POST['keep']=="true"?true:false);
	$db=new DatabaseConnector($_SESSION['user_id']);
	foreach ($names as $name){
		$lesson = $db->getLessonByName($name);
		$db->deleteLesson($lesson, $keep);
		echo("Deleting ".$lesson->getName());
	}
	echo("Done");
	
?>