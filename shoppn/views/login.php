<?php
// start session and load helpers
require_once __DIR__ . "/../core/core.php";

// if already logged in, go home
if (is_logged_in()) {
    redirect("../index.php");
}

$root = "../";
require_once "layout/header.php";
?>

<div class="login-page">
    <div class="login-card">

        <!-- avatar icon at the top -->
        <div class="login-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
            </svg>
        </div>

        <h2>Login</h2>

        <?php
        // show error message from the server if there is one
        if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" action="../actions/login_action.php" method="POST" novalidate>

            <!-- email field with icon -->
            <div class="login-field">
                <span class="field-icon">✉</span>
                <input type="email" id="customer_email" name="customer_email" placeholder="Email ID">
                <span class="field-error" id="err-login-email">Please enter a valid email.</span>
            </div>

            <!-- password field with icon -->
            <div class="login-field">
                <span class="field-icon">🔒</span>
                <input type="password" id="customer_pass" name="customer_pass" placeholder="Password">
                <span class="field-error" id="err-login-pass">Please enter your password.</span>
            </div>

            <!-- remember me and forgot password row -->
            <div class="login-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="#" class="forgot-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-login">LOGIN</button>

        </form>

        <div class="form-footer">
            Don't have an account? <a href="register.php">Register here</a>
        </div>

    </div>
</div>

<script src="../js/validate.js"></script>
<?php require_once "layout/footer.php"; ?>
