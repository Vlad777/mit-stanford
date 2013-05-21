<?php
/****************************************
*   account.php
*	Retrieve courses data from database added by user
*	and display in a sortable table
*	Team 3 @ SJSU CS160 Spring 2013
*	MIT + SEE moocs mashup
****************************************/
//require_once('connection.php');
require_once('pdo_connect.php');
include("includes/function_user.php");	

if(isset($_GET['do']) && $_GET['do'] == "search")
{
    //This should really be implemented using Zend/Lucene indexing engine

	//Using MATCH FULLTEXT indexing requires table to be MyISAM, not INNODB!!
	//this matches only the words searched, not partial strings
	//$qrm = $dbh->prepare("SELECT * FROM course_data WHERE MATCH(title,long_desc,category) AGAINST (?)");
	//$qrm->execute(array($_POST["q"]));

	//this allows partial word matches by using BOOLEAN MODE... if we want this? actually slower than using LIKE '%keyword%'
    //$qrm = $dbh->prepare("SELECT * FROM course_data WHERE MATCH(title,long_desc,category) AGAINST (? in BOOLEAN MODE)");
	//$qrm->execute(array('*' . $_POST["q"] . '*'));

	//Using LIKE with % at the beginning and with ORs is NOT SCALABLE
	//The problem here is that this would return more results than the autocomplete label shows, since autocomplete is based on words, not substrings of words
	//At least this should work with INNODB as well as MyISAM for now...
	//$qrm = $dbh->prepare("SELECT * FROM course_data WHERE title LIKE ? OR long_desc LIKE ? OR category LIKE ?");
	//$qrm->execute(array('%' . $_GET["q"] . '%', '%' . $_GET["q"] . '%', '%' . $_GET["q"] . '%'));

	//$results = $qrm->fetchAll();
	// echo "<pre>"; //for nice indented formatting of print_r
	// print_r($courses);
	// echo "</pre>";
}
?>
<!DOCTYPE html>   
<html>
<head>


<title>MOOCS mashup | MIT + SEE | CS 160 Team 3</title>
<meta charset="utf-8" />
<script  type="text/javascript" src="includes/js/sorttable.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> 



<script src="http://code.jquery.com/jquery-latest.js"></script>

<link rel="stylesheet" href="template/starRating.css"/>
<script type="text/javascript" src="includes/js/starRating.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<!-- <script src="http://code.jquery.com/jquery-1.9.1.js"></script> 
 -->

<!-- Has to be after jquery.min.js? hmm -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<link rel="stylesheet" href="template/style.css" />	


<script>
$(function() {
var availableTags = [


<?php
function sub($arr)
{
  return $arr[0];
}
require_once('pdo_connect.php');
$array = fetchAll("SELECT concat(concat(concat(word, ' ('), course_count), ')') as word FROM autocomplete ORDER BY course_count DESC");
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

</head>
<body>
<?php include("template/header.php"); ?>


<div id="userPage" class="body_message">
   <div class="text-indent"> 
	Your user name is: <?  echo $user["username"] ?>
    
    
</div> <!-- //div with margins -->
</div> <!-- //div userPage -->


    <?php include("template/footer.php"); ?>

</body>
</html>