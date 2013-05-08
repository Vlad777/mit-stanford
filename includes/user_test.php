<?php
/***************************
*  includes/user-test.php
*	
*   this file is included
*   from the header.php
*
***************************/
//path relative to index.php which includes this file
//include("includes/function_user.php");	
?>	

<div id="user-box">   
 
<?		
	if($user["userid"] > 0)
	{
		//User is logged in We need to declare some variables to make it easier to access user information
		//Rather than user $_SESSION we can assign these variables to a $user varialbe.
		echo "<div class='lines'><span class='inline'  title='You are user number ".$user["userid"] ."' >";
		echo "Hello ".$user["username"]."";
		echo "</span>";
		?><span class='inline'> <form action="index.php" method="post">
			<input type="submit" name="Logout" value="Logout"/>
		</form>   </span></div>  <?
	}
	else
	{			
		//if no session information is set, we create a new session for a guest.
		//echo "You are not logged in.";
		echo "<div class='lines'><span class='inline' title='You are user number ".$user["userid"] ."' >";
		echo "Hello ".$user["username"]."</span>";
		?>
		<span class='inline'><a onclick="$('#register-box').show();return false;" href='register.php'>Register</a>&nbsp;|&nbsp;<a onclick="$('#login-box').show();return false;" href="login.php">Login</a>  </span></div>     
		<?		
	}
?>
</div> <!-- //close tag for id="user-box" -->