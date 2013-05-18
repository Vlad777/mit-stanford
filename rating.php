<?php

	require_once('pdo_connect.php');

	$rating = new ratings($_POST['widget_id']);

	isset($_POST['fetch']) ? $rating->get_ratings($dbh) : $rating->get_ratings($dbh);

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

	/*public function vote(){
		preg_match('/star_([1-5]{1})/', $_POST['clicked_on'], $match);
        $vote = $match[1];

        # Update the record if it exists
        if($exists) {
        	$insertValue = $dbh->prepare("UPDATE `review_course` SET `starRating`= ? where userID= ?");
	 		$insertValue->execute(array($vote,$_SESSION["userid"]);
        }
        # Create a new one if it does not
        else {
        	$insertValue = $dbh->prepare("INSERT INTO review_course VALUES (?, ?, ?, ?)");
	 		$insertValue->execute(array($_SESSION["userid"],$this->widgetid,$vote,"awesome"));
        }

        this->get_ratings();
	}*/

}
?>