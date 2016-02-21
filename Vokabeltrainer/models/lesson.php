<?php
	class Lesson {
		private $name;
		private $id;
		private $user_id;
		private $active;
		private $last_chosen;
		
		
		function __construct($name="",$user_id=""){
			$this->user_id=$user_id;
			if ($name!=""){
				$this->name=$name;
			}
		}
		
		//SETTERS
		/**
		 * Set if the lesson is learned.
		 * 
		 * @param	$state Boolean or int 0-1
		 */
		function setActive($state){
			if ($state){
				$this->active = 1;
			}else{
				$this->active = 0;
			}
		}
		
		function setId($id){
			$this->id = $id;
		}
		
		function setName($name){
			$this->name = $name;
		}
		
		function setLastChosen($chosen){
			$this->last_chosen = $chosen;
		}
		
		//GETTERS
		function getName(){
			return $this->name;
		}
		
		function getId(){
			return $this->id;
		}
		
		function getActive(){
			return $this->active;
		}
		
		function getLastChosen(){
			return $this->last_chosen;
		}
		
		//METHODS
		function fillFromArray($array){
			$this->name = $array['name'];
			$this->active = $array['active'];
			$this->id = $array['id'];
			$this->last_chosen = $array['last_chosen'];
			$this->user_id = $array['user_id'];
		}
	}
	
?>