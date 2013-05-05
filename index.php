<?php
/****************************************
*   index.php
*	Retrieve courses data from database 
*	and display in a sortable table
*	Team 3 @ SJSU CS160 Spring 2013
*	MIT + SEE moocs mashup
****************************************/
//require_once('connection.php');
require_once('pdo_connect.php');

$queryString = 'SELECT d.id, d.title, d.short_desc, 
				d.course_link, d.video_link, d.course_length, d.course_image, 
				d.category, d.start_date, d.site FROM course_data d';

$results = fetchAll($queryString);
?>
<!DOCTYPE html>   
<html>
<head>
<title>MOOCS mashup | MIT + SEE | CS 160 Team 3</title>
<script  type="text/javascript" src="includes/js/sorttable.js"></script>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<link rel="stylesheet" href="template/style.css" />	
</head>
<body>
<script type="text/javascript">
function open_video(url)
{
    myWindow = window.open(url, "", "width=1000,height=820");
}

</script>
<?php include("template/header.php"); ?>

<table class="sortable">
<?php
//teable headers 
echo '<thead><tr><th class="courseid">id</th><th class="courselink">Title(link to course)</th>
          <th class="category">Category</th>
		  <th class="coursedesc">Short Description</th><th class="profimage">Instructor</th>
		  <th class="videolink"> Course Image (link to lecture video)</th>
		  <th class="courselength" >Course Length</th><th>Start Date</th><th>Site</th></tr></thead><tbody>';
$counter = 0;
foreach ($results as $aCourse)
{
    $profs = fetchAll( 'SELECT p.profname, p.profimage FROM coursedetails p
						WHERE p.id = '.$aCourse["id"]);
    echo '<tr class="a'.($counter++)%2 .'">';
	echo '<td class="courseid">'. $aCourse['id'] .'</td>';
	echo '<td class="courselink"><a href="'.$aCourse['course_link'].'"  
								    target="_blank">'. $aCourse['title'] .'</a></td>';
	echo '<td class="category">'. $aCourse['category'].'</td>';
	echo '<td class="coursedesc">'. $aCourse['short_desc'] .'</td>';
	echo '<td class="profimage"><img src="'.$profs[0]['profimage'].'" alt="prof image" /><br />'. $profs[0]['profname'] .'</td>';
	echo '<td class="videolink">'. '<a title="link to course video" 
									onclick="open_video(\''.$aCourse['video_link'].'\');return false;"  
									href="'.$aCourse['video_link'].'" target="_blank">
									<img src="'.$aCourse['course_image'].'" alt="link to video" />
									<br />Link to Course Video</a></td>';
	echo '<td class="courselength">'.$aCourse['course_length'].' Weeks</td>';
	echo '<td class="startdate">'. $aCourse['start_date'].'</td>';
	echo '<td class="site">'. $aCourse['site'] .'</td>';
	echo '</tr>';
}

?>
</tbody></table>
<?php include("template/footer.php"); ?>
</body>
</html>