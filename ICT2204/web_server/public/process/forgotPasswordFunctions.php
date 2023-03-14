<?php 
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

session_start();
//$helper = new Helper();
//$conn = new Mysql_Driver();

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

// Create an object for MySQL database access
$conn = new Mysql_Driver();
// Connect to the MySQL database
$conn->connect();

if (isset($_POST["request-security-qsn"])) {

    // Get input data
    $email = sanitize_input($_POST["email"]);

    // validation if email exist

    // Connect to the MySQL database
    // $conn->connect();

    // Check for duplicate email
    $qry = "SELECT customerPwdQn, customerEmail FROM customer WHERE customerEmail = ?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($pwdQn, $customerEmail);
    $stmt->fetch();
    $stmt->close();
    // $result = $conn->query($qry);

    if (!$pwdQn) {
        $success = false;
        $message_body .= "<p>- Email provided is not valid! Please provide an existing one.</p>";
    }

    if ($success) {

        // $row = $conn->fetch_array($result);

        // $_SESSION['customerPwdQn'] = $row['customerPwdQn'];
        // $_SESSION['customerEmail'] = $row['customerEmail'];
        $_SESSION['customerPwdQn'] = $pwdQn;
        $_SESSION['customerEmail'] = $customerEmail;
        
        $conn->close();
        $pageUrl = $helper->pageUrl('securityQuestion.php');
        header("Location: $pageUrl");

    } else {
        $message_title = "<h1 class='page-title'>Password Reset Failed</h1>";
        $message_link = "<a href='javascript:history.back()'>Go Back</a>";
    }

} else if (isset($_POST["submit-security-qsn"])) {
    // retrieve email and answer
    $email = sanitize_input($_POST["email"]);
    $pwdanswer = sanitize_input($_POST["pwdanswer"]);

    // check if the security answer is correct
    // $conn->connect();

    // Check if the password answer is the same
    $qry = "SELECT customerPwdAns, customerID FROM customer WHERE customerEmail=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($customerPwdAns, $customerID);
    $stmt->fetch();
    $stmt->close();
    // $result = $conn->query($qry);
    // $row = $conn->fetch_array($result);

    if ($customerPwdAns != $pwdanswer) {
        $success = false;
        $message_body .= "<p>- Security Answer is not correct! Please try again</p>";
    }

    if ($success) {
        // reset the password and send the email
        // $customerId = $customerID;

        // Random password generator
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789!@#$%^&*()'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $newPassword = '';
        foreach (array_rand($seed, 8) as $k) {
            $newPassword .= $seed[$k];
        }
        
        $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the user account with new password
		$qry = "UPDATE customer SET customerPwd='$newPasswordHashed' 
				WHERE customerID=$customerID";
        $conn->query($qry);
        
        $message_title = "<h1 class='page-title'>Password Reset Success</h1>";
        $message_body .= "<p>Acccount Password has been reset! New Password: $newPassword</p>";
        $pageUrl = $helper->pageUrl('login.php');
        $message_link = "<a href='$pageUrl'>Log In</a>";

    } else {
        $message_title = "<h1 class='page-title'>Password Reset Failed</h1>";
        $pageUrl = $helper->pageUrl('forgotPassword.php');
        $message_link = "<a href='$pageUrl'>Try Again</a>";
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