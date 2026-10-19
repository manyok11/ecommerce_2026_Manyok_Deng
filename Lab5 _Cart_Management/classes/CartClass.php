<?php
// CartClass.php - Model for the cart table
// All SQL for cart operations lives here.
// Cart rows are tied to IP address so guests can shop without an account.
// The column names match the DB schema: p_id, ip_add, c_id, qty.

require_once __DIR__ . '/../core/db_class.php';

class CartClass extends Database {

    // Returns true if this product is already in the cart for this IP.
    public function isInCart($productId, $ip) {
        $row = $this->fetchOne(
            'SELECT p_id FROM cart WHERE p_id = ? AND ip_add = ? LIMIT 1',
            [(int)$productId, $ip]
        );
        return $row !== false;
    }

    // Adds a product to the cart. c_id is optional (null for guests).
    public function addToCart($productId, $qty, $ip, $customerId = null) {
        return $this->execute(
            'INSERT INTO cart (p_id, ip_add, c_id, qty) VALUES (?, ?, ?, ?)',
            [(int)$productId, $ip, $customerId, (int)$qty]
        );
    }

    // Returns all cart rows for this IP with product details joined.
    public function getCartItems($ip) {
        $sql = 'SELECT cart.p_id, cart.qty,
                       products.product_title, products.product_price,
                       products.product_image
                FROM   cart
                JOIN   products ON cart.p_id = products.product_id
                WHERE  cart.ip_add = ?';
        return $this->fetchAll($sql, [$ip]);
    }

    // Returns the total number of items (rows) in the cart for this IP.
    public function getCartCount($ip) {
        $row = $this->fetchOne('SELECT COUNT(*) AS n FROM cart WHERE ip_add = ?', [$ip]);
        return (int)($row['n'] ?? 0);
    }

    // Returns the total price for all items in the cart.
    public function getCartTotal($ip) {
        $items = $this->getCartItems($ip);
        $total = 0.0;
        foreach ($items as $item) {
            $total += (float)$item['product_price'] * (int)$item['qty'];
        }
        return $total;
    }

    // Removes one product from the cart for this IP.
    public function removeFromCart($productId, $ip) {
        return $this->execute(
            'DELETE FROM cart WHERE p_id = ? AND ip_add = ?',
            [(int)$productId, $ip]
        );
    }

    // Updates the quantity of a product in the cart.
    // Quantity must be at least 1.
    public function updateQty($productId, $newQty, $ip) {
        if ((int)$newQty < 1) return false;
        return $this->execute(
            'UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?',
            [(int)$newQty, (int)$productId, $ip]
        );
    }

    // Removes all cart rows for this IP — used after checkout.
    public function clearCart($ip) {
        return $this->execute('DELETE FROM cart WHERE ip_add = ?', [$ip]);
    }
}
