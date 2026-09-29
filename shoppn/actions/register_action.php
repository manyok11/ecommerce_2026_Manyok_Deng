<?php
// show errors to help debug on live server
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Bring in core.php first — this starts the session and loads
// all our helper functions (redirect, is_logged_in, etc.)
require_once __DIR__ . "/../core/core.php";

// Bring in the controller that handles registration logic
require_once __DIR__ . "/../controllers/CustomerController.php";

// This file only runs when the registration form is submitted (POST).
// If someone tries to visit it directly in the browser (GET), send
// them back to the register page straight away.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect("../views/register.php");
}

// -------------------------------------------------------
// STEP 1: Read and sanitise every field from the form
// trim()       removes accidental leading/trailing spaces
// strip_tags() removes any HTML or script tags someone might try to inject
// -------------------------------------------------------
$name    = strip_tags(trim($_POST['customer_name']    ?? ''));
$email   = strip_tags(trim($_POST['customer_email']   ?? ''));
$pass    = trim($_POST['customer_pass']               ?? '');
$confirm = trim($_POST['customer_confirm_pass']        ?? '');
$country = strip_tags(trim($_POST['customer_country'] ?? ''));
$city    = strip_tags(trim($_POST['customer_city']    ?? ''));
$contact = strip_tags(trim($_POST['customer_contact'] ?? ''));

// -------------------------------------------------------
// STEP 2: Server-side validation
// The JavaScript in validate.js already checks these in the
// browser, but we check again here because JS can be disabled
// or bypassed by sending a request directly.
// -------------------------------------------------------

// Check that no required field is empty
if (!$name || !$email || !$pass || !$confirm || !$country || !$city || !$contact) {
    $_SESSION['error'] = "Please fill in all required fields.";
    redirect("../views/register.php");
}

// Validate the email format using PHP's built-in filter
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Please enter a valid email address.";
    redirect("../views/register.php");
}

// Check the email is not too long for the database column (VARCHAR 100)
if (strlen($email) > 100) {
    $_SESSION['error'] = "Email address is too long.";
    redirect("../views/register.php");
}

// Make sure the two password fields match
if ($pass !== $confirm) {
    $_SESSION['error'] = "Passwords do not match. Please try again.";
    redirect("../views/register.php");
}

// Password must be at least 8 characters
if (strlen($pass) < 8) {
    $_SESSION['error'] = "Password must be at least 8 characters long.";
    redirect("../views/register.php");
}

// -------------------------------------------------------
// STEP 3: Call the controller to register the customer
// -------------------------------------------------------
$controller = new CustomerController();
$result = $controller->register($name, $email, $pass, $country, $city, $contact);

// -------------------------------------------------------
// STEP 4: Handle the result
// -------------------------------------------------------
if ($result['success']) {
    // Registration worked — now fetch the new customer's data
    // so we can start their session straight away (log them in).
    $customer = $controller->login($email, $pass);

    // Store the customer's details in the session
    $_SESSION['customer_id']    = $customer['customer_id'];
    $_SESSION['customer_name']  = $customer['customer_name'];
    $_SESSION['customer_email'] = $customer['customer_email'];
    $_SESSION['user_role']      = $customer['user_role'];

    // Send them to the home page
    redirect("../index.php");

} else {
    // Something went wrong — store the error and go back to the form
    $_SESSION['error'] = $result['error'];
    redirect("../views/register.php");
}
