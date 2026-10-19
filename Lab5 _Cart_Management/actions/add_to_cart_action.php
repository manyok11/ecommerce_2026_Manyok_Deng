<?php
// add_to_cart_action.php - handles the Add to Cart link
// Triggered by a GET link: ?add_cart=N
// Cart rows are tied to IP address so guests can shop without logging in.

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$productId = filter_input(INPUT_GET, 'add_cart', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if (!$productId) {
    redirect(BASE_URL . 'index.php');
}

$ip         = get_ip();
$controller = new CartController();

// Pass the logged-in customer ID if available, otherwise null (guest).
$customerId = is_logged_in() ? (int)$_SESSION['customer_id'] : null;

if ($controller->isInCart($productId, $ip)) {
    set_flash('info', 'This item is already in your cart. Go to cart to update the quantity.');
} else {
    $ok = $controller->addToCart($productId, 1, $ip, $customerId);
    if ($ok) {
        set_flash('success', 'Item added to your cart.');
    } else {
        set_flash('error', 'Could not add item to cart. Please try again.');
    }
}

// Send the user back to where they came from.
$back = $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'index.php';
redirect($back);
