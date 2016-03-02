<?php
require_once 'lesson.php';
require_once 'vocable.php';
class DatabaseConnector {
	private $pdo;
	private $user_id;
	public function __construct($user_id=0) {
		$this->user_id = $user_id;
		if ($_SERVER['HTTP_HOST']=="localhost"){
			$this->pdo = new PDO ( 'mysql:host=localhost;dbname=vokabeltrainer', 'root', '123' );
		}else if (strpos($_SERVER['HTTP_HOST'],"skaep.lima-city.de")!==false){
			$this->pdo = new PDO ( 'mysql:host=skaep.lima-db.de;dbname=db_337105_1', 'USER337105', 'ijXgmSe5f' );
		}else if (strpos($_SERVER['HTTP_HOST'],"skaeppler.mygoodpage.org")!==false){
			$this->pdo = new PDO ( 'mysql:host=localhost;dbname=nktvnrfd_vokabeltrainer', 'nktvnrfd_admin', 'v*TL8ad_KC=4' );
		}else {
			//for remote debugging and testing of localhost server from other devices
			echo $_SERVER['HTTP_HOST'];
			$this->pdo = new PDO ( "mysql:host=localhost;dbname=vokabeltrainer", 'root', '123' );
		}
	}
	
	/**
	 * Write a new lesson to Database.
	 * Sets this lesson to last_chosen.
	 *
	 * @param Lesson $lesson        	
	 * @return Updated lesson with id if successful, FALSE otherwise.
	 */
	public function saveLesson(Lesson $lesson) {
		$newLesson ["nl"] = $lesson->getName ();
		$newLesson ["user_id"] = $this->user_id;
		$statement = $this->pdo->prepare ( "INSERT INTO lessons (name, user_id, last_chosen) VALUES (:nl, :user_id, 1)" );
		if ($statement->execute ( $newLesson )) {
			$id = $this->pdo->lastInsertId ();
			$lesson->setId ( $id );
			$lesson->setActive ( 1 );
			$lesson->setLastChosen ( 1 );
			$this->pdo->exec ( "UPDATE lessons SET last_chosen=0 WHERE user_id=" . $this->user_id );
			return $lesson;
		} else {
			/*
			 * echo $statement->queryString;
			 * echo $statement->errorInfo()[2];
			 */
			return FALSE;
		}
	}
	public function updateLesson(Lesson $lesson) {
		$statement = $this->pdo->prepare ( "UPDATE lessons SET name=:name
					, active=:active
					, last_chosen=:last_chosen 
					WHERE id=:id" );
		$update ['name'] = $lesson->getName ();
		$update ['active'] = $lesson->getActive ();
		$update ['last_chosen'] = $lesson->getLastChosen ();
		$update ['id'] = $lesson->getId ();
		return $statement->execute ( $update );
	}
	
	/**
	 * Get a lesson by name.
	 *
	 * @param String $name        	
	 * @return Lesson $lesson if success, false otherwise.
	 */
	public function getLessonByName($name) {
		$result = $this->pdo->query ( "SELECT * FROM lessons WHERE name='$name' and user_id=$this->user_id" )->fetch ();
		if (!$result){
			return false;
		}
		$lesson = new Lesson ();
		$lesson->fillFromArray( $result );
		
		return $lesson;
	}
	public function getLessonById($id) {
		$result = $this->pdo->query ( "SELECT * FROM lessons WHERE id='$id'" )->fetch ();
		$lesson = new Lesson ();
		$lesson->fillFromArray ( $result );
		
		return $lesson;
	}
	
	/**
	 * Get all active lessons.
	 *
	 * @return array[Lesson]
	 */
	public function getActiveLessons() {
		$query = "SELECT * FROM lessons WHERE active=1 AND user_id =$this->user_id";
		$lessons = [ ];
		foreach ( $this->pdo->query ( $query ) as $row ) {
			$lesson = new Lesson ();
			$lesson->fillFromArray ( $row );
			$lessons [] = $lesson;
		}
		
		return $lessons;
	}
	
	/**
	 * Get all lessons.
	 *
	 * @return array[Lesson]
	 */
	public function getAllLessons() {
		$query = "SELECT * FROM lessons WHERE user_id = $this->user_id ORDER BY name";
		$lessons = [ ];
		foreach ( $this->pdo->query ( $query ) as $row ) {
			$lesson = new Lesson ();
			$lesson->fillFromArray ( $row );
			$lessons [] = $lesson;
		}
	
		return $lessons;
	}
	
	/**
	 * Update the last chosen lesson.
	 * 
	 * @param int $id        	
	 */
	public function resetLastChosen($id) {
		$this->pdo->exec ( "UPDATE lessons SET last_chosen=0 WHERE last_chosen=1 AND user_id=" . $this->user_id );
		$this->pdo->exec ( "UPDATE lessons SET last_chosen=1 WHERE id=$id" );
	}
	
	/**
	 * Count the vocables in one lesson.
	 * @param unknown $id
	 */
	public function countLessonVocs($id) {
		$row=$this->pdo->query("SELECT COUNT(*) AS anzahl FROM vocables WHERE lesson=$id")->fetch();
		return $row['anzahl'];		
	}
	
	/**
	 * 
	 * @param Lesson $lesson
	 * @param bool $keep
	 */
	public function deleteLesson(Lesson $lesson, $keep){
		if ($keep){
			//TODO: not working!
			$unsorted_lesson=$this->getLessonByName("Unsortiert");
			$query="UPDATE vocables SET lesson=".$unsorted_lesson->getId()." WHERE lesson=".$lesson->getId();
			$this->pdo->exec("UPDATE vocables SET lesson=".$unsorted_lesson->getId()." WHERE lesson=".$lesson->getId());
		}else{
			
			$this->pdo->exec("DELETE FROM lessons WHERE lesson=".$lesson->getId());
		}
		
		$this->pdo->exec("DELETE FROM lessons WHERE id=".$lesson->getId());		
	}
	
	//VOCABLE METHODS
	/**
	 * Creates a vocable. Sets languages, user_id and lesson.
	 * @param Vocable $voc
	 */
	public function createVoc(Vocable $voc){
		$statement = $this->pdo->prepare("INSERT INTO vocables (own_language, foreign_language, lesson, next_date, user_id)
				VALUES (:own_language, :foreign_language, :lesson, :next_date, :user_id)");
		$array = $voc->toArray();
		//Unset unneeded stuff, MySql will handle defaults
		unset($array['id'], $array['step'], $array['last_date'], $array['learn_index']);
		$result = $statement->execute($array);
				
		return $result;
	}
	
	/**
	 * Updates an existing vocable. Sets all values.
	 * @param Vocable $voc
	 * @return true if successful, false otherwise.
	 */
	public function updateVoc(Vocable $voc){
		$statement = $this->pdo->prepare("UPDATE vocables SET own_language=:own_language
				, foreign_language=:foreign_language
				, lesson=:lesson
				, next_date=:next_date
				, last_date=:last_date
				, user_id=:user_id
				, learn_index=:learn_index
				, step=:step
				WHERE id=:id");
		$array = $voc->toArray();
		$result = $statement->execute($array);
		return $result;
		
	}
	
	public function getVocById($id){
		//include user_id to prevent malicious changes
		$array=$this->pdo->query("SELECT * FROM vocables WHERE id=$id AND user_id=$this->user_id")->fetch();
		if(!$array){
			return "evil";				
		}
		$voc = new Vocable();
		$voc->fillFromArray($array);
		return $voc;
	}
	
	/**
	 * Get all vocables in a lesson.
	 * @param int $lesson_id
	 */
	public function getVocablesByLesson($lesson_id){
		$query = "SELECT * FROM vocables WHERE lesson=$lesson_id";
		$vocables = [];
		foreach ($this->pdo->query($query) as $row){
			$voc = new Vocable();
			$voc->fillFromArray($row);
			$vocables[]=$voc;
		}
		
		return $vocables;
	}
	
	/**
	 * Get all vocables in a lesson.
	 * @param String $lesson_name
	 */
	public function getVocablesByLessonName($lesson_name){
		//Query selects vocables by lessons.name to prevent malicious SQL
		$query = "SELECT vocables.* FROM vocables,lessons WHERE lessons.name='$lesson_name' AND lessons.user_id=$this->user_id AND lessons.id=vocables.lesson";
		$vocables = [];
		foreach ($this->pdo->query($query) as $row){
			$voc = new Vocable();
			$voc->fillFromArray($row);
			$vocables[]=$voc;
		}
	
		return $vocables;
	}
	
	/**
	 * Prepares Vocables for Learning.
	 * @param Boolean $plan if timeplan should be used.
	 * @param int[] $steps The steps to learn.  
	 * @return Number of vocs if successful.
	 */
	public function prepareLearnVocables($plan, $steps) {
		$query = "UPDATE vocables SET learn_index=0 WHERE user_id=".$this->user_id;
		$this->pdo->exec($query);
		
		$data = $this->pdo->query("SELECT id FROM lessons WHERE active=1 AND user_id=$this->user_id");
		$lessons= $data->fetchAll(PDO::FETCH_COLUMN,0);
		//check if the plan should be used
		if ($plan) {
			$date = date("Y-m-d");
			$query = "SELECT id FROM vocables WHERE next_date <= '$date' AND lesson IN (".join(",",$lessons).") AND step<6 AND user_id=".$this->user_id;
		}else{
			$query = "SELECT id FROM vocables WHERE lesson IN (".join(",",$lessons).") AND step IN (".join(",",$steps).") AND user_id=".$this->user_id;
		}
		$vocs = $this->pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
		//Shuffle the array
		shuffle($vocs);
		$index = 1;
		foreach ($vocs as $vocable){
			$query = "UPDATE vocables SET learn_index=$index WHERE id=".$vocable['id'];
			$this->pdo->exec($query);
			$index++;
		}
		
		return sizeof($vocs);
	}
	
	/**
	 * Get the next vocable.
	 * @return Vocable is successful, false otherwise.
	 */
	public function getNextVocable(){
		$array=$this->pdo->query("SELECT * FROM vocables WHERE learn_index > 0 AND user_id=".$this->user_id." ORDER BY learn_index")
		->fetch(PDO::FETCH_ASSOC);
		
		if (!$array){
			return false;
		}
		$new_voc= new Vocable();
		$new_voc->fillFromArray($array);
		return $new_voc;
	}
	
	/**
	 * Get the total count of vocs to learn if continuing.
	 */
	public function getCount(){
		$count=$this->pdo->query("SELECT learn_index FROM vocables WHERE user_id=".$this->user_id." ORDER BY learn_index DESC LIMIT 1")->fetch()[0];
		return $count;
	}
	
	/**
	 * Schedules next_date for voc.
	 * @param int $vocable_id Id of the voc to schedule.
	 * @param unknown $result One of 'right', 'wrong', ('check')
	 * @param bool $plan If timeplan is used.
	 * @return True if successful, false otherwise.
	 */
	public function scheduleVoc($vocable_id, $result, $plan) {
		$date=date("Y-m-d");
		
		if ($result=="wrong"){
			//Take $_SESSION['current_id'] and reset step to 1, next date to today and learn_index to 0
			//reset to step 1 even if timeplan isn't used
			$error=$this->pdo->exec("UPDATE vocables SET step=1
			, next_date='$date'
			, last_date='$date'
			, learn_index=0
			WHERE id=".$vocable_id);
		}elseif ($result=="right") {
			#Calculate next_date from step, update step and set learn_index to 0
			$array=$this->pdo->query("SELECT * FROM vocables WHERE id=".$_SESSION['current_id'])->fetch(PDO::FETCH_ASSOC);
			$voc=new Vocable();
			$voc->fillFromArray($array);
			$step=$voc->getStep();
			$next_date=date_create_from_format("Y-m-d", $date);
			//Calculate date, based on current step.
			switch ($step) {
				case 1:
					date_add($next_date, date_interval_create_from_date_string("1 day"));
					break;
						
				case 2:
					date_add($next_date, date_interval_create_from_date_string("4 days"));
					break;
						
				case 3:
					date_add($next_date, date_interval_create_from_date_string("9 day"));
					break;
						
				case 4:
					date_add($next_date, date_interval_create_from_date_string("16 day"));
					break;
				//This isn't of any use currently.		
				case 5:
					date_add($next_date, date_interval_create_from_date_string("24 day"));
					break;
						
				default:
		
					break;
			}
			//If plan is used, update the step. Only set dates otherwise.
			if ($plan){
				$voc->setStep($step+1);
			}
			$voc->setNextDate(date_format($next_date, "Y-m-d"));
			$voc->setLastDate($date);
			$voc->setLearnIndex(0);
			
			$result = $this->updateVoc($voc);
			
			return $result;
		}
	}
	
	/**
	 * Delete vocable.
	 * @param Vocable $voc
	 */
	public function deleteVoc(Vocable $voc){
		$this->pdo->exec("DELETE FROM vocables WHERE user_id=$this->user_id AND id=".$voc->getId());
	}
	
	
	/**
	 * Delete voc by specifying id only.
	 * @param unknown $id
	 */
	public function deleteVocById($id){
		$statement=$this->pdo->prepare("DELETE FROM vocables WHERE user_id=$this->user_id AND id= :id");
		return $statement->execute(["id"=>$id]);
	}
	
	//USER METHODS
	public function userExists(User $user){
		$statement = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
		$statement->execute(array('email' => $user->getEmail()));
		$result = $statement->fetch();
		return $result;
	}
	
	/**
	 * This creates a new user. NOTE: This function creates a new "Unsortiert" lesson for each user.
	 * @param User $user
	 * 
	 * @return int user_id
	 */
	public function createUser(User $user){
		$statement = $this->pdo->prepare("INSERT INTO users (email, password) VALUES (:email, :password)");
		$result = $statement->execute(array('email' => $user->getEmail(), 'password' => $user->getPassword()));
		if(!$result){
			return $result;
		}
		$this->user_id=$this->pdo->lastInsertId();
		$lesson = new Lesson("Unsortiert", $this->user_id);
		$this->saveLesson($lesson);
		
		return $this->user_id;
	}
	
	/**
	 * Get user by email address.
	 * @param unknown $email
	 * @return User if successful, false otherwise.
	 */
	public function getUserByEmail($email){
		$statement = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
		$result = $statement->execute(array('email' => $email));
		$data = $statement->fetch();
		if (!$data){
			return false;
		}
		$user = new User();
		$user->fillFromArray($data);
		
		return $user;
	}
	
	/**
	 * Get user by id.
	 * @param unknown $id
	 * @return User if successful, false otherwise.
	 */
	public function getUserById($id){
		$statement = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
		$result = $statement->execute(['id' => $id]);
		$data = $statement->fetch();
		if (!$data){
			return false;
		}
		$user = new User();
		$user->fillFromArray($data);
	
		return $user;
	}
	
	/**
	 * Update existing user.
	 * 
	 * @param User $user
	 * @return boolean result
	 */
	public function updateUser(User $user){
		$statement = $this->pdo->prepare("UPDATE users SET email=:email, password=:password WHERE id=".$user->getId());
		$result = $statement->execute(array('email' => $user->getEmail(), 'password' => $user->getPassword()));
		return $result;
	}
	
	/**
	 * Store the count of results for later use.
	 * @param string $field
	 * @param int $count
	 * @return boolean
	 */
	public function setResultCount($field, $count){
		//this stores result counts in the database, so that they are available even on other devices.
		return $this->pdo->exec("UPDATE users SET $field=$count WHERE id=$this->user_id");
	}
	
	/**
	 * Get the count of results.
	 * @return Array["right","wrong","mastered"]
	 */
	public function getResultCount(){
		$data = $this->pdo->query("SELECT right_c,wrong_c,mastered_c FROM users WHERE id=$this->user_id")->fetch();
		return $data;
	}
	
	/**
	 * Store options in database. This will write a JSON string to db.
	 * @param [] $options
	 */
	public function saveOptions($options){
		$options_json = json_encode($options);
		$statement = $this->pdo->prepare("UPDATE users SET options=:json WHERE id=$this->user_id");
		$result = $statement->execute(["json"=>$options_json]);
		return $result;
	}
	
	/**
	 * Get options from db.
	 * @return array options
	 */
	public function getOptions(){
		$data = $this->pdo->query("SELECT options FROM users WHERE id=$this->user_id")->fetch();
		if (!$data || $data['options']==null){
			//Init default values.
			$options=["plan"=>"true", "direction"=>"fo", "steps"=>[1,2,3,4,5,6]];
		}else{
			$options = json_decode($data['options'],true);
		}
		return $options;
	}
}
?>