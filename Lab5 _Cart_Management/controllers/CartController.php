<?php
// CartController.php - Controller for cart operations
// Wraps CartClass. No SQL, no HTML, no redirects here.

require_once __DIR__ . '/../classes/CartClass.php';

class CartController {

    private CartClass $cart;

    public function __construct() {
        $this->cart = new CartClass();
    }

    public function isInCart($productId, $ip) {
        return $this->cart->isInCart((int)$productId, $ip);
    }

    public function addToCart($productId, $qty, $ip, $customerId = null) {
        return $this->cart->addToCart((int)$productId, (int)$qty, $ip, $customerId);
    }

    public function getCartItems($ip) {
        return $this->cart->getCartItems($ip);
    }

    public function getCartCount($ip) {
        return $this->cart->getCartCount($ip);
    }

    public function getCartTotal($ip) {
        return $this->cart->getCartTotal($ip);
    }

    public function removeFromCart($productId, $ip) {
        return $this->cart->removeFromCart((int)$productId, $ip);
    }

    public function updateQty($productId, $newQty, $ip) {
        return $this->cart->updateQty((int)$productId, (int)$newQty, $ip);
    }

    public function clearCart($ip) {
        return $this->cart->clearCart($ip);
    }
}
