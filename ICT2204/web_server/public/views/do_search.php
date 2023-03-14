<?php
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection
$helper = new Helper();

function sanitize_input($data)
{ 
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

if (isset($_GET['keywords'])):
    $searchText= '%' . sanitize_input($_GET["keywords"]) . '%';
    
    
    $baseqry = "SELECT p.productID, p.productTitle, p.productImage, p.productPrice, p.productQuantity, p.productOffer, p.productOfferStartDate, p.productOfferEndDate, p.productOfferPrice"
            . " FROM product p ";
    
    if (!isset($_GET["category"])) {
        $category = "";
    } else {
        $category = sanitize_input($_GET["category"]);
    }
    if (strcmp($category, "All") == 0) {
        $category = "";
    }
    
    if (!empty($category)) {
        $baseqry = $baseqry . "INNER JOIN productCategory pc ON p.productID = pc.productID INNER JOIN category c ON pc.catID = c.catID ";
        $baseqry = $baseqry . "WHERE (productTitle LIKE ? OR productDesc LIKE ?) ";
        $baseqry = $baseqry . "AND c.catName = ? ";
    } else {
        $baseqry = $baseqry . "WHERE (productTitle LIKE ? OR productDesc LIKE ?) ";
    }
    
    if (!isset($_GET["priceRange"])) {
        $priceRange = "";
    } else {
       $priceRange = sanitize_input($_GET["priceRange"]); 
    }
    if (!empty($priceRange) && is_numeric($priceRange)){
        $max = $_GET['priceRange'];

        $baseqry = $baseqry . "AND p.productPrice BETWEEN 0 AND ? ";
    }
    
    $stmt = $conn->prepare($baseqry);
    if (!empty($category) && !empty($priceRange)) {
        $stmt->bind_param("ssss", $searchText, $searchText, $category, $priceRange);
    }
    else if (!empty($priceRange)) {
        $stmt->bind_param("sss", $searchText, $searchText, $priceRange);
    }
    else if (!empty($category)) {
        if (strcmp($category, "All") != 0) {
            $category = "";
        }
        $stmt->bind_param("sss", $searchText, $searchText, $category);
    }
    else {
        $stmt->bind_param("ss", $searchText, $searchText);
    }
    $result = $stmt->execute(); // Execute the SQL statement
    $stmt->bind_result($productID, $productTitle, $productImage, $productPrice, $productQuantity, $productOffer, $productOfferStartDate, $productOfferEndDate, $productOfferPrice);
    
    while ($stmt->fetch()): ?>
	<div class="col-md-12 col-lg-6 mb-5 text-center">
            <?php $productUrl = $helper->pageUrl('productDetails.php') . '?pid=' . $productID ?>
            <a href="<?php echo $productUrl ?>" class="card text-dark p-5" style="text-decoration: none;">
                <img class="img-fluid" src="<?php echo $helper->imgPath($productImage, 'Products'); ?>" alt="Image of <?php echo $productTitle ?>">
                <p class="h6 title"><?php echo $productTitle ?></p>
                <?php if($productOffer == 1 && $productOfferStartDate < date("Y-m-d") AND $productOfferEndDate > date("Y-m-d")):?>
                        <p class="h5 offer-price" style="color: #348FDE;">S$ <?php echo number_format($productOfferPrice, 2) ?></p>
                        <p class="current-price" style="color: #348FDE;"><del>S$ <?php echo number_format($productPrice, 2) ?></del></p>
                        <span style="color:red"><strong>ON OFFER!</strong></span>
                <?php elseif($productQuantity > 0): ?>
                        <p class="h5 current-price" style="color: #348FDE;">S$ <?php echo number_format($productPrice, 2) ?></p>
                        &nbsp;&nbsp;
                <?php else:?>
                        <p class="h5 no-stock" style="color:red;">OUT OF STOCK</p>
                <?php endif?>
            </a>
	</div>
    <?php endwhile; endif;?>
<?php $conn->close(); ?>