<?php
// =============================================================
// views/layout/sidebar.php  —  VIEW layout layer
// -------------------------------------------------------------
// MVC Role: View (shared layout) — the sidebar displayed on
// every customer-facing page. Shows clickable category and brand
// links so users can filter products.
//
// Per the lab spec: "Fetches from DB via ProductController."
// Rules:
//   - No SQL here — data comes from ProductController only
//   - The View calls the Controller — never the Model directly
//   - core.php is already loaded by the including page
// =============================================================

require_once __DIR__ . '/../../controllers/ProductController.php';

$sidebarController = new ProductController();
$sidebarCategories = $sidebarController->getAllCategories();
$sidebarBrands     = $sidebarController->getAllBrands();

// BASE_URL is defined in core.php
$base = defined('BASE_URL') ? BASE_URL : '../../';
?>

<aside class="sidebar">

    <!-- ── Categories ─────────────────────────────────────── -->
    <section class="sidebar-section">
        <h3 class="sidebar-heading">Categories</h3>
        <?php if (!empty($sidebarCategories)): ?>
            <ul class="sidebar-list">
                <?php foreach ($sidebarCategories as $cat): ?>
                    <li>
                        <!--
                            ?cat=N triggers getProductsByCategory() in views/home.php
                            The Controller is always the middleman.
                        -->
                        <a href="<?php echo $base; ?>index.php?cat=<?php echo (int)$cat['cat_id']; ?>">
                            <?php echo htmlspecialchars($cat['cat_name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="sidebar-empty">No categories yet.</p>
        <?php endif; ?>
    </section>

    <!-- ── Brands ─────────────────────────────────────────── -->
    <section class="sidebar-section">
        <h3 class="sidebar-heading">Brands</h3>
        <?php if (!empty($sidebarBrands)): ?>
            <ul class="sidebar-list">
                <?php foreach ($sidebarBrands as $brand): ?>
                    <li>
                        <!--
                            ?brand=N triggers getProductsByBrand() in views/home.php
                        -->
                        <a href="<?php echo $base; ?>index.php?brand=<?php echo (int)$brand['brand_id']; ?>">
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
