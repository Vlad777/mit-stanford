<?
/*********************************************
* includes/linkToRateMyProfessor.php
* @Author Alice Cotti
* @date 05/03/2013
* @ CS 160 SJSU Spring 2013
*
**********************************************/

function linkToRateMyProfessor($profName, $siteName)
{
	$prof = $profName;
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
			if ($siteName == 'SEE')
					$pattern = '/Stanford/';
			else if ($siteName == 'MIT')
					$pattern = '/Massachusetts Institute of Technology/';
			else {
				//echo ' missing school name for this site name <br />';
				$pattern = '/San Jose State University/'; // default  pattern
			}			
			preg_match($pattern, $schoolname, $matches);		
			if ( count($matches) >= 1)
			{
				$aLink = 'http://www.ratemyprofessors.com/ShowRatings.jsp?tid='. $school->pk_id;
				echo '<a onclick="open_video(\''.$aLink.'\');return false;" href="'.$aLink.'" target="_blank">Link to Rate My Professor</a>';
				$foundSchool = 1;
			}		
		} //foreach
	} 	
	
	 // mostly for debug purposes
	 /*
		if (!$foundName)
			echo 'Could not find matching instructor name<br />';	
		else if (!$foundSchool)
			echo 'Could not find matching school name<br />';		
 	*/
}
?>
