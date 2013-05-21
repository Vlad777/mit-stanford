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
		<span class='inline'><a onclick="showBox($('#register-box'));return false;" href='register.php'>Register</a>&nbsp;|&nbsp;<a onclick="showBox($('#login-box'));return false;" href="login.php">Login</a>  </span></div>     
		<?		
	}
?>

<script>
//showBox($('#register-box'));return false;
//$('#register-box').openPopup();
function showBox(element)
{
	element.show();
	element.bringToTop();	
	element.click(function() {
					$(this).bringToTop();
			});
}

	(function() {	
			$.fn.bringToTop = function() {
				this.css('z-index', ++highest); // increase highest by 1 and set the style
			};
		})();
	var highest = 1;	
</script>
</div> <!-- //close tag for id="user-box" -->