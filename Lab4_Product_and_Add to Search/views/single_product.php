<?php
// views/single_product.php - product detail page
// Requires ?pro_id=N — redirects to home if missing or not found.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$proId = filter_input(INPUT_GET, 'pro_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if (!$proId) {
    redirect(BASE_URL . 'index.php');
}

$controller = new ProductController();
$product    = $controller->getProductById($proId);

if (!$product) {
    set_flash('error', 'Product not found.');
    redirect(BASE_URL . 'index.php');
}

$keywords  = array_filter(array_map('trim', explode(',', $product['product_keywords'] ?? '')));
$pageTitle = htmlspecialchars($product['product_title']) . ' — Shoppn';
$base      = BASE_URL;

include __DIR__ . '/layout/header.php';
?>

<main class="store-main">

    <nav class="store-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo $base; ?>index.php">Home</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <a href="<?php echo $base; ?>index.php?cat=<?php echo (int)$product['product_cat']; ?>">
            <?php echo htmlspecialchars($product['cat_name']); ?>
        </a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <span><?php echo htmlspecialchars($product['product_title']); ?></span>
    </nav>

    <div class="product-detail">

        <div class="product-detail-img">
            <?php if ($product['product_image']): ?>
                <img src="<?php echo $base . 'images/products/' . htmlspecialchars($product['product_image']); ?>"
                     alt="<?php echo htmlspecialchars($product['product_title']); ?>">
            <?php else: ?>
                <div style="display:flex;align-items:center;justify-content:center;height:100%;background:var(--green-050)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="64" height="64" style="color:var(--muted-lt)">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-detail-info">
            <div class="product-detail-brand"><?php echo htmlspecialchars($product['brand_name']); ?></div>
            <h1 class="product-detail-title"><?php echo htmlspecialchars($product['product_title']); ?></h1>
            <div class="product-detail-price">GHS <?php echo number_format((float)$product['product_price'], 2); ?></div>
            <div class="product-detail-meta">
                <span class="store-badge store-badge-green"><?php echo htmlspecialchars($product['cat_name']); ?></span>
                <span class="store-badge store-badge-muted"><?php echo htmlspecialchars($product['brand_name']); ?></span>
            </div>
            <?php if ($product['product_desc']): ?>
                <p class="product-detail-desc"><?php echo nl2br(htmlspecialchars($product['product_desc'])); ?></p>
            <?php endif; ?>
            <?php if (!empty($keywords)): ?>
                <div class="product-detail-keywords">
                    <?php foreach ($keywords as $kw): ?>
                        <a href="<?php echo $base; ?>views/search_results.php?user_query=<?php echo urlencode($kw); ?>"
                           class="keyword-tag">#<?php echo htmlspecialchars($kw); ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-top:.5rem">
                <a href="<?php echo $base; ?>actions/add_to_cart_action.php?add_cart=<?php echo (int)$product['product_id']; ?>"
                   class="btn-store btn-store-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h9.72a2 2 0 001.95-1.57l1.65-8.42H6"/>
                    </svg>
                    Add to Cart
                </a>
                <a href="<?php echo $base; ?>index.php?cat=<?php echo (int)$product['product_cat']; ?>"
                   class="btn-store btn-store-outline">
                    More in <?php echo htmlspecialchars($product['cat_name']); ?>
                </a>
            </div>
        </div>

    </div>

    <a href="<?php echo $base; ?>views/all_products.php" class="btn-store btn-store-ghost" style="margin-bottom:1rem">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
        </svg>
        Back to All Products
    </a>

</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
