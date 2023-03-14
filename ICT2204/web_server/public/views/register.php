<!DOCTYPE html>
<?php 
    include_once __DIR__.'/../helpers/mysql.php';
    include_once __DIR__.'/../helpers/helper.php';

    $helper = new Helper();

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

    
?>

<html lang="en">
    <?php include $helper->subviewPath('header.php') ?>
    <main class="container">
    <form class="register-form" action="../process/registration.php" method="POST" onsubmit='return validateRegisterForm()'>
        <div class='form-group'>
            <div class='col-sm-12 text-center'>
            <span class='page-title'>Member Registration</span>
            </div>
        </div>
        <div class="form-group">
            <label for="nameField">Name</label> <span class="text-danger">*</span>
            <input type="text" class="text-field form-control" id="nameField" placeholder="Full Name" name="name" required>
        </div>
        <div class="form-group">
            <label for="dobField">DOB</label> <span class="text-danger">*</span>
            <input type="date" class="text-field form-control" id="dobField" name="dob" placeholder="dd / mm / yyyy" max="<?php echo date('Y-m-d') ?>" required>
        </div>
        <div class="form-group">
            <label for="addressField">Address</label> <span class="text-danger">*</span> <small>Include Postal Code</small>
            <textarea class="form-control" name="address" id="addressField" rows="4" required></textarea>
        </div>
        <div class="form-group">
            <label for="countryField">Country</label> <span class="text-danger">*</span>
            <select id="countryField" name="country" class="form-control" aria-label="Country" required>
                <option disabled selected value="">Select Country</option>
                <?php foreach($country_list as $scountry) {?>
                    <option value="<?php echo $scountry;?>"><?php echo $scountry;?></option>
                <?php }?>
            </select>
        </div>
        <div class="form-group">
            <label for="phoneField">Phone</label> <span class="text-danger">*</span> <small>Enter numbers only</small>
            <input class='form-control' name='phone' id='phoneField' type='text' required />
        </div>
        <div class="form-group">
            <label for="emailAddressField">Email</label> <span class="text-danger">*</span>
            <input type="email" class="text-field form-control" id="emailAddressField" placeholder="Email Address" name="email" required>
        </div>
        <div class="form-group">
            <label for="ccField">Credit Card No.</label> <span class="text-danger">*</span>
            <input type="text" class="text-field form-control" id="ccField" placeholder="Credit Card No." name="cc" minlength="16" maxlength="16" required>
        </div>
        <div class="form-group">
            <label for="passwordField">Password</label> <span class="text-danger">*</span>
            <input type="password" class="text-field form-control" id="passwordField" placeholder="Password" name="password" required>
        </div>
        <div class="form-group">
            <label for="cfmPasswordField">Confirm Password</label> <span class="text-danger">*</span>
            <input type="password" class="text-field form-control" id="cfmPasswordField" placeholder="Confirm Password" name="cfmPassword" required>
        </div>
        <div class="form-group">
            <label for="pwdQuestionField">Secret Question</label> <span class="text-danger">*</span>
            <small>Used if you forget your password</small>
            <select name="pwdquestion" class="form-control" aria-label="Security question" required>
                <option disabled selected value="">Select Secret Question</option>
                <option value="Mother''s Name?">Mother's Name?</option>
                <option value="How many siblings?">How many siblings?</option>
                <option value="Graduated from which University?">Graduated from which University?</option>
            </select>
        </div>
        <div class="form-group">
            <label for="pwdAnswerField">Answer</label> <span class="text-danger">*</span>
            <input type="text" class="text-field form-control" id="pwdAnswerField" placeholder="Answer" name="pwdanswer" required>
        </div>
        <div id="errorMessage" class="alert alert-danger text-center" style="display: none;">
        </div>
        <div class="form-group text-center">
            <button class="btn btn-primary" type="submit">Sign Up</button><br/><br/>
            <small>By signing up, I agree to Ductus Carry Online Shop's Terms and Conditions</small>
        </div>
    </form>
    </main>
    <?php include $helper->subviewPath('footer.php') ?>
</html>