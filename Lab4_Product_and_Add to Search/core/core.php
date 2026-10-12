<?php
// core.php - included on every page of the application
// Handles: output buffering, session setup, security headers,
// BASE_URL, error logging, and all shared helper functions.

ob_start(); // buffer output so redirects always work

ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();

// Security headers sent on every response
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

require_once __DIR__ . '/db_class.php';

// Build the absolute URL to the app root once and store it as a constant.
// e.g. http://localhost/shoppn/
if (!defined('BASE_URL')) {
    $scheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host    = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $appRoot = dirname(__DIR__);
    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
    $rel     = str_replace('\\', '/', str_replace($docRoot, '', $appRoot));
    define('BASE_URL', $scheme . '://' . $host . $rel . '/');
}

// ---------- Error logging ----------

function log_error($message, $context = []) {
    $logDir  = dirname(__DIR__) . '/error/';
    $logFile = $logDir . 'error.log';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    $entry = sprintf(
        "[%s] [IP:%s] %s %s\n",
        date('Y-m-d H:i:s'),
        get_ip(),
        $message,
        !empty($context) ? json_encode($context) : ''
    );
    file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

// ---------- Navigation helpers ----------

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function get_ip() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($parts[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// ---------- Session / auth helpers ----------

function is_logged_in() {
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

function is_admin() {
    return is_logged_in() && (int)$_SESSION['user_role'] === 1;
}

// Redirect to login if not logged in. Saves the attempted URL so
// login_action.php can send the user back after a successful login.
function require_login() {
    if (!is_logged_in()) {
        set_flash('error', 'You must be logged in to view that page.');
        $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'] ?? '';
        redirect(defined('BASE_URL') ? BASE_URL . 'views/login.php' : '../views/login.php');
    }
}

// Must be called at the very top of every admin view and action,
// before any HTML output, so unauthorised users are blocked first.
function require_admin() {
    $loginUrl = defined('BASE_URL') ? BASE_URL . 'views/login.php' : '../views/login.php';
    $homeUrl  = defined('BASE_URL') ? BASE_URL . 'index.php'       : '../index.php';
    if (!is_logged_in()) {
        set_flash('error', 'You must be logged in to access that page.');
        redirect($loginUrl);
    }
    if (!is_admin()) {
        set_flash('error', 'You do not have permission to access that page.');
        redirect($homeUrl);
    }
}

// ---------- Flash messages ----------
// Store a message in the session; retrieve it once then clear it.

function set_flash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

function get_flash($key) {
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

// ---------- CSRF protection ----------

// Generate a random token once per session and store it.
// Every form must include this as a hidden field.
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Compare the submitted token against the session token.
// hash_equals() prevents timing attacks.
function csrf_verify() {
    $submitted = $_POST['csrf_token'] ?? '';
    $expected  = $_SESSION['csrf_token'] ?? '';
    if (!hash_equals($expected, $submitted)) {
        log_error('CSRF check failed', ['url' => $_SERVER['REQUEST_URI'] ?? '', 'ip' => get_ip()]);
        set_flash('error', 'Invalid request token. Please try again.');
        $back = $_SERVER['HTTP_REFERER'] ?? (defined('BASE_URL') ? BASE_URL . 'index.php' : '../index.php');
        redirect($back);
    }
}

// ---------- Login rate limiting ----------

// Block login attempts after 5 failures within 15 minutes.
// Keyed on email + IP so a single attacker targeting one account
// cannot also lock out other users on the same network.
function check_login_rate($email) {
    $key     = 'lr_' . md5(strtolower(trim($email)) . get_ip());
    $attempts = $_SESSION[$key]['count'] ?? 0;
    $since    = $_SESSION[$key]['since'] ?? time();

    if (time() - $since > 900) {
        unset($_SESSION[$key]);
        return;
    }
    if ($attempts >= 5) {
        $minutes = ceil((900 - (time() - $since)) / 60);
        set_flash('error', "Too many failed attempts. Please wait {$minutes} minute(s) before trying again.");
        $loginUrl = defined('BASE_URL') ? BASE_URL . 'views/login.php' : '../views/login.php';
        redirect($loginUrl);
    }
}

function record_failed_login($email) {
    $key = 'lr_' . md5(strtolower(trim($email)) . get_ip());
    if (empty($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'since' => time()];
    }
    $_SESSION[$key]['count']++;
}

function clear_login_attempts($email) {
    $key = 'lr_' . md5(strtolower(trim($email)) . get_ip());
    unset($_SESSION[$key]);
}
