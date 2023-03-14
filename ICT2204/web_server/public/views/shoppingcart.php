<?php 

include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

session_start();
$helper = new Helper();
$conn = new Mysql_Driver();
$conn->connect(); 
$seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789!@#$%^&*()'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $csrftoken = '';
        foreach (array_rand($seed, 16) as $k) {
            $csrftoken .= $seed[$k];
        }
$_SESSION['csrftoken'] = $csrftoken
?>
<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
	<?php // Check if user logged in 
		if (! isset($_SESSION["customerID"])) {
			// redirect to login page if the session variable shopperid is not set
			header ("Location: login.php");
			exit;
		}
	?>
    <main class="container">
	<?php if (isset($_SESSION["cart"])) :
		$qry = "SELECT productID, name, price, quantity, (price*quantity) AS total FROM cartItem WHERE cartID=$_SESSION[cart]";
		$result = $conn->query($qry);
		if ($conn->num_rows($result) > 0) : ?>
			<p class='page-title' style='text-align:center'>Shopping Cart</p>
			<div class='table-responsive' >
			<table class='table table-hover'>
			<thead class='cart-header'>
			<tr>
			<th style='vertical-align: top;' width='40px'>Product ID</th>
			<th style='vertical-align: top;' width='250px'>Name</th>
			<th style='vertical-align: top;' width='90px'>Price (S$)</th>
			<th style='vertical-align: top;' width='60px'>Quantity</th>
			<th style='vertical-align: top;' width='120px'>Total (S$)</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			</tr>
			</thead>
			
			<?php //$_SESSION["items"] = array();?>
			
			<tbody style="background-color:#DCDCDC;">
			<?php while($row = $conn->fetch_array($result)): ?>
				<tr>
				<td><?php echo "$row[productID]"?></td>
				<td><?php echo "$row[name]"?></td>
				<?php $formattedPrice = number_format($row["price"], 2); ?>
				<td><?php echo $formattedPrice?></td>
				<form action='../process/cart-functions.php' method='post'>
				<td>
				<input type='number' name='quantity' style='width:40px' value=<?php echo "$row[quantity]"?> min='1' max='10' required />
				</td> 
				<?php $formattedTotal = number_format($row["total"], 2);?>
				<td><?php echo $formattedTotal?></td>
				<td>
				<input type='hidden' name='actionU' value='update' />
				<input type='hidden' name='product_id' value=<?php echo $row["productID"]?>></input>
				<button type='submit'>Update</button>
				</td>
				</form>
				<form action='../process/cart-functions.php' method='post' >
				<td>
				<input type='hidden' name='actionR' value='remove' />
				<input type='hidden' name='product_id' value=<?php echo $row["productID"]?>></input>
				<button type='submit'>Remove</button>
				</td>
				</form>
				</tr>
                                
			<?php
                        /*foreach ($_SESSION["Items"] as $myitem => $myvalue{
                            if ($_SESSION["Items"][$myitem]['productID'] == $row["productID"]){
                                
                            }
                        }
                        endforeach;*/
                        $_SESSION["Items"][] = array("productID"=>$row["productID"],"name"=>$row["name"],"price"=>$row["price"],"quantity"=>$row["quantity"],"total"=>$row["total"]);?>

			<?php endwhile?>
			</tbody>
			
			</table>
			</div>
			
			<?php $qry = "SELECT SUM(price*quantity) as subTotal FROM cartItem WHERE cartID=$_SESSION[cart]";
			$result = $conn->query($qry);
			$row = $conn->fetch_array($result); ?>
			<div class="charges">
			
                            <span>
                            <p style='text-align:left; float:left; '>*Enjoy free delivery on purchases over $500!</p>
                            <?php $qry = "SELECT SUM(price*quantity) as subTotal FROM cartItem WHERE cartID=$_SESSION[cart]";
					$result = $conn->query($qry);
					$row = $conn->fetch_array($result); ?>
                            
                            <?php if($row["subTotal"] > 500): $_SESSION["shippingCost"] = 0; ?>
                                <p style='text-align:left; float:right; '>Your order is over $500, free express shipping has been applied!</p>
                                <input form='checkout' type='hidden' id="shippingCost" name="shippingCost" value='0' required/>
                            <?php else: $_SESSION["shippingCost"] = 5;?>
                                <div class='shippingoptions' style='float:right;'>
                                    <input form='checkout' type='radio' onchange="calVal()" id="shippingCost" name="shippingCost"  value='5' checked required/>  Standard Shipping (S$ 5.00)</input><br>
                                    <input form='checkout' type='radio' onchange="calVal()" id="shippingCost" name="shippingCost"  value='10' required/>  Express Shipping (S$ 10.00)</input>
                                </div>
                                <br>
                            <?php endif?>
                            </span><br>
				<form id='checkout' method='post' action='process.php'>
                                    <table style='text-align:right; float:right;'>
                                        <tbody>						
                                            <tr>
                                                <td>
                                                    <p style='text-align:right; margin-bottom:0px';>
                                                        Subtotal (<?php echo $_SESSION["numCartItem"]?> items): 
                                                        <strong>S$</strong>
                                                    </p>
                                                </td>
                                                <td>
                                                    <p><span style='margin-bottom:0px'><strong><?php echo number_format($row["subTotal"],2)?> </strong></span></p> 
                                                </td>
                                            </tr>
				
                                        
                                            <tr>
                                                <td>
                                                    <p style='text-align:right;margin-bottom:0px';>
                                                        Delivery Charge: 
                                                        <strong>S$</strong>
                                                    </p>
                                                </td>
                                                <td>
                                                    <p><span id='shippingVal' style='margin-bottom:0px'><strong>
                                                        <?php echo number_format($_SESSION["shippingCost"],2)?></strong></span>
                                                    </p>
                                                </td>
                                            </tr>
					<br/>
					<?php $_SESSION["SubTotal"] = $row["subTotal"]; 
					$taxqry = "SELECT * FROM gst WHERE effectiveDate < NOW() ORDER BY gstID DESC LIMIT 1";
					$taxresult = $conn->query($taxqry);
					$tax = $conn->fetch_array($taxresult);
					$_SESSION["Tax"] = $tax["taxRate"];
					$taxrate = $tax["taxRate"]/100;
					$_SESSION["mytax"] = round($_SESSION["SubTotal"]*$taxrate,2);
					$_SESSION['subtotalwtax'] = $row["subTotal"]+$_SESSION["mytax"];
					$_SESSION['finaltotal'] = $_SESSION['subtotalwtax']+$_SESSION["shippingCost"];
					?>
                                                <tr>
                                                    <td>
                                                        <p>Tax: 
                                                            <strong>S$</strong>
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p style='text-align:right;'><strong><?php echo number_format($_SESSION["mytax"],2)?></strong></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h3> 
                                                            <strong>Total: S$</strong>
                                                        </h3>
                                                    </td>
                                                    <td>   
                                                        <h3 style='text-align:right;'><span id="totalVal"><strong><?php echo number_format($_SESSION['finaltotal'],2)?></strong></span>
                                                        </h3>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <input type='hidden' name='csrf-protection' value='<?php echo $_SESSION['csrftoken'];?>'></input>
                                                        <button type="submit" style='float:right;' class="btn btn-primary">Confirm Purchase</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
				</form>
                                <br><br><br><br><br><br><br><br>
			</p>
			</div>
		<?php else: ?>
			<span style='font-weight:bold; color:red;'>
							Empty shopping cart!</span>
		<?php endif?>
	<?php else: ?>
		<span style='font-weight:bold; color:red;'>
						Empty shopping cart!</span>

	<?php endif?>
	<?php $conn->close();?>
	</main>
	<?php include $helper->subviewPath('footer.php') ?>
</html>
<script>
function calVal(){
	var subTotal = <?php echo number_format($_SESSION['subtotalwtax'],2); ?>;
        var radioVal = $("input[name='shippingCost']:checked").val();
        var ship = parseInt(radioVal).toFixed(2);
	var Total = +subTotal + +radioVal;
        var tot = Total.toFixed(2);
    if(tot) {
        $( "#shippingVal" ).html("<strong> "+ ship +"</strong>");
        $( "#totalVal" ).html("<strong> "+ tot +"</strong>");
    }
}
</script>

