<?php

	require_once('pdo_connect.php');
	include("includes/function_user.php");	

	$rating = new ratings($_POST['widget_id']);

	isset($_POST['fetch']) ? $rating->get_ratings() : $rating->vote();




	class ratings{
	private $widget_id;
	private $avg_rating;
	private $exists;

	function__construct($classID){
		$this->widget_id = $classID;
		//i think i can make the query here

		$query = $dbh->prepare("SELECT * FROM review_course where courseID = ?");
		$query->execute(array($classID));

		if($query->rowCount() < 1){
			$exists = 0;
		}
		else{
			$exists = 1;
		}
		

	}

	public function get_ratings(){

		if($exists){
			$queryString = 'SELECT AVG(starRating) d FROM review_course ON courseID = $classID';
			$results = fetchAll($queryString);
			$this->avg_rating = $results[0]['d'];
			echo ($this->avg_rating);
		}
		else{
			echo ($exists);
		}



	}

	public function vote(){
		preg_match('/star_([1-5]{1})/', $_POST['clicked_on'], $match);
        $vote = $match[1];

        $ID = $this->widget_id;
        # Update the record if it exists
        if($exists) {
        	$insertValue = $dbh->prepare("INSERT INTO review_course VALUES (?, ?, ?, ?)");
	 		$insertValue->execute(array($_SESSION["userid"],$this->widgetid,$vote,"awesome"));
        }
        # Create a new one if it does not
        else {
        	$insertValue = $dbh->prepare("INSERT INTO review_course VALUES (?, ?, ?, ?)");
	 		$insertValue->execute(array($_SESSION["userid"],$this->widgetid,$vote,"awesome"));
        }
	}

	}
?>