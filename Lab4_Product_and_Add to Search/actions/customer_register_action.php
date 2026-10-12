<?php
// customer_register_action.php - handles the registration form POST
// Returns JSON because the form is submitted via fetch() in validate.js.
// Validates every field server-side — JS validation can be bypassed.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Manual CSRF check — csrf_verify() redirects, but this endpoint returns JSON.
$submittedToken = $_POST['csrf_token'] ?? '';
$expectedToken  = $_SESSION['csrf_token'] ?? '';
if (!hash_equals($expectedToken, $submittedToken)) {
    log_error('CSRF failed on registration', ['ip' => get_ip()]);
    echo json_encode(['success' => false, 'message' => 'Invalid request token. Please refresh and try again.']);
    exit;
}

$name    = trim(strip_tags($_POST['customer_name']    ?? ''));
$email   = trim(strip_tags($_POST['customer_email']   ?? ''));
$pass    =      ($_POST['customer_pass']              ?? '');
$country = trim(strip_tags($_POST['customer_country'] ?? ''));
$city    = trim(strip_tags($_POST['customer_city']    ?? ''));
$contact = trim(strip_tags($_POST['customer_contact'] ?? ''));

if ($name === '' || $email === '' || $pass === '' || $country === '' || $city === '' || $contact === '') {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled.']);
    exit;
}

if (strlen($name) > 100) {
    echo json_encode(['success' => false, 'message' => 'Name is too long (max 100 characters).']);
    exit;
}

if (strlen($email) > 50) {
    echo json_encode(['success' => false, 'message' => 'Email is too long (max 50 characters).']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

if (!preg_match('/^[0-9+\-\s]{7,15}$/', $contact)) {
    echo json_encode(['success' => false, 'message' => 'Contact must be 7-15 digits (+ - spaces allowed).']);
    exit;
}

// Password must have uppercase, lowercase, digit, and special character.
// This mirrors the PASSWORD_REGEX in validate.js exactly.
$pwPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+\[\]{};\':"\\|,.<>\\/?]).{8,}$/';
if (!preg_match($pwPattern, $pass)) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters with uppercase, lowercase, number, and special character.']);
    exit;
}

$hashedPass = password_hash($pass, PASSWORD_BCRYPT);

$controller = new CustomerController();
$result     = $controller->register($name, $email, $hashedPass, $country, $city, $contact, null, 2);

if ($result['success']) {
    echo json_encode(['success' => true, 'message' => 'Registration successful! You can now log in.']);
} else {
    echo json_encode(['success' => false, 'message' => $result['error']]);
}
