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
foreach($array as $course_link){
$html = file_get_html($course_link);
$e = $html->find('title',0);


//dissect the element for title and course name
$titleElement = preg_split("/\\|/",$e->plaintext);
$title = $titleElement[0];
$category = $titleElement[1];

//grabs image from courseURL
$e = $html->find('img[itemprop="image"]',0);
$course_image =  'http://ocw.mit.edu'.$e->src;

//grabs professors from courseURL
$e = $html->find('p[class="ins"]');
$professor_name = $e;


echo '<br>'. "title: " . $title;
echo '<br>'. "category: " . $category;
echo '<br>'. "course_link: " . $course_link;
echo '<br>' . "course_image: " . $course_image;
echo '<br>' . "professor_name: ". $professor_name[0];

//prints all professors
/*foreach($professor_name as $prof)
echo " ".$prof;*/



}



?>
	