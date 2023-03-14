<!DOCTYPE html>
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
?>

<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
    <main class="container">
    <form class="feedback-form" action="<?php echo $helper->processUrl('feedbackFunction.php') ?>" method="POST">
        <div class='form-group'>
                        <div class='col-sm-12 text-center'>
                        <span class='page-title'>Provide a Feedback</span>
                        </div>
        </div>
        <div class="form-group">
            <label for="subjectField">Subject</label> <span class="text-danger">*</span>
            <input type="text" class="text-field form-control" id="subjectField" placeholder="About a service or product" name="subject" required>
        </div>
        <div class="form-group">
            <label for="contentField">Message</label>
            <textarea class="form-control" name="content" id="contentField" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="ratingField">Rating</label> <span class="text-danger">*</span>
            <select id="ratingField" name="rating" class="form-control" required>
                <option disabled selected value="">Please select a rating</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <small>
                <ul class="pl-0 pt-3" style="list-style:none;">
                Legend:
                    <li>1 - Very Bad</li>
                    <li>3 - Neutral</li>
                    <li>5 - Excellent</li>
                </ul>
            </small>
        </div>
        
        

        <div class="form-group text-center">
            <button class="btn btn-primary" type="submit">Submit</button><br/><br/>
            <small>Make sure to check before submiting! Submitted feedbacks are not editable</small>
        </div>
    </form>
    </main>
    <?php include $helper->subviewPath('footer.php') ?>
</html>
</html>