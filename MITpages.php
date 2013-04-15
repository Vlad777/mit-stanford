<?php
include ('simple_html_dom.php');

$html = file_get_html('http://ocw.mit.edu/courses/audio-video-courses/');

//too lazy too figure out how to initalize array so array[0] will currently be "nothing"
$array[] = nothing;


//save every URL to every course offered into array
foreach($html->find('a[rel="coursePreview"]') as $e){
	if(!array_search('http://ocw.mit.edu' . $e->href,$array))
		$array[] = 'http://ocw.mit.edu' . $e->href;
	}


//seeing how printing values worked for php
foreach($array as $key => $value){
	//echo $value . '<br>';
}


//this would be a foreach loop but for now just working on getting the first webpage
$html = file_get_html($array[1]); 
$e = $html->find('title',0);


//dissect the element for title and course name
$titleElement = preg_split("/\\|/",$e->plaintext);
$courseName = $titleElement[0];
$subject = $titleElement[1];

echo '<br>'. "courseName: " . $courseName;
echo '<br>'. "subject: " . $subject;



?>
	
	