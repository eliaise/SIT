<!DOCTYPE html>
<?php 
session_start ();
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

// $helper = new Helper();
// $conn = new Mysql_Driver();

// $conn->connect();

$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection
$helper = new Helper();

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

if (!isset($_SESSION["customerID"])) {
    $pageUrl = $helper->pageUrl('login.php');
    header ("Location: $pageUrl");
    exit;
}

if (isset($_POST["update-personal"])) {

    // Get input data
    $name = sanitize_input($_POST["name"]);
    $dob = sanitize_input($_POST["dob"]);
    $address = sanitize_input($_POST["address"]);
    $country = sanitize_input($_POST["country"]);
    $phone = sanitize_input($_POST["phone"]);

    // validation of fields TO-DO:

    if ($success) {
        
        $qry = "UPDATE customer SET customerName = ?, customerDOB = ?, customerAddress = ?, customerCountry = ?, customerNo = ? WHERE customerID = $_SESSION[customerID]";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("sssss", $name, $dob, $address, $country, $phone);
		$stmt->execute();

        // $conn->query($qry);

        $message_title = "<h1 class='page-title'>Update Profile Successful</h1>";
        $message_body = "<p>Your Account Personal Information has been successfully updated!</p>";
        $pageUrl = $helper->pageUrl('home.php');
        $message_link = "<a href=". $pageUrl ." >Home</a>";
        
        $_SESSION["customerName"] = $name;

    } else {

        $message_title = "<h1 class='page-title'>Update Profile Failed</h1>";
        $message_link = "<a href='javascript:history.back()'>Go Back</a>";
    }

} else if (isset($_POST["update-email"])) {

    // Get input data
    $email = sanitize_input($_POST["email"]);
    $currPwd = $_POST["password"];

    // validation of fields:

    // get current details of the customer

    $qry = "SELECT * FROM customer WHERE customerID = $_SESSION[customerID]";

    $result = $conn->query($qry);

    $row = $conn->fetch_array($result);

    // check if current pwd is correct
    
    if (!(password_verify($currPwd, $row["customerPwd"]))) {
        $success = false;
        $message_body .= "<p>- Current Password is incorrect</p>";
    }
    
    // check if new email is unique

    $qry = "SELECT customerID, customerEmail FROM customer WHERE customerEmail = ?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("s", $email);
	$result = $stmt->execute();
	$stmt->bind_result($customerID, $customerEmail);

    // $result = $conn->query($qry);

    if ($stmt->fetch()) {
        // check if the used email is not the current user's email
        if ($customerID !== $_SESSION["customerID"]) {
            $success = false;
            $message_body .= '<p>- New email provided is already in use!</p>';
        }
    }

    if ($success) {
        $qry = "UPDATE customer SET customerEmail = ? WHERE customerID = $_SESSION[customerID]";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("s", $email);
		$stmt->execute();
        // $conn->query($qry);
        $message_title = "<h1 class='page-title'>Update Profile Successful</h1>";
        $message_body = "<p>Your Account Email has been successfully updated!</p>";
        $pageUrl = $helper->pageUrl('home.php');
        $message_link = "<a href=". $pageUrl ." >Home</a>";

    } else {
        $message_title = "<h1 class='page-title'>Update Profile Failed</h1>";
        $message_link = "<a href='javascript:history.back()'>Go Back</a>";
    }

} else if (isset($_POST["update-password"])) {

    // Get input data
    $newPwd = $_POST["newPassword"];
    $cfmNewPwd = $_POST["cfmNewPassword"];
    $currPwd = $_POST["password"];

    // validation of fields

    // get current details of the customer
    $qry = "SELECT * FROM customer WHERE customerID = $_SESSION[customerID]";

    $result = $conn->query($qry);

    $row = $conn->fetch_array($result);
    
    // check if both new passwords match
    if (!($newPwd === $cfmNewPwd)) {
        $success = false;
        $message_body .= '<p>- Passwords do not match!</p>';
    }
    
    // check if current pwd is correct
    if (!(password_verify($currPwd, $row["customerPwd"]))) {
        $success = false;
        $message_body .= '<p>- Current Password is incorrect!</p>';
    }

    if ($success) {
        $hashedNewPwd = password_hash($newPwd, PASSWORD_DEFAULT);
        $qry = "UPDATE customer SET customerPwd = '$hashedNewPwd' WHERE customerID = $_SESSION[customerID]";
        $conn->query($qry);
        $message_title = "<h1 class='page-title'>Update Profile Successful</h1>";
        $message_body = "<p>Your Account Password has been successfully updated!</p>";
        $pageUrl = $helper->pageUrl('home.php');
        $message_link = "<a href=". $pageUrl ." >Home</a>";
    } else {

        $message_title = "<h1 class='page-title'>Update Profile Failed</h1>";
        $message_link = "<a href='javascript:history.back()'>Go Back</a>";
    }
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
