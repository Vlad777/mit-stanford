<?php

	session_start();
	$error = array();
	
	
	
	///used for debug 
	/* foreach($_POST as $key=>$value)
	{
		echo "$key=$value ";
	} */
	
	/************************
	*		LOGOUT
	**************************/
	if (isset($_POST["Logout"]))
	{
		session_unset();
		session_destroy();
		$user =  NULL;
	}
	
	
	
	/**************************
	*       REGISTER
	*************************/
	
	//User Registration scripts
	if(isset($_POST['Submit']) && $_POST['do'] == 'register')
	{
		//echo 'registered?';
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
		if(count($error) == 0)
		{
			//Enter into db
			$qs = $dbh->prepare("INSERT INTO users (username,first_name,last_name,password,salt,email)values (?,?,?,?,?,?)");
			$qs->execute(array($username,$firstname,$lastname,$saltedPassword,$salt,$email));
			echo "Your Account was created";
			
		}
		else {
			// print_r($error);
		}
	}


	/************************
	*		LOGIN
	**************************/	
	if(isset($_POST['Submit']) && $_POST['do'] == 'login' )
	{
		//Recieved a post from a login panel
		//We need to set some session information		
		$username = $_POST["username"];
		$password = $_POST["password"];
		$page = $_POST["page"];
	
		//check db
		$st = $dbh->prepare("SELECT * FROM users where username = ?");
		$st->execute(array($username));
	
		if($st->rowCount() < 1)
		{
			//No user based on the username was found;
			$error[] .= "No account was found with this username";
		}
		else
		{
			$row = $st->fetch();
			$salt = $row["salt"];
			$hashedPassword = $row["password"];
	
			$saltedPassword = hash("sha256", $password.$salt);
			if($saltedPassword != $hashedPassword)
			{
				//Wrong password
				$error[] .= "Wrong password entered";
			} 
			else 
			{
				//Destroy old session. Prevent XSS 
				session_unset();
				session_destroy();
				session_start();
				$_SESSION["userid"] = $row["userid"];
				$_SESSION["username"] = $row["username"];
				//header("Location: $page");
				header("Location: index.php");				
			}	
		}
	}
	
	if(isset($_SESSION['userid']))
	{
		///User is logged in
		//echo 'user ID is valid';
		$user["username"] = $_SESSION['username'];
		$user["userid"] = $_SESSION['userid'];

	} 
	else 
	{
		if (count($error))
		{
			echo '<br />';
			foreach($error as $a)
			{
				echo $a.'<br />';
			}
		}
		
		// THe user is not logged in 
		$user["username"] = "Guest";
		$user["userid"] = 0;
		
	}

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

?>
