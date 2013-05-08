<div id="nav">
  <li><a href="index.php">Home</a></li>
  <li><a href="account.php">User Account</a></li>

</div>
<div id="header">

	<div id="logo">
    	<h1><span class="blue">Moocs</span><span class="gold">4u</span></h1>
		  <!-- <span class="gold">MIT + Stanford </span><span class="blue"><b>Mashup</b></span> -->
  </div>  
    <?php  include("includes/user_test.php");	?>
    
    
    <div class="ui-widget" id="searchBox">
        <form id="search" action="index.php" method="get">
        <input type="hidden" name="do" value="search"/>
        <label for="tags"></label>
        <input name="q" id="tags" autofocus />
        <script>
        if (!("autofocus" in document.createElement("input"))) {
         document.getElementById("tags").focus();
        }
        </script>
        <input class="searchSubmit" type="submit" value=""/>
        </form>
    </div>
    
    <div id="login-box" class="popup" style="display:none;">
     <a class="closebutton" onClick="$('#login-box').fadeOut();" alt="close" title="close"></a>
   	 <?  include("includes/login.php"); ?>        
  </div>
  <div id="register-box" class="popup" style="display:none;">
     <a class="closebutton" onClick="$('#register-box').fadeOut();" alt="close" title="close"></a>
  	 <?  include("includes/register.php"); ?>   
  </div> 

</div>