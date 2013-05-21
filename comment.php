<?php
//Comments
require_once('pdo_connect.php');
include("includes/function_user.php");	
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Comments | Moocs4U </title>

<link rel="stylesheet" href="template/style.css" />	

</head>

<body>
<?

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


      
		 $array = fetchAll('SELECT d.id, d.title, d.site FROM course_data d WHERE d.id='. $_GET['id']);
		?>
        <div class="comments_page">
		<h2><? echo $array[0]['title'] ?></h2>
        <div style="height:auto;width:100%;border:solid 1px #9CF; margin: 0 0 130px;">
        <?
		 
		foreach ($results as $row) {
			echo "<strong>".$row["username"]."</strong> - ".$row["datesubmitted"]."<br/>";
			echo "<div style='border-bottom:1px solid  #9CF;'>";
			echo $row["message"];
			echo "</div>";
		}
	echo "</div>";
	
	
	} else {
		echo "No course selected.";
	}
	

}
?>
    <div style="position:fixed;bottom:0px;background:#FFF;width:100%;padding:6px;">    
		<? if($user["userid"] == 0)
        {
            echo '<h3>You Must Login to leave a comment</h3>';
        }
        else {
        ?>
        
        <h3>Leave a comment:</h3>
        <form method="post" action="comment.php?id=<?php echo $_GET["id"]; ?>">
            <input type="hidden" name="courseid" value="<?php echo $_GET["id"]; ?>" />
            <textarea name="message" style="height:50px;width:270px;"></textarea><br />
            <input type="submit" name="Submit">
        </form>
        <? } ?>
    </div>
</div>
</body>
</html>
