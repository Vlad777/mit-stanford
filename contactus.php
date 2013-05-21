<?
require_once('pdo_connect.php');
include("includes/function_user.php");	
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contact us | Moocs4U | CS 160 Team 3 Section 2</title>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<link rel="stylesheet" href="template/style.css" />	

</head>

<body>

<?php include("template/header.php"); ?>

<div id="contactsUs" class="body_message">
   <div class="text-indent"> 
	
    <b><h2>Contact Us</h2></b><br />
  	<div>
		<p>Have questions for us? Need more information? We're here to help. Easily find the right contact below for all your inquiries.	</p>
		
		<p>If you're visiting our website for the first time, let us know if we can <a href="help.php">help</a> you in any way. Our experienced team of professionals will find the answers to help you achieve your successes.</p>
		
		&copy; SJSU<br />
		<br /><br />
		
		<b>Contact Information:</b><br /><br />
                
		<div style="text-indent:20px;">Moocs4u</div>
		<div style="text-indent:20px;">Group: Three Tree</div>
		<div style="text-indent:20px;">San Jose Sate University</div>
		<div style="text-indent:20px;">One Washington Square</div>
		<div style="text-indent:20px;">San Jose, CA 95192</div>
		<div style="text-indent:20px;">408-924-1000</div>
        <div style="text-indent:20px;">moocs4u@gmail.com</div>
			
		<br />
  	</div>
</div>
</div>

<?php include("template/footer.php"); ?>
</body>
</html>
