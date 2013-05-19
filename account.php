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

if($_GET['do'] == "search")
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
	$qrm->execute(array('%' . $_GET["q"] . '%', '%' . $_GET["q"] . '%', '%' . $_GET["q"] . '%'));

	$results = $qrm->fetchAll();
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

<script type="text/javascript">
function open_video(url)
{
    //myWindow = window.open(url, "", "width=1000,height=820");	
		var specs = 'toolbar=yes,location=yes,directories=no,status=no,menubar=no,scrollbars=yes, width=1000px,height=820px,resizeable=yes,copyhistory=yes';	
		
		var myWin = window.open(url,"_blank",specs);		
		myWin.focus(); 
		
}

</script>
<?php include("template/header.php"); ?>
<?php include("includes/linkToRateMyProfessor.php"); ?>
<table class="sortable">
<?php
//teable headers 
echo '<thead><tr>
		  <th class="courseid">id</th>
		  <th class="courselink">Title(link to course)</th>
          <th class="category">Category</th>
		  <th class="profimage">Instructor</th>
		  <th class="starRating">Stars</th>
		  </tr></thead><tbody>';
$counter = 0;

$queryString = 'SELECT d.id, d.title, d.course_link, d.category, r.starRating, r.comments 
				FROM course_data d INNER JOIN review_course r ON d.id = r.courseID AND r.userID ='.$_SESSION["userid"];
$results = fetchAll($queryString);



foreach ($results as $aCourse)
{
    $profs = fetchAll( 'SELECT p.profname, p.profimage FROM coursedetails p
						WHERE p.id = '.$aCourse["id"]);

    echo '<tr class="a'.($counter++)%2 .'">';
	echo '<td class="courseid">'. $aCourse['id'] .'</td>';
	echo '<td class="courselink"><a href="'.$aCourse['course_link'].'"  
								    target="_blank">'. $aCourse['title'] .'</a></td>';
	echo '<td class="category">'. $aCourse['category'].'</td>';
	echo '<td class="profimage"><img src="'.$profs[0]['profimage'].'" alt="prof image" /><br />'. $profs[0]['profname'];
 	echo '<br />'; 
 	linkToRateMyProfessor($profs[0]['profname'], $aCourse['site']);
 	echo '</td>';
 	echo '<td class="starRating">';
 	echo '<div id="'.$aCourse["id"].'" class="rate_widget">';
	echo '<div class="star_1 ratings_stars"></div>';
	echo '<div class="star_2 ratings_stars"></div>';
	echo '<div class="star_3 ratings_stars"></div>';
	echo '<div class="star_4 ratings_stars"></div>';
	echo '<div class="star_5 ratings_stars"></div>';
	echo '<div class="total_votes">vote data</div>';
	echo '</div>';
 	echo '</div>'.'</td>';
	echo '</tr>';

}

?>
</tbody></table>
<?php include("template/footer.php"); ?>
</body>
</html>