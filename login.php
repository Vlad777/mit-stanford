<?php
include("includes/function_user.php");
include("pdo_connect.php");

$error = array();

if($_POST["do"] == "login")
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
			header("Location: $page");
		}

	}
}
//The login template.
?>
<html>
<body>



<?php
if($user["userid"] === 0)
{
	//User is logged in, do not show login screen
	foreach($error as $a)
	{
		?>

		 <b><?php echo $a;?></b>

		 <?php
	}
	?>
	Welcome Guest, please input login information <br/>
	<form action="login.php" method="post">
		<lable>Username:</label><input type="text" name="username" value="<?php echo $username ?>" />
		<lable>Password:</label><input type="password" name="password"/>
		<input type="hidden" name="page" value="login.php"/>
		<input type="hidden" name="do" value="login"/>
		<input type="submit" name="Submit" />
	</form>
	<?php
} 
else 
{
	//User is not logged in
	echo $user["username"]?>
	, you are already logged in
	<?php
}
?>
</body>
</html>