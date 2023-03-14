<?php 
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

session_start();
// $helper = new Helper();
// $conn = new Mysql_Driver();

$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection
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

if (isset($_POST["authenticate"])) {
    $email = sanitize_input($_POST["email"]);
    $pwd = $_POST["password"];

    $conn->connect();

    $qry = "SELECT customerId, customerName, customerPwd FROM customer WHERE customerEmail= ?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("s", $email);
	$result = $stmt->execute();
	$stmt->bind_result($customerId, $customerName, $customerPwd);

    // $result1 = $conn->query($qry);

    if ($stmt->fetch()) {
        // Get the correct password from database
        $correctPwd = $customerPwd;
        if (password_verify($pwd, $correctPwd)) {
            // echo 'correct password';
            // Save user info in session variable
            $_SESSION["customerID"] = $customerId;
            $_SESSION["customerName"] = $customerName;

            // Get active shopping cart
            $qry = "SELECT MAX(cartID) AS cartID FROM cart WHERE orderPlaced=0 AND customerID=$_SESSION[customerID]";
			$stmt->close();
			$stmt = $conn->prepare($qry);
			$result = $stmt->execute();
			$stmt->bind_result($cartID);
            //$result2 = $conn->query($qry);

            if ($stmt->fetch() && $cartID) {
                // echo 'cart found';
                $_SESSION["cart"] = $cartID;

                //$qry = "SELECT *, SUM(ci.quantity) AS sumval FROM cartItem ci INNER JOIN cart c ON ci.cartID = c.cartID WHERE c.customerID =$_SESSION[customerID] AND c.cartID =$_SESSION[cart]";
                // $qry = "SELECT SUM(ci.quantity) as sumval from cartItem ci, cart c where ci.cartID = c.cartID and c.customerID = $_SESSION[customerID] and c.cartID = $_SESSION[cart] and c.orderPlaced = 0;";
                $qry = "SELECT SUM(quantity) as sumval from cartItem WHERE cartID = $cartID";
                //$result3 = $conn->query($qry);

                //$row = $conn->fetch_array($result3);
				$stmt->close();
				$stmt = $conn->prepare($qry);
				$result = $stmt->execute();
				$stmt->bind_result($sumVal);
				$stmt->fetch();

                $_SESSION["numCartItem"] = $sumVal;

            }

            $conn->close();
            $pageUrl = $helper->pageUrl('home.php');
            header("Location: $pageUrl");
            exit;

        } else {
            $conn->close();
            $pageUrl = $helper->pageUrl('login.php') . "?login=invalid";
            header("Location: $pageUrl");
            exit;
        }

    }  else {
        $conn->close();
        $pageUrl = $helper->pageUrl('login.php') . "?login=invalid";
        header("Location: $pageUrl");
        exit;
    }
}
?>
