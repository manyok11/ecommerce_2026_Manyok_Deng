<?php
// head.php - shared <head> block for every admin page
// $pageTitle and $activePage must be set before this is included.
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../../core/core.php';
}
$pageTitle = $pageTitle ?? 'Admin — Shoppn';
// Use a relative path for the CSS so it works on any server
// regardless of how BASE_URL resolves the tilde (~) path.
$cssBase = '../../../../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-body">
