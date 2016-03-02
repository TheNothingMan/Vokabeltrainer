<?php
	class Vocable{
		private $id;
		private $user_id;
		private $own_lan;
		private $foreign_lan;
		private $step;
		private $next_date;
		private $last_date;
		private $lesson;
		private $learn_index;
		
		/**
		 * Create a new voc. Use parameters to create a new voc,
		 * or leave empty and use fillFromArray($array) instead.
		 * 
		 * @param number $user_id
		 * @param string $own_lan
		 * @param string $foreign_lan
		 * @param number $lesson
		 * @patam string $next_date
		 */
		function __construct($user_id=0, $own_lan="",$foreign_lan="",$lesson=0,$next_date=""){
			$this->id=0;
			$this->user_id=$user_id;
			$this->own_lan=$own_lan;
			$this->foreign_lan=$foreign_lan;
			$this->lesson=$lesson;
			$this->step=1;
			$this->next_date=$next_date;
			$this->last_date="";
			$this->learn_index=0;
		}
		
		//GETTERS
		function getId(){
			return $this->id;
		}
		
		function getUserId(){
			return $this->user_id;
		}
		
		function getOwnLang(){
			return $this->own_lan;
		}
		
		function getForeignLang(){
			return $this->foreign_lan;
		}
		
		function getStep(){
			return $this->step;
		}
		
		function getNextDate(){
			return $this->next_date;
		}
		
		function getLastDate(){
			return $this->last_date;
		}
		
		function getLesson(){
			return $this->lesson;
		}
		
		function getLearnIndex(){
			return $this->learn_index;
		}
		
		//SETTERS
		public function setId($id){
			$this->id = $id;
		}
		
		public function setUserId($user_id){
			$this->user_id = $user_id;
		}
	
		public function setOwnLang($own_lan){
			$this->own_lan = $own_lan;
		}
	
		public function setForeignLang($foreign_lan){
			$this->foreign_lan = $foreign_lan;
		}
	
		public function setStep($step){
			$this->step = $step;
		}
	
		public function setNextDate($next_date){
			$this->next_date = $next_date;
		}
	
		public function setLastDate($last_date){
			$this->last_date = $last_date;
		}
	
		public function setLesson($lesson){
			$this->lesson = $lesson;
		}
	
		public function setLearnIndex($learn_index){
			$this->learn_index = $learn_index;
		}
		
		//METHODS
		public function fillFromArray($array){
			$this->id=$array['id'];
			$this->user_id=$array['user_id'];
			$this->own_lan=$array['own_language'];
			$this->foreign_lan=$array['foreign_language'];
			$this->step=$array['step'];
			$this->next_date=$array['next_date'];
			$this->last_date=$array['last_date'];
			$this->lesson=$array['lesson'];
			$this->learn_index=$array['learn_index'];
		}
		
		public function toJSON(){
			$array = $this->toArray();
			
			return json_encode($array);
		}
		
		public function toArray(){
			$array['id']=$this->id;
			$array['user_id']=$this->user_id;
			$array['own_language']=$this->own_lan;
			$array['foreign_language']=$this->foreign_lan;
			$array['step']=$this->step;
			$array['next_date']=$this->next_date;
			$array['last_date']=$this->last_date;
			$array['lesson']=$this->lesson;
			$array['learn_index']=$this->learn_index;
			
			return $array;
		}
		
		public function swap(){
			$helper=$this->foreign_lan;
			$this->foreign_lan=$this->own_lan;
			$this->own_lan=$helper;
		}
		
		public function swapRandomly(){
			if (mt_rand(0,1)==1){
				$this->swap();
			}
		}
}
?>