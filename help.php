<?
require_once('pdo_connect.php');
include("includes/function_user.php");	
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Help | Moocs4U | CS 160 Team 3 Section 2</title>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
      
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<!-- jquery-ui.js MUST be loaded after jquery.min.js -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="template/style.css" />	

</head>

<body>

<?php include("template/header.php"); ?>


<div id="helpPage" class="body_message">
   <div class="text-indent"> 
   
   <b><h2>Help to Start</h2></b>
 
		<b>Top Nav</b><br />
		The top nav is located at the top of the screen. By using these search, you can navigate the Mooc4u site.
        <br /><br />
		
		<b>Featured Courses</b><br />
		On the home page is a cycling list of Featured Courses. These courses are new, exemplary, or offer something special.
        <br />								 		<br />
		
		<b>Quick Links</b><br />
		At the bottom of all pages are a list of “Quick Links.” These reflect all of the navigating options in the top nav.
        <br /><br />
 
</div>
</div>

<?php include("template/footer.php"); ?>
</body>
</html>
