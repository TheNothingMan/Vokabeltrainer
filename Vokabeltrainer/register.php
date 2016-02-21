<?php 
session_start();
require_once 'models/database.php';
require_once 'models/user.php';
?>
<!DOCTYPE html> 
<html> 
<head>
	<link rel="icon" href="favicon.ong" type="image/png">
	<link rel="stylesheet" href="style/main.css" type="text/css">
	<meta charset="utf-8">
	<title>Registrierung</title>	
</head> 
<body>
	<div id="header">
		<?php include 'header.php';?>
	</div>
	<div class="content">
		<?php
		$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
		 
		if(isset($_GET['register'])) {
			$db = new DatabaseConnector();
			$error = false;
			$email = $_POST['email'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
		  
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
				$error = true;
			} 	
			if(strlen($password) == 0) {
				echo 'Bitte ein Passwort angeben<br>';
				$error = true;
			}
			if($password != $password2) {
				echo 'Die Passwörter müssen übereinstimmen<br>';
				$error = true;
			}
			
			//Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
			if(!$error) {
				$user = new User($email);
								
				if($db->userExists($user)) {
					echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
					$error = true;
				}	
			}
			
			//Keine Fehler, wir können den Nutzer registrieren
			if(!$error) {	
				$user->hashPassword($password);
				
				$result=$db->createUser($user);
				
				if($result) {		
					echo 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
					$showFormular = false;
				} else {
					echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
				}
			} 
		}
		 
		if($showFormular) {
		?>
		 
		<form action="?register=1" method="post">
		E-Mail:<br>
		<input type="email" size="40" maxlength="250" name="email"><br><br>
		 
		Dein Passwort:<br>
		<input type="password" size="40"  maxlength="250" name="password"><br>
		 
		Passwort wiederholen:<br>
		<input type="password" size="40" maxlength="250" name="password2"><br><br>
		 
		<input type="submit" value="Abschicken">
		</form>
		 
		<?php
		} //Ende von if($showFormular)
		?>
	</div>
</body>
</html>
