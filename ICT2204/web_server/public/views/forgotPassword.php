<?php 
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

session_start();
$helper = new Helper();

// Check if the user is logged in
if (isset($_SESSION["customerID"])) {
    $pageUrl = $helper->pageUrl('home.php');
    header ("Location: $pageUrl");
    exit;
}

?>
<html lang="en">
	<?php include $helper->subviewPath('header.php') ?>
	<main class="login-wrapper container">
		<div class="forgot-form">
			<form action='<?php echo $helper->processUrl('forgotPasswordFunctions.php') ?>' method='post'>
				<div class='form-group'>
					<div class='col-sm-12 text-center'>
					<span class='page-title'>Forgot Your Password?</span>
					</div>
				</div>
				<div class='form-group'>
                    <p class="text-center">Please provide your account email in order for the system to authenticate the request with your security question.</p>
					<label for='email'>
									Email Address:</label>
					<div>
					<input class='form-control' type='email'
									name='email' id='email' required/>
					</div>
				</div>
				<div class='form-group text-center'>
					<button class="btn btn-primary" type='submit' name="request-security-qsn">Submit</button>
				</div>
			</form>
		</div>
	</main>
	<?php include $helper->subviewPath('footer.php') ?>
</html>
