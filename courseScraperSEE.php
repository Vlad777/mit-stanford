<?php
/***********************************
* courseScraperSEE.php
* scrapes courses data for SEE
* 	title
*	short_desc
*	long_dec
*	course_link
*	video_link
*	start_date (default since not applicable)
*	course_lenght (calculated from lecutres)
*	course_image (not available)
*	category
*	site (SEE)
*
*  Writes data to Database
*
*  @date 4/21/2013
*  CS 160 SJSU Spring 2013
*  Team 3 milestone 2a
************************************/
//require_once('connection.php');
require_once('pdo_connect.php');
include ('simple_html_dom.php');

//(if running this directly, truncate table, if being included, do not?)
execQuery("TRUNCATE TABLE course_data");
execQuery("TRUNCATE TABLE coursedetails");
	
$html = file_get_html('http://see.stanford.edu/see/courses.aspx');
$site = "SEE";
	// FOREACH: div class listBlock (categories) strong class courseListDepartment 
	// 		stores the dept name 
	// 		walks its siblings (divs coming after it)
	// 			checks if its the one signaling next category 
	//			if its valid course divs, stores course info in array 
	foreach($html->find('div.listBlock') as $listBlock){
		
		$courseListDepartment = $listBlock->childNodes(0)->text();	
		$nextDiv = $listBlock->next_sibling(0); 	
		
		while ($nextDiv->className!='listBlock' 
			   && $nextDiv->tag=='div' ){
			//echo print_r($nextDiv->find('a'));
			$course_link = $nextDiv->first_child()->href;
			$course_title = $nextDiv->first_child()->first_child()->text();
			$course_code = $nextDiv->first_child()->first_child()->next_sibling(0)->text();
			
			$aCourse = new course();
			$aCourse->title = $course_title;
			$aCourse->course_link = $course_link;
			$aCourse->category =  $courseListDepartment;
			$aCourse->course_code = $course_code;
			$aCourse->site = $site;
			$array[] = $aCourse;		
			
			$nextDiv = $nextDiv->next_sibling();			
		}
	}

	$html->clear();
	
	// for each course object in array.
	// open its link 
	foreach($array as $aCourse)
	{
		$courseLink = $aCourse->course_link;
		
		//Go to link make sure that its a stanford link - Some links lead to like app store
		//if(! preg_match('/^http\:\/\/see.stanford.edu/',$courseLink)){
		//	continue;	 //skips non-conventional links
		//}
		// Note: instead of skipping those page, I still proceed and will check for null content
		// so to set null content to defsult values
		// this makes it easier when fetching content so there is no null values there.
		
		
		$html = file_get_html($courseLink);
		
		scrapeInstructor($html,$aCourse);
		scrapeDescription($html,$aCourse);
						   
		/*******************************************
		* Scraping COURSE CONTENT box with links
		*******************************************/
		$contentBoxWrapper2 = $html->find('div#contentBoxWrapper2');
		if ($contentBoxWrapper2 == NULL)
		continue; //skips iTunes and other unconventional course pages
	
			$courseLinks = array();
			foreach($contentBoxWrapper2[0]->children() as $boxLink){	
				if ($boxLink->tag == 'a')
				{
					$courseLinks[trim($boxLink->text())] = $boxLink->href;
					if (trim($boxLink->text()) == "Lectures")
					{
						//echo $boxLink->text();
						scrapeLectures($boxLink->href,$aCourse);
					}
				}	
			}
			$aCourse->links = $courseLinks;
		/*******************************************/
		$html->clear();	
	}

	// writes data to database 
	foreach($array as $aCourse)
	{
		writeToDatabase($aCourse);
	}
	
	
// DEBUG DATA PRINTING
?><pre><?php echo print_r($array);?></pre><?php 


/****************************
* scrapeLectures
* given URL of Lectures page
* 	gets course videos links
* 	sets Youtube as default
* 	gets videos duration time
* 	estimates number of weeks
****************************/
function scrapeLectures($url,$aCourse){

	$lecturePage =  file_get_html($url);	
	$lectureList = array();
	$lectures = $lecturePage->find('div#tableWrapper table tr');
	// each lecture is in 2 tr one th and one td
	// for each lecture table. create a lecture object
	//echo '<b>'. $aCourse->title.'</b><br />';
	$count = 1;
	foreach ($lectures as $lecture)
	{
		// if this is header tr, 
		// lecture 'headers' tr have td and othher have td
		if ( count($lecture->children(1)) >= 2 || ($lecture->children(0)->tag == 'script' 
													&& $lecture->children(1)->tag == 'th') )
		{
			$aLecture = new lecture();
			$aLecture->id = $count;
			$aLecture->courseTitle = $aCourse->title;
			//grab lecture id and View Now link <br />			
			continue; //and go next tr
		}		
		$aLecture->length = $lecture->children(0)->children(0)->text();
		$topics = $lecture->children(0)->find('ul',0)->text();
		$aLecture->topics = trim($topics);
		//grab each lecture videolinks
		//echo $count .': '. $aLecture->topics.'<br /><br />';
		$vLinks = $lecture->find('center a');
		//echo $count .': '. print_r($vLinks).'<br/>';
		foreach($vLinks as $aLink){			
			// echo $count .': '. $aLink->text() .'<br/>';
			//copy first youtube lecture video as "course video"	
			if ($count == 1 && $aLink->text() == "YouTube")
			{
				$aCourse->video_link  = $aLink->href;
			}
			$aLecture->videoLinks[$aLink->text()] = $aLink->href;				
		}		
		array_push($lectureList,$aLecture);
		$count = $count + 1;
	}
	//stores lectures array as course lectures
	$aCourse->lectures = $lectureList;
	unset($lectureList);
	//$aCourse->videoLinks = $videoLinks;	
	//----------------------------------------	
	$totalLength = 0;
	$courseLength = $lecturePage->find('table td p');
	// scrapes lectureLength
	// todo: this loop too be merged with above lectures loop
	foreach($courseLength as $p)
	{			
		$pSlice =   explode(' ',$p->text());
		if (count($pSlice) >= 2 && $pSlice[1] == 'min')
		{	
			$totalLength += $pSlice[0]; //minutes
		}	
		else if (count($pSlice) >= 3 && $pSlice[1] == 'hr')
		{
			$totalLength += $pSlice[0] * 60; //hours to minutes
			$totalLength += $pSlice[2]; //minutes
		}
	}		
	// divide totalLength by 3 hours/per week
	// to estimate a # of weeks of workload
	$totalLength = ceil(($totalLength / 60) / 3);
	//echo $aCourse->title. ': '. $totalLength. ' weeks <br />';
	$aCourse->course_length = $totalLength;	
	
	//Clear data
	$lecturePage->clear();
}


/****************************
* scrapeInstructor
* 
****************************/
function scrapeInstructor($details,$aCourse){
	
	$instructorTable = $details->find('table#snav',0);
	if ($instructorTable != NULL)
	{
		$instructorImage = $instructorTable->find('td#snav');
		$instructorImage = $instructorImage[1]->children(0)->src;
		//echo '<img src="'.$instructorImage.'"/>';
		$instructorName = $instructorTable->find('td#snav');
		$instructorName = $instructorName[2]->children(0)->plaintext;
		//echo $instructorName . '<br />';		
	}
	else
	{
		$instructorImage = 'avatar_placeholder.jpg';
		$instructorName = $aCourse->site . ' Instructor';
	}
	$courseDetails = array();
	$courseDetails[$instructorName] = $instructorImage;
	$aCourse->instructors = $courseDetails;
}	


/****************************
* scrapeDescription
* 
****************************/
function scrapeDescription($html,$aCourse){	
	
	$tableWrapper = $html->find('div#tableWrapper p',0);
	//echo '<b>'.$aCourse->title ."</b> ". $tableWrapper->text().'<br />';
	if ($tableWrapper != NULL)
	{
		$courseDescription = $tableWrapper->text();
	}
	else
	{
		$courseDescription = "Course description coming soon.";
	}
	$aCourse->long_desc = $courseDescription;
	$shortDesc = explode('.',$courseDescription);
	$aCourse->short_desc = $shortDesc[0].'.';
}	


/**************************************
*
* Write to Database
***************************************/
function writeToDatabase($aCourse){
	global $dbh;
	try {
		$qrm = $dbh->prepare("INSERT INTO course_data VALUES ( NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$qrm->execute(array($aCourse->title, 
			                empty($aCourse->short_desc) ? 'no desc': $aCourse->short_desc, 
			                empty($aCourse->long_desc) ? 'no desc': $aCourse->long_desc,
			                empty($aCourse->course_link) ? 'no link': $aCourse->course_link,
			                empty($aCourse->video_link) ? 'no video': $aCourse->video_link, 
			                $aCourse->start_date,
			                empty($aCourse->course_length) ? 0 : $aCourse->course_length, 
			                empty($aCourse->course_image) ? 'course_image_placeholder' : $aCourse->course_image, 
			                empty($aCourse->category) ? 'no category': $aCourse->category,
			                $aCourse->site));
	}
	catch (PDOException $err) {
		    $err->getMessage();
			echo $err;
	}
	$num = $dbh->lastInsertId();
	//echo $num;
			
	if($num > 0){
		if (count($aCourse->instructors) > 0)
		{
			foreach ($aCourse->instructors as $name => $image)
			{		
				$qrye = $dbh->prepare("INSERT INTO coursedetails VALUES (?, ?, ?)"); 
			 	$qrye->execute(array($num, $name, $image));
		 		//echo "$num\t$p\t";
			}
		}
	}        	
}

/******************************/

/*********************************
*		DATA STRUCTURES
*********************************/

/* Course Class/Object */
class course
{
    public $course_code;
	public $title;
    public $course_link;
	public $category;
	public $site;
	public $long_desc;
	public $short_desc;
	public $video_link;
	public $course_length;
	public $course_image = 'course_image_placeholder.jpg'; 
	//start date - not applicable in our case, so set to '2001-01-01 01:01:01'
	public $start_date = '2001-01-01 01:01:01';
	public $instructors; //assoc array profname => profimage 
		
	//custom values:
	public $lectures; //an array of lecture objects
	public $links; // associative array label:url

}

class lecture 
{
	public $id;
	public $courseTitle; //for debugging
	public $viewNowLink;
	public $length;
	public $videoLinks;
	public $topics;
	public $HTMLtranscripts;
	public $PDFTranscripts;	
}


