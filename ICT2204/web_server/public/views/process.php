<?php 
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';
session_start();
$helper = new Helper();
$conn = new Mysql_Driver();
date_default_timezone_set("Asia/Singapore");
?>
<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
    <main>
		<?php 
		if($_POST) //Post Data received from Shopping cart page.
		{   
                    if (! isset($_SESSION['csrftoken']) || $_POST['csrf-protection'] !== $_SESSION['csrftoken']) {
                       echo "<div class='container'><h1>Invalid CSRF Token! Please try again! </h1></div>";
                       exit;
                    }
			foreach($_SESSION['Items'] as $key => $item){
				
				$conn->connect();
				$qry = "SELECT * FROM product WHERE productID= $item[productID]";
				$result = $conn->query($qry);
				$row = $conn->fetch_array($result);
				if($row["productQuantity"] < $item["quantity"]){
					echo "Product $item[productId] : $item[name] is out of stock! </br> Please return to shopping cart to amend your purchase. </br>";
					
					exit;
				}
				$conn->close();
			}
			
                        $shippingCost = $_POST["shippingCost"];
                        $_SESSION["ShipCharge"] = $shippingCost;
                        
                        $ExpressDate = strtotime("+1 day");
                        $NormalDate = strtotime("+2 day");
                        
			if ($shippingCost == 0){
				$_SESSION["DeliveryMode"] = "Express";
				$_SESSION["DeliveryDate"] = date("Y-m-d",$ExpressDate);
			} else if ($shippingCost == 10){	
				$_SESSION["DeliveryMode"] = "Express";
				$_SESSION["DeliveryDate"] = date("Y-m-d",$ExpressDate);
                        } else if ($shippingCost == 5){
				$_SESSION["DeliveryMode"] = "Normal";
				$_SESSION["DeliveryDate"] = date("Y-m-d",$NormalDate);
			}
			echo "Total". $_SESSION["SubTotal"];
			echo "Ship". $_SESSION["ShipCharge"];
			
		} else {
                    echo "<div style='color:red'><h1>Error: Checkout failed</h1></div>";
                    exit;
		}
			
                    $conn->connect();


                    $qry = "SELECT productID, quantity FROM cartItem WHERE cartID=$_SESSION[cart]";
                    $result = $conn->query($qry);
                    $qryResult = array();
                    while($row = $conn->fetch_array($result)) {
                            $qryResult[] = array("productId"=>$row["productID"],"currentQuantity"=>$row["quantity"]);
                    }
                    foreach($qryResult as $key => $item){
                            $qry = "UPDATE product SET productQuantity=(productQuantity-$item[currentQuantity]) WHERE productID=$item[productId]";
                            $result = $conn->query($qry);
                    }
                    
                    $_SESSION["total"]= $_SESSION["SubTotal"] + $_SESSION["mytax"] + $_SESSION["ShipCharge"];
                    $qry = "UPDATE cart SET quantity=$_SESSION[numCartItem], orderPlaced=1, subTotal=$_SESSION[SubTotal], shippingCost=$_SESSION[ShipCharge], tax=$_SESSION[mytax], total=$_SESSION[total] WHERE cartID=$_SESSION[cart]";
                    $conn->query($qry);
				
                                
                    $qry = "SELECT * FROM customer WHERE customerID=$_SESSION[customerID]";
                    $result = $conn->query($qry);
                    $row = $conn->fetch_array($result);
                    
                    $ShipName = $row["customerName"];
                    $ShipAddress = $row["customerAddress"];                           
                    $ShipCountry = $row["customerCountry"];
                    $ShipEmail = $row["customerEmail"];
                    $ShipCountry = $row["customerCountry"];
               
                    $qry = "INSERT INTO orderData(shipName, shipAddress, shipCountry, shipEmail, billName, billAddress, billCountry, deliveryMode, deliveryDate, cartID) VALUES('$ShipName','$ShipAddress','$ShipCountry', '$ShipEmail', '$ShipName','$ShipAddress','$ShipCountry', '$_SESSION[DeliveryMode]', '$_SESSION[DeliveryDate]',$_SESSION[cart])";
                    $conn->query($qry);

                    $qry = "SELECT LAST_INSERT_ID() as orderID";
                    $result = $conn->query($qry);
                    $row = $conn->fetch_array($result);
                    $_SESSION["orderID"] = $row["orderID"];

                    $conn->close();

                    $_SESSION["numCartItem"] = 0;

                    //unset($_SESSION["cart"]);

                    header("Location: orderconfirmed.php");
                    exit;
				//}else {
					/*echo "<div style='color:red'><b>GetTransactionDetails failed :  </b>".
									urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
					echo "<pre>";
					echo print_r($httpParsedResponseAr);
					echo "</pre>";
					
					$conn->close();
				}
			}
			else {
				echo "<div style='color:red'><b>DoExpressCheckoutPayment failed :  </b>".
								urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
				echo "<pre>";
				echo print_r($httpParsedResponseAr);
				echo "</pre>";
			}
		}*/

		?>
	</main>
    <?php include $helper->subviewPath('footer.php') ?>
</html>
