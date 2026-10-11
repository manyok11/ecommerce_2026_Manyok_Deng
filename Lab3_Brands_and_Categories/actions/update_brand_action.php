<?php
// update_brand_action.php - handles the Edit Brand form POST
// brand_id is validated as a positive integer to block tampered or negative IDs.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/brand.php');
}

csrf_verify();

$brandId   = filter_input(INPUT_POST, 'brand_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$brandName = trim(strip_tags($_POST['brand_name'] ?? ''));

if (!$brandId) {
    set_flash('error', 'Invalid brand ID. Please try again.');
    redirect('../views/admin/brand.php');
}

$controller = new ProductController();
$result     = $controller->updateBrand($brandId, $brandName);

if ($result['success']) {
    set_flash('success', 'Brand updated successfully.');
} else {
    set_flash('error', $result['error']);
}

redirect('../views/admin/brand.php');
