<?php
// views/home.php - customer-facing home page
// Shows featured products by default.
// If ?cat=N is set, shows products in that category.
// If ?brand=N is set, shows products from that brand.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$pageTitle  = 'Shoppn — Home';
$controller = new ProductController();

$catId   = filter_input(INPUT_GET, 'cat',   FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$brandId = filter_input(INPUT_GET, 'brand', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($catId) {
    $products     = $controller->getProductsByCategory($catId);
    $cat          = $controller->getCategoryById($catId);
    $sectionTitle = $cat ? htmlspecialchars($cat['cat_name']) : 'Category';
    $sectionDesc  = 'Products in this category';
} elseif ($brandId) {
    $products     = $controller->getProductsByBrand($brandId);
    $brand        = $controller->getBrandById($brandId);
    $sectionTitle = $brand ? htmlspecialchars($brand['brand_name']) : 'Brand';
    $sectionDesc  = 'All products from this brand';
} else {
    $products     = $controller->getFeaturedProducts(6);
    $sectionTitle = 'Featured Products';
    $sectionDesc  = 'Hand-picked products just for you';
}

$flashSuccess = get_flash('success');
$flashError   = get_flash('error');
$base         = BASE_URL;

include __DIR__ . '/layout/header.php';
?>

<main class="store-main">

    <?php if ($flashSuccess): ?>
        <div class="store-alert store-alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            <?php echo htmlspecialchars($flashSuccess); ?>
        </div>
    <?php endif; ?>
    <?php if ($flashError): ?>
        <div class="store-alert store-alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <?php echo htmlspecialchars($flashError); ?>
        </div>
    <?php endif; ?>

    <?php if (!$catId && !$brandId): ?>
    <div class="store-hero">
        <img src="<?php echo $base; ?>images/ad_banner.jpg" alt="Shop the best deals" class="store-hero-img">
        <div class="store-hero-content">
            <h1>Discover Amazing<br>Products</h1>
            <p>Shop the best brands across all categories.</p>
            <a href="<?php echo $base; ?>views/all_products.php" class="btn-store btn-store-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.57l1.65-8.42H6"/>
                </svg>
                Shop All Products
            </a>
        </div>
    </div>
    <?php endif; ?>

    <div class="store-layout">

        <?php include __DIR__ . '/layout/sidebar.php'; ?>

        <div>
            <div class="section-header">
                <div>
                    <h1><?php echo $sectionTitle; ?></h1>
                    <p><?php echo $sectionDesc; ?></p>
                </div>
                <?php if ($catId || $brandId): ?>
                    <a href="<?php echo $base; ?>index.php" class="btn-store btn-store-ghost">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        Back to All
                    </a>
                <?php endif; ?>
            </div>

            <?php if (empty($products)): ?>
                <div class="store-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                    </svg>
                    <h3>No products found</h3>
                    <p>Try a different category or <a href="<?php echo $base; ?>index.php">go back to home</a>.</p>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $p): ?>
                    <div class="product-card">
                        <a href="<?php echo $base; ?>views/single_product.php?pro_id=<?php echo (int)$p['product_id']; ?>" style="display:contents">
                            <div class="product-card-img">
                                <?php if ($p['product_image']): ?>
                                    <img src="<?php echo $base . 'images/products/' . htmlspecialchars($p['product_image']); ?>"
                                         alt="<?php echo htmlspecialchars($p['product_title']); ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="product-card-img-placeholder">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <polyline points="21 15 16 10 5 21"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <span class="product-card-badge"><?php echo htmlspecialchars($p['cat_name']); ?></span>
                            </div>
                        </a>
                        <div class="product-card-body">
                            <div class="product-card-brand"><?php echo htmlspecialchars($p['brand_name']); ?></div>
                            <a href="<?php echo $base; ?>views/single_product.php?pro_id=<?php echo (int)$p['product_id']; ?>">
                                <div class="product-card-title"><?php echo htmlspecialchars($p['product_title']); ?></div>
                            </a>
                            <div class="product-card-footer">
                                <span class="product-card-price">GHS <?php echo number_format((float)$p['product_price'], 2); ?></span>
                                <a href="<?php echo $base; ?>views/single_product.php?pro_id=<?php echo (int)$p['product_id']; ?>"
                                   class="btn-store btn-store-outline" style="font-size:.78rem;padding:.35rem .8rem">
                                    Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
