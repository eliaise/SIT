<?php 
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

session_start();
$helper = new Helper();
// Detect the current session
$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection
$qry = "select * from category";
$result = $conn->query($qry); // Execute the SQL statement

?>
<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
    <main>
        <div class='container'>
            <div class='col-sm-12' style='width:100%; margin:auto;'>
                <div class='row' style='padding:5px'>
                    <div class='col-sm-12'>
                        <span class='page-title'>Product Categories</span>
                        <p>Select a category listed below:</p>
                    </div>
                </div>
                <hr>
                <?php while ($row = $conn->fetch_array($result) ) : ?>
                    <div class='row card' style='padding:5px; flex-direction:row;'>
                        <?php $catproduct = "catProduct.php?cid=$row[catID]"; ?>
                        <div class='col-lg-6 col-sm-4'> 
                            <p class='prod-title'><a href=<?php echo $catproduct ?>><?php echo "$row[catName]"?></a></p>
                            <p class='prod-desc'><?php echo "$row[catDesc]" ?></p>
                        </div>

                        <?php $img = "../Images/Category/$row[catImage]"; ?>
                        <div class='col-lg-6 col-sm-8'>
                            <a href=<?php echo $catproduct ?>><img class='img-fluid' alt='<?php echo "Image of $row[catName]"?>' src=<?php echo "$img"?>></a>
                        </div>
                    </div><br>
                <?php endwhile ?>
            </div>
        </div>
    </main>
    <?php include $helper->subviewPath('footer.php') ?>
</html>

