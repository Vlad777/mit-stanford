<?php
/****************************************
*   index.php
*  Retrieve courses data from database 
*	and display in a sortable table
*	Team 3 @ SJSU CS160 Spring 2013
*	MIT + SEE moocs mashup
****************************************/
//require_once('connection.php');
require_once('pdo_connect.php');

$queryString = 'SELECT d.id, d.title, d.short_desc, 
				d.course_link, d.video_link, d.course_length, d.course_image, 
				d.category, d.site FROM course_data d';

$results = fetchAll($queryString);
?>
<!DOCTYPE html>   
<html>
<head>
<title>MOOCS mashup | MIT + SEE | CS 160 Team 3</title>
<script  type="text/javascript" src="sorttable.js"></script>
<link rel="stylesheet" href="style.css" />	
</head>
<body>
<script type="text/javascript">
function open_video(url)
{
    myWindow = window.open(url, "", "width=1000,height=820");
}

</script>
<table class="sortable">
<?php
//teable headers 
echo '<tr><td>id</td><td>Title(link to course)</td>
          <td>Short Description</td><td>Course Image (link to lecture video)</td>
		  <td>Link to Course Lecture</td><td>Site</td></tr>';

foreach ($results as $aCourse)
{
    $profs = fetchAll( 'SELECT p.profname, p.profimage FROM coursedetails p
						WHERE p.id = '.$aCourse["id"]);
    echo '<tr>';
	echo '<td class="courseid">'. $aCourse['id'] .'</td>';
	echo '<td class="courselink"><a href="'.$aCourse['course_link'].'"  
								    target="_blank">'. $aCourse['title'] .'</a></td>';
	echo '<td class="coursedesc">'. $aCourse['short_desc'] .'</td>';
	echo '<td class="profimage"><img src="'.$profs[0]['profimage'].'" alt="prof image" /><br />'. $profs[0]['profname'] .'</td>';
	echo '<td class="videolink">'. '<a title="link to course video" 
									onclick="open_video(\''.$aCourse['video_link'].'\');return false;"  
									href="'.$aCourse['video_link'].'" target="_blank">
									<img src="'.$aCourse['course_image'].'" alt="link to video" />
									<br />Link to Course Video</a></td>';
	echo '<td class="site">'. $aCourse['site'] .'</td>';
	
	echo '</tr>';
}

?>
</table>

</body>
</html>