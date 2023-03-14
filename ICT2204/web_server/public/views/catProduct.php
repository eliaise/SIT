
<?php 
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

session_start();
$helper = new Helper();
// verify if user is logged in
if (!isset($_SESSION["customerID"])) {
    $pageUrl = $helper->pageUrl('login.php');
    header ("Location: $pageUrl");
    exit;
    }
$db = new Mysql_Driver();
$db->connect();

$cid = $_GET["cid"];
$qry = "SELECT * FROM productCategory pc INNER JOIN product p ON pc.productID = p.productID WHERE pc.catID  = $cid ORDER BY p.productTitle ASC";

$result = $db->query($qry); 
$db->close();
?>
<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
    <main class="container">
	<div class="row">
	<div class='col-12'>
	<?php 
	$db->connect();
	$qry1 = "SELECT * FROM category WHERE catID = $cid";
	$result1 = $db->query($qry1); 
	$db->close();
	while ($row = $db->fetch_array($result1)):?>

		<h1 class="page-title text-center mt-4"><?php echo $row["catName"] ?></h1>
	<?php endwhile;?>
	</span>
	</div>
	<?php while ($row = $db->fetch_array($result)): ?>
	<div class="col-md-6 col-lg-4 mb-5 text-center">
		<?php $productUrl = $helper->pageUrl('productDetails.php') . '?pid=' . $row['productID'] ?>
			<a href="<?php echo $productUrl ?>" class="card text-dark p-5" style="text-decoration: none;">
				<img class="img-fluid" src="<?php echo $helper->imgPath($row["productImage"], 'Products'); ?>">
				<p class="h6 title"><?php echo $row["productTitle"] ?></p>
				<?php if($row["productOffer"] == 1 && $row["productOfferStartDate"] < date("Y-m-d") AND $row["productOfferEndDate"] > date("Y-m-d")):?>
					<p class="h5 offer-price" style="color: #348FDE;">S$ <?php echo number_format($row["productOfferPrice"], 2) ?></p>
					<p class="current-price" style="color: #348FDE;"><del>S$ <?php echo number_format($row["productPrice"], 2) ?></del></p>
					<span style="color:red"><strong>ON OFFER!</strong></span>
				<?php elseif($row["productQuantity"] > 0): ?>
					<p class="h5 current-price" style="color: #348FDE;">S$ <?php echo number_format($row["productPrice"], 2) ?></p>
					&nbsp;&nbsp;
				<?php else:?>
					<p class="h5 no-stock" style="color:red;">OUT OF STOCK</p>
				<?php endif?>

			</a>
	</div>
	<?php endwhile;?>
	</main>
    <?php include $helper->subviewPath('footer.php') ?>
</html>
