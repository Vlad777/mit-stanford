<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Privacy and Policy</title>

<script type="text/javascript" language="JavaScript"><!--
function toggle5(showHideDiv, switchImgTag) {
        var ele = document.getElementById(showHideDiv);
        var imageEle = document.getElementById(switchImgTag);
        if(ele.style.display == "block") {
                ele.style.display = "none";
				imageEle.innerHTML = '<img src="images/plus.png" width="18" height="18" align="absmiddle" hspace="5" border="0">';
        }
        else {
                ele.style.display = "block";
                imageEle.innerHTML = '<img src="images/minus.png" width="18" height="18" align="absmiddle" hspace="5" border="0">';
        }
}
//--></script>

<style type="text/css"><!--

//--></style>

</head>

<body>

<?php include("template/header.php"); ?>

<div style="border: 2px solid green ;">
<div style="margin-left:60px;margin-right:60px;"><div style="font-size:20px;"><b>Sitemap</b></div><br />       
	<div style="text-indent:20px;">
		<img src="images/ctdot.gif" width="22" height="22" align="absmiddle" hspace="5" border="0">
			<span style="vertical-align:bottom;"> <a href="index.php">Home</a></span>
		</img>
	</div>

	<div style="text-indent:20px;">
		<img src="images/ctdot.gif" width="22" height="22" align="absmiddle" hspace="5" border="0">
			<span style="vertical-align:bottom;"> <a href="aboutus.php">About Mooc4u</a></span>
		</img>
	</div>

	<div style="text-indent:20px;">
		<img src="images/ctdot.gif" width="22" height="22" align="absmiddle" hspace="5" border="0">
			<span style="vertical-align:bottom;"> <a href="help.php">Help to start</a></span>
		</img>
	</div>
	
	<ul style="margin-left:25px;margin-top:0px;margin-bottom:5px;">
 
		<div id="headerDivImg">
			<a id="imageDivLink" href="javascript:toggle5('contentDivImg', 'imageDivLink');"><img src="images/plus.png" width="18" height="18" align="absmiddle" hspace="5" border="0" /></a>FAQ: Technology
		</div>
		<div id="contentDivImg" style="display:none; margin-left:40px; margin-bottom:5px;">
			What are the technical requirements for viewing Mooc4u course materials?<br />
			Mooc4u has tested the site with the following browsers:<br />
			Chrome 20+<br />
			Firefox 13+ (all platforms)<br />
			Internet Explorer 8.0+ (Windows)<br />
			Safari 5.1+ (Mac OSX)<br /><br />
			
			Although higher-speed connections are preferable, slower connections, such as 28.8 kbps modems, should allow users to view most materials on the sites; however, downloading materials will take a longer period of time.<br /><br />
			
			Some file types within the course material require special software to use; these are identified on the individual course pages.<br />
		</div>		
		

		<div id="headerDivImg">
			<a id="imageDivLink2" href="javascript:toggle5('contentDivImg2', 'imageDivLink2');"><img src="images/plus.png" width="18" height="18" align="absmiddle" hspace="5" border="0" /></a>FAQ: Technical Requirements
		</div>
		<div id="contentDivImg2" style="display:none; margin-left:40px; margin-bottom:5px;">
			To best view and use the sites, Mooc4u has adopted the following guidelines:<br />

			Our course sites work on the Macintosh, Unix, and Windows platforms.<br /><br />
			Although higher-speed connections are preferable, slower connections, such as 28.8 kbps modems, should allow users to view most materials on the sites; however, downloading materials will take a longer period of time.<br /><br />
			Supported Browsers: Mooc4u has tested the course sites with the following browsers:<br />
			Chrome 20+ (all platforms)<br />
			Firefox 13+ (all platforms)<br />
			Internet Explorer 8.0+ (Windows)<br />
			Safari 5.1+ (Mac OSX)<br /><br />
			Due to rapid development in the open source world, there may be other open source software packages that will work as well as of any of those suggested below. We are unable to offer technical support for any of the suggested software.<br />
		</div>		
		
	</ul>
	
	<div style="text-indent:20px;">
		<img src="images/ctdot.gif" width="22" height="22" align="absmiddle" hspace="5" border="0">
			<span style="vertical-align:bottom;"> <a href="contactus.php">Contact Us</a></span>
		</img>
	</div>

	<div style="text-indent:20px;">
		<img src="images/ctdot.gif" width="22" height="22" align="absmiddle" hspace="5" border="0">
			<span style="vertical-align:bottom;"> <a href="policy.php">Privacy Policy</a></span>
		</img>
	</div>

	<br />
</div>
</div>

<?php include("template/footer.php"); ?>
</body>
</html>
