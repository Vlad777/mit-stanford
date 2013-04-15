<?php
include ('simple_html_dom.php');

$html = file_get_html('http://ocw.mit.edu/courses/audio-video-courses/');

//save every URL to every course offered into array
foreach($html->find('a[rel="coursePreview"]') as $e){
	if(empty($array)){
		$array[] = 'http://ocw.mit.edu' . $e->href;
		}
	elseif(!(in_array('http://ocw.mit.edu'.$e->href,$array))){
		$array[] = 'http://ocw.mit.edu' . $e->href;
	}
}

//foreach loop iterating through all course urls
foreach($array as $url){
$html = file_get_html($url);
$e = $html->find('title',0);


//dissect the element for title and course name
$titleElement = preg_split("/\\|/",$e->plaintext);
$courseName = $titleElement[0];
$subject = $titleElement[1];

//grabs image from courseURL
$e = $html->find('img[itemprop="image"]',0);
$image =  'http://ocw.mit.edu'.$e->src;

//grabs professors from courseURL
$e = $html->find('p[class="ins"]');
$professor = $e;


echo '<br>'. "courseName: " . $courseName;
echo '<br>'. "subject: " . $subject;
echo '<br>'. "classURL: " . $url;
echo '<br>' . "imageURL: " . $image;
echo '<br>' . "professor: ". $professor[0];

//prints all professors
/*foreach($professor as $prof)
echo " ".$prof;*/



}



?>
	