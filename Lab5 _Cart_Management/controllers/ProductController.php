<?php
// ProductController.php - Controller for brands, categories, and products
// Validates inputs, calls ProductClass, returns result arrays.
// No SQL, no HTML, no redirects here.

require_once __DIR__ . '/../classes/ProductClass.php';

class ProductController {

    private ProductClass $product;

    public function __construct() {
        $this->product = new ProductClass();
    }

    // ---- Brand methods ----

    public function addBrand($name) {
        $name = trim($name);
        if ($name === '')        return ['success' => false, 'error' => 'Brand name cannot be empty.'];
        if (strlen($name) > 100) return ['success' => false, 'error' => 'Brand name is too long (max 100 characters).'];
        $ok = $this->product->addBrand($name);
        return $ok ? ['success' => true] : ['success' => false, 'error' => 'Could not add brand. Please try again.'];
    }

    public function getAllBrands()     { return $this->product->getAllBrands(); }
    public function getBrandById($id) { return $this->product->getBrandById((int)$id); }

    public function updateBrand($id, $name) {
        $id = (int)$id; $name = trim($name);
        if ($id <= 0)            return ['success' => false, 'error' => 'Invalid brand ID.'];
        if ($name === '')        return ['success' => false, 'error' => 'Brand name cannot be empty.'];
        if (strlen($name) > 100) return ['success' => false, 'error' => 'Brand name is too long (max 100 characters).'];
        $ok = $this->product->updateBrand($id, $name);
        return $ok ? ['success' => true] : ['success' => false, 'error' => 'Could not update brand. Please try again.'];
    }

    // ---- Category methods ----

    public function addCategory($name) {
        $name = trim($name);
        if ($name === '')        return ['success' => false, 'error' => 'Category name cannot be empty.'];
        if (strlen($name) > 100) return ['success' => false, 'error' => 'Category name is too long (max 100 characters).'];
        $ok = $this->product->addCategory($name);
        return $ok ? ['success' => true] : ['success' => false, 'error' => 'Could not add category. Please try again.'];
    }

    public function getAllCategories()     { return $this->product->getAllCategories(); }
    public function getCategoryById($id)  { return $this->product->getCategoryById((int)$id); }

    public function updateCategory($id, $name) {
        $id = (int)$id; $name = trim($name);
        if ($id <= 0)            return ['success' => false, 'error' => 'Invalid category ID.'];
        if ($name === '')        return ['success' => false, 'error' => 'Category name cannot be empty.'];
        if (strlen($name) > 100) return ['success' => false, 'error' => 'Category name is too long (max 100 characters).'];
        $ok = $this->product->updateCategory($id, $name);
        return $ok ? ['success' => true] : ['success' => false, 'error' => 'Could not update category. Please try again.'];
    }

    // ---- Product methods ----

    public function addProduct($cat, $brand, $title, $price, $desc, $imageFilename, $keywords) {
        $title = trim($title); $price = (float)$price;
        if ((int)$cat <= 0)       return ['success' => false, 'error' => 'Please select a category.'];
        if ((int)$brand <= 0)     return ['success' => false, 'error' => 'Please select a brand.'];
        if ($title === '')        return ['success' => false, 'error' => 'Product title cannot be empty.'];
        if ($price <= 0)          return ['success' => false, 'error' => 'Price must be greater than zero.'];
        if ($imageFilename === '') return ['success' => false, 'error' => 'A product image is required.'];
        $ok = $this->product->addProduct($cat, $brand, $title, $price, trim($desc), $imageFilename, trim($keywords));
        return $ok ? ['success' => true] : ['success' => false, 'error' => 'Could not add product. Please try again.'];
    }

    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $imageFilename, $keywords) {
        $id = (int)$id; $title = trim($title); $price = (float)$price;
        if ($id <= 0)             return ['success' => false, 'error' => 'Invalid product ID.'];
        if ((int)$cat <= 0)       return ['success' => false, 'error' => 'Please select a category.'];
        if ((int)$brand <= 0)     return ['success' => false, 'error' => 'Please select a brand.'];
        if ($title === '')        return ['success' => false, 'error' => 'Product title cannot be empty.'];
        if ($price <= 0)          return ['success' => false, 'error' => 'Price must be greater than zero.'];
        if ($imageFilename === '') return ['success' => false, 'error' => 'Product image cannot be empty.'];
        $ok = $this->product->updateProduct($id, $cat, $brand, $title, $price, trim($desc), $imageFilename, trim($keywords));
        return $ok ? ['success' => true] : ['success' => false, 'error' => 'Could not update product. Please try again.'];
    }

    public function getProductById($id)         { return $this->product->getProductById((int)$id); }
    public function getAllProducts()             { return $this->product->getAllProducts(); }
    public function getFeaturedProducts($n = 6) { return $this->product->getFeaturedProducts($n); }
    public function getProductsByCategory($id)  { return $this->product->getProductsByCategory((int)$id); }
    public function getProductsByBrand($id)     { return $this->product->getProductsByBrand((int)$id); }
    public function searchProducts($q)          { return $this->product->searchProducts($q); }
    public function getProductCount()           { return $this->product->getProductCount(); }

    // Returns brand, category, and product counts for the dashboard.
    public function getStoreSummary() {
        return [
            'total_brands'     => count($this->product->getAllBrands()),
            'total_categories' => count($this->product->getAllCategories()),
            'total_products'   => $this->product->getProductCount(),
        ];
    }
}
