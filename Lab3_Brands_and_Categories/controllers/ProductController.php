<?php
// ProductController.php - Controller for brand and category operations
// Wraps ProductClass and adds input validation before calling the model.
// Returns structured result arrays. No SQL, no HTML, no redirects here.

require_once __DIR__ . '/../classes/ProductClass.php';

class ProductController {

    private ProductClass $product;

    public function __construct() {
        $this->product = new ProductClass();
    }

    // ---- Brand methods ----

    public function addBrand($name) {
        $name = trim($name);
        if ($name === '')       return ['success' => false, 'error' => 'Brand name cannot be empty.'];
        if (strlen($name) > 100) return ['success' => false, 'error' => 'Brand name is too long (max 100 characters).'];
        $ok = $this->product->addBrand($name);
        return $ok ? ['success' => true] : ['success' => false, 'error' => 'Could not add brand. Please try again.'];
    }

    public function getAllBrands() {
        return $this->product->getAllBrands();
    }

    public function getBrandById($id) {
        return $this->product->getBrandById((int)$id);
    }

    public function updateBrand($id, $name) {
        $id   = (int)$id;
        $name = trim($name);
        if ($id <= 0)           return ['success' => false, 'error' => 'Invalid brand ID.'];
        if ($name === '')       return ['success' => false, 'error' => 'Brand name cannot be empty.'];
        if (strlen($name) > 100) return ['success' => false, 'error' => 'Brand name is too long (max 100 characters).'];
        $ok = $this->product->updateBrand($id, $name);
        return $ok ? ['success' => true] : ['success' => false, 'error' => 'Could not update brand. Please try again.'];
    }

    // ---- Category methods ----

    public function addCategory($name) {
        $name = trim($name);
        if ($name === '')       return ['success' => false, 'error' => 'Category name cannot be empty.'];
        if (strlen($name) > 100) return ['success' => false, 'error' => 'Category name is too long (max 100 characters).'];
        $ok = $this->product->addCategory($name);
        return $ok ? ['success' => true] : ['success' => false, 'error' => 'Could not add category. Please try again.'];
    }

    public function getAllCategories() {
        return $this->product->getAllCategories();
    }

    public function getCategoryById($id) {
        return $this->product->getCategoryById((int)$id);
    }

    public function updateCategory($id, $name) {
        $id   = (int)$id;
        $name = trim($name);
        if ($id <= 0)           return ['success' => false, 'error' => 'Invalid category ID.'];
        if ($name === '')       return ['success' => false, 'error' => 'Category name cannot be empty.'];
        if (strlen($name) > 100) return ['success' => false, 'error' => 'Category name is too long (max 100 characters).'];
        $ok = $this->product->updateCategory($id, $name);
        return $ok ? ['success' => true] : ['success' => false, 'error' => 'Could not update category. Please try again.'];
    }

    // Return brand and category counts for the admin dashboard.
    public function getStoreSummary() {
        return [
            'total_brands'     => count($this->product->getAllBrands()),
            'total_categories' => count($this->product->getAllCategories()),
        ];
    }
}
