<?php
// views/layout/header.php - shared customer-facing header
// Included at the top of every customer-facing view.
// Loads CartController to show live cart count and total in the header.

require_once __DIR__ . '/../../controllers/CartController.php';

$base        = defined('BASE_URL') ? BASE_URL : '../../';
$ip          = get_ip();
$cartCtrl    = new CartController();
$cartCount   = $cartCtrl->getCartCount($ip);
$cartTotal   = $cartCtrl->getCartTotal($ip);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Shoppn'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base; ?>css/store.css">
</head>
<body class="store-body">

<header class="store-header">
    <div class="store-header-inner">

        <a href="<?php echo $base; ?>index.php" class="store-logo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 01-8 0"/>
            </svg>
            Shoppn
        </a>

        <form class="store-search" method="GET"
              action="<?php echo $base; ?>views/search_results.php" role="search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="search" name="user_query"
                   value="<?php echo htmlspecialchars($_GET['user_query'] ?? ''); ?>"
                   placeholder="Search products, brands…" autocomplete="off">
            <button type="submit">Search</button>
        </form>

        <nav class="store-nav" aria-label="Main navigation">
            <a href="<?php echo $base; ?>index.php">Home</a>
            <a href="<?php echo $base; ?>views/all_products.php">All Products</a>

            <!-- Cart icon with live item count badge -->
            <a href="<?php echo $base; ?>views/cart.php" class="cart-nav-link" aria-label="Shopping cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.57l1.65-8.42H6"/>
                </svg>
                Cart
                <?php if ($cartCount > 0): ?>
                    <span class="cart-nav-badge"><?php echo $cartCount; ?></span>
                <?php endif; ?>
            </a>

            <?php if (is_logged_in()): ?>
                <?php if (is_admin()): ?>
                    <a href="<?php echo $base; ?>views/admin/dashboard.php" class="store-nav-admin">Admin Panel</a>
                <?php endif; ?>
                <a href="<?php echo $base; ?>logout.php">Logout</a>
            <?php else: ?>
                <a href="<?php echo $base; ?>views/login.php">Login</a>
                <a href="<?php echo $base; ?>views/register.php" class="store-nav-register">Register</a>
            <?php endif; ?>
        </nav>

    </div>

    <!-- Cart summary bar — visible when cart has items -->
    <?php if ($cartCount > 0): ?>
    <div class="cart-summary-bar">
        <div class="cart-summary-bar-inner">
            <span class="cart-summary-bar-text">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.57l1.65-8.42H6"/>
                </svg>
                Shopping Cart &mdash;
                Total Items: <strong><?php echo $cartCount; ?></strong> &nbsp;|&nbsp;
                Total Price: <strong>GHS <?php echo number_format($cartTotal, 2); ?></strong>
            </span>
            <a href="<?php echo $base; ?>views/cart.php" class="cart-summary-bar-link">
                Go to Cart &rarr;
            </a>
        </div>
    </div>
    <?php endif; ?>

</header>
