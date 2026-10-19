<?php
// ProductClass.php - Model for brands, categories, and products
// All SQL lives here. No HTML, no $_POST, no redirects.

require_once __DIR__ . '/../core/db_class.php';

class ProductClass extends Database {

    // ---- Brand methods ----

    public function addBrand($name) {
        return $this->execute('INSERT INTO brands (brand_name) VALUES (?)', [$name]);
    }

    public function getAllBrands() {
        return $this->fetchAll('SELECT brand_id, brand_name FROM brands ORDER BY brand_name ASC');
    }

    public function getBrandById($id) {
        return $this->fetchOne('SELECT brand_id, brand_name FROM brands WHERE brand_id = ? LIMIT 1', [(int)$id]);
    }

    public function updateBrand($id, $name) {
        return $this->execute('UPDATE brands SET brand_name = ? WHERE brand_id = ?', [$name, (int)$id]);
    }

    // ---- Category methods ----

    public function addCategory($name) {
        return $this->execute('INSERT INTO categories (cat_name) VALUES (?)', [$name]);
    }

    public function getAllCategories() {
        return $this->fetchAll('SELECT cat_id, cat_name FROM categories ORDER BY cat_name ASC');
    }

    public function getCategoryById($id) {
        return $this->fetchOne('SELECT cat_id, cat_name FROM categories WHERE cat_id = ? LIMIT 1', [(int)$id]);
    }

    public function updateCategory($id, $name) {
        return $this->execute('UPDATE categories SET cat_name = ? WHERE cat_id = ?', [$name, (int)$id]);
    }

    // ---- Product methods ----

    public function addProduct($cat, $brand, $title, $price, $desc, $imageFilename, $keywords) {
        $sql = 'INSERT INTO products
                    (product_cat, product_brand, product_title, product_price,
                     product_desc, product_image, product_keywords)
                VALUES (?, ?, ?, ?, ?, ?, ?)';
        return $this->execute($sql, [(int)$cat, (int)$brand, $title, $price, $desc, $imageFilename, $keywords]);
    }

    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $imageFilename, $keywords) {
        $sql = 'UPDATE products
                SET product_cat = ?, product_brand = ?, product_title = ?,
                    product_price = ?, product_desc = ?, product_image = ?,
                    product_keywords = ?
                WHERE product_id = ?';
        return $this->execute($sql, [(int)$cat, (int)$brand, $title, $price, $desc, $imageFilename, $keywords, (int)$id]);
    }

    // Returns one product with its category and brand names joined.
    public function getProductById($id) {
        $sql = 'SELECT p.*, c.cat_name, b.brand_name
                FROM   products p
                JOIN   categories c ON p.product_cat   = c.cat_id
                JOIN   brands     b ON p.product_brand = b.brand_id
                WHERE  p.product_id = ? LIMIT 1';
        return $this->fetchOne($sql, [(int)$id]);
    }

    // Returns all products ordered newest first — used by the admin table.
    public function getAllProducts() {
        $sql = 'SELECT p.*, c.cat_name, b.brand_name
                FROM   products p
                JOIN   categories c ON p.product_cat   = c.cat_id
                JOIN   brands     b ON p.product_brand = b.brand_id
                ORDER  BY p.product_id DESC';
        return $this->fetchAll($sql);
    }

    // Returns N randomly selected products for the home page.
    // LIMIT is interpolated as a cast integer — PDO binds LIMIT as a
    // string which some MySQL versions reject.
    public function getFeaturedProducts($limit = 6) {
        $limit = (int)$limit;
        $sql   = "SELECT p.*, c.cat_name, b.brand_name
                  FROM   products p
                  JOIN   categories c ON p.product_cat   = c.cat_id
                  JOIN   brands     b ON p.product_brand = b.brand_id
                  ORDER  BY RAND() LIMIT {$limit}";
        return $this->fetchAll($sql);
    }

    public function getProductsByCategory($catId) {
        $sql = 'SELECT p.*, c.cat_name, b.brand_name
                FROM   products p
                JOIN   categories c ON p.product_cat   = c.cat_id
                JOIN   brands     b ON p.product_brand = b.brand_id
                WHERE  p.product_cat = ? ORDER BY p.product_id DESC';
        return $this->fetchAll($sql, [(int)$catId]);
    }

    public function getProductsByBrand($brandId) {
        $sql = 'SELECT p.*, c.cat_name, b.brand_name
                FROM   products p
                JOIN   categories c ON p.product_cat   = c.cat_id
                JOIN   brands     b ON p.product_brand = b.brand_id
                WHERE  p.product_brand = ? ORDER BY p.product_id DESC';
        return $this->fetchAll($sql, [(int)$brandId]);
    }

    // Searches product title and keywords. Returns empty array if no results.
    public function searchProducts($query) {
        $like = '%' . $query . '%';
        $sql  = 'SELECT p.*, c.cat_name, b.brand_name
                 FROM   products p
                 JOIN   categories c ON p.product_cat   = c.cat_id
                 JOIN   brands     b ON p.product_brand = b.brand_id
                 WHERE  p.product_title LIKE ? OR p.product_keywords LIKE ?
                 ORDER  BY p.product_id DESC';
        return $this->fetchAll($sql, [$like, $like]);
    }

    public function getProductCount() {
        $row = $this->fetchOne('SELECT COUNT(*) AS n FROM products');
        return (int)($row['n'] ?? 0);
    }
}
