<?php
// =============================================================
// views/layout/header.php  —  VIEW layout layer
// -------------------------------------------------------------
// MVC Role: View (shared layout) — included at the top of every
// page. HTML + minimal PHP for session-based nav only.
// Rules:
//   - No SQL — ever
//   - No business logic — only session-based display decisions
//   - core.php is already loaded by the including page
//   - BASE_URL is defined in core.php
// =============================================================

$base = defined('BASE_URL') ? BASE_URL : '../';
?>
<header class="site-header">
    <div class="header-inner">

        <div class="header-brand">
            <a href="<?php echo $base; ?>index.php" class="brand-logo">
                🛍 Shoppn
            </a>
        </div>

        <nav class="site-nav" aria-label="Main navigation">
            <ul>
                <li><a href="<?php echo $base; ?>index.php">Home</a></li>

                <?php if (is_logged_in()): ?>

                    <li class="nav-welcome">
                        👤 <strong><?php echo htmlspecialchars($_SESSION['customer_name']); ?></strong>
                    </li>

                    <?php if (is_admin()): ?>
                        <!--
                            Admin-only nav items. Per the lab spec:
                            "The Brand menu item must only appear when
                            is_admin() returns true."
                            The same rule applies to all admin links.
                        -->
                        <li class="nav-divider" aria-hidden="true">|</li>
                        <li>
                            <a href="<?php echo $base; ?>views/admin/dashboard.php"
                               class="nav-admin-link">
                                📊 Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $base; ?>views/admin/customers.php"
                               class="nav-admin-link">
                                👥 Customers
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $base; ?>views/admin/brand.php"
                               class="nav-admin-link">
                                🏷️ Brands
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $base; ?>views/admin/category.php"
                               class="nav-admin-link">
                                📂 Categories
                            </a>
                        </li>
                        <li class="nav-divider" aria-hidden="true">|</li>
                    <?php endif; ?>

                    <li><a href="<?php echo $base; ?>logout.php">Logout</a></li>

                <?php else: ?>

                    <li><a href="<?php echo $base; ?>views/login.php">Login</a></li>
                    <li><a href="<?php echo $base; ?>views/register.php">Register</a></li>

                <?php endif; ?>
            </ul>
        </nav>

    </div>
</header>
