<?php
class Database 
{
	var $host = "localhost";
	var $database = "test";
	var $user = "root";
	var $password = "";
	
	var $databaseLink;
	var $results; 
	
	function connect()
	{
		$this->databaseLink = mysql_connect( $this->host, $this->user, $this->password);
		if(!$this->databaseLink)
			die('Could not connect: ' . mysql_error());
		if(!mysql_select_db($this->database, $this->databaseLink)){
			die('Could not use DB:'.$this->database.' : ' . mysql_error());
		}
	}
	//Still need to mysql_fetch_array
	function query($queryString)
	{
		$this->connect();
		$this->results = mysql_query($queryString, $this->databaseLink);
		if(!$this->results){
			$message  = 'Invalid query: ' . mysql_error() . "\n";
			$message .= 'Whole query: ' . $queryString;
			die($message);
		}
		return $this->results;
	}
	
	function num_of_rows()
	{
		return mysql_num_rows( $this->results );
	}
	
	//Plan to add more functions so that all mysqlfunction stay in this file.
	//Such as a method to go through each record.
}

//Test
$db = new Database();
$db->query("SELECT * FROM test");
echo $db->num_of_rows();
?>