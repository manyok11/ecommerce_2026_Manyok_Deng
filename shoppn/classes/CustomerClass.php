<?php

// Bring in the Database base class
require_once __DIR__ . "/../core/db_class.php";

// CustomerClass is the MODEL for the customer table.
// Its only job is to talk to the database about customers.
// It does NOT echo HTML, read $_POST, or handle redirects —
// all of that happens in the controller and action files.
// This is the MVC pattern — the model is kept clean and focused.

class CustomerClass extends Database
{

    // -------------------------------------------------------
    // TASK 3: REGISTRATION METHODS
    // -------------------------------------------------------

    // Check if an email is already registered in the database.
    // We call this before inserting a new customer to avoid duplicates.
    // Returns true if the email exists, false if it does not.
    public function emailExists($email)
    {
        $row = $this->fetchOne(
            "SELECT customer_email FROM customer WHERE customer_email = ?",
            "s",
            [$email]
        );
        return $row !== null;
    }

    // Insert a new customer row into the customer table.
    // The password passed in must already be hashed — we never store plain text.
    // user_role defaults to 2 (regular customer). Admin is 1.
    // Returns true on success, false on failure.
    public function addCustomer($name, $email, $pass, $country, $city, $contact)
    {
        return $this->execute(
            "INSERT INTO customer
                (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, customer_image, user_role)
             VALUES (?, ?, ?, ?, ?, ?, NULL, 2)",
            "ssssss",
            [$name, $email, $pass, $country, $city, $contact]
        );
    }


    // -------------------------------------------------------
    // TASK 4: LOGIN METHODS
    // -------------------------------------------------------

    // Find a customer by their email address.
    // Returns the full customer row as an array, or null if not found.
    public function getCustomerByEmail($email)
    {
        return $this->fetchOne(
            "SELECT * FROM customer WHERE customer_email = ?",
            "s",
            [$email]
        );
    }

    // Check if the email and password combination is correct.
    // password_verify() compares the plain text password against
    // the hashed version we stored when the customer registered.
    // Returns the customer row on success, false on failure.
    public function login($email, $pass)
    {
        $customer = $this->getCustomerByEmail($email);

        // no customer found with that email
        if (!$customer) {
            return false;
        }

        // check the typed password against the stored hash
        if (password_verify($pass, $customer['customer_pass'])) {
            return $customer;
        }

        return false;
    }

}
