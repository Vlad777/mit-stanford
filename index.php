<?php
/****************************************
*   index.php
*	Retrieve courses data from database 
*	and display in a sortable table
*	Team 3 @ SJSU CS160 Spring 2013
*	MIT + SEE moocs mashup
****************************************/
require_once('pdo_connect.php');
include("includes/function_user.php");	

if(isset($_GET['do']) && $_GET['do'] == "search")
{
    $qrm = $dbh->prepare("SELECT * FROM course_data WHERE title LIKE ? OR long_desc LIKE ? OR category LIKE ?");
	$qrm->execute(array('%' . $_GET["q"] . '%', '%' . $_GET["q"] . '%', '%' . $_GET["q"] . '%'));
	$results = $qrm->fetchAll();
	// echo "<pre>"; //for nice indented formatting of print_r
	// print_r($courses);
	// echo "</pre>";
	$tableTitle = "Courses matching your search: " . $_GET["q"];
}
else
{
	$queryString = 'SELECT d.id, d.title, d.short_desc, 
				d.course_link, d.video_link, d.course_length, d.course_image, 
				d.category, d.start_date, d.site FROM course_data d LIMIT 20';
	$results = fetchAll($queryString);
	
	$tableTitle = "Most popular courses:";
}
?>
<!DOCTYPE html>   
<html>
<head>

<title>MOOCS mashup | MIT + SEE |</title>
<meta charset="utf-8" />
<!-- <script  type="text/javascript" src="includes/js/sorttable.js"></script> -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
      
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<!-- jquery-ui.js MUST be loaded after jquery.min.js -->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<link rel="stylesheet" href="template/style.css" />	
<link rel="stylesheet" href="template/demo_table.css" />	

<link rel="stylesheet" href="template/starRating.css"/>
<script type="text/javascript" src="includes/js/starRating.js"></script> 

<script type="text/javascript" language="javascript" src="includes/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">
	/* Create an array with the values of all the select options in a column */
	$.fn.dataTableExt.afnSortData['dom-starRating'] = function  ( oSettings, iColumn )
	{
		return $.map( oSettings.oApi._fnGetTrNodes(oSettings), function (tr, i) {
			// return $('td:eq('+iColumn+') select', tr).val();
			// $(widget).find('.hidden_avg_sorter').text(avg);
			// alert($('td:eq('+iColumn+') span.hidden_avg_sorter', tr).text());
			  return $('td:eq('+iColumn+') span.hidden_avg_sorter', tr).text();
		} );
	}
	
	$(document).ready(function() {		
		 $('#dataTable').dataTable( {
			  "sPaginationType": "full_numbers",
			  "aoColumns": [
				null,
				null,
				null,					
				{ "sSortDataType": "dom-starRating", "asSorting": [ "desc", "asc" ] },
				null
			]
		 } );
		 $('#dataTable').show();
		 $('.loadingMessage').hide();
		 $('.tableTitle').show();
		
	} );
	//global popups z-index
	var highest = 1;		
</script>


</head>
<body>

<script type="text/javascript">
	function open_window(url,w,h)
	{
		
		if (!w) w = 850;
		if (!h) h = 640;
		var specs = 'toolbar=yes,location=yes,directories=no,status=no,menubar=no,scrollbars=yes,';
		    specs += 'width='+w+'x,height='+h+'px,resizeable=yes,copyhistory=yes';	
		var myWin = window.open(url,"_blank",specs);		
		myWin.focus(); 		
	}
	/* function show_details()
	{
		//show/hide the <div class="course-info"> of this tr
		alert(this);
		$(this).find("div.course-info").toggle();
	} */
</script>
<?php include("template/header.php"); ?>
<?php include("includes/linkToRateMyProfessor.php"); ?>

<div class="loadingMessage">
    <img src="images/spinner.gif" alt="loading">
	<h2 class="loadingMessage">Loading Online Courses 
    <? if(isset($_GET['do']) && $_GET['do'] == "search")  {	?>
		matching: <? echo $_GET["q"];  ?>
	<? } ?></h2>
</div>

<div class="tableTitle" style="display:none;">
	<h2 class="tableTitle"><? echo $tableTitle; ?></h2>
</div>
<table class="sortable" id="dataTable" style="display:none;">
<?php
//table headers 
echo '<thead><tr>
		  <th class="courseid">id</th>
		  <th class="courselink">Title</th>
          <th class="category">Category</th>
		  <th class="starRating">Rating</th> 
		  <th class="site">Site</th> 
		  </tr></thead><tbody>';

foreach ($results as $aCourse)
{
    $profs = fetchAll( 'SELECT p.profname, p.profimage FROM coursedetails p
						WHERE p.id = '.$aCourse["id"]);
    //echo '<tr onclick="show_details(this);">';
	?>
	<!-- <tr onclick="$('#course-info-<? echo $aCourse['id'] ?>').toggle('slow');$(this).bringToTop();"> -->
    <tr onClick="$('#course-info-<? echo $aCourse['id'] ?>').openPopup();">
	<?
	echo '<td class="courseid">'. $aCourse['id'] .'</td>';
	echo '<td class="courselink">'. $aCourse['title'] .'</td>';
	echo '<td class="category">'. $aCourse['category'].'</td>';	
	echo '<td class="starRating" id="starRating'.$aCourse["id"].'">'; 
	 	
    echo '<div id="'.$aCourse["id"].'" class="rate_widget" title="Not rated" rel="static_widget_'.$aCourse["id"].'">';
	echo '<span class="hidden_avg_sorter" style="visibility:hidden;position: absolute;">0</span>';		
	echo '<div class="star_1 ratings_stars_static"></div>';
	echo '<div class="star_2 ratings_stars_static"></div>';
	echo '<div class="star_3 ratings_stars_static"></div>';
	echo '<div class="star_4 ratings_stars_static"></div>';
	echo '<div class="star_5 ratings_stars_static"></div>';
	//echo '<div class="total_votes">vote data</div>';
	echo '</div>';  
    echo '</div>'.'</td>';  
	echo '<td class="site">'. $aCourse['site'].'</td>';	
	//echo '<td class="addClass">'."<FORM NAME='addClass' method='post'>"."<input type='submit' name=".$aCourse['id']." value='Add Class'/>"."</FORM>".'</td>';	
	echo '</tr>';

	/*
	//add class button
	if(isset($_POST[$aCourse['id']])){

		//checks to see if user already added the class
		$checkExisting = $dbh->prepare("SELECT userID,courseID FROM review_course WHERE userID = ? AND courseID = ?");
		$checkExisting->execute(array($_SESSION["userid"],$aCourse['id']));
	
		//adds class if user hasnt already have class added
		if($checkExisting->rowCount() == 0){
			$insertValue = $dbh->prepare("INSERT INTO review_course VALUES (?, ?, ?, ?)");
	 		$insertValue->execute(array($_SESSION["userid"],$aCourse['id'],0,"awesome"));
		 }
		 else{
		 	echo ("Class already taken!");
		 }
	} */
	
} //for each course build table row
?>
</tbody></table>

<?
// building a popup course info for each course
foreach ($results as $aCourse)
{
    $profs = fetchAll( 'SELECT p.profname, p.profimage FROM coursedetails p
						WHERE p.id = '.$aCourse["id"]);
                        
	$short_desc = substr($aCourse['short_desc'],0, 400);
	if (strlen(trim($short_desc)) == 0 )
		 	$short_desc = substr($aCourse['long_desc'],0, 400);	
	$descr_array = explode ( '.' , $short_desc, -1 );
	if ( count($descr_array) > 1)	
		$short_desc = implode ('.', $descr_array ) . '.';
	?>
    
    
<!--  *************************************************  
				POPUP COURSE INFO  
       ***********************************************  -->    
         
<div class="popup course-info" id="course-info-<? echo $aCourse['id'] ?>">
	<a class="closebutton" onClick="$('#course-info-<? echo $aCourse['id'] ?>').fadeOut();" alt="close" title="close"></a>
 	<div class="info-content-box">
    <h2><? echo $aCourse['title']; ?></h2>
    <div class="course_info">
     	<b>Category: </b><? echo  $aCourse['category']; ?>.
        <b>From: </b><? echo  $aCourse['site']; ?>.<br />
     </div>
     <div class="course_image">
    <a title="link to course video" onClick="open_window('<? echo $aCourse['video_link']; ?>');return false;"  
									href="<? echo $aCourse['video_link']; ?>" target="_blank">
									<img src="<? echo $aCourse['course_image']; ?>" alt="link to video" />	</a>								
     </div>
      <div class="course_data">
         <div class="course_description">
            <? echo $short_desc; ?> <br />
         </div>
        
         <div class="instructor_data">
            <img src="<? echo $profs[0]['profimage']; ?>" alt="prof image" class="prof_image"/>
            <b>Instructor: </b><? 
			foreach ($profs as $prof) 
			{
				echo $prof['profname'] . '<br />'; 
				linkToRateMyProfessor($prof['profname'], $aCourse['site']); 
				echo '<br />';
			}			
			?>           
          </div>
          <b>Duration: </b><? echo $aCourse['course_length']; ?> weeks.<br />  
          <div class="startdate"><b>Start Date: </b><? echo $aCourse['start_date'] ?></div>
          <div style="clear:left;margin: 0 0 8px;"> 
          	<a href="<? echo $aCourse['course_link']; ?>"  target="_blank">Link to Course</a>
          </div>
          
          <!-- START OF STAR RATINGS -->	
           <!-- if guest -->
          <?	if(!isset($_SESSION['userid']))
				{
				///User is logged in
				//echo 'user ID is valid';
				//$user["username"] = $_SESSION['username'];
				//$user["userid"] = $_SESSION['userid'];
				?>
		 <span class="notice">Login To Rate this Course.</span><br />
         <div style="display:inline;float:left;">Average Ratings:&nbsp; </div>
          <div id="<? echo $aCourse["id"] ?>" class="rate_widget" title="Not rated" style="float:left;display:inline;width:100px;">
        	
				<div class="star_1 ratings_stars_static"></div>
              	<div class="star_2 ratings_stars_static"></div>
              	<div class="star_3 ratings_stars_static"></div>
              	<div class="star_4 ratings_stars_static"></div>
              	<div class="star_5 ratings_stars_static"></div>
                <?
			}
		  else { //user is logged in		 
		  	if (isset ($user["starVotes"][$aCourse["id"]]) )
			{   
				if ($user["starVotes"][$aCourse["id"]] > 1)				
					$string = 'You rated: ' . $user["starVotes"][$aCourse["id"]] . ' stars.';				
				else
					$string = 'You rated: ' . $user["starVotes"][$aCourse["id"]] . ' star.';
				?>
        		 <span class="notice"><span id="user_ratings_<? echo $aCourse["id"]; ?>"><? echo $string; ?></span></span><br />
            	 <? 		 
		 	} else { //user did not rate this course 
			$string = 'Click to rate this course';
			?>
          		<span class="notice"><span id="user_ratings_<? echo $aCourse["id"]; ?>"><? echo $string; ?></span></span><br />
          <? } ?>
              <div style="display:inline;float:left;">Average Ratings:&nbsp;</div>
          <div id="<? echo $aCourse["id"] ?>" class="rate_widget" title="Not rated" style="display:inline;width:100px;">
          
              <div class="star_1 ratings_stars"></div>
              <div class="star_2 ratings_stars"></div>
              <div class="star_3 ratings_stars"></div>
              <div class="star_4 ratings_stars"></div>
              <div class="star_5 ratings_stars"></div>
              <!-- <div class="total_votes">No Votes!</div> -->
           <? }
           ?>   
          </div>       <!-- //rate_widget -->
            <!-- END OF STAR RATINGS -->
          
     </div> <!-- //course data -->
     </div>  <!-- info content box -->
      <!-- <div class="user_comments"><h3>User Comments</h3> <a onClick="$('#comments_list<? echo $aCourse['id'] ?>').toggle();">View/Hide Comments</a>
      <div id="comments_list<? echo $aCourse['id'] ?>" style="display:none;width:440px;"> -->
         <a onClick="open_window('comment.php?id=<? echo $aCourse['id'] ?>',300,500);return false;" href="javascript:window.open('comment.php?id='<? echo $aCourse['id']; ?>','Comment','width=300,height=500')" target="_blank">Comments</a> 
          
          <?  //	$_GET['id']=$aCourse['id'];
		  		//include('includes/comment.php'); 	?>
      <!--     </div>   //comments_list --> 
   <!--  </div>  //user_comments -->    
</div> <!-- //popup course info -->

	 <script>
		$(function() {
			$( "#course-info-<? echo $aCourse['id'] ?>" ).draggable({
				drag: function() {	$(this).bringToTop(); }
			});
			$( "#course-info-<? echo $aCourse['id'] ?>" ).click(function() {
					$(this).bringToTop();
			});
		});
		
		(function() {	
			$.fn.bringToTop = function() {
				this.css('z-index', ++highest); // increase highest by 1 and set the style
			};
		})();
		
		(function() {
			$.fn.openPopup = function() {		
			this.toggle('slow');
			this.bringToTop();	
		};
		})();

    </script>
<? } //end of foreach course  ?>
<?php include("template/footer.php"); ?>
</body>
</html>