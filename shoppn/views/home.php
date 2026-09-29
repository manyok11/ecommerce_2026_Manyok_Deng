<?php
// $root is set by the calling file (index.php sets it to "./")
// if not already set, default to "../" for direct view access
if (!isset($root)) $root = "../";

// Bring in the shared header
require_once __DIR__ . "/layout/header.php";
?>

<div class="content">

    <!-- Welcome banner -->
    <div class="home-banner">
        <h1>Welcome to Shoppn 🛒</h1>
        <p>Your one-stop online store. Browse products, add to cart, and checkout with ease.</p>
    </div>

    <div class="home-placeholder">
        <div class="home-actions">
            <?php if (!is_logged_in()): ?>
                <a href="views/register.php" class="btn-primary" style="display:inline-block; width:auto; padding: 10px 24px; text-decoration:none;">
                    Create an Account
                </a>
                <a href="views/login.php" style="display:inline-block; margin-left:12px; color:#1a6b3c; font-weight:600; text-decoration:none;">
                    Log In →
                </a>
            <?php else: ?>
                <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION['customer_name']); ?></strong>!</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php
// Bring in the sidebar and footer
require_once __DIR__ . "/layout/sidebar.php";
require_once __DIR__ . "/layout/footer.php";
?>
