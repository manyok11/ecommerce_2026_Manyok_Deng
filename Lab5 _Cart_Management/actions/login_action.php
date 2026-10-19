<?php
// login_action.php - handles the login form POST
// Validates input, checks credentials via the controller,
// sets the session on success, and redirects either way.
// This file never outputs HTML — it only redirects.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../views/login.php');
}

csrf_verify();

$email = trim(strip_tags($_POST['login_email'] ?? ''));
$pass  = $_POST['login_pass'] ?? '';

if ($email === '' || $pass === '') {
    set_flash('error', 'Please enter your email and password.');
    redirect('../views/login.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Please enter a valid email address.');
    redirect('../views/login.php');
}

check_login_rate($email);

$controller = new CustomerController();
$result     = $controller->login($email, $pass);

if ($result['success']) {
    $customer = $result['customer'];

    // Regenerate the session ID after login to prevent session fixation.
    session_regenerate_id(true);
    clear_login_attempts($email);

    $_SESSION['customer_id']    = $customer['customer_id'];
    $_SESSION['customer_name']  = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role']      = $customer['user_role'];
    if (!empty($customer['customer_image'])) {
        $_SESSION['customer_image'] = $customer['customer_image'];
    }

    $destination = $_SESSION['redirect_to'] ?? '../index.php';
    unset($_SESSION['redirect_to']);

    set_flash('success', 'Welcome back, ' . htmlspecialchars($customer['customer_name']) . '!');
    redirect($destination);

} else {
    record_failed_login($email);
    set_flash('error', $result['error']);
    redirect('../views/login.php');
}
