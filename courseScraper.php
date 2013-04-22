<?php

require_once("pdo_connect.php");

execQuery("TRUNCATE TABLE course_data");
execQuery("TRUNCATE TABLE coursedetails");

include("courseScraperMIT.php");
include("courseScraperSEE.php");

?>
