<!DOCTYPE html>
<?php 
    session_start();
    include_once __DIR__.'/../helpers/mysql.php';
    include_once __DIR__.'/../helpers/helper.php';

    $helper = new Helper();
    $db = new Mysql_Driver();
    $db->connect();
    $sql = "SELECT * FROM product ORDER BY RAND() LIMIT 3"; // this is the sql query for this loop

//    $sql = "SELECT * FROM Product WHERE Offered = 1 AND OfferStartDate < NOW() AND OfferEndDate > NOW() ORDER BY RAND() LIMIT 3"; // this is the sql query for this loop
    $result = $db->query($sql);// connect to the database to get the info
    $resultArray = []; // instantiate empty array 
    while ($row = $db->fetch_array($result)) { // while loop based on connection 
        $resultArray[] = $row; // append results into the array
    }

    $sql2 = "SELECT * FROM customer WHERE customerID=". $_SESSION["customerID"];
    $result2 = $db->query($sql2);// connect to the database to get the info
    $resultArray2 = $db->fetch_array($result2); // instantiate empty array 

    $sql3 = "SELECT * FROM orderData WHERE orderID=". $_SESSION["orderID"];
    $result3 = $db->query($sql3);// connect to the database to get the info
    $resultArray3 = $db->fetch_array($result3); // instantiate empty array 
    
?>

<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
    <main class="container">
    <?php if(isset($_SESSION["orderID"])): ?>
        <div class="ordercfm-title">
            <h2><b>Order Details:</b></h2>
        </div>
        <section class="display-ordertext">
            <p>Checkout successful. Thank you for shopping with us.</p>

            <?php if ($_SESSION["ShipCharge"] == 5){?>
                <p>[Standard Shipping] Your item will be delivered in about 2 working days.</p>
            <?php } else {?>
                <p>[Express Shipping] Your item will be delivered in less than 24 hours.</p>
            <?php }?>

            <b>Your items will be delivered to:</b>
            <p><?php echo htmlspecialchars($resultArray3["shipAddress"]); ?></p>
            
            <p>Your order number is <b>#<?php echo "$_SESSION[orderID]"?>.</p>

            <p>Your order was made on <b><?php echo $resultArray3['dateOrdered']?>.</p>
        </section>
    <?php endif ?>
        <section class="display-ordertable">
            
            <?php if (isset($_SESSION["cart"])) :
		$cartqry = "SELECT productID, name, price, quantity, (price*quantity) AS total FROM cartItem WHERE cartID=$_SESSION[cart];";
		$cartresult = $db->query($cartqry);
                //$cartresultarray = $db->fetch_array($cartresult);
		if ($db->num_rows($cartresult) > 0) : ?>
		
            
                    <div class='table-responsive' >
			<table class='table table-hover order-table'>
			<thead class='order-header'>
			<tr>
			<th width='40px'>Product ID</th>
			<th width='250px'>Name</th>
			<th width='90px'>Price (S$)</th>
			<th width='60px'>Quantity</th>
			<th width='120px'>Total (S$)</th>
			</tr>
			</thead>
			
			<tbody class="order-body" style="background-color:#DCDCDC;">
                            
            <?php while($row = $db->fetch_array($cartresult)):?>
                <tr>
                    <td><?php echo $row["productID"]?></td>
                    <td><?php echo $row["name"]?></td>	
                    <td><?php echo number_format($row["price"],2)?></td>
                    <td><?php echo $row[quantity]?></td>	
                    <td><?php echo number_format($row["total"],2)?></td>
                </tr>
            <?php endwhile ?>
            <?php endif ?>
            <?php
                unset($_SESSION["Items"]); //clear items
                unset($_SESSION["cart"]);
            ?>
			</tbody>
			</table>
			</div>
            <div>
                <table style="float:right;">
                    <tbody>
                        <tr>
                            <td>
                                <p style='text-align:right';>
                                    SubTotal: 
                                    <strong>S$ </strong>
                                </p>
                            </td>
                            <td>
                                <p style='text-align:right;'><span style='margin-bottom:0px'><strong><?php echo number_format($_SESSION["SubTotal"],2)?></strong></span</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style='text-align:right';>
                                    Shipping Charge: 
                                    <strong>S$ </strong>
                                </p>
                            </td>
                            <td>
                                <p style='text-align:right;'><span style='margin-bottom:0px'><strong><?php echo number_format($_SESSION["ShipCharge"],2)?></strong></span</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style='text-align:right';>
                                    Tax: 
                                    <strong>S$ </strong>
                                </p>
                            </td>
                            <td>
                                <p style='text-align:right;'><span style='margin-bottom:0px'><strong><?php echo number_format($_SESSION["mytax"],2)?></strong></span</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h3 style='text-align:right';>
                                    Total: 
                                    <strong>S$ </strong>
                                </h3>
                            </td>
                            <td>
                                <h3 style='text-align:right;'><span style='margin-bottom:0px'><strong><?php echo number_format($_SESSION['total'],2)?></strong></span></h3>
                            </td>
                        </tr>
                    </tbody>
                </table>                                                     
            </div>
        </section>
        <br><br><br><br><br><br><br><br><br>
        <section class="rec-products">
            <h1 class="page-title text-center mt-4">Here are some other products you might be interested in:</h1>
            <div class="row">
            <?php foreach ($resultArray as $order => $row): ?>
                    <div class="col-md-6 col-lg-4 mb-5 text-center">
                    <?php $productUrl = $helper->pageUrl('productDetails.php') . '?pid=' . $row['productID'] ?>
                        <a href="<?php echo $productUrl ?>" class="card text-dark p-5" style="text-decoration: none;">
                            <img class="img-fluid" src="<?php echo $helper->imgPath($row["productImage"], 'Products'); ?>">
                            <p class="h6 title"><?php echo $row["productTitle"] ?></p>
                            <!-- if offer -->
                            <?php if ($row['productOffer'] == 1){?>
                                <p class="h5 offer-price" style="color: #348FDE;">S$ <?php echo number_format($row["productOfferPrice"], 2) ?></p>
                                <p class="usual-price"><del>S$ <?php echo number_format($row["productPrice"], 2) ?></del></p>
                            <?php } elseif ($row['productOffer'] == 0){?>
                            <!-- else if no offer -->
                               <p class="h5 usual-price" style="color: #348FDE;">S$ <?php echo number_format($row["productPrice"], 2) ?></p>
                            <?php }?>
                        </a>
                    </div>
            <?php endforeach ?>
            </div>
        </section>
        <div class="cont-shop">
            <a class="cont-shopping" href="<?php echo "/home.php"?>">Continue Shopping</a>
        </div>
	<?php endif;?>
    </main>
    <?php include $helper->subviewPath('footer.php') ?>
<style>
.cont-shopping {
  background-color: white; 
  color: black; 
  border: 2px solid #008CBA;
  padding: 16px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
  cursor: pointer;
}

.cont-shopping:hover {
  background-color: #008CBA;
  color: white;
}

.cont-shop{
    text-align:center;
}

.order-header {
	/*background-color: #0040FF;*/
	background-color: #017CA7;
	color: white;
}

.order-subtotal{
    text-align:right;
}

.ordercfm-title{
    text-align:center;
}
</style>
</html>
