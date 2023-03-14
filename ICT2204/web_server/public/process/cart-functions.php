<?php 
session_start();
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

//$helper = new Helper();

$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection
$helper = new Helper();
 
date_default_timezone_set("Asia/Singapore");
// Check if user logged in 
if (! isset($_SESSION["customerID"])) {
	// redirect to login page if the session variable shopperid is not set
	header ("Location: ".$helper->pageUrl("login.php"));
	exit;
}

//$conn = new Mysql_Driver();

function sanitize_input($data)
{ 
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (isset($_POST['actionA']))
{
	// TO DO 1
	// Write code to implement: if a user clicks on "Add to Cart" button, insert/update the 
	// database and also the session variable for counting number of items in shopping cart.
	// $conn->connect();
	// Check if a shopping cart exist, if not create a new shopping cart
	if (! isset($_SESSION["cart"])) {
		// Create a shopping cart for the customer
		$qry = "INSERT INTO cart(customerID) VALUES ($_SESSION[customerID])";
		$conn->query($qry);
		$qry = "SELECT LAST_INSERT_ID() AS cartID" ;
		$result = $conn->query($qry);
		$row = $conn->fetch_array($result);
		$_SESSION["cart"] = $row["cartID"];
	}
	// If the ProductID exists in the shopping cart,
	// update the quantity, else add the item to the Shopping Cart.
	$pid = sanitize_input($_POST["product_id"]);
	$check = 1;
	$quantity = sanitize_input($_POST["quantity"]);
	
	$qry = "SELECT count(*) from cartItem WHERE cartID=$_SESSION[cart] AND productID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("d", $pid);
	$result = $stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();
	
	// $result = $conn->query($qry);
	$addNewItem = 0;
	if ($count > 0) {
		// update quantity
		$qry = "SELECT productQuantity FROM product WHERE productID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("d", $pid);
		$result = $stmt->execute();
		$stmt->bind_result($productQuantity);
		$stmt->fetch();
		// $result = $conn->query($qry);
		
		if ($stmt->fetch()) {
			$stmt->close();
			// check if the stock is enough
			// $row = $conn->fetch_array($result);
			if($productQuantity >= $quantity){
				$qry = "UPDATE cartItem SET quantity = quantity + ? WHERE cartID=$_SESSION[cart] AND productID=?";
				// $conn->query($qry);
				// $addNewItem = $quantity;
				$stmt = $conn->prepare($qry);
				$stmt->bind_param("dd", $quantity, $pid);
				$stmt->execute();
				$stmt->close();
				$check = 0;
			}
			else{
				echo "<script type='text/javascript'>alert('Invalid Quantity!');history.go(-1);</script>";
			}
		}
	}
	else {
		// insert into cart
		$qry = "SELECT productTitle, productQuantity, productOffer, productOfferStartDate, productOfferEndDate, productOfferPrice, productPrice FROM product WHERE productID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("d", $pid);
		$result = $stmt->execute();
		$stmt->bind_result($productTitle, $productQuantity, $productOffer, $productOfferStartDate, $productOfferEndDate, $productOfferPrice, $productPrice);
		// $result = $conn->query($qry);
		if ($stmt->fetch()) {
			$stmt->close();
			// $row = $conn->fetch_array($result);
			$productname = $productTitle;
			if($productQuantity >= $quantity){
				if($productOffer == 1 && $productOfferStartDate < date("Y-m-d") AND $productOfferEndDate > date("Y-m-d")){
					$price = $productOfferPrice;
				}
				else{
					$price = $productPrice;
				}	
				$qry = "INSERT INTO cartItem(cartID, productID, price, name, quantity) VALUES ($_SESSION[cart], ?, ?, ?, ?)";
				$stmt = $conn->prepare($qry);
				$stmt->bind_param("iisi", $pid, $price, $productname, $quantity);
				$result = $stmt->execute();
				// $conn->query($qry);
				$addNewItem = $quantity;
				$check = 0;
			}
			else{
				echo "<script type='text/javascript'>alert('Invalid Quantity!');history.go(-1);</script>";
			}

		}
	}
	
	
  	$conn->close();
	
  	// Update session variable used for counting number of items in the shopping cart.
	if (isset($_SESSION["numCartItem"])) {
		$_SESSION["numCartItem"] = $_SESSION["numCartItem"] + $addNewItem;
	}
	else{
		$_SESSION["numCartItem"] = 1;
	}
	
	// Redirect shopper to shopping cart page
	if($check == 0){
		header ("Location: ".$helper->pageUrl("shoppingcart.php"));
		exit;
	}
}


if (isset($_POST['actionU']))
{
	// TO DO 2
	// Write code to implement: if a user clicks on "Update" button, update the database
	// and also the session variable for counting number of items in shopping cart.
	$cartid = $_SESSION["cart"];
	$pid = sanitize_input($_POST["product_id"]);
	$quantity = sanitize_input($_POST["quantity"]);
	$check = 1;
	$updateItem = 0;
	$currentItem = 0;
	$updatedItem = 0;
	//$conn->connect();
	$qry = "SELECT productQuantity FROM product WHERE productID=?";
	// $result = $conn->query($qry);
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("d", $pid);
	$result = $stmt->execute();
	$stmt->bind_result($productQuantity);
	
	if ($stmt->fetch()) {
		$stmt->close();
		//$row = $conn->fetch_array($result);
		if($productQuantity >= $quantity){
			$qry = "SELECT quantity FROM cartItem WHERE productID=? AND cartID=$cartid";
			//$result = $conn->query($qry);
			//$row = $conn->fetch_array($result);
			//$currentItem = $row["quantity"];
			$stmt = $conn->prepare($qry);
			$stmt->bind_param("d", $pid);
			$result = $stmt->execute();
			$stmt->bind_result($quantity);
			$stmt->close();
			
			$qry = "UPDATE cartItem SET quantity = $quantity WHERE productID=? AND cartID=$cartid";
			$stmt = $conn->prepare($qry);
			$stmt->bind_param("d", $pid);
			$result = $stmt->execute();
			$stmt->close();
			//$conn->query($qry);
			//$conn->close();	
			$updateItem = $quantity;
			$check = 0;
			$updatedItem = $updateItem - $currentItem;
		}
		else{
			echo "<script type='text/javascript'>alert('Not enough stocks left!');history.go(-1);</script>";
		}
		
	}
	
	$conn->close();
	
	// Update session variable used for counting number of items in the shopping cart.
	if (isset($_SESSION["numCartItem"])) {      
		$_SESSION["numCartItem"] = $_SESSION["numCartItem"] + $updatedItem;
	}
	if($check == 0){
		header ("Location: ".$helper->pageUrl("shoppingcart.php"));
		exit;
	}
}

if (isset($_POST['actionR']))
{
	$cartid = $_SESSION["cart"];
	$pid = sanitize_input($_POST["product_id"]);
	//$conn->connect();
	$qry = "DELETE FROM cartItem WHERE cartID=$cartid AND productID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("d", $pid);
	$result = $stmt->execute();
	$stmt->close();
	
	$qry = "SELECT SUM(quantity) AS sumval FROM cartItem WHERE cartID=$cartid AND productID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("d", $pid);
	$result = $stmt->execute();
	$stmt->bind_result($sum);
	$stmt->fetch();
	$stmt->close();
	//$result = $conn->query($qry1);
	//$row = $conn->fetch_array($result);
	//$conn->query($qry);
	$conn->close();	
	$_SESSION["numCartItem"] = $_SESSION["numCartItem"] - $sum;
	
	header ("Location: ".$helper->pageUrl("shoppingcart.php"));
	exit;
}		
?>

