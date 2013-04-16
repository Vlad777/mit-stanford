<?php
                                                  /* Do we need to modify this file? */
    // Include the library
  require_once('connection.php');
    include('simple_html_dom.php');
	mysql_query("TRUNCATE TABLE course_data");
	mysql_query("TRUNCATE TABLE coursedetails");
    // Retrieve the DOM from a given URL
    $folder = file_get_html("https://www.canvas.net");
	 $week = 0;
	 $count =0;
	 $check = "{{actionText}}";
	 $canvidLink = $folder->find('.video-container',0)->childNodes(0)->childNodes(0)->getAttribute('src');
    foreach ($folder->find('div[class=block-box no-pad featured-course-outer-container]') as $e){
	  $canlink= $e->find('a',0)->getAttribute('href');
	  //$canname= $e->find('.featured-course-container',0)->childNodes(0)->childNodes(0)->getAttribute('title');
	  $canname= $e->find('.featured-course-title',0)->text();	  
	 
	  $canimage=$e->find('.featured-course-image',0)->childNodes(0)->getAttribute('style');
	  $img = explode("(",$canimage);
	  $finimage = explode(")",$img[1]);
	  $canimage = "http://www.canvas.net$finimage[0]";
	  $candesc = $e->find('.featured-course-container',0)->childNodes(0)->childNodes(2)->text();
	  $candate = $e->find('.featured-course-container',0)->childNodes(0)->childNodes(0)->childNodes(0)->text();
	  $pcanimage = $e->find('.featured-course-container',0)->childNodes(0)->childNodes(3)->childNodes(0)->getAttribute('src');
	  //$pcanimage = $e->find('.instructor-image-container',0)->childNodes(0)->getAttribute('src');
	  if($pcanimage){
	        $pcanimage = "http://www.canvas.net$pcanimage";
	  }
     
	  $subfolder = file_get_html("$canlink");
	  
	  
	  if (stripos($subfolder->find('div[class=instructor-bio]',0)->childNodes(1)->text(),'org') === false){
			$pcanimage = $subfolder->find('div[class=instructor-bio]',0)->childNodes(0)->getAttribute('src');
	        $pcanname= $subfolder->find('div[class=instructor-bio]',0)->childNodes(1)->text();
			$pcanimage = "http://www.canvas.net$pcanimage";
			
	 }
	  else{
			$pcanname = $subfolder->find('.instructor-bio',0)->childNodes(0)->text();
		 }
	  $canstart= $subfolder->find('div[class=course-detail-info]',0)->childNodes(2)->text();
	  $canstart = trim($canstart);
	  $csdate = explode("to", $canstart);     //Seperating the start-date '$csdate[0]' and end-date '$csdate[1]'
	  $long_desc = $subfolder->find('div[class=block-box two-thirds first-box]',0)->text();
		//05/Feb/2010 Feb 25, 2013
		if(strpos($csdate[0],'Self-paced') === false && strpos($csdate[0],'Coming') === false){
			$strtDate = calcDate($csdate[0]);
			//echo " (a) ".$strtDate." (a) ";
			$startdate = date(strtotime($strtDate));
			$enddate = date(strtotime(calcDate($csdate[1])));
			$difference = $startdate - $enddate;
			$weeks = floor($difference / 86400 / 7 );
			$week = str_replace("-"," ",$weeks);
			$week = trim($week);
			//echo " & ".$csdate[1]." & ";
			
		}
		else if(strpos($csdate[0],'Coming') !== false){
			$newDate=substr($csdate[0],7);
			//echo " new ".$newDate." new ";
			$strtDate = trim($newDate);
			$strtDate = calcDate($strtDate);
			//echo " (b) ".$strtDate." (b) ";
			//$tempDate = explode("/",$strtDate);	
			//$strtDate = $tempDate[2]."-".$tempDate[0]."-".$tempDate[1];
		}
		else{
			$newDate=substr($csdate[0],22,12);
			$strtDate = trim($newDate);
			$strtDate = calcDate($strtDate);
			//echo " (c) ".$strtDate." (c) ";
			
		}
		
			$siteName = "Canvas";
			$category = "Science";
			unset($subfolder); // unset $subfolder			
			//Writing into DB ,long_desc,course_link,video_link,start_date,course_length,course_image
			//$qry="INSERT INTO coursera VALUES (DEFAULT,'$cname','$cdesc','$clink','$videoLink','$cdate','$count','$cimage','$category')";
			//,null,null,'$canlink','$canvidLink','$strtDate','$week','$canimage'
			try {
				$qrm="INSERT INTO course_data VALUES (DEFAULT,'".mysql_real_escape_string($canname)."','".mysql_real_escape_string($candesc)."','".mysql_real_escape_string($long_desc)."','".mysql_real_escape_string($canlink)."','".mysql_real_escape_string($canvidLink)."','".mysql_real_escape_string($strtDate)."','".mysql_real_escape_string($week)."','".mysql_real_escape_string($canimage)."','".mysql_real_escape_string($category)."','".mysql_real_escape_string($siteName)."')";
				$op1 = mysql_query($qrm);
			}
			catch (MySQLException $err) {
			    $err->getMessage();
				echo $err;
			}
			catch (MySQLDuplicateKeyException $err) {
			// duplicate entry exception
				$err->getMessage();
				echo $err;
			}
			$qryc = "Select id from course_data where title = '$canname'";
			$result=mysql_query($qryc);
			$member = mysql_fetch_assoc($result);
			$num = $member['id'];
			//echo $num;
			
			if($num > 0){
			
			$qrye = "INSERT INTO coursedetails (id, profname,profimage) VALUES ('".mysql_real_escape_string($num)."','".mysql_real_escape_string($pcanname)."','".mysql_real_escape_string($pcanimage)."')";
			$op2=mysql_query($qrye);
			 //echo "$num\t$p\t";
			}
		
        }// End of FOR
		$folder->clear();
		unset($folder);
		
		
		
	function calcDate($date){
			$norm = trim($date);
			$norms = str_replace("  "," ",$norm);
			$normdate = explode(" ",$norms);
			$day = str_replace(","," ",$normdate[1]);
			$normdate[0] = trim($normdate[0]);
			$normdate[2] = trim($normdate[2]);
			//$normdate[2] = substr($normdate[2],0,4);
			
				if(strcasecmp($normdate[0], "Jan") == 0 || strcasecmp($normdate[0], "January") == 0){
					$month = 1;
				}
				else if(strcasecmp($normdate[0], "Feb") == 0 || strcasecmp($normdate[0], "February") == 0 ){
					$month = 2;
				}
				else if(strcasecmp($normdate[0], "Mar") == 0 || strcasecmp($normdate[0], "March") == 0){
					$month = 3;
				}
				else if(strcasecmp($normdate[0], "Apr") == 0 || strcasecmp($normdate[0], "April") == 0){
					$month = 4;
				}
				else if(strcasecmp($normdate[0], "May") == 0){
					$month = 5;
				}
				else if(strcasecmp($normdate[0], "Jun") == 0 || strcasecmp($normdate[0], "June") == 0){
					$month = 6;
				}
				else if(strcasecmp($normdate[0], "Jul") == 0 || strcasecmp($normdate[0], "July")==0){
					$month = 7;
				}
				else if(strcasecmp($normdate[0], "Aug") == 0 || strcasecmp($normdate[0], "August")==0){
					$month = 8;
				}
				else if(strcasecmp($normdate[0], "Sep") == 0 || strcasecmp($normdate[0], "September") == 0){
					$month = 9;
				}
				else if(strcasecmp($normdate[0], "Oct") == 0 || strcasecmp($normdate[0], "October") == 0){
					$month = 10;
				}
				else if(strcasecmp($normdate[0], "Nov") == 0 || strcasecmp($normdate[0], "November") == 0){
					$month = 11;
				}
				else if(strcasecmp($normdate[0], "Dec") == 0 || strcasecmp($normdate[0], "December") == 0){
					$month = 12;
				}
				
				return $normdate[2]."-".$month."-".$day;
		}
				       
?>
