// validate.js
// Client-side form validation for the registration and login forms.
//
// This runs in the browser BEFORE the form is submitted to the server.
// It gives the user instant feedback without a page reload.
//
// NOTE: The server (register_action.php and login_action.php) also
// validates everything again — this JS is just for a better user experience.
// Server-side validation is always the real security check.


// -------------------------------------------------------
// REGEX PATTERNS
// These define what "valid" looks like for each field type.
// -------------------------------------------------------

// Email: must have something @ something . something
var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Phone: 7 to 15 digits, allows +, -, and spaces
var phonePattern = /^[0-9+\-\s]{7,15}$/;

// Password: at least 8 characters and must include at least one number
var passPattern = /^(?=.*\d).{8,}$/;


// -------------------------------------------------------
// HELPER FUNCTION
// Shows or hides the small error message under a field.
// -------------------------------------------------------

// Show an error message under a field
function showError(fieldId) {
    var el = document.getElementById(fieldId);
    if (el) el.style.display = "block";
}

// Hide an error message under a field
function hideError(fieldId) {
    var el = document.getElementById(fieldId);
    if (el) el.style.display = "none";
}


// -------------------------------------------------------
// REGISTRATION FORM VALIDATION
// Runs when the register form is submitted
// -------------------------------------------------------

var registerForm = document.getElementById("registerForm");

if (registerForm) {
    registerForm.addEventListener("submit", function (e) {

        // Read and trim each field value
        var name    = document.getElementById("customer_name").value.trim();
        var email   = document.getElementById("customer_email").value.trim();
        var pass    = document.getElementById("customer_pass").value.trim();
        var confirm = document.getElementById("customer_confirm_pass").value.trim();
        var country = document.getElementById("customer_country").value;
        var city    = document.getElementById("customer_city").value.trim();
        var contact = document.getElementById("customer_contact").value.trim();

        // Assume everything is valid until we find a problem
        var isValid = true;

        // Check full name — must be at least 2 characters
        if (name.length < 2) {
            showError("err-name");
            isValid = false;
        } else {
            hideError("err-name");
        }

        // Check email format using the regex pattern above
        if (!emailPattern.test(email)) {
            showError("err-email");
            isValid = false;
        } else {
            hideError("err-email");
        }

        // Check password strength using the regex pattern above
        if (!passPattern.test(pass)) {
            showError("err-pass");
            isValid = false;
        } else {
            hideError("err-pass");
        }

        // Check that the two password fields match
        if (pass !== confirm) {
            showError("err-confirm");
            isValid = false;
        } else {
            hideError("err-confirm");
        }

        // Check that a country was selected
        if (country === "") {
            showError("err-country");
            isValid = false;
        } else {
            hideError("err-country");
        }

        // Check city is not empty
        if (city.length < 2) {
            showError("err-city");
            isValid = false;
        } else {
            hideError("err-city");
        }

        // Check phone number format
        if (!phonePattern.test(contact)) {
            showError("err-contact");
            isValid = false;
        } else {
            hideError("err-contact");
        }

        // If any check failed, stop the form from submitting
        if (!isValid) {
            e.preventDefault();
        }
    });
}


// -------------------------------------------------------
// LOGIN FORM VALIDATION
// Runs when the login form is submitted
// -------------------------------------------------------

var loginForm = document.getElementById("loginForm");

if (loginForm) {
    loginForm.addEventListener("submit", function (e) {

        var email = document.getElementById("customer_email").value.trim();
        var pass  = document.getElementById("customer_pass").value.trim();

        var isValid = true;

        // Check email format
        if (!emailPattern.test(email)) {
            showError("err-login-email");
            isValid = false;
        } else {
            hideError("err-login-email");
        }

        // Check password is not empty
        if (pass.length === 0) {
            showError("err-login-pass");
            isValid = false;
        } else {
            hideError("err-login-pass");
        }

        // Stop form submission if validation failed
        if (!isValid) {
            e.preventDefault();
        }
    });
}
