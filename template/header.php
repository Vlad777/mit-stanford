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
        <form id="search" action="index.php" method="get" >
        <input type="hidden" name="do" value="search"/>
        <label for="tags"></label>
        <input name="q" id="tags" autofocus value="Search online courses"   onkeydown="clearText($(this));" onclick="$(this).val('');"/>
        <script>
        if (!("autofocus" in document.createElement("input"))) {
         document.getElementById("tags").focus();
        }
        </script>
        <input class="searchSubmit" type="submit" value=""/>
        </form>
        <script>
		function clearText(element)
		{
			element.css('color','#000');
			if (element.val()=='Search online courses')
			{
				element.val('');
			}
			//javascript: if($(this).val()=='Search online courses'){$(this).val('');}
		}
		$(document).ready( function() {
		$("#tags").focus( function() {
	        $(this).css('color','#000');
			if ( $(this).val()=="Search online courses") {
					$(this).val(''); }			
			});
		
			$("#tags").blur( function() {
				if ( $(this).val()=="") {
					$(this).css('color','#999');
					$(this).val('Search online courses');
				} 
			});
			<? if(isset($_GET['do']) && $_GET['do'] == "search")  {	?>				
				$("#tags").val( '<? echo $_GET["q"];  ?>');				
			<? } ?>
		});
		</script>
        <script>
		/* ********************************* 
		* Loads list of autocompletion tags
		************************************ */
		$(function() {
			var availableTags = [	
				<?php
				function sub($arr)
				{
				  return $arr[0];
				}
				require_once('pdo_connect.php');
				$array = fetchAll("SELECT concat(concat(concat(word, ' ('), course_count), ')') 
									as word FROM autocomplete ORDER BY course_count DESC");
				//print_r(implode(array_map("sub", $array)));
				echo '"' . implode('","', array_map("sub", $array)) . '"';
				?>	
			];
			$( "#tags" ).autocomplete({
			source: availableTags,
			select: function(event, ui) { ui.item.value = ui.item.value.split(" (")[0]; }
			});
		});
		</script>

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
