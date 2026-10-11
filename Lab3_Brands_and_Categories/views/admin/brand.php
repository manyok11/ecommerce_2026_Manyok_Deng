<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();

$pageTitle  = 'Brands — Shoppn Admin';
$activePage = 'brands';

$controller = new ProductController();

// Check if we are in edit mode via ?edit_id=N
$editId     = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$editBrand  = null;
$isEditMode = false;

if ($editId) {
    $editBrand  = $controller->getBrandById($editId);
    $isEditMode = (bool)$editBrand;
    if (!$editBrand) set_flash('error', 'Brand not found.');
}

$brands       = $controller->getAllBrands();
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
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo BASE_URL; ?>views/admin/dashboard.php">Dashboard</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>Brands</span>
        </nav>
        <h1><?php echo $isEditMode ? 'Edit Brand' : 'Manage Brands'; ?></h1>
        <p><?php echo $isEditMode ? 'Update brand details below.' : 'Add new brands and manage existing ones.'; ?></p>
    </div>
</div>

<div class="two-col">

    <!-- Add / Edit form -->
    <div class="card glass--static">
        <div class="card-header">
            <h2 class="card-title">
                <?php if ($isEditMode): ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit Brand
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Add New Brand
                <?php endif; ?>
            </h2>
            <?php if ($isEditMode): ?>
                <a href="brand.php" class="btn btn-ghost btn-sm">Cancel</a>
            <?php endif; ?>
        </div>

        <form method="POST" action="<?php echo BASE_URL; ?>actions/<?php echo $isEditMode ? 'update_brand_action' : 'add_brand_action'; ?>.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
            <?php if ($isEditMode): ?>
                <input type="hidden" name="brand_id" value="<?php echo (int)$editBrand['brand_id']; ?>">
            <?php endif; ?>

            <div class="form-field">
                <label class="form-label" for="brand_name">Brand Name</label>
                <input type="text" id="brand_name" name="brand_name"
                       value="<?php echo $isEditMode ? htmlspecialchars($editBrand['brand_name']) : ''; ?>"
                       placeholder="e.g. Nike" required maxlength="100"
                       oninput="charCount('brand_name','brand_char','100')">
                <span id="brand_char" class="char-counter">0 / 100</span>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                <?php echo $isEditMode ? 'Update Brand' : 'Add Brand'; ?>
            </button>
        </form>
    </div>

    <!-- Brands table -->
    <div class="card glass--static">
        <div class="card-header">
            <h2 class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                    <line x1="8" y1="18" x2="21" y2="18"/>
                    <line x1="3" y1="6" x2="3.01" y2="6"/>
                </svg>
                All Brands
                <span class="badge badge-count num"><?php echo count($brands); ?></span>
            </h2>
            <?php if (!empty($brands)): ?>
            <div class="search-input-wrap" style="max-width:200px">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="search" id="brandSearch" placeholder="Filter brands…"
                       oninput="filterTable('brandSearch','brandsTable')">
            </div>
            <?php endif; ?>
        </div>

        <?php if (empty($brands)): ?>
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                </svg>
                <p>No brands yet. Add your first brand using the form.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table" id="brandsTable">
                    <thead>
                        <tr><th>#</th><th>Brand Name</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($brands as $b): ?>
                        <tr>
                            <td class="num" style="color:var(--muted-lt);font-size:.8rem"><?php echo (int)$b['brand_id']; ?></td>
                            <td><?php echo htmlspecialchars($b['brand_name']); ?></td>
                            <td>
                                <a href="brand.php?edit_id=<?php echo (int)$b['brand_id']; ?>" class="btn btn-ghost btn-sm">
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
            <p id="brandsTable-empty" style="display:none;color:var(--muted);font-size:.85rem;padding:.75rem 0">
                No brands match your search.
            </p>
        <?php endif; ?>
    </div>

</div>

</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
</main>
</div>

<script>
window.pageFilterConfig = { inputId: 'brandSearch', tableId: 'brandsTable' };
document.addEventListener('DOMContentLoaded', function() { charCount('brand_name','brand_char',100); });
</script>
