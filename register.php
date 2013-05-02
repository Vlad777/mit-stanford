<?php

function checkPasswordStrength($password, &$error){
	if(strlen($password) < 8){
		$error[] .= "Password too short";
	}
	if(!preg_match("#[0-9]#", $password))
	{
		$error[] .= "Password must contain 1 number";
	}
	if(!preg_match("#[A-Z]#", $password))
	{
		$error[] .= "Password must have one upper case letter";
	} 
}

include("pdo_connect.php");

//User Registration scripts
if(isset($_POST['Submit']))
	{
	$username = $_POST['username'];
	$email =  $_POST['email'];
	$firstname =  $_POST['firstname'];
	$lastname =  $_POST['lastname'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];

	$error = array();
	//Check if email or username is in use

	$qs = $dbh->prepare("SELECT username,email FROM users WHERE email = ? OR username = ?");
	$qs->execute(array($email,$username));

	if($qs->rowCount() > 0)
	{
		$error[] .=  "Username or email in use";
	}
	
	//Check password Strength
	checkPasswordStrength($password1, $error);

	if($password1 != $password2)
	{
		$error[] .= "Passwords do not match";
	} 
	else {
		$salt = crypt($password1);
		//Should be go enought, but if you want to increase hash size, just go to 512. Dont know if it supported on which version.
		$saltedPassword = hash("sha256", $password1.$salt);
	}

	//No error? commit
	if(!isset($error))
	{
		//Enter into db
		//$qs = $dbh->prepare("INSERT INTO users (username,first_name,last_name,password,salt,email)values (?,?,?,?,?,?)");
		//$qs->execute(array($username,$firstname,$lastname,$saltedPassword,$salt,$email));
		echo "done";
	}
	else {
		print_r($error);
	}
}


?>

<html>
<head>
	<link type="text/css" rel="stylesheet" href="template/style.css" />
</head>
<body>
	<?php include("template/header.php"); ?>

	<div id="content">
		<h1>Registration form</h1>
		<form action="register.php" method="post">
			<label>Username</label><input type="text" name="username"><br/>
			<label>Email</label><input type="text" name="email"><br/>
			<label>First Name</label><input type="text" name="firstname"><br/>
			<label>Last Name</label><input type="text" name="lastname"><br/>
			<label>Password</label><input type="password" name="password1"><br/>
			<label>Confirm Password</label><input type="password" name="password2"><br/>
			<input type="submit" name="Submit">
		</form>
	</div>
	<?php include("template/footer.php"); ?>

</body>
</html>