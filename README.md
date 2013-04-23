mit-stanford
============

http://cs160.sjsu-cs.org/spring2013/sec2group3/

MIT-Stanford MOOC mashup

* (done) Need code snippet to insert into database table.

* (done) Need another separate page to select from database and display in a (html5?) table that's sortable and filterable, and works on common browsers and mobile.

* Need help to parse the various different ways MIT organizes videos. My code only covers 77/133 of the pages that have a common page structure.

* Need help to extract video lengths.


Extra feature request:

In order to support continued maintenance of the application, we must ensure that our web scraping code can be verified and fixed if broken when the source websites are updated. Web scraping code is by its nature difficult to make so it works indefinitely. Sooner or later something in the source web sites will change that will break the web-scraping code. The following features will support being to react to those changes.

* Insert into temp staging table and compare with previous results. If there are differences, send email of what changed.

* Create validation scripts to check the data is still valid, such as making sure it's not null, video time ranges are within reasonable range 0 to 3 hours, links are valid and don't cause 404 errors, etc.


****MIT****<br>
professor name (Charles/Takahiro)<br>
<b>professor image</b> - url to image (I dont think this is applicable for MIT? - charles) <br>
title - (Charles) course title<br>
short description - is there such a thing as a short vs long description? (Alice)<br>
long description (Alice)<br>
course link - (Charles) <br>
video link - (Vlad - there are many variations of how the courses organize their video links... I could use help on this as well.) link to first video, since there can be multiple. I suppose we will need a separate table to keep track of all the videos and other features we'll need. We'll need all the videos to calculate course_length. We'll need to be able to scrape all the video links ultimately.<br>
start date - not applicable in our case, so set to '2001-01-01 01:01:01'<br>
<b>course_length</b> - I suspect this is different from our feature of total video length, but I think we can use it anyway. However, I suspect this cannot be scraped easily, but must be calculated by calling youtube API. Leave at 0 for now I guess, unless you want to start working on figuring out the API calls needed. Note that some of the "videos" are actually mp3 recordings, which we'll need to download and use some library to determine its length from the file.<br>
course_image - (Charles) just link to the image<br>
category - (Charles) this may need be normalized... for now just extract<br>
site - 'MIT'(Diem)<br>
<br>
****Stanford****<br>
professor name(Chris)<br>
professor image - url to image(Chris)<br>
title - course title(Chris)<br>
<b>short description</b> - is there such a thing as a short vs long description? (Meena) <br>
<b>long description</b> (Meena)<br>
course link - (Chris)<br>
video link - (Alice) link to first video, since there can be multiple. I suppose we will need a separate table to keep track of all the videos and other features we'll need. We'll need all the videos to calculate course_length. We'll need to be able to scrape all the video links ultimately.<br>
start date - not applicable in our case, so set to '2001-01-01 01:01:01'<br>
course_length - (Alice) I suspect this is different from our feature of total video length, but I think we can use it anyway. However, I suspect this cannot be scraped easily, but must be calculated by calling youtube API. Leave at 0 for now I guess, unless you want to start working on figuring out the API calls needed. Note that some of the "videos" are actually mp3 recordings, which we'll need to download and use some library to determine its length from the file.<br>
<b>course_image</b> - just link to the image(Chris) - Perhaps use the video thumbnail if nothing else is available?<br>
category - this may need be normalized... for now just extract(Chris)<br>
site - 'Stanford'(Diem)<br>


