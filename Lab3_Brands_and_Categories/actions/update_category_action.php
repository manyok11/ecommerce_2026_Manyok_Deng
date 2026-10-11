<?php
// update_category_action.php - handles the Edit Category form POST
// Mirrors update_brand_action.php exactly.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/category.php');
}

csrf_verify();

$catId   = filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$catName = trim(strip_tags($_POST['cat_name'] ?? ''));

if (!$catId) {
    set_flash('error', 'Invalid category ID. Please try again.');
    redirect('../views/admin/category.php');
}

$controller = new ProductController();
$result     = $controller->updateCategory($catId, $catName);

if ($result['success']) {
    set_flash('success', 'Category updated successfully.');
} else {
    set_flash('error', $result['error']);
}

redirect('../views/admin/category.php');
