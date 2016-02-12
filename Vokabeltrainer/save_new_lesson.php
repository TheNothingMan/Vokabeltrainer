<?php
	$pdo = new PDO("mysql:host=localhost;dbname=vokabeltrainer", "root", "123");
	
	#Prepare Array for insert
	$newLesson["nl"]=$_POST["newLesson"];
	$statement = $pdo->prepare("INSERT INTO lessons (name) VALUES (:nl)");
	if ($statement->execute($newLesson)){
		$id=$pdo->lastInsertId();
		echo TRUE;
	}else{
		/*echo $statement->queryString;
		echo $statement->errorInfo()[2];*/
		echo FALSE;
	}
?>