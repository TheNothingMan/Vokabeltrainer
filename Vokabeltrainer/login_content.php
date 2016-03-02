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
				echo("<script>top.window.location='".$_SESSION['origin_url']."';</script>");
				
				//header("Location: ".$_SESSION['origin_url']);
			}else{
				echo("<script>top.window.location='index.php';</script>");
				//header("Location: index.php");
			}
	
			die();
		} else {
			$errorMessage = "E-Mail oder Passwort war ungültig<br>";
		}
	
	}
?>
<html> 
<head>
	<link rel="stylesheet" href="style/main.css" type="text/css">	
</head> 
<body>
	<div>
		<?php 
		if(isset($errorMessage)) {
			echo $errorMessage;
		}
		?>
		 
		<form action="?login=1" method="post">
		E-Mail:<br>
		<input type="email" size="40" maxlength="250" name="email"><br><br>
		 
		Dein Passwort:<br>
		<input type="password" size="40"  maxlength="250" name="password"><br>
		 
		<input type="submit" value="Login">
		</form> 

		<span>Noch kein Acount? Hier </span><a href="register.php" target='_top'>registrieren.</a>
	</div>
</body>
</html>