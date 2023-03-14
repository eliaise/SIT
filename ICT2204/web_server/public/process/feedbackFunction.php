<!DOCTYPE html>
<?php 
session_start();
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

$helper = new Helper(); 

// verify if user is logged in
if (!isset($_SESSION["customerID"])) {
    $pageUrl = $helper->pageUrl('login.php');
    header ("Location: $pageUrl");
    exit;
}

$success = true;
$message_title = "";
$message_body = "";
$message_link = "";

function sanitize_input($data)
{ 
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

//Read the data input from previous page
$subject = sanitize_input($_POST["subject"]);
$content = sanitize_input($_POST["content"]);
$rank = sanitize_input($_POST["rating"]);
$dateTimeCreated = date("Y-m-d H:i:s");

// Create an object for MySQL database access
$conn = new Mysql_Driver();
// Connect to the MySQL database
$conn->connect();

// Check if feedback is already created for today

$qry = "SELECT f.feedbackID FROM feedback f INNER JOIN customer c ON f.customerID = c.customerID WHERE DATE(f.feedbackTimestamp) = CURDATE() AND c.customerID = $_SESSION[customerID]";

$result = $conn->query($qry);

if ($conn->num_rows($result) > 0) {
    $success = false;
}

if ($success) {
    $qry = "INSERT INTO feedback (customerID, feedbackSubject, feedbackContent, feedbackRanking, feedbackTimestamp) VALUES ('$_SESSION[customerID]', ?, ?, ?, '$dateTimeCreated')";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("ssi", $subject, $content, $rank);
	$stmt->execute();

    //$conn->query($qry);

    $message_title = "<h1 class='page-title'>Feedback Submitted</h1>";
    $message_body = "<p>Your Feedback has been submitted Successfully! You can now see it in the review section at the home page.<p>";
    $pageUrl = $helper->pageUrl('home.php');
    $message_link = "<a href=". $pageUrl ." >Home</a>";
}
else {

    $message_title = "<h1 class='page-title'>Feedback Submission Failed</h1>";
    $message_body = "<p>Sorry, you can only submit one feedback a day. Please try again tomorrow!<p>";
    $pageUrl = $helper->pageUrl('home.php');
    $message_link = "<a href=". $pageUrl ." >Home</a>";
}

$conn->close();
?>
<html lang="en">
<?php include $helper->subviewPath('header.php') ?>
<main class="container text-center">
    <?php 
        echo $message_title;
        echo $message_body;
        echo $message_link; 
    ?>
</main>
<?php include $helper->subviewPath('footer.php') ?>
</html>
