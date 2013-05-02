<?php
include("includes/function_user.php");

	if($user["userid"] > 0)
	{
		//User is logged in We need to declare some variables to make it easier to access user information
		//Rather than user $_SESSION we can assign these variables to a $user varialbe.
		echo "Hello ".$user["username"]."<br/>";
		echo "You are user number ".$user["userid"]."<br/>";
		echo "<a href=\"logout.php\" > Logout </a>";

	}
	else
	{
		//if no session information is set, we create a new session for a guest.
		echo "You are not logged in.";
		echo "Hello ".$user["username"]."<br/>";
		echo "You are user number ".$user["userid"];
	}
?>
