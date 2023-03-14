<?php
include_once __DIR__ . '/../helpers/mysql.php';
include_once __DIR__ . '/../helpers/helper.php';

session_start();
$helper = new Helper();
// Detect the current session
$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection
?>
<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
    <div class='container'>
        <div class='col-sm-12' style='width:100%; margin:auto;'>
            <div class='row' style='padding:5px'>
                <div class='col-sm-12'>
                    <span class='page-title'>About</span>
                </div>
            </div>
            <hr>
            <div class='col-sm-12 d-flex'>
                <div class='my-about col-lg-6 col-sm-12 d-inline-block'>
                    <p>
                        Who are we?<br>
                        We are Ductus Carry.<br>
                        We sell luggages. No seriously, that's all we sell.<br>

                        Our mission : Accessible grandeur<br>
                        <br>   Ductus Carry believes that a journey begins with the selection of a companion to discover alongside. Our collection of luxury luggage that is both functional and durable at an economical price, enabling our consumers to have a partner to be proud of.
                        <br>
                        <br>    We design and produce our products in-house and sell directly to you, our customers. 
                        <br>    This eliminates the markup introduced via middlemen, such as from distributors, wholesalers and retail stores, allowing us to sell luxuries at a fraction of the price.
						<br>
						<br>
						<br>
						If you have any enquiries, forward them to cust-svc@ductuscarry.sitict.net
					</p>
                </div>
                <div class='col-lg-6 d-inline-block embed-responsive'>
                    <iframe class="embed-responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.66540302967!2d103.84659831483293!3d1.377433398995397!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da16e96db0a1ab%3A0x3d0be54fbbd6e1cd!2sSingapore%20Institute%20of%20Technology%20(SIT%40NYP)!5e0!3m2!1sen!2ssg!4v1617624272739!5m2!1sen!2ssg" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
    <?php include $helper->subviewPath('footer.php') ?>
</html>

