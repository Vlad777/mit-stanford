<?php

	require_once('pdo_connect.php');
	include("includes/function_user.php");

	$rating = new ratings($_POST['widget_id']);

	isset($_POST['fetch']) ? $rating->get_ratings($dbh) : $rating->vote($dbh);

class ratings{
	private $class_id;

	function __construct($classID){
		$this->class_id = $classID;
	}

	public function get_ratings($dbh){

		$results = $dbh->prepare('SELECT * FROM review_course where courseID ='.$this->class_id);
		$results->execute();
		$total_votes = $results->rowCount();

		if($total_votes > 0){

			$queryString = 'SELECT AVG(starRating) d FROM review_course where courseID ='. $this->class_id;
			$results = fetchAll($queryString);
			$this->avg_rating = round($results[0]['d']);

			$queryString = 'SELECT * FROM review_course where courseID ='. $this->class_id;

			$average = array('avg' => $this->avg_rating, 'total_votes' => $total_votes);
			echo json_encode($average);
		}
	}

	public function vote($dbh){		
		preg_match('/star_([1-5]{1})/', $_POST['clicked_on'], $match);
        $vote = $match[1];

        if($_SESSION['userid'] > 0){

        	$check_voted = $dbh->prepare('SELECT * FROM review_course where userID = '.$_SESSION['userid'].' AND courseID ='.$this->class_id);
        	$check_voted->execute();
        	$already_voted = $check_voted->rowCount();

        	if($already_voted > 0){
        		$insertValue = $dbh->prepare("UPDATE review_course SET starRating = ? where userID = ".$_SESSION['userid']." AND courseID =".$this->class_id);
	 			$insertValue->execute(array($vote));
		 	}
		 	else{
		 		$insert_new_value = $dbh->prepare("INSERT INTO `review_course` (`userID`, `courseID`, `starRating`, `comments`) VALUES (?,?,?,?)");
		 		$insert_new_value->execute(array($_SESSION['userid'],$this->class_id,$vote,"awesome"));
		 	}

	 		$this->get_ratings($dbh);
	 	}
	}

}
?>