<?php
/***************************
*  includes/login.php
*	
*   this file is included
*	from the user_test.php
*   from the header.php
*
***************************/
$error = array();

if($user["userid"] === 0)
{	
	?>
    <b>Registration form</b>
	<span>Welcome Guest! <br />Please input login information <br/></span>
	<form action="index.php" method="post">
		<lable>Username: </label><input type="text" name="username" value="" /></lable>
		<lable>Password: </label><input type="password" name="password"/></lable>
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
	<br />You are logged in!
	<?php
}
?>
