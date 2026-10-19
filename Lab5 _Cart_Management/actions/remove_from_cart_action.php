<?php
// remove_from_cart_action.php - handles the Remove from Cart form POST
// Reads the remove[] checkbox array from the cart form.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . 'views/cart.php');
}

csrf_verify();

$ip         = get_ip();
$controller = new CartController();

// The cart form submits name="remove[]" checkboxes with product IDs as values.
$removeIds = $_POST['remove'] ?? [];

if (!empty($removeIds)) {
    foreach ($removeIds as $pid) {
        $pid = filter_var($pid, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($pid) {
            $controller->removeFromCart($pid, $ip);
        }
    }
    set_flash('success', 'Item(s) removed from cart.');
}

redirect(BASE_URL . 'views/cart.php');
