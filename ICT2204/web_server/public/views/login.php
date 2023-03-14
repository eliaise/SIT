<?php 
include_once __DIR__.'/../helpers/mysql.php';
include_once __DIR__.'/../helpers/helper.php';

session_start();
$helper = new Helper();

if (isset($_SESSION["customerID"])) {
    $pageUrl = $helper->pageUrl('home.php');
    header ("Location: $pageUrl");
    exit;
}

$login_error = false;
if (isset($_GET["login"])) {
	if ($_GET["login"] == 'invalid') {
		$login_error = true;
	}
}


?>
<html lang="en">
	<?php include $helper->subviewPath('header.php') ?>
	<main class="login-wrapper container">
		<div class="login-form">
			<form action='<?php echo $helper->processUrl('loginFunctions.php') ?>' method='post'>
				<div class='form-group'>
					<div class='col-sm-12 text-center'>
					<span class='page-title'>Member Login</span>
					</div>
				</div>
				<div class='form-group'>
					<label for='email'>
									Email Address:</label>
					<div>
					<input class='form-control' type='email'
									name='email' id='email' required/>
					</div>
				</div>
				<div class='form-group'>
					<label for='password'>
									Password:</label>
					<div>
					<input class='form-control' type='password'
									name='password' id='password' required/>
					</div>
				</div>
				<div class='form-group'>
					<div class="text-center">
					<?php if($login_error): ?>
						<div id="loginErrorMessage" class="alert alert-danger" role="alert">
							Sorry, it seems that the <b> email and/or password is incorrect </b>. Please try again.
						</div>
					<?php endif; ?>
					<button class="btn btn-primary mb-3 mt-3" type='submit' name="authenticate">Login</button>
					<p>Don't have an account? <a href="<?php echo $helper->pageUrl("register.php") ?>">Sign up</a></p>
					<p class="text-center"><a href="<?php echo $helper->pageUrl("forgotPassword.php") ?>">Forgot Password?</a></p>
					</div>
				</div>
			</form>
		</div>
	</main>
	<?php include $helper->subviewPath('footer.php') ?>
</html>
