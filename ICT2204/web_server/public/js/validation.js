function validateRegisterForm() {
    var success = true;
    // error alert
    var errorMessage = "";
    // Get form fields 
    var dob = document.getElementById("dobField");
    var phone = document.getElementById("phoneField");
    var pwd = document.getElementById("passwordField");
    var cfmPwd = document.getElementById('cfmPasswordField');

    // validate dob field must be minimum sixteen years old
    var dobVal = new Date(dob.value);
    var today = new Date();
    var age = today.getFullYear() - dobVal.getFullYear();
    var m = today.getMonth() - dobVal.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dobVal.getDate())) {
        age--;
    }
    if (age < 16) {
        success = false;
        errorMessage += "<li> You must be 16 years old and above to be a member </li>"
    }

    // validate phone 4 - 15 digits
    var phoneRegex = new RegExp(/^\d{4,20}$/);
    if (!phoneRegex.test(phone.value)) {
        success = false;
        errorMessage +=  "<li> Phone number must be 4-20 digits </li>"
    }

    // validate password to be the same as cfm password and must be 8 characters or more, must have 1 upper, lower, numeric and special characters
    if (pwd.value !== cfmPwd.value) {
        success = false;
        errorMessage +=  "<li> Password and Confirm Password must be the same </li>"
    } else {
        var pwdRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
        if (!pwdRegex.test(pwd.value)) {
            success = false;
            errorMessage += "<li> Password must have at least 1 numeric, special, upper case, lower case alphabetical characters. Also it must be 8 characters or more.  </li>"
        }
    }

    if (!success) {
        var error = document.getElementById("errorMessage");
        error.style.display = 'block';
        error.innerHTML = errorMessage;
    }

    return success;
}

function validatePersonalForm() {
    console.log('putangina bat ayaw mo gumana')
    var success = true;
    // error alert
    var errorMessage = "";
    // Get form fields 
    var dob = document.getElementById("dobField");
    var phone = document.getElementById("phoneField");

    // validate dob field must be minimum sixteen years old
    var dobVal = new Date(dob.value);
    var today = new Date();
    var age = today.getFullYear() - dobVal.getFullYear();
    var m = today.getMonth() - dobVal.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dobVal.getDate())) {
        age--;
    }
    if (age < 16) {
        success = false;
        errorMessage += "<li> You must be 16 years old and above to be a member </li>"
    }

    // validate phone 4 - 15 digits
    var phoneRegex = new RegExp(/^\d{4,20}$/);
    if (!phoneRegex.test(phone.value)) {
        success = false;
        errorMessage +=  "<li> Phone number must be 4-20 digits </li>"
    }

    if (!success) {
        var error = document.getElementById("errorMessage");
        error.style.display = 'block';
        error.innerHTML = errorMessage;
    }

    return success;
}

function validateCredentialForm() {
    var success = true;
    // error alert
    var errorMessage = "";
    var newPwd = document.getElementById("newPasswordField");
    var newCfmPwd = document.getElementById('cfmNewPasswordField');

    // validate password to be the same as cfm password and must be 8 characters or more, must have 1 upper, lower, numeric and special characters
    if (newPwd.value !== newCfmPwd.value) {
        success = false;
        errorMessage +=  "<li> New Password and Confirm Password must be the same </li>"
    } else {
        var pwdRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
        if (!pwdRegex.test(newPwd.value)) {
            success = false;
            errorMessage += "<li> Password must have at least 1 numeric, special, upper case, lower case alphabetical characters. Also it must be 8 characters or more.  </li>"
        }
    }

    if (!success) {
        var error = document.getElementById("errorMessage2");
        error.style.display = 'block';
        error.innerHTML = errorMessage;
    }
    return success;
}