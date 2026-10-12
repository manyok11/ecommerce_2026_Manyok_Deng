<?php
// add_product_action.php - handles the Add Product form POST
// Order: admin check → POST check → CSRF → validate fields → validate image → save → DB

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/product.php');
}

csrf_verify();

$cat      = filter_input(INPUT_POST, 'product_cat',   FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$brand    = filter_input(INPUT_POST, 'product_brand', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$title    = trim(strip_tags($_POST['product_title']    ?? ''));
$price    = trim($_POST['product_price']               ?? '');
$desc     = trim(strip_tags($_POST['product_desc']     ?? ''));
$keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));

if (!$cat || !$brand || $title === '' || $price === '') {
    set_flash('error', 'Please fill in all required fields.');
    redirect('../views/admin/product.php');
}

if (!is_numeric($price) || (float)$price <= 0) {
    set_flash('error', 'Price must be a positive number.');
    redirect('../views/admin/product.php');
}

if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
    set_flash('error', 'A product image is required.');
    redirect('../views/admin/product.php');
}

$tmpPath = $_FILES['product_image']['tmp_name'];

if ($_FILES['product_image']['size'] > 3 * 1024 * 1024) {
    set_flash('error', 'Image must be under 3 MB.');
    redirect('../views/admin/product.php');
}

// finfo reads the actual file bytes so a renamed .php file is detected and rejected.
$finfo   = new finfo(FILEINFO_MIME_TYPE);
$mime    = $finfo->file($tmpPath);
$allowed = ['image/jpeg' => 'jpg', 'image/jpg' => 'jpg', 'image/png' => 'png',
            'image/gif' => 'gif', 'image/webp' => 'webp'];

if (!array_key_exists($mime, $allowed)) {
    log_error('Product image rejected', ['mime' => $mime, 'ip' => get_ip()]);
    set_flash('error', 'Only JPG, PNG, GIF, and WEBP images are accepted.');
    redirect('../views/admin/product.php');
}

if (!@getimagesize($tmpPath)) {
    set_flash('error', 'The file does not appear to be a valid image.');
    redirect('../views/admin/product.php');
}

$ext      = $allowed[$mime];
$filename = bin2hex(random_bytes(12)) . '.' . $ext;
$destDir  = __DIR__ . '/../images/products/';
$destPath = $destDir . $filename;

if (!move_uploaded_file($tmpPath, $destPath)) {
    log_error('Product image move failed', ['dest' => $destPath]);
    set_flash('error', 'Could not save the image. Please try again.');
    redirect('../views/admin/product.php');
}

$controller = new ProductController();
$result     = $controller->addProduct($cat, $brand, $title, (float)$price, $desc, $filename, $keywords);

if ($result['success']) {
    set_flash('success', 'Product "' . htmlspecialchars($title) . '" added successfully.');
} else {
    unlink($destPath);
    set_flash('error', $result['error']);
}

redirect('../views/admin/product.php');
