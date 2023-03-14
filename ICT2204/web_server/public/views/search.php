<!DOCTYPE html>
<?php 
    include_once __DIR__.'/../helpers/mysql.php';
    include_once __DIR__.'/../helpers/helper.php';

    session_start();
    $helper = new Helper();
    $conn = new Mysql_Driver();  // Create an object for database access
    $conn->connect(); // Open database connnection
?>
<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
	<main>
	<div style='width:80%; margin:auto;'>
	<form id="formSearch" name='formSearch' method='GET' action='do_search.php'>
            <div class='form-group row'>
                <div class='col-sm-6 mx-auto'>
                    <span class='page-title'>Product Search</span>
                </div>
            </div>
            <div class='form-group row'>
                <div class='col-sm-3'>
                    <div class="card bg-light mb-3" style="max-width: 18rem;">
                        <div class="card-header">Filters</div>
                        <div class="card-body">
                            <select class="form-control" name="category" aria-label="Categories">
                                <option value="All">All Categories</option>
                                <?php 
                                    $qry = "SELECT catName FROM category";
                                    $result = $conn->query($qry); 
                                    while ($row = $conn->fetch_array($result)):?>
                                    <option value="<?php echo $row["catName"] ?>"><?php echo $row["catName"] ?></option>
                                    <?php endwhile; ?>
                            </select>
                            <div class="slidecontainer">
                                <?php 
                                    $qry2 = "SELECT MAX(productPrice) FROM product";
                                    $result2 = $conn->query($qry2);
                                    $maxPrice = $conn->fetch_row($result2);
                                    $conn->close();
                                ?>
                                <label for='priceRange' class='col-form-label'>Range: $0 to $<span id="rangeLimit"><?php echo $maxPrice[0] ?></span></label>
                                <input type="range" min="1" max="<?php echo $maxPrice[0] ?>" value="<?php echo $maxPrice[0] ?>" class="slider" id="priceRange" name="priceRange">
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-sm-6'>
                    <div class="row">
                        <div class="col-sm-10">
                            <input class='form-control' name='keywords' id='keywords' type='search' placeholder='What are you looking for?'/>
                        </div>
                        <div class='col-sm-2'>
                            <button class='btn btn-primary mb-2' type='submit'>Search</button>
                        </div>
                    </div>
                    <div class="row" id="searchResults" style="padding-top: 1em;">
                        
                    </div>
                </div>
                
            </div>
        </form>
	
	</main>
    <?php include $helper->subviewPath('footer.php') ?>
</html>