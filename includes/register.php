<?php
/***************************
*  includes/register.php
*	
*   this file is included
*	from the user_test.php
*   from the header.php
*
***************************/
?>

<html>
<head>
	<link type="text/css" rel="stylesheet" href="template/style.css" />
</head>
<body>
	<div id="content">
		<b>Registration form</b>
		<form action="index.php" method="post">
			<label>Username: <input type="text" name="username"></label>
			<label>Email: <input type="text" name="email"></label>
			<label>First Name: <input type="text" name="firstname"></label>
			<label>Last Name: <input type="text" name="lastname"></label>
			<label>Password: <input type="password" name="password1"></label>
			<label>Confirm Password<input type="password" name="password2"></label>
            	<input type="hidden" name="do" value="register"/>
			<input type="submit" name="Submit">
		</form>
	</div>
</body>
</html>