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
		$db->scheduleVoc($_SESSION['current_id'], $_POST['result'], $_SESSION['plan']);
		
		if ($_POST['result']=="right"){
			$_SESSION['right_c']++;
			$db->setResultCount('right_c', $_SESSION['right_c']);
			if ($db->getVocById($_SESSION['current_id'])->getStep()==6){
				$_SESSION['mastered_c']++;
				$db->setResultCount('mastered_c', $_SESSION['mastered_c']);
			}
		}elseif ($_POST['result']=="wrong"){
			$_SESSION['wrong_c']++;
			$db->setResultCount('wrong_c', $_SESSION['wrong_c']);
		}
	}
	
	#  Now return the next vocable
	$new_voc = $db->getNextVocable();
	if (!$new_voc){
		die("end");
	}
	$_SESSION['current_id']=$new_voc->getId();
	//shuffle based on direction setting
	if ($_SESSION['direction']=="of"){
		$new_voc->swap();
	}elseif ($_SESSION['direction']=="ra"){
		$new_voc->swapRandomly();
	}
	$lesson = $db->getLessonById($new_voc->getLesson());
	$data = $new_voc->toArray();
	$data["lesson_name"] = $lesson->getName();
	//echo $new_voc->toJSON();
	echo json_encode($data);
?>