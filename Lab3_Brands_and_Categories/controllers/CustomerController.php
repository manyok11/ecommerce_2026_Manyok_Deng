<?php
// CustomerController.php - Controller for customer operations
// Sits between the action files and CustomerClass.
// Receives plain data, calls the model, and returns results.
// No SQL, no HTML, no redirects here.

require_once __DIR__ . '/../classes/CustomerClass.php';

class CustomerController {

    private CustomerClass $customer;

    public function __construct() {
        $this->customer = new CustomerClass();
    }

    // Register a new customer. Returns a result array.
    public function register($name, $email, $hashedPass, $country, $city, $contact, $image, $role) {
        if ($this->customer->emailExists($email)) {
            return ['success' => false, 'error' => 'That email address is already registered.'];
        }
        $ok = $this->customer->addCustomer($name, $email, $hashedPass, $country, $city, $contact, $image, $role);
        return $ok
            ? ['success' => true]
            : ['success' => false, 'error' => 'Registration failed. Please try again.'];
    }

    // Verify login credentials. Returns the customer row or an error array.
    public function login($email, $pass) {
        $customer = $this->customer->login($email, $pass);
        return $customer
            ? ['success' => true, 'customer' => $customer]
            : ['success' => false, 'error' => 'Invalid email or password.'];
    }

    public function getAllCustomers() {
        return $this->customer->getAllCustomers();
    }

    public function getCustomerStats() {
        return $this->customer->getCustomerStats();
    }

    public function getRecentCustomers($limit = 5) {
        return $this->customer->getRecentCustomers($limit);
    }

    public function updateProfileImage($customerId, $filename) {
        return $this->customer->updateProfileImage($customerId, $filename);
    }

    public function removeProfileImage($customerId) {
        return $this->customer->removeProfileImage($customerId);
    }

    public function getCustomerById($id) {
        return $this->customer->getCustomerById($id);
    }

    // Backward-compatibility aliases used by older code in this project.
    public function selectAll() {
        return $this->getAllCustomers();
    }

    public function insert($name, $email, $pass, $country, $city, $contact, $image, $role) {
        return $this->customer->addCustomer($name, $email, $pass, $country, $city, $contact, $image, $role);
    }
}
