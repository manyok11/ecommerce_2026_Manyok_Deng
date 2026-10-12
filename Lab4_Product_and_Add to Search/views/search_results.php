<?php
// views/search_results.php - search results page
// Reads ?user_query from GET, calls searchProducts(), shows results.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$query = trim(strip_tags($_GET['user_query'] ?? ''));

if ($query === '') {
    redirect(BASE_URL . 'index.php');
}

$controller = new ProductController();
$products   = $controller->searchProducts($query);
$pageTitle  = 'Search: ' . htmlspecialchars($query) . ' — Shoppn';
$base       = BASE_URL;

include __DIR__ . '/layout/header.php';
?>

<main class="store-main">

    <nav class="store-breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo $base; ?>index.php">Home</a>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
        <span>Search results</span>
    </nav>

    <div class="store-layout">

        <?php include __DIR__ . '/layout/sidebar.php'; ?>

        <div>
            <div class="section-header">
                <div>
                    <h1>Search Results</h1>
                    <p>
                        <?php echo count($products); ?> result<?php echo count($products) !== 1 ? 's' : ''; ?>
                        for &ldquo;<strong><?php echo htmlspecialchars($query); ?></strong>&rdquo;
                    </p>
                </div>
                <a href="<?php echo $base; ?>index.php" class="btn-store btn-store-ghost">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Back to Home
                </a>
            </div>

            <?php if (empty($products)): ?>
                <div class="store-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <h3>No products found</h3>
                    <p>Nothing matched &ldquo;<?php echo htmlspecialchars($query); ?>&rdquo;.
                       Try a different word or <a href="<?php echo $base; ?>views/all_products.php">browse all products</a>.</p>
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
