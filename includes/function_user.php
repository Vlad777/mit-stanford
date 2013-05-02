<?php

session_start();

	
	if(isset($_SESSION['userid']))
	{
		///User is a guest, we can set userId to 0
		$user["username"] = $_SESSION['username'];
		$user["userid"] = $_SESSION['userid'];

	} else 
	{
		// THe user is a user, we need to set some variables. 
		$user["username"] = "Guest";
		$user["userid"] = 0;
	}


?>
