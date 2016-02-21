<?php
	session_start();
	require_once '../models/database.php';
	
	$db = new DatabaseConnector($_SESSION['user_id']);
	$_SESSION['count'] = $db->prepareLearnVocables();
?>