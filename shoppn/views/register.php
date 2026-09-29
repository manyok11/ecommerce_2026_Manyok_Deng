<?php
// Bring in core so session is started and helpers are available
require_once __DIR__ . "/../core/core.php";

// If the customer is already logged in, no need to register again
if (is_logged_in()) {
    redirect("../index.php");
}

// $root tells header.php where the project root is
// (one level up from views/)
$root = "../";

require_once "layout/header.php";
?>

<div class="content">
    <div class="page-card">

        <h2>Create an Account</h2>
        <p class="subtitle">Join Shoppn and start shopping today.</p>

        <?php
        // Show error message from the server if there is one,
        // then clear it so it does not show again on refresh
        if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!--
            form id="registerForm" — validate.js listens for submit on this id
            action goes to register_action.php which handles the POST
        -->
        <form id="registerForm" action="../actions/register_action.php" method="POST" novalidate>

            <div class="form-group">
                <label for="customer_name">Full Name</label>
                <input type="text" id="customer_name" name="customer_name" placeholder="e.g. Manyok Deng">
                <!-- validate.js shows error messages inside these spans -->
                <span class="field-error" id="err-name">Please enter your full name.</span>
            </div>

            <div class="form-group">
                <label for="customer_email">Email Address</label>
                <input type="email" id="customer_email" name="customer_email" placeholder="e.g. manyok@email.com">
                <span class="field-error" id="err-email">Please enter a valid email address.</span>
            </div>

            <div class="form-group">
                <label for="customer_pass">Password</label>
                <input type="password" id="customer_pass" name="customer_pass" placeholder="At least 8 characters">
                <span class="field-error" id="err-pass">Password must be at least 8 characters and include a number.</span>
            </div>

            <div class="form-group">
                <label for="customer_confirm_pass">Confirm Password</label>
                <input type="password" id="customer_confirm_pass" name="customer_confirm_pass" placeholder="Repeat your password">
                <span class="field-error" id="err-confirm">Passwords do not match.</span>
            </div>

            <div class="form-group">
                <label for="customer_country">Country</label>
                <select id="customer_country" name="customer_country">
                    <option value="">-- Select Country --</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Kenya">Kenya</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Other">Other</option>
                </select>
                <span class="field-error" id="err-country">Please select your country.</span>
            </div>

            <div class="form-group">
                <label for="customer_city">City</label>
                <input type="text" id="customer_city" name="customer_city" placeholder="e.g. Accra">
                <span class="field-error" id="err-city">Please enter your city.</span>
            </div>

            <div class="form-group">
                <label for="customer_contact">Contact Number</label>
                <input type="text" id="customer_contact" name="customer_contact" placeholder="e.g. +233 24 000 0000">
                <span class="field-error" id="err-contact">Please enter a valid phone number (7-15 digits).</span>
            </div>

            <button type="submit" class="btn-primary">Create Account</button>

        </form>

        <div class="form-footer">
            Already have an account? <a href="login.php">Log in here</a>
        </div>

    </div>
</div>

<!-- Load the JS validation file — must come after the form HTML -->
<script src="../js/validate.js"></script>

<?php require_once "layout/footer.php"; ?>
