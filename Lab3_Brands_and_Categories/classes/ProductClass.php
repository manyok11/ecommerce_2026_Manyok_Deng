<?php
// ProductClass.php - Model for the brands and categories tables
// All SQL for products, brands, and categories lives here.
// No HTML, no $_POST, no redirects.

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
        return $this->fetchOne(
            'SELECT brand_id, brand_name FROM brands WHERE brand_id = ? LIMIT 1',
            [(int)$id]
        );
    }

    public function updateBrand($id, $name) {
        return $this->execute(
            'UPDATE brands SET brand_name = ? WHERE brand_id = ?',
            [$name, (int)$id]
        );
    }

    // ---- Category methods ----

    public function addCategory($name) {
        return $this->execute('INSERT INTO categories (cat_name) VALUES (?)', [$name]);
    }

    public function getAllCategories() {
        return $this->fetchAll('SELECT cat_id, cat_name FROM categories ORDER BY cat_name ASC');
    }

    public function getCategoryById($id) {
        return $this->fetchOne(
            'SELECT cat_id, cat_name FROM categories WHERE cat_id = ? LIMIT 1',
            [(int)$id]
        );
    }

    public function updateCategory($id, $name) {
        return $this->execute(
            'UPDATE categories SET cat_name = ? WHERE cat_id = ?',
            [$name, (int)$id]
        );
    }
}
