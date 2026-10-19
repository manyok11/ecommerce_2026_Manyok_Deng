<?php
// update_product_action.php - handles the Edit Product form POST
// If a new image is uploaded it replaces the old one.
// If no new image is selected the existing filename is kept.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/product.php');
}

csrf_verify();

$productId   = filter_input(INPUT_POST, 'product_id',    FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$cat         = filter_input(INPUT_POST, 'product_cat',   FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$brand       = filter_input(INPUT_POST, 'product_brand', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$title       = trim(strip_tags($_POST['product_title']    ?? ''));
$price       = trim($_POST['product_price']               ?? '');
$desc        = trim(strip_tags($_POST['product_desc']     ?? ''));
$keywords    = trim(strip_tags($_POST['product_keywords'] ?? ''));
$existingImg = trim($_POST['existing_image']              ?? '');

if (!$productId || !$cat || !$brand || $title === '' || $price === '') {
    set_flash('error', 'Please fill in all required fields.');
    redirect('../views/admin/product.php');
}

if (!is_numeric($price) || (float)$price <= 0) {
    set_flash('error', 'Price must be a positive number.');
    redirect('../views/admin/product.php?edit_id=' . (int)$productId);
}

$destDir  = __DIR__ . '/../images/products/';
$filename = $existingImg;

if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $tmpPath = $_FILES['product_image']['tmp_name'];

    if ($_FILES['product_image']['size'] > 3 * 1024 * 1024) {
        set_flash('error', 'Image must be under 3 MB.');
        redirect('../views/admin/product.php?edit_id=' . (int)$productId);
    }

    $finfo   = new finfo(FILEINFO_MIME_TYPE);
    $mime    = $finfo->file($tmpPath);
    $allowed = ['image/jpeg' => 'jpg', 'image/jpg' => 'jpg', 'image/png' => 'png',
                'image/gif' => 'gif', 'image/webp' => 'webp'];

    if (!array_key_exists($mime, $allowed)) {
        set_flash('error', 'Only JPG, PNG, GIF, and WEBP images are accepted.');
        redirect('../views/admin/product.php?edit_id=' . (int)$productId);
    }

    if (!@getimagesize($tmpPath)) {
        set_flash('error', 'The file does not appear to be a valid image.');
        redirect('../views/admin/product.php?edit_id=' . (int)$productId);
    }

    $newFilename = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];

    if (move_uploaded_file($tmpPath, $destDir . $newFilename)) {
        if ($existingImg && file_exists($destDir . $existingImg)) {
            unlink($destDir . $existingImg);
        }
        $filename = $newFilename;
    } else {
        set_flash('error', 'Could not save the new image. Please try again.');
        redirect('../views/admin/product.php?edit_id=' . (int)$productId);
    }
}

$controller = new ProductController();
$result     = $controller->updateProduct($productId, $cat, $brand, $title, (float)$price, $desc, $filename, $keywords);

if ($result['success']) {
    set_flash('success', 'Product "' . htmlspecialchars($title) . '" updated successfully.');
    redirect('../views/admin/product.php');
} else {
    set_flash('error', $result['error']);
    redirect('../views/admin/product.php?edit_id=' . (int)$productId);
}
