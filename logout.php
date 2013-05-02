<?php
include("includes/function_user.php");

	session_destroy();

	
?>

<html>
<head>
	<link type="text/css" rel="stylesheet" href="template/style.css" />
</head>
<body>
<?php
include("template/header.php");
?>
<div id="content">
	You have successfully logged off. 
</div>

<?php

include("template/footer.php");
?>
</body>
</html>
