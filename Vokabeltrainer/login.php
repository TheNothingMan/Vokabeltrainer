<?php 
session_start();
require_once 'models/database.php';
require_once 'models/user.php';
 
if(isset($_GET['login'])) {
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	$db = new DatabaseConnector();
	$user = $db->getUserByEmail($email);
		
	//Überprüfung des Passworts
	if ($user !== false && $user->verifyPassword($password)) {
		$_SESSION['user_id'] = $user->getId();
		$_SESSION['email'] = $email;
		//TODO: Use Session variable to redirect to the page the user was trying to access
		if(isset($_SESSION['origin_url'])){
			header("Location: ".$_SESSION['origin_url']);
		}else{
			header("Location: index.php");
		}
		
		die();
		die('Login erfolgreich. Weiter zu <a href="geheim.php">internen Bereich</a>');
	} else {
		$errorMessage = "E-Mail oder Passwort war ungültig<br>";
	}
	
}
?>
<!DOCTYPE html> 
<html>
<head>
	<?php include 'head_tag.html';?>
	<title>Login</title>	
</head> 
<body>
<div class='page'>
	<div id="header">
		<?php include 'header.php';?>
	</div>
	<div class="content">
		<?php 
		if(isset($errorMessage)) {
			echo("<p class='result-negative' id='result'>$errorMessage</p>");
		}
		?>
		 
		<form action="?login=1" method="post">
		E-Mail:<br>
		<input type="email" size="40" maxlength="250" name="email"><br><br>
		 
		Dein Passwort:<br>
		<input type="password" size="40"  maxlength="250" name="password"><br>
		 
		<input type="submit" value="Abschicken">
		</form> 

		<span>Noch kein Acount? Hier </span><a href="register.php">registrieren.</a>
	</div>
</div>
</body>
</html>