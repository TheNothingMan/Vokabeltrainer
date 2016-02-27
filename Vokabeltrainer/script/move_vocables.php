<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/vocable.php';
	require_once '../models/lesson.php';
	
	$ids=json_decode($_POST['ids']);
	$db=new DatabaseConnector($_SESSION['user_id']);
	$lesson=$db->getLessonById($_POST['lesson']);
	foreach ($ids as $id){
		$voc=$db->getVocById($id);
		$db->moveVoc($voc, $lesson);
	}
	echo("Done");
	
?>