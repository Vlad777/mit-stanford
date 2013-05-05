<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>jQuery UI Autocomplete - Default functionality</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css" />
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



<div class="ui-widget">
<form id="search" action="search.php" method="post">
<label for="tags">Search: </label>
<input name="q" id="tags" autofocus />
<script>
if (!("autofocus" in document.createElement("input"))) {
 document.getElementById("tags").focus();
}
</script>
<input type="submit" name="Submit" value="Search" />
</form>
</div>



<?php
global $dbh;

if(isset($_POST['Submit']))
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
	$qrm = $dbh->prepare("SELECT * FROM course_data WHERE title LIKE ? OR long_desc LIKE ? OR category LIKE ?");
	$qrm->execute(array('%' . $_POST["q"] . '%', '%' . $_POST["q"] . '%', '%' . $_POST["q"] . '%'));

	$courses = $qrm->fetchAll();
	echo "<pre>"; //for nice indented formatting of print_r
	print_r($courses);
	echo "</pre>";
}
?>

</body>
</html>
