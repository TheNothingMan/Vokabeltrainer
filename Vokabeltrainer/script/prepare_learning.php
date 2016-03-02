<?php
	session_start();
	require_once '../models/database.php';
	
	$db = new DatabaseConnector($_SESSION['user_id']);
	$plan = ($_POST['plan']=="true"?true:false);
	$steps = json_decode($_POST['steps']);
	$direction = $_POST['direction'];
	
	//Store options to database
	$options = ["plan"=>$plan,"direction"=>$direction,"steps"=>$steps];
	$db->saveOptions($options);
	
	$_SESSION['count'] = $db->prepareLearnVocables($plan, $steps);
	$_SESSION['direction'] = $direction;
	$_SESSION['plan'] = $plan;
	//Reset result counts
	$_SESSION['right_c'] = 0;
	$_SESSION['wrong_c'] = 0;
	$_SESSION['mastered_c'] = 0;
	$db->setResultCount("right_c", 0);
	$db->setResultCount("wrong_c", 0);
	$db->setResultCount("mastered_c", 0);
?>