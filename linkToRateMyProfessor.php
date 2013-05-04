<?
/*********************************************
* linkToRateMyProfessor.php<br />
* @Author Alice Cotti<br />
* @date 05/03/2013
* @ CS 160 SJSU Spring 2013
*
**********************************************/

require_once('pdo_connect.php');
include ('simple_html_dom.php');

$queryString = 'SELECT d.id, d.title, d.course_link, d.site FROM course_data d';
$results = fetchAll($queryString);

?>
<!DOCTYPE html>   
<html>
<head>
<title>MOOCS mashup | MIT + SEE | CS 160 Team 3</title>
<link rel="stylesheet" href="template/style.css" />  
</head>
<body>
<script type="text/javascript">
	function open_video(url)
	{
		var specs = 'toolbar=yes,location=yes,directories=no,status=no,menubar=no,scrollbars=yes, width=1000px,height=820px,resizeable=yes,copyhistory=yes';			
		var myWin = window.open(url,"_blank",specs);		
		myWin.focus(); 			
	}
</script>
<style type="text/css">
	div.grid-box{
		float:left;
		width:280px;
		height:280px;
		padding:10px;
		text-align:center;
		border:solid 1px #ededed;
		margin: 20px;	
	}
	div.grid-box:hover{
		background:#D1DBDC;
	}
</style>
<?
foreach ($results as $aCourse)
{
 	$profs = fetchAll( 'SELECT p.profname, p.profimage  FROM coursedetails p 
						WHERE p.id = '.$aCourse["id"]);	
	echo '<div class="grid-box">';
	echo '<img src="'.$profs[0]['profimage'].'" alt="prof image" /><br />';
	echo $profs[0]['profname'] .'<br /><br />';	

	$prof = $profs[0]['profname'];
	//$replace = array('Dr.' => '', 'Prof.'=>'','Biological Engineering Faculty'=>'','\(*\)'=>'');
  	$prof = str_replace('Dr. ','',$prof);
  	$prof = str_replace('Prof. ','',$prof);
  	$prof = str_replace('Biological Engineering Faculty','',$prof);
  	$prof = preg_replace('/\(*\)/','',$prof);
	
	// example URL to fetch JSON data
	// http://www.ratemyprofessors.com/solr/interim.jsp?select?facet=true&q=Mehran%20Sahami&wt=json
	$reqResults =  file_get_contents('http://www.ratemyprofessors.com/solr/interim.jsp?select?facet=true&q='. urlencode($prof) . '&wt=json');
	$data = json_decode($reqResults, FALSE);
	
	$foundName = 0;
	$foundSchool = 0;	
  
	//this could be a while loop to try more combinatiion of partial name?
	if ($data->response->numFound == 0)
	{
		//if profname can be split in 3 parts, try to get the second name initial out of the search
		$nameArray = explode(' ',$prof);
		//print_r($nameArray);
		if (count($nameArray) > 2)
		{
			$reqResults =  file_get_contents('http://www.ratemyprofessors.com/solr/interim.jsp?select?facet=true&q='. 
											 urlencode($nameArray[0] .' '.$nameArray[2]) . '&wt=json');
			$data = json_decode($reqResults, FALSE);
		}		
	}
	
	if ($data->response->numFound == 1)
	{
		$foundName = 1;
		$aLink = 'http://www.ratemyprofessors.com/ShowRatings.jsp?tid='. $data->response->docs[0]->pk_id;
		echo '<a onclick="open_video(\''.$aLink.'\');return false;" href="'.$aLink.'" target="_blank">Link to Rate My Professor</a>';
		$foundSchool = 1;
	}
	else if ($data->response->numFound > 1) //if more than one match
	{
		$foundName = 1;
		$docs = $data->response->docs;	
		//foreach school, matching profname
		foreach ($docs as $school)
		{
			$schoolname = $school->schoolname_s;			
			if ($aCourse["site"] == 'SEE')
					$pattern = '/Stanford/';
			else if ($aCourse["site"] == 'MIT')
					$pattern = '/Massachusetts Institute of Technology/';
			else {
				echo ' missing school name for this site name <br />';	
			}			
			preg_match($pattern, $schoolname, $matches);		
			if ( count($matches) >= 1)
			{
				$aLink = 'http://www.ratemyprofessors.com/ShowRatings.jsp?tid='. $school->pk_id;
				echo '<a onclick="open_video(\''.$aLink.'\');return false;" href="'.$aLink.'" target="_blank">Link to Rate My Professor</a><br /> ';
				$foundSchool = 1;
			}		
		} //foreach
	} 	
	
	 // mostly for debug purposes
		if (!$foundName)
			echo 'Could not find matching instructor name<br />';	
		else if (!$foundSchool)
			echo 'Could not find matching school name<br />';		
	
	echo '<br />';	
	echo '</div>';	 
	 
} //foeach course in database

?>
</body>
</html>
