<!DOCTYPE html>
<?php 
session_start();
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

$helper = new Helper(); 

if (isset($_SESSION["customerID"])) {
    $pageUrl = $helper->pageUrl('home.php');
    header ("Location: $pageUrl");
    exit;
}
function sanitize_input($data)
{ 
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$success = true;
$message_title = "";
$message_body = "";
$message_link = "";

//Read the data input from previous page
$name = sanitize_input($_POST["name"]);
$dob = sanitize_input($_POST["dob"]);
$address = sanitize_input($_POST["address"]);
$country = sanitize_input($_POST["country"]);
$phone = sanitize_input($_POST["phone"]);
$email = sanitize_input($_POST["email"]);
$cc = sanitize_input($_POST["cc"]);
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
$pwdquestion = sanitize_input($_POST["pwdquestion"]);
$pwdanswer = sanitize_input($_POST["pwdanswer"]);
$activestatus = 1;
$dateentered = date("Y-m-d H:i:s");

// Create an object for MySQL database access
$conn = new Mysql_Driver();
// Connect to the MySQL database
$conn->connect();

// Check for duplicate email
$qry = "SELECT count(*) FROM customer WHERE customerEmail = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
//$result = $conn->query($qry);
if ($count > 0 ) {
    $success = false;
    $message_body .= "<p>- Email is already in use!</p>";
}

if($success){ 
    //Define the INSERT SQL statement
    $qry = "INSERT INTO customer (customerName, customerDOB, customerAddress, customerCountry, customerNo, customerEmail, customerCC, customerPwd, customerPwdQn, customerPwdAns, customerStatus, customerJoinDate)
	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("ssssssssssss", $name, $dob, $address, $country, $phone, $email, $cc, $password, $pwdquestion, $pwdanswer, $activestatus, $dateentered);
	$result = $stmt->execute();
    // VALUES ('$name', '$dob', '$address', '$country' , '$phone', '$email', '$cc', '$password', '$pwdquestion', '$pwdanswer', $activestatus, '$dateentered')";
    // Execute the SQL statement
    //$result = $conn->query($qry);
	$stmt->close();
	
    if ($result) {
        $qry = "SELECT LAST_INSERT_ID() AS customerID";
        $result = $conn->query($qry);
        // Save the customer ID in a session variable
        $row = $conn->fetch_array($result);
        $_SESSION["customerID"] = $row["customerID"];
        $_SESSION["customerName"] = $name; 
        
        
        $escapedname = htmlspecialchars($name);
        $message_title = "<h1 class='page-title'>Registration Successful!</h1>";
        $message_body = "<p>Welcome, $escapedname<p>";
        $pageUrl = $helper->pageUrl('home.php');
        $message_link = "<a href=". $pageUrl ." >Home</a>";

    } else {
        // $pageUrl = $helper->pageUrl('register.php') . '?register=failed';
        // header("Location: $pageUrl");
        // exit;
        $message_title = "<h1 class='page-title'>Registration Failed</h1>";
        $message_body = "<p>Server error occured! Please try again<p>";
        $message_link = "<a href='javascript:history.back()'>Go Back</a>";
    }
}
else {

    $message_title = "<h1 class='page-title'>Registration Failed</h1>";
    $message_link = "<a href='javascript:history.back()'>Go Back</a>";
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
