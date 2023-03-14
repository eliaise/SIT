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

$email = "";
$question = "";

if (isset($_SESSION["customerEmail"]) && isset($_SESSION["customerPwdQn"])) {
    $email = $_SESSION["customerEmail"];
    $question = $_SESSION["customerPwdQn"];
    unset($_SESSION['customerEmail']);
    unset($_SESSION['customerPwdQn']);
}

?>
<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>

    <?php if($email && $question): ?>
        <main class="login-wrapper container">
            <div class="forgot-form">
                <form action='<?php echo $helper->processUrl('forgotPasswordFunctions.php') ?>' method='post'>
                    <div class='form-group'>
                        <div class='col-sm-12 text-center'>
                        <span class='page-title'>Forgot Your Password?</span>
                        </div>
                    </div>
                    <div class='form-group'>
                        <p class="text-center">Please answer the Security Question to authenticate the password reset process.</p>
                        <label for="pwdAnswerField">Security Question: <?php echo $question ?></label> <span class="text-danger">*</span>
                        <input type="text" class="text-field form-control" id="pwdAnswerField" placeholder="Answer" name="pwdanswer" required>
                    </div>
                    <div class='form-group text-center'>
                        <input class='form-control' type='hidden' name='email' id='email' value="<?php echo $email ?>" />
                        <button class="btn btn-primary" type='submit' name="submit-security-qsn">Reset Password</button>
                    </div>
                </form>
            </div>
        </main>
    <?php else: ?>
        <main class="container text-center">
        <h1 class='page-title'>Password Reset Failed</h1>
        <p>- Something went wrong! Please try submitting your account email again.</p>
        <a href='javascript:history.back()'>Go Back</a>
        </main>
    <?php endif; ?>
	<?php include $helper->subviewPath('footer.php') ?>
</html>
