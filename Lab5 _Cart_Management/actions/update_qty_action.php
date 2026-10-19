<?php
// update_qty_action.php - handles the Update Quantity form POST
// Reads parallel arrays: qty[] and update[] (product IDs).
// Each quantity must be a positive integer before calling updateQty().

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . 'views/cart.php');
}

csrf_verify();

$ip         = get_ip();
$controller = new CartController();

$qtys      = $_POST['qty']    ?? [];
$productIds = $_POST['update'] ?? [];

foreach ($productIds as $index => $pid) {
    $pid = filter_var($pid, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $qty = filter_var($qtys[$index] ?? 0, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    if ($pid && $qty) {
        $controller->updateQty($pid, $qty, $ip);
    }
}

set_flash('success', 'Cart updated.');
redirect(BASE_URL . 'views/cart.php');
