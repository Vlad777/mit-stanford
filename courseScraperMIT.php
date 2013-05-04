<?php
require_once('pdo_connect.php');
//set_time_limit(60);
include ('simple_html_dom.php');
set_time_limit(60000);

$html = file_get_html('http://ocw.mit.edu/courses/audio-video-courses/');

$array=array();

//save every URL to every course offered into array
foreach($html->find('a[rel="coursePreview"]') as $e)
{
  if(!(in_array('http://ocw.mit.edu'.$e->href,$array)))
	{
		$array[] = 'http://ocw.mit.edu' . $e->href;
	}
}
echo "number of links: " . count($array) . "<br>";


//echo "title|category|course_link|course_image|professor_name|video_link|video_title|youtube_url<br>";

//foreach loop iterating through all course urls
foreach($array as $course_link)
//$course_link = 'http://ocw.mit.edu/courses/electrical-engineering-and-computer-science/6-001-structure-and-interpretation-of-computer-programs-spring-2005';
//$course_link = 'http://ocw.mit.edu/courses/aeronautics-and-astronautics/16-660-introduction-to-lean-six-sigma-methods-january-iap-2008';
{
	$html->clear();
	$html = file_get_html($course_link);

	$e = $html->find('title',0);


	//dissect the element for title and course name
	$titleElement = preg_split("/\\|/",$e->plaintext);
	$title = trim($titleElement[0]);
	$category = trim($titleElement[1]);

	//grabs image from courseURL
	$e = $html->find('img[itemprop="image"]',0);
	if ($e->src)  //if null placeholder will be set when writing to DB
		$course_image = 'http://ocw.mit.edu'.$e->src;
	else $course_image = '';

	//grabs professors from courseURL
	$profs= $html->find('p[class="ins"]');
	$professor_name = trim($profs[0]->plaintext);

	$video_link = "";
	$videos=array();
	foreach($html->find('a') as $a)
	{
		switch ($a->plaintext)
		{
			case "Video lectures":
			case "Selected video lectures":
			case "Audio lectures":
			case "Selected audio lectures":
				$video_link = "http://ocw.mit.edu" . $a->href;
			break;
		}

		if ($video_link <> "")
		{
			//now extract all the videos
			//there are different ways videos are organized
			$video_html = file_get_html($video_link);
			if ($video_html)
			{
				foreach($video_html->find('div[class=mediatext] a') as $mediatext)
				{
					$video = new video();
					if ($mediatext->href == "javascript:void(0);")
					{
						//videos on same page, loaded with javascript
						$video->title = $mediatext->plaintext;
						$video->youtube_url = "http://ocw.mit.edu" . $mediatext->onclick;
					}
					else
					{
						$video->title = $mediatext->plaintext;
						//$video->youtube_url = "http://ocw.mit.edu" . $mediatext->href;
						$video_html2 = file_get_html("http://ocw.mit.edu" . $mediatext->href);
						if ($video_html2)
						{
							//get html_url to get youtube_url
							//$s = $video_html2->find("div[id=course_inner_media] script"); //doesn't seem to work
							$x = $video_html2->find("div[id=course_inner_media]",-1);
							if ($x)
							{
								//$video->youtube_url = $x->find("script",0)->innerhtml; //for some reason this doesn't work.
								foreach($x->find("script") as $s)
								{
									if (stripos($s->innertext, "ocw_embed_media") !== false)
									{
										$f = explode(",", $s->innertext);
										$video->youtube_url = trim($f[1], "' ");

										break;
									}
								}
							}
							//print_r($video->youtube_url);
							$video_html2->clear();
						}
					}
					
					$videos[]=$video;
				}
				$video_html->clear();
			}
			//end of extracting all videos
			break;
		}
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
			                empty($course_image) ? $default_course_image : trim($course_image), 
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
}

$html->clear();


class video
{
	public $title;
	public $youtube_url;
}

?>
