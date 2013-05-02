<?php

session_start();

	
	if(isset($_SESSION['userid']))
	{
		///User is logged in
		$user["username"] = $_SESSION['username'];
		$user["userid"] = $_SESSION['userid'];

	} else 
	{
		// THe user is not logged in 
		$user["username"] = "Guest";
		$user["userid"] = 0;
	}


?>
