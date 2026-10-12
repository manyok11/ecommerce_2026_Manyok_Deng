<?php
// CustomerClass.php - Model for the customer table
// All SQL for customers lives here. No HTML, no $_POST, no redirects.
// Extends Database to inherit the PDO connection and query helpers.

require_once __DIR__ . '/../core/db_class.php';

class CustomerClass extends Database {

    // Check if an email is already registered. Used before inserting.
    public function emailExists($email) {
        $row = $this->fetchOne(
            'SELECT customer_id FROM customer WHERE customer_email = ? LIMIT 1',
            [$email]
        );
        return $row !== false;
    }

    // Insert a new customer row.
    // The password must already be hashed before being passed in.
    public function addCustomer($name, $email, $hashedPass, $country, $city, $contact, $image, $role) {
        $sql = 'INSERT INTO customer
                    (customer_name, customer_email, customer_pass,
                     customer_country, customer_city, customer_contact,
                     customer_image, user_role)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        return $this->execute($sql, [$name, $email, $hashedPass, $country, $city, $contact, $image, (int)$role]);
    }

    // Fetch one customer row by email. Used by login().
    // Returns the row array or false if not found.
    public function getCustomerByEmail($email) {
        return $this->fetchOne(
            'SELECT customer_id, customer_name, customer_email, customer_pass,
                    customer_country, customer_city, customer_contact,
                    customer_image, user_role
             FROM   customer
             WHERE  customer_email = ? LIMIT 1',
            [$email]
        );
    }

    // Verify login credentials. Returns the customer row on success,
    // false if the email does not exist or the password is wrong.
    // The same return value for both cases prevents user-enumeration.
    public function login($email, $pass) {
        $customer = $this->getCustomerByEmail($email);
        if (!$customer) return false;
        if (!password_verify($pass, $customer['customer_pass'])) return false;
        unset($customer['customer_pass']); // never pass the hash to the session
        return $customer;
    }

    // Return all customers ordered newest first.
    // customer_pass is excluded so it never reaches a view.
    public function getAllCustomers() {
        return $this->fetchAll(
            'SELECT customer_id, customer_name, customer_email,
                    customer_country, customer_city, customer_contact,
                    customer_image, user_role
             FROM   customer
             ORDER  BY customer_id DESC'
        );
    }

    // Return counts used by the admin dashboard.
    public function getCustomerStats() {
        $total     = $this->fetchOne('SELECT COUNT(*) AS n FROM customer');
        $admins    = $this->fetchOne('SELECT COUNT(*) AS n FROM customer WHERE user_role = 1');
        $customers = $this->fetchOne('SELECT COUNT(*) AS n FROM customer WHERE user_role = 2');
        return [
            'total'         => (int)($total['n']     ?? 0),
            'role_admin'    => (int)($admins['n']    ?? 0),
            'role_customer' => (int)($customers['n'] ?? 0),
        ];
    }

    // Return the N most recently registered customers for the dashboard table.
    public function getRecentCustomers($limit = 5) {
        $limit = (int)$limit;
        return $this->fetchAll(
            "SELECT customer_id, customer_name, customer_email,
                    customer_country, customer_image, user_role
             FROM   customer
             ORDER  BY customer_id DESC
             LIMIT  {$limit}"
        );
    }

    // Save a new avatar filename for a customer.
    public function updateProfileImage($customerId, $filename) {
        return $this->execute(
            'UPDATE customer SET customer_image = ? WHERE customer_id = ?',
            [$filename, (int)$customerId]
        );
    }

    // Clear the avatar, setting it back to NULL.
    public function removeProfileImage($customerId) {
        return $this->execute(
            'UPDATE customer SET customer_image = NULL WHERE customer_id = ?',
            [(int)$customerId]
        );
    }

    // Fetch a single customer by their ID.
    public function getCustomerById($id) {
        return $this->fetchOne(
            'SELECT customer_id, customer_name, customer_email,
                    customer_country, customer_city, customer_contact,
                    customer_image, user_role
             FROM   customer
             WHERE  customer_id = ? LIMIT 1',
            [(int)$id]
        );
    }

    // Alias kept for backward compatibility with older code.
    public function insertCustomer($name, $email, $pass, $country, $city, $contact, $image, $role) {
        return $this->addCustomer($name, $email, $pass, $country, $city, $contact, $image, $role);
    }
}
