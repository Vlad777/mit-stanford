<?php
/***************************
*  includes/user-test.php
*	
*   this file is included
*   from the header.php
*
***************************/
//path relative to index.php which includes this file
?>
<div id="user-box">   
 
<?		
	if($user["userid"] > 0)
	{
		//User is logged in We need to declare some variables to make it easier to access user information
		//Rather than user $_SESSION we can assign these variables to a $user varialbe.
		echo "<div class='lines'><span class='inline'>";
		echo "Hello ".$user["username"]."";
		echo "</span>";
		?><span class='inline'> <form action="index.php" method="post">
			<input type="submit" name="Logout" value="Logout"/>
		</form>   </span></div>  <?
		echo "<div class='lines'><span class='inline'>";
		echo "You are user number ".$user["userid"]."<br/> </span></div>";	}
	else
	{			
		//if no session information is set, we create a new session for a guest.
		//echo "You are not logged in.";
		echo "<div class='lines'><span class='inline'>";
		echo "Hello ".$user["username"]."  </span>";
		?>
		<span class='inline'><a onclick="$('#register-box').show();return false;" href='register.php'>Register</a> </span></div>
        <div class='lines'><span class='inline'>
		<? echo "You are user number ".$user["userid"];
		?>	</span><span class='inline'>
        <a onclick="$('#login-box').show();return false;" href="login.php">Login</a>       
        </span></div>     
		<?		
	}
?>
</div> <!-- //close tag for id="user-box" -->

  <div id="login-box" class="popup" style="display:none;">
     <a class="closebutton" onClick="$('#login-box').fadeOut();">Close</a>
   	 <?  include("includes/login.php"); ?>        
  </div>
  <div id="register-box" class="popup" style="display:none;">
     <a class="closebutton" onClick="$('#register-box').fadeOut();">Close</a>
  	 <?  include("includes/register.php"); ?>   
  </div> 