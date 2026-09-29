<?php
// show errors to help debug on live server
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start the session and load helper functions
require_once __DIR__ . "/../core/core.php";

// Bring in the controller that handles login logic
require_once __DIR__ . "/../controllers/CustomerController.php";

// Only run this file when the login form is submitted (POST).
// Anyone visiting directly via GET gets sent to the login page.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect("../views/login.php");
}

// -------------------------------------------------------
// STEP 1: Read and sanitise the form fields
// -------------------------------------------------------
$email = strip_tags(trim($_POST['customer_email'] ?? ''));
$pass  = trim($_POST['customer_pass']             ?? '');

// -------------------------------------------------------
// STEP 2: Server-side validation
// -------------------------------------------------------
if (!$email || !$pass) {
    $_SESSION['error'] = "Please enter your email and password.";
    redirect("../views/login.php");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Please enter a valid email address.";
    redirect("../views/login.php");
}

// -------------------------------------------------------
// STEP 3: Call the controller to check the credentials
// -------------------------------------------------------
$controller = new CustomerController();
$result = $controller->login($email, $pass);

// -------------------------------------------------------
// STEP 4: Handle the result
// -------------------------------------------------------

// If login() returned an array with 'success' => false, it failed
if (isset($result['success']) && $result['success'] === false) {
    $_SESSION['error'] = $result['error'];
    redirect("../views/login.php");
}

// If we get here, $result is the full customer row — login worked!
// Store what we need in the session so every page knows who is logged in.
$_SESSION['customer_id']    = $result['customer_id'];
$_SESSION['customer_name']  = $result['customer_name'];
$_SESSION['customer_email'] = $result['customer_email'];
$_SESSION['user_role']      = $result['user_role'];

// Send them to the home page
redirect("../index.php");
