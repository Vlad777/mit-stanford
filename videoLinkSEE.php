<?php
/***********************************
* videoLinkSEE.php
* scrapes links to video for SEE courses
* @author: Alice Cotti
* @date 4/20/2013
*  CS 160 SJSU Spring 2013
*  Team 3 milestone 2a
************************************/
include ('simple_html_dom.php');

$html = file_get_html('http://see.stanford.edu/see/courses.aspx');
$site = "SEE";

// FOREACH:
// for each div class listBlock (categories) strong class courseListDepartment 
//   	stores the dept name 
// 		walks its siblings (divs coming after it)
// 		for each sibling, checks if its the one signaling next category 
//		or if its an empty paragraph (signaling end of list)
//		if its a valid course divs, it stores course info in the array 
foreach($html->find('div.listBlock') as $listBlock){
	//echo $listBlock;
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


// for each course object.
// open its link 
foreach($array as $aCourse)
{

	$html = file_get_html($aCourse->course_link);		

	/*******************************************
	* Scraping COURSE CONTENT box with links
	*******************************************/
	$contentBoxWrapper2 = $html->find('div#contentBoxWrapper2');
	if ($contentBoxWrapper2 == NULL)
	continue;

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

// DEBUG DATA PRINTING

//echo '<br>';
//echo print_r($array);
//echo json_encode($array);
//arrayPrettyPrint($array);
?><pre><?php echo print_r($array);?></pre><?php 
//var_dump($array);

/****************************
* scrapeLectures
* given URL or Lectures page
****************************/
function scrapeLectures($url,$aCourse){

	$lecturePage =  file_get_html($url);
	$videoLinks = array();
	$vLinks = $lecturePage->find('table td center a');
	foreach($vLinks as $aLink){			
		// echo $aLink->href;
		// echo $aLink->text();		
		$videoLinks[$aLink->text()]=$aLink->href;	
		if ($aLink->text() == "YouTube")
		{
			$aCourse->video_link = $aLink->href;
		}
	}	
	$aCourse->videoLinks = $videoLinks;		
	$lecturePage->clear();
}

/*********************************
*		DATA STRUCTURES
*********************************/

/*
* Course Class/Object
*/

class course
{
    public $course_code;
	public $title;
    public $course_link;
	public $category;
	public $site;
	public $long_desc;
	public $profname;
	public $video_link;
	
	//custom values:
	public $lectures; //an array of lecture objects(?unused?)
	public $links; // associative array label:url
	public $videoLinks; // associative array label:url
}

class lecture //unused structure
{
	public $id;
	public $viewNowLink;
	public $length;
	public $youtubeLink;
	public $iTunesLink;
	public $WMVTorrent;
	public $MP4Torrent;
	public $topics;
	public $HTMLtranscripts;
	public $PDFTranscripts;	
}



/*********************************
*		FUNCTIONS
*********************************/
function arrayPrettyPrint($anArray)
{

	echo '<div class="tablePrint">';
	foreach($anArray as $aCourse)
	{
		echo $aCourse->title .'&nbsp;';
		echo ' '.$aCourse->course_code .'&nbsp;';
		echo '<a href="'.$aCourse->course_link .'">course_link</a>&nbsp;';
		echo '<a href="'.$aCourse->video_link .'">video_link</a>&nbsp;';
		echo "<br />";
	}
	echo '</div>';
}
/*********************************/

?>

<style>
div.tablePrint{
	font-family:Verdana, Geneva, sans-serif;
	font-size:10px;
}
</style>
