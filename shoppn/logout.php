<?php
// Bring in core so session_start() has already run
require_once "core/core.php";

// Clear every value stored in the session
$_SESSION = [];

// Delete the session cookie from the browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy the session completely on the server side
session_destroy();

// Send the user back to the login page after logging out
redirect("views/login.php");
