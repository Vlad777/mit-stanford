<?php

require_once('pdo_connect.php');


class ratings{
	private $class_id;

	function __construct($classID){
		$this->class_id = $classID;
	}

	public function get_ratings($dbh){

		$results = $dbh->prepare('SELECT * FROM review_course where courseID ='.$this->class_id);
		$results->execute();

		if($results->rowCount() > 0){

			$queryString = 'SELECT AVG(starRating) d FROM review_course where courseID ='. $this->class_id;
			$results = fetchAll($queryString);
			$this->avg_rating = round($results[0]['d']);
			$average = array('avg' => $this->avg_rating);
			echo json_encode($average);
		}
	}


}

	$awesome = new ratings(1);
	$awesome->get_ratings($dbh);

?>