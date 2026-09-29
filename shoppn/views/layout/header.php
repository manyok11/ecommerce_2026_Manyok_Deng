<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppn - Online Store</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Our own stylesheet -->
    <link rel="stylesheet" href="<?php echo $root; ?>css/style.css">
</head>
<body>

<!-- =========================================================
     NAVIGATION BAR
     - Shows the store name and navigation links
     - Links change depending on whether user is logged in or not
     - Admin users see extra links like Brand and Category
     ========================================================= -->
<header class="navbar">
    <div class="navbar-brand">
        <a href="<?php echo $root; ?>index.php">🛒 Shoppn</a>
    </div>

    <nav class="navbar-links">

        <a href="<?php echo $root; ?>index.php">Home</a>

        <?php if (is_admin()): ?>
            <!-- Only admin users see these management links -->
            <a href="<?php echo $root; ?>views/admin/brand.php">Brands</a>
            <a href="<?php echo $root; ?>views/admin/category.php">Categories</a>
            <a href="<?php echo $root; ?>views/admin/product.php">Products</a>
        <?php endif; ?>

        <?php if (is_logged_in()): ?>
            <!-- Show these links when the customer is logged in -->
            <a href="<?php echo $root; ?>views/account/my_account.php">
                👤 Welcome, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
            </a>
            <a href="<?php echo $root; ?>logout.php">Logout</a>
        <?php else: ?>
            <!-- Show these links when no one is logged in -->
            <a href="<?php echo $root; ?>views/register.php">Register</a>
            <a href="<?php echo $root; ?>views/login.php">Login</a>
        <?php endif; ?>

    </nav>
</header>

<!-- Main wrapper starts here — closed in footer.php -->
<div class="main-wrapper">
