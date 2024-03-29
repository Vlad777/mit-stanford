<?php
/***********************************
* courseScraperMIT.php
* scrapes courses data for MIT
* 	title
*	short_desc (first sentence of long description)
*	long_dec
*	course_link
*	video_link
*	start_date 
*	course_image 
*	category
*	site (MIT)
*
*  Writes data to Database
*
*  @date 4/30/2013
*  CS 160 SJSU Spring 2013
*  Team 3 milestone 2a
************************************/
require_once('pdo_connect.php');
//set_time_limit(60);
include ('simple_html_dom.php');
set_time_limit(60000);

$site_url = "http://ocw.mit.edu";
$html = file_get_html("$site_url/courses/audio-video-courses/");

$array=array();
$i = 0;
//save every URL to every course offered into array
foreach($html->find('a[rel="coursePreview"]') as $e)
{
	$i++;
	if (empty($e->href)) { /* echo "\nFound empty course link $i"; */ }
	else
	{
		if (substr($e->href,0,4) <> "http") { $link = $site_url; }
		$link .= $e->href;

	  	if(!(in_array($link,$array)))
		{
			$array[] = $link;
		}
	}	
}

//echo "title|category|course_link|course_image|professor_name|video_link|video_title|youtube_url<br>";
//foreach loop iterating through all course urls
foreach($array as $course_link)
//$course_link = 'http://ocw.mit.edu/courses/electrical-engineering-and-computer-science/6-001-structure-and-interpretation-of-computer-programs-spring-2005';
//$course_link = 'http://ocw.mit.edu/courses/aeronautics-and-astronautics/16-660-introduction-to-lean-six-sigma-methods-january-iap-2008';
//$course_link = 'http://ocw.mit.edu/resources/res-ll-001-introduction-to-radar-systems-spring-2007';
{
	$html->clear();
	$html = file_get_html($course_link);

	$e = $html->find('title',0);

	$title = NULL;
	$short_desc = NULL;
	$long_desc = NULL;
	$category = NULL;
	$videos = NULL;
	$course_length = NULL;
	$course_image = NULL;
	$video_link = NULL;
	$professor_name = NULL;
	$prof_image = NULL;

	if (empty($e)) { echo "\nFailed to find title in $course_link "; }
	else
	{
		//dissect the element for title and course name
		$titleElement = preg_split("/\\|/",$e->plaintext);
		if (empty($titleElement)) { echo "\nFailed to get title"; }
		else
		{
			$title = trim($titleElement[0]);			
			$category = trim($titleElement[1]);
		}
	}

	//grabs image from courseURL
	$e = $html->find('img[itemprop="image"]',0);
	if (empty($e)) { 
		//echo "\nFailed to find course image of" . $course_link; 
	}
	else
	{
		if(substr($e->src,0,4) <> "http") { $course_image = $site_url; }
		$course_image .= $e->src;
	}
    
       //grabs long course description from courseURL
	   // div itemprop="description"
	   $longdescrip = $html->find('div#description p');
	   if (count($longdescrip) > 1)
	   	   $longdescrip = $longdescrip[1];
	   else if (count($longdescrip) == 1)
	   		$longdescrip =  $longdescrip[0];
	    //$longdescrip = $html->find('div[id="description"]//p');
		/* Failed to find course description of: http://ocw.mit.edu/courses/mathematics/18-086-mathematical-methods-for-engineers-ii-spring-2006 */		
       else if (empty($longdescrip))
	   {
		   	$longdescrip = $html->find('div#description',0);			
			$longdescrip  = explode('Course Description',$longdescrip);
			$long_desc  = $longdescrip[1];			
		}	   
        if(empty($longdescrip) && empty($long_desc)) {echo "\nFailed to find course description of: " . $course_link ."<br />"; }
        else
        { 
            $long_desc = trim($longdescrip->plaintext);
			
        }
		
        //grabs short course description from courseURL
        $shortdescrip = $long_desc;
        if(empty($shortdescrip)) {
			// echo "\nFailed to find course description of" . $course_link ."<br />";
			}
        else
        { 
            //$short_desc = trim($shortdescrip->plaintext);
			$short_desc = explode('.',$shortdescrip);
			$try_short_desc = $short_desc[0].'.';			
			
			if (strlen($try_short_desc) < 10 )
				 $short_desc = $short_desc[0].'.'.$short_desc[1].'.';			
			else $short_desc = $short_desc[0].'.';
		//	echo "ShortDescription: ".$short_desc . " ";
        }
//    echo "<b>". $title . "</b> " . $long_desc ."<br />";
	//grabs professors from courseURL
	$profs= $html->find('p[class="ins"]');
	if (empty($profs)) { echo "\nFailed to find prof of: " . $course_link ."<br />"; }
	else
	{
		$professor_name = trim($profs[0]->plaintext);
		//echo " ". $professor_name ." <br />";
	}

	$videos=array();
	//<div id="course_tabs">
	foreach($html->find('a') as $a)
	{
		//echo $a->plaintext . "<br />";
		switch ($a->plaintext)
		{
		case "Video lectures":
		case "Selected video lectures":
		case "Audio lectures":
		case "Selected audio lectures":
		    if (substr($a->href, 0, 4) <> "http" ) { $video_link = $site_url; }
			$video_link .= $a->href;
			break;
		}
	} //should be here	//unless you empty $video_link after starting each foreach loop
	if (empty($video_link)) { /* echo "\nFailed to find video link in: " . $course_link ."<br />";  */ }
	else
	{
		$video = new video();
		//now extract all the videos
		//there are different ways videos are organized
		$video_html = file_get_html($video_link);
		if (empty($video_html)) { echo "\nFailed to fetch: " . $video_link ." of: " . $course_link ."<br />"; }
		else
		{
			//echo "<u>videolink page:</u> ". $video_link . "<br />";
			//most videos are in a script ocw_embed_media
			foreach($video_html->find("script") as $s)
			{
				 $i = stripos($s->innertext, "ocw_embed_media");
				 if ($i !== false)
				 {
					$f = explode(",", substr($s->innertext, $i));
					$video->youtube_url = trim($f[1], "' ");
					$videos[0] = $video;
					break;
				 }
			}
			// if video was not found:
			if (empty($video->youtube_url))
			{				
				foreach($video_html->find('div[class=mediatext] a') as $mediatext)
				{
					if (empty($mediatext)) 
					{ echo "\nFailed to find mediatext in : ". $video_link . " of: " . $course_link ."<br />"; }
					else if($mediatext->href == "javascript:void(0);")
					{
						//videos on same page, loaded with javascript
						$video->title = $mediatext->plaintext;
						if (substr($mediatext->onclick, 0, 4) <> "http") { $video->youtube_url = $site_url; }
						$video->youtube_url .= $mediatext->onclick;
						$videos[0] = $video;
					}
					else
					{
						$video->title = $mediatext->plaintext;
						//$video->youtube_url = "http://ocw.mit.edu" . $mediatext->href;
						if (substr($mediatext->href,0,4) <> "http") { $link = $site_url; }
						$link .= $mediatext->href;
						$video_html2 = file_get_html($link);
						if (empty($video_html2)) { /* echo "\nFailed to fetch html of $course_link"; */ }
						else
						{
							//get html_url to get youtube_url
							//$s = $video_html2->find("div[id=course_inner_media] script"); //doesn't seem to work
							$x = $video_html2->find("div[id=course_inner_media]",-1);
							if (!empty($x))
							{
								//$video->youtube_url = $x->find("script",0)->innerhtml; //for some reason this doesn't work.
								foreach($x->find("script") as $s)
								{
									 $i = stripos($s->innertext, "ocw_embed_media");
									 if ($i !== false)
									 {
										$f = explode(",", substr($s->innertext, $i));
										$video->youtube_url = trim($f[1], "' ");
										$videos[0] = $video;
										break;
									 }
								}
							}
							$video_html2->clear();
						}
					}
					
					$videos[] = $video;
				} //foreach mediatext
			}//if
			   	//if no video was found,
				// look for 'http://www.youtube.com/' in the page, grab first link
				// but if link contains 'view_play_list' then gopen that page and grab first video<br />
				// (eg. http://ocw.mit.edu/courses/chemistry/5-74-introductory-quantum-mechanics-ii-spring-2004/video-lectures/)
				// if still no video, find a .mp4  (or .mp3) in the page
				// (eg. http://ocw.mit.edu/courses/architecture/4-301-introduction-to-the-visual-arts-spring-2007/lecture-notes/)
				// if still no video look for .rm	
				
			$video_html->clear();
			} 
			// echo  "videolink: " . $video->youtube_url . "<br />";
			if ($videos)
			{
			 	echo  "<u>Videolink:</u> " . $videos[0]->youtube_url . "<br />";
			}
			else echo "No course video link <br />";
			// end of extracting all videos			
	}
	

//	echo $title.'|'.$category.'|'.$course_link.'|'.$course_image.'|'.$professor_name.'|'.$video_link.'|'.$videos[0]->title.'|'.$videos[0]->youtube_url.'<br>';
	$default_prof_image = 'images/avatar_placeholder.jpg';
 	$default_course_image = 'images/course_image_placeholder.jpg';

	global $dbh;
	try {
		$qrm = $dbh->prepare("INSERT INTO course_data VALUES ( NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$qrm->execute(array(empty($title) ? 'no title' : trim($title), 
			                empty($short_desc) ? 'no desc': trim($short_desc), 
			                empty($long_desc) ? 'no desc': trim($long_desc),
			                empty($course_link) ? 'no link': trim($course_link),
			                empty($videos[0]->youtube_url) ? 'no video': trim($videos[0]->youtube_url), 
			                '2001-01-01 01:01:01',
			                empty($course_length) ? 0 : trim($course_length), 
			                empty($course_image) ? $default_course_image: trim($course_image), 
			                empty($category) ? 'no category': trim($category),
			                'MIT'));
		//$op1 = execQuery($qrm);
		}
	catch (PDOException $err) {
		    $err->getMessage();
			echo $err;
	}

	$num = $dbh->lastInsertId();
	//echo $num;
			
	if($num > 0){
		$qrye = $dbh->prepare("INSERT INTO coursedetails VALUES (?, ?, ?)"); 
	 	$qrye->execute(array($num, 
	 		                 empty($professor_name) ? 'MIT Instructor' : trim($professor_name), 
	 		                 empty($prof_image) ? $default_prof_image : trim($prof_image)));
	}
	
	
echo "<hr>";
}

$html->clear();

echo "\nnumber of links: " . count($array);

class video
{
	public $title;
	public $youtube_url;
}

?>
