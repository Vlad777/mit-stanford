<?php
//Comments
require_once('pdo_connect.php');
include("includes/function_user.php");	



if(isset($_POST['Submit']))
{
	//Submitting a comment
	// get userid
	if($user["userid"] == 0){
		die("You are a guest. You cannot submit a comment.");
	}
	$qs = $dbh->prepare("INSERT INTO comments (userid,message,datesubmitted,course_id,username)values (?,?,?,?,?)");
	$message = $_POST["message"];
	$userid = $user["userid"];
	$courseid = $_POST["courseid"];
	$qs->execute(array($userid,$message,date("D M j G:i:s T Y"),$courseid,$user["username"]));
	header("Location: comment.php?id=". $courseid);
} else {
	if(isset($_GET['id'])){
		$st = $dbh->prepare("SELECT * FROM comments where course_id = ? ORDER BY id Desc");

		$st->execute(array($_GET["id"]));
		$results = $st->fetchAll();

		foreach ($results as $row) {
			echo "<strong>".$row["username"]."</strong> - ".$row["datesubmitted"]."<br/>";
			echo "<div style='border-bottom:1px solid #000;margin-bottom:20px'>";
			echo $row["message"];
			echo "</div>";
		}

	} else {
		echo "No course selected.";
	}
	

}



?>

<form method="post" action="comment.php?id=<?php echo $_GET["id"]; ?>">
	<input type="hidden" name="courseid" value="<?php echo $_GET["id"]; ?>" />
	<textarea name="message"></textarea>
	<input type="submit" name="Submit">
</form>