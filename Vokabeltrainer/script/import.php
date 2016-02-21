<?php
	session_start();
	require_once '../models/database.php';
	require_once '../models/vocable.php';
	require_once '../models/lesson.php';
	$user_id = $_SESSION['user_id'];
	function getLessonName($string){
		$lesson = substr($string, strpos($string, "# ")+2,strpos($string, " #")-2);
		return $lesson;
	}
	
	$db = new DatabaseConnector($user_id);
	$file = $_FILES["file"];
	$fileType = pathinfo($file["name"],PATHINFO_EXTENSION);
	if ($fileType != "csv"){
		die("Wrong file type");
	}
	
	//$csv = array_map('str_getcsv', (file($file['tmp_name'])), [";"]);
	$data = file_get_contents($file['tmp_name']);
	$lines = explode(PHP_EOL, $data);
	$csv = [];
	foreach ($lines as $line) {
		$csv[] = str_getcsv($line,";");
	}
	//$pdo = new PDO('mysql:host=localhost;dbname=vokabeltrainer', 'root', '123');
	$date = date("Y-m-d");
	//$statement = $pdo->prepare("INSERT INTO vocables (own_language, foreign_language, lesson, next_date, user_id) VALUES (:ol, :fl, :lesson, :next_date, :user_id)");
	foreach ($csv as $row){
		if (count($row) == 1 AND $row[0]!=""){
			$name = getLessonName($row[0]);
			$lesson = $db->getLessonByName($name);
			if ($lesson){
				$lesson_id = $lesson->getId();
			}else{
				$new_lesson = new Lesson($name);
				$lesson_id = $db->saveLesson($new_lesson)->getId();
			}
		}else{
			$voc = new Vocable($user_id,$row[1],$row[0],$lesson_id,$date);
			/*$newVoc["ol"] = $row[1];
			$newVoc["fl"] = $row[0];
			$newVoc["lesson"] = $lesson_id;
			$newVoc["next_date"] = $date;
			$newVoc["user_id"] = $user_id;
			$statement->execute($newVoc);*/
			$db->createVoc($voc);
		}
	}
	
?>