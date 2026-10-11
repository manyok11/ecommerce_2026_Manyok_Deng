<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

require_admin();

$pageTitle  = 'Categories — Shoppn Admin';
$activePage = 'categories';

$controller = new ProductController();

// Check if we are in edit mode via ?edit_id=N
$editId     = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$editCat    = null;
$isEditMode = false;

if ($editId) {
    $editCat    = $controller->getCategoryById($editId);
    $isEditMode = (bool)$editCat;
    if (!$editCat) set_flash('error', 'Category not found.');
}

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
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo BASE_URL; ?>views/admin/dashboard.php">Dashboard</a>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>Categories</span>
        </nav>
        <h1><?php echo $isEditMode ? 'Edit Category' : 'Manage Categories'; ?></h1>
        <p><?php echo $isEditMode ? 'Update category details below.' : 'Add new categories and manage existing ones.'; ?></p>
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
                    Edit Category
                <?php else: ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Add New Category
                <?php endif; ?>
            </h2>
            <?php if ($isEditMode): ?>
                <a href="category.php" class="btn btn-ghost btn-sm">Cancel</a>
            <?php endif; ?>
        </div>

        <form method="POST" action="<?php echo BASE_URL; ?>actions/<?php echo $isEditMode ? 'update_category_action' : 'add_category_action'; ?>.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
            <?php if ($isEditMode): ?>
                <input type="hidden" name="cat_id" value="<?php echo (int)$editCat['cat_id']; ?>">
            <?php endif; ?>

            <div class="form-field">
                <label class="form-label" for="cat_name">Category Name</label>
                <input type="text" id="cat_name" name="cat_name"
                       value="<?php echo $isEditMode ? htmlspecialchars($editCat['cat_name']) : ''; ?>"
                       placeholder="e.g. Electronics" required maxlength="100"
                       oninput="charCount('cat_name','cat_char','100')">
                <span id="cat_char" class="char-counter">0 / 100</span>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                <?php echo $isEditMode ? 'Update Category' : 'Add Category'; ?>
            </button>
        </form>
    </div>

    <!-- Categories table -->
    <div class="card glass--static">
        <div class="card-header">
            <h2 class="card-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                    <line x1="8" y1="18" x2="21" y2="18"/>
                </svg>
                All Categories
                <span class="badge badge-count num"><?php echo count($categories); ?></span>
            </h2>
            <?php if (!empty($categories)): ?>
            <div class="search-input-wrap" style="max-width:200px">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="search" id="catSearch" placeholder="Filter categories…"
                       oninput="filterTable('catSearch','catsTable')">
            </div>
            <?php endif; ?>
        </div>

        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                    <line x1="8" y1="18" x2="21" y2="18"/>
                </svg>
                <p>No categories yet. Add your first one using the form.</p>
            </div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table" id="catsTable">
                    <thead>
                        <tr><th>#</th><th>Category Name</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td class="num" style="color:var(--muted-lt);font-size:.8rem"><?php echo (int)$cat['cat_id']; ?></td>
                            <td><?php echo htmlspecialchars($cat['cat_name']); ?></td>
                            <td>
                                <a href="category.php?edit_id=<?php echo (int)$cat['cat_id']; ?>" class="btn btn-ghost btn-sm">
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
            <p id="catsTable-empty" style="display:none;color:var(--muted);font-size:.85rem;padding:.75rem 0">
                No categories match your search.
            </p>
        <?php endif; ?>
    </div>

</div>

</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
</main>
</div>

<script>
window.pageFilterConfig = { inputId: 'catSearch', tableId: 'catsTable' };
document.addEventListener('DOMContentLoaded', function() { charCount('cat_name','cat_char',100); });
</script>
