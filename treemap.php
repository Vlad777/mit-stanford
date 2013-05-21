<?php
/****************************************
*   index.php
*  Retrieve courses data from database 
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
				d.category, d.start_date, d.site FROM course_data d';
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
<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>

<link rel="stylesheet" href="template/style.css" />	
<link rel="stylesheet" href="template/demo_table.css" />	

<link rel="stylesheet" href="template/starRating.css"/>
<script type="text/javascript" src="includes/js/starRating.js"></script> 


<script>
    var highest = 1;

/* ********************************* 
* Loads list of autocompletion tags
************************************ */
$(function() {
	var availableTags = [	
		<?php
		function sub($arr)
		{
		  return $arr[0];
		}
		require_once('pdo_connect.php');
		$array = fetchAll("SELECT concat(concat(concat(word, ' ('), course_count), ')') 
							as word FROM autocomplete ORDER BY course_count DESC");
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

<style>

#chart {
  background: #bbb;
}

text {
  pointer-events: none;
}

.grandparent text { /* header text */
  font-weight: bold;
  font-size: large;
  font-family: "Open Sans", Helvetica, Arial, sans-serif; 
}

rect {
  fill: none;
  stroke: #fff;
}

rect.parent,
.grandparent rect {
  stroke-width: 2px;
 
}

.grandparent rect {
  fill: #fff;
}

.children rect.parent,
.grandparent rect {
  cursor: pointer;
}

rect.parent {
  pointer-events: all; 
}

.children:hover rect.child,
.grandparent:hover rect {
  fill: #aaa;
}

.textdiv { /* text in the boxes */
    font-size: x-large;
    padding: 5px;
    font-family: "Open Sans", Helvetica, Arial, sans-serif; 
}

</style>


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
	function show_details()
	{
		//show/hide the <div class="course-info"> of this tr
		alert(this);
		$(this).find("div.course-info").toggle();
	}
</script>
<?php include("template/treemap_header.php"); ?>
<?php include("includes/linkToRateMyProfessor.php"); ?>

<div class="loadingMessage">
    <img src="images/spinner.gif" alt="loading">
  <h2 class="loadingMessage">Loading Online Courses 
    <? if(isset($_GET['do']) && $_GET['do'] == "search")  { ?>
    matching: <? echo $_GET["q"];  ?>
  <? } ?></h2>
</div>
    

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
				linkToRateMyProfessor($profs[0]['profname'], $aCourse['site']); 
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
<?php } //end of foreach course  ?>

<?php
$category = "";
$flare = '{"name:":"Mooc4u","children":[';

if(count($results) > 10)
{

	//has to be ordered by category!
	//can specify "value" of the final rectangles to influence their size
	//has to be ordered by category!
	//can specify "value" of the final rectangles to influence their size
	foreach ($results as $row)
	{
		if ($category !== $row['category']) //new category started
		{
			if ($category !== "") //not first category, then close previous category
			{
				$flare .= "]},";
			}
			
			$category = $row['category'];

			$flare .= '{"name":"'.addslashes($row['category']).'","children":[';
		}
		else
		{
			$flare .= ','; //current is not the first course in the category
		}

		$flare .= '{"name":"'.addslashes($row['title']).'","value":1,"url":"'.$row['video_link'].'","course_id":'.$row['id'].',"img":"'.$row['course_image'].'"}';
	}
	$flare .= "]}";
}
else
{
	foreach ($results as $row)
	{
		if ($category !== "")
		{
			$flare .= ','; //current is not the first course in the category
		}
		else
		{
			$category = $row['category']; //doesn't matter as long as it's not ""
		}

		$flare .= '{"name":"'.addslashes($row['title']).'","value":1,"url":"'.$row['video_link'].'","course_id":'.$row['id'].',"img":"'.$row['course_image'].'"}';
	}
}

$flare .= ']}';
?>

<p id="chart">

<script>
<!-- https://secure.polisci.ohio-state.edu/faq/d3/zoomabletreemap_code.php -->

var margin = {top: 20, right: 0, bottom: 0, left: 0},
    width = $(window).width(),
    height = $(window).height() - margin.top - margin.bottom,
    formatNumber = d3.format(",d"),
    transitioning;

/* create x and y scales */
var x = d3.scale.linear()
    .domain([0, width])
    .range([0, width]);

var y = d3.scale.linear()
    .domain([0, height])
    .range([0, height]);

var treemap = d3.layout.treemap()
    .children(function(d, depth) { return depth ? null : d.children; })
    .sort(function(a, b) { return a.value - b.value; })
    .ratio(height / width * 0.5 * (1 + Math.sqrt(5)))
    .round(false);

/* create svg */
var svg = d3.select("#chart").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.bottom + margin.top)
    .style("margin-left", -margin.left + "px")
    .style("margin.right", -margin.right + "px")
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")")
    .style("shape-rendering", "crispEdges");

var grandparent = svg.append("g")
    .attr("class", "grandparent");

grandparent.append("rect")
    .attr("y", -margin.top)
    .attr("width", width)
    .attr("height", margin.top);

grandparent.append("text")
    .attr("x", 6)
    .attr("y", 6 - margin.top)
    .attr("dy", ".75em");

/* load in data, display root */
//d3.json($flare, function(root) {
  root = <?php echo $flare; ?>;

  initialize(root);
  accumulate(root);
  layout(root);
  display(root);
  $('.loadingMessage').hide();
  
  function initialize(root) {
    root.x = root.y = 0;
    root.dx = width;
    root.dy = height;
    root.depth = 0;
  }

  // Aggregate the values for internal nodes. This is normally done by the
  // treemap layout, but not here because of our custom implementation.
  function accumulate(d) {
    return d.children
        ? d.value = d.children.reduce(function(p, v) { return p + accumulate(v); }, 0)
        : d.value;
  }

  // Compute the treemap layout recursively such that each group of siblings
  // uses the same size (1×1) rather than the dimensions of the parent cell.
  // This optimizes the layout for the current zoom state. Note that a wrapper
  // object is created for the parent node for each group of siblings so that
  // the parent’s dimensions are not discarded as we recurse. Since each group
  // of sibling was laid out in 1×1, we must rescale to fit using absolute
  // coordinates. This lets us use a viewport to zoom.
  function layout(d) {
    if (d.children) {
      treemap.nodes({children: d.children});
      d.children.forEach(function(c) {
        c.x = d.x + c.x * d.dx;
        c.y = d.y + c.y * d.dy;
        c.dx *= d.dx;
        c.dy *= d.dy;
        c.parent = d;
        layout(c);
      });
    }
  }

  /* display show the treemap and writes the embedded transition function */
  function display(d) {
    /* create grandparent bar at top */
    grandparent
        .datum(d.parent)
        .on("click", transition)
        .select("text")
        .text(name(d));

    var g1 = svg.insert("g", ".grandparent")
        .datum(d)
        .attr("class", "depth");
    
    /* add in data */
    var g = g1.selectAll("g")
        .data(d.children)
        .enter().append("g");

    /* transition on child click */
    g.filter(function(d) { return d.children; })
        .classed("children", true)
        .on("click", transition);
    
    /* write children rectangles */
    g.selectAll(".child")
        .data(function(d) { return d.children || [d]; })
        .enter().append("rect")
        .attr("class", "child")
        .call(rect)
        ;


    /* write parent rectangle */
    g.append("rect")
        .attr("class", "parent")
        .call(rect)
        /* open new window based on the json's URL value for leaf nodes */
        /* Chrome displays this on top */
        .on("click", function(d) { 
            if(!d.children){
                //window.open(d.url); 
                $('#course-info-'+d.course_id).openPopup();
            }
        })
        .append("title")
        .text(function(d) { return formatNumber(d.value); })

        ;


         //textdiv class allows us to style the text easily with CSS

    /* Adding a foreign object instead of a text object, allows for text wrapping */
    g.append("foreignObject")
        .call(rect)
        /* open new window based on the json's URL value for leaf nodes */
        /* Firefox displays this on top */
        .on("click", function(d) { 
            if(!d.children){
                //window.open(d.url); 
                $('#course-info-'+d.course_id).openPopup();
            }
        })
        .attr("class","foreignobj")



        .append("xhtml:div")
        .attr("style", function(d) { return "height:200px;background: url('"+d.img+"') no-repeat scroll center center transparent; display: block; background-size:cover; text-shadow: -2px -2px 0 #ffffff, 2px -2px 0 #ffffff, -2px 2px 0 #ffffff, 2px 2px 0 #ffffff;"})
        //.attr("dy", ".75em")
        //.attr("style", "display:block; background:#dedede;")
        .html(function(d) { return d.name; })
        .attr("class","textdiv")

         // .append("img")
         // .attr("src", function(d) { return d.img; })
         // .attr
 ;

    

    /* create transition function for transitions */
    function transition(d) {
      if (transitioning || !d) return;
      transitioning = true;

      var g2 = display(d),
          t1 = g1.transition().duration(750),
          t2 = g2.transition().duration(750);

      // Update the domain only after entering new elements.
      x.domain([d.x, d.x + d.dx]);
      y.domain([d.y, d.y + d.dy]);

      // Enable anti-aliasing during the transition.
      svg.style("shape-rendering", null);

      // Draw child nodes on top of parent nodes.
      svg.selectAll(".depth").sort(function(a, b) { return a.depth - b.depth; });

      // Fade-in entering text.
      g2.selectAll("text").style("fill-opacity", 0);
      g2.selectAll("foreignObject div").style("display", "none"); /*added*/

      // Transition to the new view.
      t1.selectAll("text").call(text).style("fill-opacity", 0);
      t2.selectAll("text").call(text).style("fill-opacity", 1);
      t1.selectAll("rect").call(rect);
      t2.selectAll("rect").call(rect);
      
      t1.selectAll(".textdiv").style("display", "none"); /* added */
      t1.selectAll(".foreignobj").call(foreign);
      t2.selectAll(".textdiv").style("display", "block"); /* added */
      t2.selectAll(".foreignobj").call(foreign); /* added */      

      // Remove the old node when the transition is finished.
      t1.remove().each("end", function() {
        svg.style("shape-rendering", "crispEdges");
        transitioning = false;               
      });
      
    }//endfunc transition

    
    return g;
  }

  function text(text) {
    text.attr("x", function(d) { return x(d.x) + 6; })
        .attr("y", function(d) { return y(d.y) + 6; });
  }

  function rect(rect) {
    rect.attr("x", function(d) { return x(d.x); })
        .attr("y", function(d) { return y(d.y); })
        .attr("width", function(d) { return x(d.x + d.dx) - x(d.x); })
        .attr("height", function(d) { return y(d.y + d.dy) - y(d.y); });     
  }

  function foreign(foreign){  /* added */
      foreign.attr("x", function(d) { return x(d.x); })
        .attr("y", function(d) { return y(d.y); })
        .attr("width", function(d) { return x(d.x + d.dx) - x(d.x); })
        .attr("height", function(d) { return y(d.y + d.dy) - y(d.y); })
        ;
  }

  function name(d) {
    return d.parent
        ? name(d.parent) + "." + d.name
        : d.name;
  }
//});


</script>


<?php include("template/footer.php"); ?>
</body>
</html>
