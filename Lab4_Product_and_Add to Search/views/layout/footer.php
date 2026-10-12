<?php
// views/layout/footer.php - shared customer-facing footer
$base = defined('BASE_URL') ? BASE_URL : '../../';
?>
<footer class="store-footer">
    <div class="store-footer-inner">
        <div class="footer-brand">
            <a href="<?php echo $base; ?>index.php" class="store-logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 01-8 0"/>
                </svg>
                Shoppn
            </a>
            <p>Your one-stop online store.</p>
        </div>
        <div class="footer-links">
            <div>
                <h4>Shop</h4>
                <a href="<?php echo $base; ?>views/all_products.php">All Products</a>
                <a href="<?php echo $base; ?>index.php">Featured</a>
            </div>
            <div>
                <h4>Account</h4>
                <?php if (is_logged_in()): ?>
                    <a href="<?php echo $base; ?>logout.php">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $base; ?>views/login.php">Login</a>
                    <a href="<?php echo $base; ?>views/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> Shoppn. All rights reserved.</p>
    </div>
</footer>
</body>
</html>
