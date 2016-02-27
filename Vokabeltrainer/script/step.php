<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/vocable.php';
	require_once '../models/lesson.php';
	#  This script calculates the next_date field for right vocs and resets wrong ones.
	#  It returns all information that is needed for the javascript to update the site with new vocs,
	#  that is: own_/foreign_language, lesson, step, index
	
	$db = new DatabaseConnector($_SESSION['user_id']);
	if (isset($_POST['result'])){
		$db->scheduleVoc($_SESSION['current_id'], $_POST['result']);
	}
	
	#  Now return the next vocable
	$new_voc = $db->getNextVocable();
	if (!$new_voc){
		die("end");
	}
	$_SESSION['current_id']=$new_voc->getId();
	echo $new_voc->toJSON();
?>