<?php
	$pdo = new PDO("mysql:host=localhost;dbname=vokabeltrainer", "root", "123");
	#Prepare Array for insert
	$newVocable["ol"]=$_POST["ownLanguage"];
	$newVocable["fl"]=$_POST["foreignLanguage"];
	$newVocable["lesson"]=$_POST["lesson"]; 
	$statement = $pdo->prepare("INSERT INTO vocables (own_language, foreign_language, lesson) VALUES (:ol, :fl, :lesson)");
	$statement->execute($newVocable);
	
	$id=$pdo->lastInsertId();
	echo TRUE;
?>