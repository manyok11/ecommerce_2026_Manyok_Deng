<?php

// core.php is included at the top of every page in this app.
// It starts the session, sets up shared helper functions, and
// makes sure things like error logging are ready before any
// page code runs.

// Start the session so $_SESSION is available on every page.
// Must happen before any output goes to the browser.
session_start();

// Set the timezone so dates and times are correct for our location.
date_default_timezone_set("Africa/Accra");

// Bring in the Database base class so any model can extend it.
require_once __DIR__ . "/db_class.php";


// -------------------------------------------------------
// HELPER FUNCTIONS
// These are small utility functions that many pages need.
// -------------------------------------------------------

// Send the user to a different page.
// Example: redirect("../views/login.php");
function redirect($url)
{
    header("Location: " . $url);
    exit;
}

// Get the visitor's IP address.
// Used to track guest carts (tasks 11-12).
function get_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Check if someone is logged in.
// Returns true if a customer session exists, false if not.
function is_logged_in()
{
    return isset($_SESSION['customer_id']);
}

// Check if the logged-in user is an admin.
// user_role 1 = admin, user_role 2 = regular customer.
function is_admin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1;
}

// Protect a page so only logged-in users can see it.
// If not logged in, send them to the login page.
function require_login()
{
    if (!is_logged_in()) {
        $_SESSION['error'] = "Please log in to access that page.";
        redirect("../views/login.php");
    }
}

// Protect a page so only admins can see it.
// If not an admin, send them back to the homepage.
function require_admin()
{
    if (!is_admin()) {
        $_SESSION['error'] = "You do not have permission to access that page.";
        redirect("../index.php");
    }
}

// Write an error message to our error log file.
// Example: log_error("Login failed for user: " . $email);
function log_error($message)
{
    error_log(date("Y-m-d H:i:s") . " - " . $message . PHP_EOL, 3, __DIR__ . "/../error/error.log");
}

// define the project root path so all files can use it
define('ROOT_PATH', dirname(__DIR__));
