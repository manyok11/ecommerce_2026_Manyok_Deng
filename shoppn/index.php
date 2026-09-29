<?php
// show errors temporarily to help debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// __DIR__ gives the absolute path to this file's folder
require_once __DIR__ . "/core/core.php";

// set root for views
$root = "./";

// Load the home page view
require_once __DIR__ . "/views/home.php";

// flush the output buffer
ob_end_flush();
