<?php
// upload_avatar_action.php - handles the profile photo upload
// Security steps applied in order:
//   1. Login check
//   2. POST only
//   3. CSRF token
//   4. File present and no upload error
//   5. Size limit: 2 MB
//   6. MIME type validated with finfo (reads actual file bytes, not extension)
//   7. Secondary check with getimagesize()
//   8. Random filename generated with bin2hex(random_bytes(16))
//   9. File moved to uploads/avatars/ (PHP execution blocked there via .htaccess)
//  10. Old avatar file deleted
//  11. Database updated and session refreshed

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if (!is_logged_in()) {
    redirect('../views/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/profile.php');
}

csrf_verify();

if (!isset($_FILES['avatar_file']) || $_FILES['avatar_file']['error'] !== UPLOAD_ERR_OK) {
    $code = $_FILES['avatar_file']['error'] ?? -1;
    set_flash('error', $code === UPLOAD_ERR_NO_FILE ? 'No file was selected.' : 'Upload error (code ' . $code . ').');
    redirect('../views/admin/profile.php');
}

$tmpPath = $_FILES['avatar_file']['tmp_name'];

if ($_FILES['avatar_file']['size'] > 2 * 1024 * 1024) {
    set_flash('error', 'Image must be under 2 MB.');
    redirect('../views/admin/profile.php');
}

// finfo reads the actual file bytes — a renamed .php file will fail this check.
$finfo    = new finfo(FILEINFO_MIME_TYPE);
$mimeReal = $finfo->file($tmpPath);
$allowed  = ['image/jpeg' => 'jpg', 'image/jpg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

if (!array_key_exists($mimeReal, $allowed)) {
    log_error('Avatar rejected: bad MIME', ['mime' => $mimeReal, 'ip' => get_ip()]);
    set_flash('error', 'Only JPG, PNG, and WEBP images are accepted.');
    redirect('../views/admin/profile.php');
}

if (!@getimagesize($tmpPath)) {
    set_flash('error', 'The file does not appear to be a valid image.');
    redirect('../views/admin/profile.php');
}

$ext      = $allowed[$mimeReal];
$newName  = bin2hex(random_bytes(16)) . '.' . $ext;
$destDir  = __DIR__ . '/../uploads/avatars/';
$destPath = $destDir . $newName;

if (!move_uploaded_file($tmpPath, $destPath)) {
    log_error('Avatar move failed', ['dest' => $destPath]);
    set_flash('error', 'Could not save the image. Please try again.');
    redirect('../views/admin/profile.php');
}

// Delete the previous avatar file if one exists.
$controller  = new CustomerController();
$currentUser = $controller->getCustomerById((int)$_SESSION['customer_id']);
$oldFile     = $currentUser['customer_image'] ?? null;

if ($oldFile && $oldFile !== $newName) {
    $oldPath = $destDir . $oldFile;
    if (file_exists($oldPath) && is_file($oldPath)) {
        unlink($oldPath);
    }
}

$ok = $controller->updateProfileImage((int)$_SESSION['customer_id'], $newName);

if ($ok) {
    $_SESSION['customer_image'] = $newName;
    set_flash('success', 'Profile photo updated successfully.');
} else {
    unlink($destPath);
    set_flash('error', 'Could not save photo to database. Please try again.');
}

redirect('../views/admin/profile.php');
