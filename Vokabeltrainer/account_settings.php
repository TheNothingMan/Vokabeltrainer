<?php 
session_start();
require_once 'models/database.php';
require_once 'models/user.php';
if (!isset($_SESSION['user_id'])){
	//TODO: Update urls
	$_SESSION['origin_url']="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	header("Location: login.php");
}
$db=new DatabaseConnector($_SESSION['user_id']);
$user=$db->getUserById($_SESSION['user_id'])
?>
<!DOCTYPE html> 
<html> 
<head>
	<?php include 'head_tag.html';?>
	<title>Account</title>	
</head> 
<body>
	<div class='page'>
	<div id="header">
		<?php include 'header.php';?>
	</div>
	<div class="content">
		<?php
		$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
		 
		if(isset($_GET['register'])) {
			$error = false;
			$email = $_POST['email'];
			$old_password = $_POST['old_password'];
			$password = $_POST['new_password'];
			$password2 = $_POST['new_password2'];
		  
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
				$error = true;
			} 	
			if($password != $password2) {
				echo 'Die Passwörter müssen übereinstimmen<br>';
				$error = true;
			}
			
			//Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
			if(!$error && $email!=$user->getEmail()) {
				$user_check = new User($email);
								
				if($db->userExists($user_check)) {
					echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
					$error = true;
				}	
			}
			
			//Check old password
			if($password!=""){
				if (!$user->verifyPassword($old_password)){
					$error=true;
					echo "Das alte Passwort stimmt nicht.";
				}
			}
			
			//Keine Fehler, wir können den Nutzer registrieren
			if(!$error) {	
				$user->hashPassword($password);
				$user->setEmail($email);
				$result=$db->updateUser($user);
				
				if($result) {
					$_SESSION['email']=$email;
					echo 'Einstellungen gespeichert. <a href="index.php">Zur Startseite</a>';
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
		<input type="email" size="40" maxlength="250" name="email" value="<?php echo($user->getEmail());?>"><br><br>
		 
		Passwort ändern:<br><br>	
		Altes Passwort:<br>
		<input type="password" size="40"  maxlength="250" name="old_password"><br>
		 
		Neues Passwort:<br>
		<input type="password" size="40"  maxlength="250" name="new_password"><br>
		 
		Passwort wiederholen:<br>
		<input type="password" size="40" maxlength="250" name="new_password2"><br><br>
		 
		<input type="submit" value="Speichern">
		</form>
		 
		<?php
		} //Ende von if($showFormular)
		?>
	</div>
	</div>
</body>
</html>
