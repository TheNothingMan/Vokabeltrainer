<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/vocable.php';
	$date = date("Y-m-d");
	$db = new DatabaseConnector($_SESSION['user_id']);
	
	//if request come from change-site, update the vocable and return to managing page
	if (isset($_POST['change'])){
		$voc = $db->getVocById($_POST['id']);
		$voc->setOwnLang($_POST['ownLanguage']);
		$voc->setForeignLang($_POST['foreignLanguage']);
		$db->updateVoc($voc);
		header("Location: ".$_POST['origin']);
		die();
	}
	$voc = new Vocable($_SESSION['user_id'],$_POST["ownLanguage"],$_POST["foreignLanguage"],$_POST["lesson"],$date);
	$db->createVoc($voc);
	/*$pdo = new PDO("mysql:host=localhost;dbname=vokabeltrainer", "root", "123");
	#Prepare Array for insert
	$newVocable["ol"]=$_POST["ownLanguage"];
	$newVocable["fl"]=$_POST["foreignLanguage"];
	$newVocable["lesson"]=$_POST["lesson"];
	$newVocable["nextDate"]=$_POST["date"];
	$newVocable["user_id"]=$_SESSION['user_id'];
	$statement = $pdo->prepare("INSERT INTO vocables (own_language, foreign_language, lesson, next_date, user_id) VALUES (:ol, :fl, :lesson, :nextDate, :user_id)");

	$statement->execute($newVocable);
	
	$id=$pdo->lastInsertId();*/
	echo TRUE;
?>