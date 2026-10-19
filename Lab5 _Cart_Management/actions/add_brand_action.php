<?php
// add_brand_action.php - handles the Add Brand form POST
// Security order: admin check → POST check → CSRF → sanitise → controller → redirect

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/brand.php');
}

csrf_verify();

$brandName  = trim(strip_tags($_POST['brand_name'] ?? ''));
$controller = new ProductController();
$result     = $controller->addBrand($brandName);

if ($result['success']) {
    set_flash('success', 'Brand "' . htmlspecialchars($brandName) . '" added successfully.');
} else {
    set_flash('error', $result['error']);
}

redirect('../views/admin/brand.php');
