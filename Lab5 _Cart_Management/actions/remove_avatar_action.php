<?php
// remove_avatar_action.php - deletes the profile photo
// Removes the file from disk, clears the DB column, and unsets the session key.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if (!is_logged_in()) {
    redirect('../views/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/admin/profile.php');
}

csrf_verify();

$controller  = new CustomerController();
$currentUser = $controller->getCustomerById((int)$_SESSION['customer_id']);
$oldFile     = $currentUser['customer_image'] ?? null;

if ($oldFile) {
    $oldPath = __DIR__ . '/../uploads/avatars/' . $oldFile;
    if (file_exists($oldPath) && is_file($oldPath)) {
        unlink($oldPath);
    }
}

$ok = $controller->removeProfileImage((int)$_SESSION['customer_id']);

if ($ok) {
    unset($_SESSION['customer_image']);
    set_flash('success', 'Profile photo removed.');
} else {
    set_flash('error', 'Could not remove photo. Please try again.');
}

redirect('../views/admin/profile.php');
