<!DOCTYPE html>
<?php 
    session_start();
    include_once __DIR__.'/../helpers/mysql.php';
    include_once __DIR__.'/../helpers/helper.php';

    $helper = new Helper();

   
    if (!isset($_SESSION["customerID"])) {
        $pageUrl = $helper->pageUrl('login.php');
        header ("Location: $pageUrl");
        exit;
    }

    // Define country list
    $country_list = array(
        "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia",
        "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium",
        "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria",
        "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Central African Republic", "Chad",
        "Chile", "China", "Colombi", "Comoros", "Congo (Brazzaville)", "Congo", "Costa Rica", "Cote d'Ivoire", "Croatia",
        "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor (Timor Timur)",
        "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France",
        "Gabon", "Gambia, The", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau",
        "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel",
        "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kuwait",
        "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg",
        "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania",
        "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Morocco", "Mozambique", "Myanmar", "Namibia",
        "Nauru", "Nepa", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Norway", "Oman", "Pakistan", "Palau",
        "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia",
        "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent", "Samoa", "San Marino", "Sao Tome and Principe",
        "Saudi Arabia", "Senegal", "Serbia and Montenegro", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia",
        "Solomon Islands", "Somalia", "South Africa", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden",
        "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tonga", "Trinidad and Tobago",
        "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom",
        "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
    );


    $db = new Mysql_Driver();
    $db->connect();
    
    $sql = "SELECT * FROM customer WHERE customerID = $_SESSION[customerID]";
    $result = $db->query($sql);
    $count = mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);

    // Personal Information
    $name = $row["customerName"];
    $dob = $row["customerDOB"];
    $address = $row["customerAddress"];
    $country = $row["customerCountry"];
    $phone = $row["customerNo"];

    // Credential Information 
    $email = $row["customerEmail"];
    
    $db->close();
?>
<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
    <main class="container">
    <div class="row">
        <div class="col col-12 text-center mb-4 ">
            <span class='page-title'>My Account</span>
        </div>
        <div class="col col-sm-12 col-lg-6">
            <form class="profile-form" action="<?php echo $helper->processUrl("profileFunctions.php") ?>" method="POST" onsubmit='return validatePersonalForm()'>
                <h5>Personal Information</h5>
                <hr>
                <div class="form-group">
                    <label for="nameField">Name</label> <span class="text-danger">*</span>
                    <input type="text" class="text-field form-control" id="nameField" placeholder="Full Name" name="name" value="<?php echo htmlspecialchars($name) ?>" required>
                </div>
                <div class="form-group">
                    <label for="dobField">DOB</label> <span class="text-danger">*</span>
                    <input type="date" class="text-field form-control" id="dobField" name="dob" placeholder="dd / mm / yyyy" value="<?php echo $dob ?>" required>
                </div>
                <div class="form-group">
                    <label for="addressField">Address</label> <span class="text-danger">*</span> <small>Include Postal Code</small>
                    <textarea class="form-control" name="address" id="addressField" rows="4" required><?php echo htmlspecialchars($address) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="countryField">Country</label> <span class="text-danger">*</span>
                    <select id="countryField" name="country" class="form-control" value="<?php echo $country ?>" required>
                        <option disabled selected value="">Select Country</option>
                        <?php foreach($country_list as $scountry) {?>
                            <option value="<?php echo $scountry;?>"<?php if($scountry==$country){echo ' selected';}?>><?php echo $scountry;?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="phoneField">Phone</label> <span class="text-danger">*</span> <small>Enter numbers only</small>
                    <input class='form-control' name='phone' id='phoneField' type='text' value="<?php echo $phone ?>" required />
                </div>
                <div id="errorMessage" class="alert alert-danger text-center" style="display: none;">
                </div>
                <div class="form-group text-center"> 
                    <button class="btn btn-primary" type="submit" name="update-personal">Update Information</button>
                </div>
            </form>
        </div>


        <div class="col col-sm-12 col-lg-6">
            <form class="credential-form" action="<?php echo $helper->processUrl("profileFunctions.php") ?>" method="POST">
                <h5>Account Credentials</h5>
                <hr>
                <div class="form-group">
                    <label for="emailAddressField">Email</label> <span class="text-danger">*</span>
                    <input type="email" class="text-field form-control" id="emailAddressField" placeholder="Email Address" name="email" value="<?php echo $email ?>" required>
                </div>
                <div class="form-group"> 
                    <label for="passwordField">Current Password</label> <span class="text-danger">*</span> <small>To authenticate credentials update</small>
                    <input type="password" class="text-field form-control" id="passwordField" placeholder="Current Password" name="password" required>
                </div>
                <div class="form-group text-center"> 
                    <button class="btn btn-primary" type="submit" name="update-email">Update Email</button>
                </div>
            </form>
            <form class="credential-form" action="<?php echo $helper->processUrl("profileFunctions.php") ?>" method="POST" onsubmit='return validateCredentialForm()'>
                <div class="form-group">
                    <label for="newPasswordField">New Password</label> <span class="text-danger">*</span>
                    <input type="password" class="text-field form-control" id="newPasswordField" placeholder="New Password" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="cfmNewPasswordField">Confirm New Password</label> <span class="text-danger">*</span>
                    <input type="password" class="text-field form-control" id="cfmNewPasswordField" placeholder="Confirm New Password" name="cfmNewPassword" required>
                </div>
                <!-- <div class="form-group">
                    <label for="pwdQuestionField">Secret Question</label> <span class="text-danger">*</span>
                    <small>Used if you forget your password</small>
                    <select name="pwdquestion" class="form-control" required>
                        <option disabled selected value="">Select Secret Question</option>
                        <option value="Wife''s Name?">Wife's Name?</option>
                        <option value="How many brothers and sisters?">How many brothers and sisters?</option>
                        <option value="Which polytechnic?">Which polytechnic?</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="pwdAnswerField">Answer</label> <span class="text-danger">*</span>
                    <input type="text" class="text-field form-control" id="pwdAnswerField" placeholder="Answer" name="pwdanswer" required>
                </div> -->
                <div class="form-group"> 
                    <label for="passwordField">Current Password</label> <span class="text-danger">*</span> <small>To authenticate credentials update</small>
                    <input type="password" class="text-field form-control" id="passwordField" placeholder="Current Password" name="password" required>
                </div>
                <div id="errorMessage2" class="alert alert-danger text-center" style="display: none;">
                </div>
                <div class="form-group text-center"> 
                    <button class="btn btn-primary" type="submit" name="update-password">Update Password</button>
                </div>
            </form>
        </div>
    </div>
    </main>
    <?php include $helper->subviewPath('footer.php') ?>
</html>