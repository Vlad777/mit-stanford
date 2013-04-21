<?php
//Basic Scapper
include ('simple_html_dom.php');
$html = file_get_html('http://see.stanford.edu/see/courses.aspx');
$content = $html->find('div#content',0);
foreach($content->find('div [class="listBlock"]') as $e)
{
	$courseCatagory =  $e->childNodes(0)->text();
	echo $courseCatagory;
	echo "<br>";
	while($e->next_sibling()->tag == 'div'){
		$e = $e->next_sibling();
		$courseLink = $e->children(0)->href;
		$courseTitle = $e->children(0)->children(0)->plaintext;
		$courseNumber = $e->children(0)->children(1)->plaintext;
		echo $courseTitle;
		echo "<br>";
		echo $courseLink;
		echo "<br>";
		echo $courseNumber;
		echo "<br>";
		//Go to link make sure that its a stanford link - Some links lead to like app store
		if(preg_match('/^http\:\/\/see.stanford.edu/',$courseLink)){
			$course = file_get_html($courseLink);
			$details = $course->find('div#content',0);
			$courseDescription = $details->find('p',0)->plaintext;
			echo $courseDescription;
			echo "<br>";
			$instructorTable = $details->find('table',0);
			$instructorImage = $instructorTable->find('td',1)->children(0)->src;
			echo $instructorImage;
			echo "<br>";
			$instructorName = $instructorTable->find('td',2)->children(0)->plaintext;
			echo $instructorName;
			echo "<br>";
		}
	}
}
?>	
