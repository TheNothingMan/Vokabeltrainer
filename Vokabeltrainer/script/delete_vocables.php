<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/vocable.php';
	require_once '../models/lesson.php';
	
	$ids=json_decode($_POST['ids']);
	$db=new DatabaseConnector($_SESSION['user_id']);
	foreach ($ids as $id){
		//ID and user_id are safe enough
		//$voc=$db->getVocById($id);
		$db->deleteVocById($id);
	}
	echo("Done");
	
?>