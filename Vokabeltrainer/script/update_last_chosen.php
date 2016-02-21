<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/lesson.php';
	
	$db = new DatabaseConnector($_SESSION['user_id']);
	$db->resetLastChosen($_POST['lesson']);
?>