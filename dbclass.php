<?php
class Database 
{
	var $host = "localhost";
	var $database = "test";
	var $user = "root";
	var $password = "";
	
	var $databaseLink; 	// your db link
	
	function connect()
	{
		$this->databaseLink = mysql_connect( $this->host, $this->user, $this->password);
		if(!$this->databaseLink)
			die('Could not connect: ' . mysql_error());
		if(!mysql_select_db($this->database, $this->databaseLink)){
			die('Could not use DB:'.$this->database.' : ' . mysql_error());
		}
	}
	//Returns a resultSet. Use result_next() to grab data
	function query_read($queryString)
	{
		$this->connect();
		$results = mysql_query($queryString, $this->databaseLink);
		if(!$results){
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $queryString;
			die($message);
		}
		return $results;
	}
	//Returns True or False if the query succeed 
	function query_write($queryString){
		$this->connect();
		$results= mysql_query(mysql_escape_string($queryString), $this->databaseLink);
		if(!$results){
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $queryString;
			die($message);
		}
		return true;
	}

	function fetch_array($resultSet)
	{
		if($row = mysql_fetch_array($resultSet))
			return $row;
		else
			return false;
	}

	function num_of_rows($resultSet)
	{
		return mysql_num_rows($resultSet);
	}
	
	//Plan to add more functions so that all mysqlfunction stay in this file.
	//Such as a method to go through each record.
}

//Test
$db = new Database();
$results = $db->query_read("SELECT * FROM test");
echo $db->num_of_rows($results);
while($row = $db->fetch_array($results)){
	echo $row['number'];
}

echo "<br>";
$db->query_write("INSERT INTO test (number) VALUES (5)");

$results = $db->query_read("SELECT * FROM test");
echo $db->num_of_rows($results);
while($row = $db->fetch_array($results)){
	echo $row['number'];
}

?>