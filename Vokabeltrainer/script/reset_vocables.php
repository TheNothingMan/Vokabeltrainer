<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/vocable.php';
	require_once '../models/lesson.php';
	
	$ids=json_decode($_POST['ids']);
	$db=new DatabaseConnector($_SESSION['user_id']);
	$step=$_POST['step'];
	foreach ($ids as $id){
		$voc=$db->getVocById($id);
		if ($voc->getStep()>$step){
			$voc->setStep($step);
		}
		$db->updateVoc($voc);
	}
	echo("Done");
	
?>