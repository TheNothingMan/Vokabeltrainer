<?php
	class User{
		private $id;
		private $email;
		private $password;
		
		public function __construct($email=""){
			$this->email=$email;
		}
		
		//GETTERS and SETTERS
		public function getId(){
			return $this->id;
		}
		
		public function setId($id){
			$this->id = $id;
		}
		
		public function getEmail(){
			return $this->email;
		}
		
		public function setEmail($email){
			$this->email = $email;
		}
		
		public function getPassword(){
			return $this->password;
		}
		
		public function setPassword($password){
			$this->password = $password;
		}
		
		//METHODS
		public function hashPassword($password){
			$password_hash = password_hash($password, PASSWORD_DEFAULT);
			$this->password = $password_hash;
		}
		
		/**
		 * Verify password.
		 * @param unknown $password
		 * @return boolean
		 */
		public function verifyPassword($password){
			return password_verify($password, $this->password);
		}
		
		public function fillFromArray($array){
			$this->email=$array['email'];
			$this->password=$array['password'];
			$this->id=$array['id'];
		}
	}
?>