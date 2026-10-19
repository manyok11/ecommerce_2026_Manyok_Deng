<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();

$pageTitle   = 'Products — Shoppn Admin';
$activePage  = 'products';
$controller  = new ProductController();

$editId      = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$editProduct = null;
$isEditMode  = false;

if ($editId) {
    $editProduct = $controller->getProductById($editId);
    $isEditMode  = (bool)$editProduct;
    if (!$editProduct) set_flash('error', 'Product not found.');
}

$products     = $controller->getAllProducts();
$brands       = $controller->getAllBrands();
$categories   = $controller->getAllCategories();
$flashSuccess = get_flash('success');
$flashError   = get_flash('error');

include __DIR__ . '/partials/head.php';
include __DIR__ . '/partials/sidebar.php';
?>
<div class="admin-shell">
<main class="admin-main">
<?php include __DIR__ . '/partials/topbar.php'; ?>
<div class="page-content">

<?php if ($flashSuccess): ?>
<script>document.addEventListener('DOMContentLoaded',function(){ showToast(<?php echo json_encode($flashSuccess); ?>,'success'); });</script>
<?php endif; ?>
<?php if ($flashError): ?>
<script>document.addEventListener('DOMContentLoaded',function(){ showToast(<?php echo json_encode($flashError); ?>,'error'); });</script>
<?php endif; ?>

<div class="page-header">
    <div class="page-header-left">
        <nav class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>views/admin/dashboard.php">Dashboard</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>Products</span>
        </nav>
        <h1><?php echo $isEditMode ? 'Edit Product' : 'Manage Products'; ?></h1>
        <p><?php echo $isEditMode ? 'Update the product details below.' : 'Add new products and manage existing ones.'; ?></p>
    </div>
</div>

<!-- Add / Edit form card -->
<div class="card glass--static" style="margin-bottom:1.75rem">
    <div class="card-header">
        <h2 class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <?php if ($isEditMode): ?>
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                <?php else: ?>
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                <?php endif; ?>
            </svg>
            <?php echo $isEditMode ? 'Edit Product' : 'Add New Product'; ?>
        </h2>
        <?php if ($isEditMode): ?>
            <a href="product.php" class="btn btn-ghost btn-sm">Cancel</a>
        <?php endif; ?>
    </div>

    <form method="POST"
          action="<?php echo BASE_URL; ?>actions/<?php echo $isEditMode ? 'update_product_action' : 'add_product_action'; ?>.php"
          enctype="multipart/form-data">

        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
        <?php if ($isEditMode): ?>
            <input type="hidden" name="product_id"     value="<?php echo (int)$editProduct['product_id']; ?>">
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editProduct['product_image']); ?>">
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">

            <div class="form-field">
                <label class="form-label" for="product_title">Product Title <span style="color:var(--accent-rose)">*</span></label>
                <input type="text" id="product_title" name="product_title"
                       value="<?php echo $isEditMode ? htmlspecialchars($editProduct['product_title']) : ''; ?>"
                       placeholder="e.g. Nike Air Max 2024" required maxlength="255">
            </div>

            <div class="form-field">
                <label class="form-label" for="product_price">Price (GHS) <span style="color:var(--accent-rose)">*</span></label>
                <input type="number" id="product_price" name="product_price"
                       value="<?php echo $isEditMode ? htmlspecialchars($editProduct['product_price']) : ''; ?>"
                       placeholder="e.g. 250.00" required min="0.01" step="0.01">
            </div>

            <div class="form-field">
                <label class="form-label" for="product_cat">Category <span style="color:var(--accent-rose)">*</span></label>
                <select id="product_cat" name="product_cat" required>
                    <option value="">-- Select category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo (int)$cat['cat_id']; ?>"
                            <?php if ($isEditMode && (int)$editProduct['product_cat'] === (int)$cat['cat_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($cat['cat_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-field">
                <label class="form-label" for="product_brand">Brand <span style="color:var(--accent-rose)">*</span></label>
                <select id="product_brand" name="product_brand" required>
                    <option value="">-- Select brand --</option>
                    <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo (int)$brand['brand_id']; ?>"
                            <?php if ($isEditMode && (int)$editProduct['product_brand'] === (int)$brand['brand_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <div class="form-field">
            <label class="form-label" for="product_desc">Description</label>
            <textarea id="product_desc" name="product_desc" rows="3"
                      placeholder="Describe the product..."><?php echo $isEditMode ? htmlspecialchars($editProduct['product_desc']) : ''; ?></textarea>
        </div>

        <div class="form-field">
            <label class="form-label" for="product_keywords">Keywords</label>
            <input type="text" id="product_keywords" name="product_keywords"
                   value="<?php echo $isEditMode ? htmlspecialchars($editProduct['product_keywords']) : ''; ?>"
                   placeholder="e.g. shoes, sports, running">
            <span class="form-hint">Comma-separated keywords used for search.</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;align-items:start">
            <div class="form-field">
                <label class="form-label" for="product_image">
                    Product Image
                    <?php echo !$isEditMode
                        ? '<span style="color:var(--accent-rose)">*</span>'
                        : '<span style="color:var(--muted)">(leave blank to keep current)</span>'; ?>
                </label>
                <input type="file" id="product_image" name="product_image"
                       accept="image/jpeg,image/png,image/gif,image/webp"
                       <?php echo !$isEditMode ? 'required' : ''; ?>
                       onchange="previewProductImage(this)">
                <span class="form-hint">JPG, PNG, GIF or WEBP &middot; max 3 MB</span>
                <span id="img-error" class="form-error"></span>
            </div>

            <div id="img-preview-wrap" style="<?php echo ($isEditMode && $editProduct['product_image']) ? '' : 'display:none'; ?>">
                <label class="form-label">Current Image</label>
                <img id="img-preview"
                     src="<?php echo ($isEditMode && $editProduct['product_image'])
                         ? BASE_URL . 'images/products/' . htmlspecialchars($editProduct['product_image'])
                         : ''; ?>"
                     alt="Product preview"
                     style="height:120px;width:120px;object-fit:cover;border-radius:var(--radius-sm);border:2px solid var(--border-solid)">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            <?php echo $isEditMode ? 'Update Product' : 'Add Product'; ?>
        </button>
    </form>
</div>

<!-- Products table -->
<div class="card glass--static">
    <div class="card-header">
        <h2 class="card-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                <line x1="8" y1="21" x2="16" y2="21"/>
                <line x1="12" y1="17" x2="12" y2="21"/>
            </svg>
            All Products
            <span class="badge badge-count num"><?php echo count($products); ?></span>
        </h2>
        <?php if (!empty($products)): ?>
        <div class="search-input-wrap" style="max-width:240px">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="search" id="prodSearch" placeholder="Filter products…"
                   oninput="filterTable('prodSearch','productsTable')">
        </div>
        <?php endif; ?>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
            </svg>
            <p>No products yet. Add your first product using the form above.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table" id="productsTable">
                <thead>
                    <tr>
                        <th>Image</th><th>Title</th><th>Category</th>
                        <th>Brand</th><th>Price</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <?php if ($p['product_image']): ?>
                                <img src="<?php echo BASE_URL . 'images/products/' . htmlspecialchars($p['product_image']); ?>"
                                     alt="<?php echo htmlspecialchars($p['product_title']); ?>"
                                     style="width:52px;height:52px;object-fit:cover;border-radius:var(--radius-xs)">
                            <?php else: ?>
                                <div style="width:52px;height:52px;background:var(--green-050);border-radius:var(--radius-xs);display:flex;align-items:center;justify-content:center">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" style="color:var(--muted-lt)">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        <circle cx="8.5" cy="8.5" r="1.5"/>
                                        <polyline points="21 15 16 10 5 21"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight:600"><?php echo htmlspecialchars($p['product_title']); ?></td>
                        <td><span class="badge badge-customer"><?php echo htmlspecialchars($p['cat_name']); ?></span></td>
                        <td><?php echo htmlspecialchars($p['brand_name']); ?></td>
                        <td class="num" style="font-weight:600">GHS <?php echo number_format((float)$p['product_price'], 2); ?></td>
                        <td>
                            <a href="product.php?edit_id=<?php echo (int)$p['product_id']; ?>" class="btn btn-ghost btn-sm">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                                Edit
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p id="productsTable-empty" style="display:none;color:var(--muted);font-size:.85rem;padding:.75rem 0">
            No products match your search.
        </p>
    <?php endif; ?>
</div>

</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
</main>
</div>

<script>
window.pageFilterConfig = { inputId: 'prodSearch', tableId: 'productsTable' };

function previewProductImage(input) {
    const errEl = document.getElementById('img-error');
    const wrap  = document.getElementById('img-preview-wrap');
    const prev  = document.getElementById('img-preview');

    errEl.textContent = '';
    errEl.style.display = 'none';

    if (!input.files || !input.files[0]) return;

    const file    = input.files[0];
    const allowed = ['image/jpeg','image/png','image/gif','image/webp'];

    if (!allowed.includes(file.type)) {
        errEl.textContent = 'Only JPG, PNG, GIF and WEBP images are allowed.';
        errEl.style.display = 'block';
        input.value = '';
        return;
    }
    if (file.size > 3 * 1024 * 1024) {
        errEl.textContent = 'Image must be under 3 MB.';
        errEl.style.display = 'block';
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        prev.src = e.target.result;
        wrap.style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>
