<?php
// This is the registration View.
// It only displays HTML. No SQL, no business logic here.
// Any session error (e.g. from a failed non-JS submission) is
// displayed at the top and then cleared so it only shows once.
require_once __DIR__ . '/../core/core.php';

// If the user is already logged in, send them to the home page
if (is_logged_in()) {
    redirect('../index.php');
}

$error   = get_flash('error');
$success = get_flash('success');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — Shoppn</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include '../views/layout/header.php'; ?>

<main class="form-page">
    <h1>Create an Account</h1>

    <?php if ($error): ?>
        <p class="msg msg--error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="msg msg--success"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <!--
        Each input has:
          name=""  — what PHP reads via $_POST in the action file
          id=""    — what customer.js reads for client-side validation
        A matching <span id="<field>-error"> sits beside each input
        so customer.js can write per-field error messages inline.
    -->
    <form id="registerForm" method="POST" action="../actions/customer_register_action.php" novalidate>

        <div class="form-group">
            <label for="customer_name">Full Name</label>
            <input type="text" id="customer_name" name="customer_name"
                   placeholder="e.g. Jane Doe" autocomplete="name">
            <span id="customer_name-error" class="field-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_email">Email Address</label>
            <input type="email" id="customer_email" name="customer_email"
                   placeholder="e.g. jane@example.com" autocomplete="email">
            <span id="customer_email-error" class="field-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_pass">Password</label>
            <input type="password" id="customer_pass" name="customer_pass"
                   autocomplete="new-password">
            <!--
                Password hint: tells the user upfront what is required.
                This mirrors the PASSWORD_REGEX in customer.js and the
                server-side check in customer_register_action.php.
            -->
            <small class="field-hint">
                At least 8 characters, with an uppercase letter, a lowercase
                letter, a number, and a special character (e.g. <em>MyPass1!</em>).
            </small>
            <span id="customer_pass-error" class="field-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_country">Country</label>
            <input type="text" id="customer_country" name="customer_country"
                   placeholder="e.g. Ghana" autocomplete="country-name">
            <span id="customer_country-error" class="field-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_city">City</label>
            <input type="text" id="customer_city" name="customer_city"
                   placeholder="e.g. Accra" autocomplete="address-level2">
            <span id="customer_city-error" class="field-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_contact">Contact Number</label>
            <input type="tel" id="customer_contact" name="customer_contact"
                   placeholder="e.g. +233241234567" autocomplete="tel">
            <span id="customer_contact-error" class="field-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_image">Profile Image (optional)</label>
            <input type="file" id="customer_image" name="customer_image"
                   accept="image/jpeg,image/png,image/gif,image/webp">
            <span id="customer_image-error" class="field-error"></span>
        </div>

        <div class="form-group">
            <!-- type="button" prevents the default browser submit so
                 registerCustomer() in customer.js handles it instead -->
            <button type="button" id="register-btn" onclick="registerCustomer()">
                Register
            </button>
        </div>

        <p id="formMessage" class="msg" aria-live="polite"></p>

    </form>

    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</main>

<?php include '../views/layout/footer.php'; ?>

<script src="../js/customer.js"></script>
</body>
</html>
