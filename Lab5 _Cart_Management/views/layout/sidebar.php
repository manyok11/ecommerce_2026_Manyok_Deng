<?php
// views/layout/sidebar.php - customer-facing sidebar
// Gets categories and brands via ProductController — not the Model directly.
require_once __DIR__ . '/../../controllers/ProductController.php';

$sidebarCtrl   = new ProductController();
$sidebarCats   = $sidebarCtrl->getAllCategories();
$sidebarBrands = $sidebarCtrl->getAllBrands();
$base = defined('BASE_URL') ? BASE_URL : '../../';
?>

<aside class="store-sidebar">

    <section class="sidebar-widget">
        <h3 class="sidebar-widget-title">Categories</h3>
        <?php if (!empty($sidebarCats)): ?>
            <ul class="sidebar-list">
                <li>
                    <a href="<?php echo $base; ?>index.php"
                       class="<?php echo !isset($_GET['cat']) && !isset($_GET['brand']) ? 'active' : ''; ?>">
                        All Products
                    </a>
                </li>
                <?php foreach ($sidebarCats as $cat): ?>
                    <li>
                        <a href="<?php echo $base; ?>index.php?cat=<?php echo (int)$cat['cat_id']; ?>"
                           class="<?php echo isset($_GET['cat']) && (int)$_GET['cat'] === (int)$cat['cat_id'] ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($cat['cat_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="sidebar-empty">No categories yet.</p>
        <?php endif; ?>
    </section>

    <section class="sidebar-widget">
        <h3 class="sidebar-widget-title">Brands</h3>
        <?php if (!empty($sidebarBrands)): ?>
            <ul class="sidebar-list">
                <?php foreach ($sidebarBrands as $brand): ?>
                    <li>
                        <a href="<?php echo $base; ?>index.php?brand=<?php echo (int)$brand['brand_id']; ?>"
                           class="<?php echo isset($_GET['brand']) && (int)$_GET['brand'] === (int)$brand['brand_id'] ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="sidebar-empty">No brands yet.</p>
        <?php endif; ?>
    </section>

</aside>
