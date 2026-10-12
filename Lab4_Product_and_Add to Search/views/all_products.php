<?php
// views/all_products.php - shows every product in the store

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$pageTitle  = 'All Products — Shoppn';
$controller = new ProductController();
$products   = $controller->getAllProducts();
$base       = BASE_URL;

include __DIR__ . '/layout/header.php';
?>

<main class="store-main">
    <div class="store-layout">

        <?php include __DIR__ . '/layout/sidebar.php'; ?>

        <div>
            <div class="section-header">
                <div>
                    <h1>All Products</h1>
                    <p><?php echo count($products); ?> product<?php echo count($products) !== 1 ? 's' : ''; ?> available</p>
                </div>
                <a href="<?php echo $base; ?>index.php" class="btn-store btn-store-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Featured
                </a>
            </div>

            <?php if (empty($products)): ?>
                <div class="store-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                    </svg>
                    <h3>No products yet</h3>
                    <p>The store is being stocked. Check back soon!</p>
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
