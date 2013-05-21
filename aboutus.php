<?
require_once('pdo_connect.php');
include("includes/function_user.php");	
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>About us | Moocs4U </title>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
      
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<!-- jquery-ui.js MUST be loaded after jquery.min.js -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="template/style.css" />	

</head>

<body>

<?php include("template/header.php"); ?>

<div id="aboutUs" class="body_message">
   <div class="text-indent"> 
	<b><h2>About Moocs4u</h2></b>
		<br />    
   
        <p>Moocs4u was created in 2013 by SJSU Computer Science students. </p>
      
        <p>It was created to provide users a one stop search to find online courses. </p>
       
        <p>We are dedicated to provide you a user friendly experience that will meet the needs of all users.</p>
                       
	</div>
    
</div> <!-- //div with margins -->
</div> <!-- //div aboutUs -->
<?php include("template/footer.php"); ?>
</body>
</html>
