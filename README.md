mit-stanford
============

MIT-Stanford MOOC mashup

As a test to ensure everyone knows how to commit/push, sign in below:

Eric
Charles
Vlad
Taka
Alice
Meena
Chris

****MIT****<br>
professor name (Charles)<br>
professor image - url to image (I dont think this is applicable for MIT? - charles) <br>
title - (Charles) course title<br>
short description - is there such a thing as a short vs long description? (Meena)<br>
long description (Meena)<br>
course link - (Charles) <br>
video link - link to first video, since there can be multiple. I suppose we will need a separate table to keep track of all the videos and other features we'll need. We'll need all the videos to calculate course_length. We'll need to be able to scrape all the video links ultimately.<br>
start date - not applicable in our case, so set to '2001-01-01 01:01:01'<br>
course_length - I suspect this is different from our feature of total video length, but I think we can use it anyway. However, I suspect this cannot be scraped easily, but must be calculated by calling youtube API. Leave at 0 for now I guess, unless you want to start working on figuring out the API calls needed. Note that some of the "videos" are actually mp3 recordings, which we'll need to download and use some library to determine its length from the file.<br>
course_image - (Charles) just link to the image<br>
category - (Charles) this may need be normalized... for now just extract<br>
site - 'MIT'<br>
<br>
****Stanford****<br>
professor name(Chris)<br>
professor image - url to image(Chris<br>
title - course title(Chris)<br>
short description - is there such a thing as a short vs long description? (Meena) <br>
long description(Meena)<br>
course link - (Chris)<br>
video link - link to first video, since there can be multiple. I suppose we will need a separate table to keep track of all the videos and other features we'll need. We'll need all the videos to calculate course_length. We'll need to be able to scrape all the video links ultimately.<br>
start date - not applicable in our case, so set to '2001-01-01 01:01:01'<br>
course_length - I suspect this is different from our feature of total video length, but I think we can use it anyway. However, I suspect this cannot be scraped easily, but must be calculated by calling youtube API. Leave at 0 for now I guess, unless you want to start working on figuring out the API calls needed. Note that some of the "videos" are actually mp3 recordings, which we'll need to download and use some library to determine its length from the file.<br>
course_image - just link to the image(Chris)<br>
category - this may need be normalized... for now just extract(Chris)<br>
site - 'Stanford'<br>


