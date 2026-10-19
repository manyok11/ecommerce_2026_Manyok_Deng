<?php
// add_category_action.php - handles the Add Category form POST
// Mirrors add_brand_action.php exactly.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/category.php');
}

csrf_verify();

$catName    = trim(strip_tags($_POST['cat_name'] ?? ''));
$controller = new ProductController();
$result     = $controller->addCategory($catName);

if ($result['success']) {
    set_flash('success', 'Category "' . htmlspecialchars($catName) . '" added successfully.');
} else {
    set_flash('error', $result['error']);
}

redirect('../views/admin/category.php');
