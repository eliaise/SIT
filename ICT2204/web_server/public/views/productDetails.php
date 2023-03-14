
<?php 
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';
$helper = new Helper();
session_start();
// verify if user is logged in
if (!isset($_SESSION["customerID"])) {
    $pageUrl = $helper->pageUrl('login.php');
    header ("Location: $pageUrl");
    exit;
    }
$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection
$pid=$_GET["pid"]; // Read Product ID from query string
$qry = "SELECT * from product where productID=$pid" ;
$result = $conn->query($qry); // Execute the SQL statement
$quantity = 0;
?>
<html lang="en">
	<?php include $helper->subviewPath('header.php') ?>
	<main class="container">
	<div style='width:90%; margin:auto;'>
	<?php
	while ($row = $conn->fetch_array($result)):
		$quantity = $row["productQuantity"];
	?>
		<div class='row'>
			<div class='col-sm-12' style='padding: 5px'>
				<span class='page-title'><?php echo "$row[productTitle]"?></span>
			</div>
		</div>
		<div class='row'>
			<div class='col-sm-9' style='padding:5px'>
				<p><?php echo "$row[productDesc]"?></p>
				<?php $qry = "SELECT s.specName, ps.specVal from productSpec ps INNER JOIN specification s ON ps.specID=s.specID WHERE ps.productID =$pid ORDER BY ps.priority";
				$result2 = $conn->query($qry);
					while ($row2 = $conn->fetch_array($result2)){
						echo $row2["specName"].":  ".$row2["specVal"]."<br />";
					}
				?>
			</div>
			<div class='col-sm-3' style=' vertical-align: top; padding:5px'>
				<p><img class="img-fluid" src="<?php echo $helper->imgPath($row["productImage"], 'Products'); ?>" /></p>
				<?php $formattedPrice = number_format ($row["productPrice"], 2); 
				$formattedDiscPrice = number_format ($row["productOfferPrice"], 2); 
				?>
				<?php if($row["productOffer"] == 1 && $row["productOfferStartDate"] < date("Y-m-d") AND $row["productOfferEndDate"] > date("Y-m-d")):?>
						Price : <span style='color:red;'><del>
						S$<?php echo $formattedPrice?></del></span> <br/>
						<span style="color:red">ON OFFER: <strong>S$<?php echo $formattedDiscPrice?></strong></span>
				<?php elseif($quantity > 0):?>
					Price: <span style='font-weight: bold; color:red; '> S$ <?php echo $formattedPrice?></span> 
				<?php else:?>
					Price: <span style='color:red;'><del> S$ <?php echo $formattedPrice?></del></span> <span style="font-weight: bold;color:red"> Out of Stock! </span>
				<?php endif;?>
				<br/>Quantity Left: <span style='color:red;'><?php echo $row["productQuantity"]?></span>

	<?php endwhile?>
					
				<form action='../process/cart-functions.php' method='post'>
					<input type='hidden' name='actionA' value='add'/>
					<input type='hidden' name='product_id' value='<?php echo $pid?>'/>

				<?php if($quantity > 0):?>
					<label for="quantityField">Quantity:</label>
						<input type='number' id="quantityField" name='quantity' style='width:40px' value='1' min='1' max='10' required/>
					<button type='submit'>Add to Cart</button>
				<?php else:?>
					<label for="quantityField">Quantity:</label>
						<input disabled type='number' id="quantityField" name='quantity' style='width:40px' value='1' min='1' max='10' required/>
						<button disabled type='submit'>Add to Cart</button>
				<?php endif?>

				</form>
			</div>
			
	</div>
	<?php $conn->close(); ?>
	</div>
    </main>
    <?php include $helper->subviewPath('footer.php') ?>
</html>