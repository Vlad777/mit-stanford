<?php

function dbConnect() {
  global $dbh;

  $dbInfo['database_target'] = "localhost";
  $dbInfo['database_name'] = "moocs";
  $dbInfo['username'] = "root";
  $dbInfo['password'] = "";


  $dbConnString = "mysql:host=" . $dbInfo['database_target'] . "; dbname=" . $dbInfo['database_name'];
  $dbh = new PDO($dbConnString, $dbInfo['username'], $dbInfo['password']);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $error = $dbh->errorInfo();
  if($error[0] != 0) {
      print "<p>DATABASE CONNECTION ERROR:</p>";
      print_r($error);
  }
}

function fetchAll($queryString) {
  global $dbh;
  return $dbh->query($queryString)->fetchAll();
}

dbConnect(); // Connect to Database

//Sample usage:
/*
<?php
require_once('pdo_connect.php');
$query = fetchAll("SELECT * FROM course_data");
echo "<pre>"; //for nice indented formatting of print_r
foreach ($query as $row) { //print all rows
  print_r($row); // prints all columns
}
echo "</pre>";
?>

*/
?>

