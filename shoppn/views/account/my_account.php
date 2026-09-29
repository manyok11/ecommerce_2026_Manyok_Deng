<?php
// Bring in core — starts session and loads helper functions
require_once __DIR__ . "/../../core/core.php";

// This page requires the customer to be logged in.
// require_login() will redirect to login.php if they are not.
require_login();

$root = "../../";
require_once "../layout/header.php";
?>

<div class="content">
    <div class="page-card">

        <h2>My Account</h2>
        <p class="subtitle">Welcome back, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!</p>

        <div style="margin-top: 20px;">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['customer_email']); ?></p>
        </div>

        <div style="margin-top: 24px;">
            <a href="../../logout.php" class="btn-primary" style="display:inline-block; width:auto; padding:10px 24px; text-decoration:none;">
                Log Out
            </a>
        </div>

        <!-- More account features (edit profile, change password, order history)
             will be added in later tasks -->

    </div>
</div>

<?php require_once "../layout/footer.php"; ?>
