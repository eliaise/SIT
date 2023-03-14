<!DOCTYPE html>
<?php 
    include_once __DIR__.'/helpers/mysql.php';
    include_once __DIR__.'/helpers/helper.php';

    function sanitize_input($data)
        { 
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
        }
    session_start();
    $helper = new Helper();
    // Retrieving the course details
    $db = new Mysql_Driver();
    $db->connect();

    $sql = "SELECT * FROM product WHERE productOffer = 1 AND productOfferStartDate < NOW() AND productOfferEndDate > NOW()"; // this is the sql query for this loop
    $result = $db->query($sql);// connect to the database to get the info
    
    $resultArray = []; // instantiate empty array 

    while ($row = $db->fetch_array($result)) { // while loop based on connection 
        $resultArray[] = $row; // append results into the array
    }

    // Get feedbacks
    $sql = "SELECT f.*, c.customerName FROM feedback f INNER JOIN customer c ON f.customerID = c.customerID ORDER BY feedbackTimestamp DESC";
    $result = $db->query($sql);// connect to the database to get the info

    $feedbackArray = [];

    while ($row = $db->fetch_array($result)) { // while loop based on connection 
        $feedbackArray[] = $row; // append results into the array
    }
    $db->close();

?>
<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
    <div class="main-banner container-fluid d-flex justify-content-center align-items-center">
        <div>
        <h1><span class="shop-name">Travel with Style</span></h1>
        <h6 class="text-center">Eco-friendly and Carbon Neutral.</h6>
        </div>
    </div>
    <main class="container col-sm-8">
    <h1 class="page-title text-center mt-4">Featured Items</h1>
    <div class="row">
    <?php foreach ($resultArray as $key => $row): ?>
            <div class="col-md-6 col-lg-4 mb-5 text-center">
            <?php $productUrl = $helper->pageUrl('productDetails.php') . '?pid=' . $row['productID'] ?>
                <a href="<?php echo $productUrl ?>" class="card text-dark p-5" style="text-decoration: none;">
                    <?php $img = "../Images/Products/$row[productImage]";?>
                    <img class="img-fluid" src="<?php echo $helper->imgPath($row["productImage"], 'Products'); ?>" alt="Image of <?php echo $row["productTitle"] ?>">
                    <p class="h6 title"><?php echo $row["productTitle"] ?></p>
                    <p class="h5 offer-price" style="color: #348FDE;">S$ <?php echo number_format($row["productOfferPrice"], 2) ?></p>
                    <p class="usual-price"><del>S$ <?php echo number_format($row["productPrice"], 2) ?></del></p>
                </a>
            </div>
    <?php endforeach ?>
    </div>
    <div class="feedback-section text-right">
        <h1 class="page-title mt-4 text-center">Our Customer Reviews</h1>
        <?php if (isset($_SESSION['customerID'])): ?>
            <a href="<?php echo $helper->pageUrl("feedback.php") ?>" class="btn btn-primary">Provide Feedback</a>
        <?php endif; ?>
        <div class="rating-block mt-4 text-left">
        <?php foreach($feedbackArray as $key => $row): 
            $counter = 0
        ?>
        <div class="row">
            <div class="col-sm-3 text-center">
                <img class="w-50" src="<?php echo $helper->imgPath('avatar.png') ?>" class="img-rounded" alt="<?php echo sanitize_input($row['customerName']); ?>'s profile picture">
                <div class="review-block-name"><a href="#"><?php echo sanitize_input($row['customerName']) ?></a></div>
                <!-- <div class="review-block-date">January 29, 2016<br/>1 day ago</div> -->
                <div class="review-block-date"><?php echo $row['feedbackTimestamp']?></div>
            </div>
            <div class="col-sm-9">
                <div class="review-block-rate">
                    <h5>
                    <?php 
                        // determine the rating
                        $shadedStars = $row['feedbackRanking'];
                        $unshadeStars = 5 - $shadedStars;
                        for ($i = 0; $i < $shadedStars; $i++) {
                            echo "<i class='fas fa-star text-warning'></i>";
                        }
                        for ($i = 0; $i < $unshadeStars; $i++) {
                            echo "<i class='far fa-star text-warning'></i>";
                        }
                    ?>
                    </h5>
                </div>
                <div class="review-block-title"><?php echo sanitize_input($row['feedbackSubject']);?></div>
                <div class="review-block-description"><?php echo sanitize_input($row['feedbackContent']); ?></div>
            </div>
        </div>
        <?php 
            if($counter != count( $feedbackArray ) - 1) {
            echo '<hr/>';
            }     
            $counter++;
            endforeach; 
        ?>
    </div>
    </main>
    <?php include $helper->subviewPath('footer.php') ?>
</html>