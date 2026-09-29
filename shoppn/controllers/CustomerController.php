<?php

// Bring in the CustomerClass model
require_once __DIR__ . '/../classes/CustomerClass.php';

// CustomerController sits between the action files and the model.
// Action files call controller methods.
// The controller calls the model and returns a result.
// This keeps each layer focused on its own job — MVC pattern.

class CustomerController
{
    // holds our CustomerClass instance
    private $customer;

    // create the CustomerClass when the controller is created
    public function __construct()
    {
        $this->customer = new CustomerClass();
    }


    // -------------------------------------------------------
    // TASK 3: REGISTRATION
    // -------------------------------------------------------

    // Register a new customer.
    // Checks email is not taken, hashes the password, then inserts.
    // Returns ['success' => true] or ['success' => false, 'error' => '...']
    public function register($name, $email, $pass, $country, $city, $contact)
    {
        // check if email is already in the database
        if ($this->customer->emailExists($email)) {
            return [
                'success' => false,
                'error'   => 'That email is already registered. Please log in instead.'
            ];
        }

        // hash the password before storing — never store plain text
        $hashedPass = password_hash($pass, PASSWORD_BCRYPT);

        // insert the new customer
        $result = $this->customer->addCustomer($name, $email, $hashedPass, $country, $city, $contact);

        if ($result) {
            return ['success' => true];
        }

        return [
            'success' => false,
            'error'   => 'Registration failed. Please try again.'
        ];
    }


    // -------------------------------------------------------
    // TASK 4: LOGIN
    // -------------------------------------------------------

    // Log a customer in by checking email and password.
    // Returns the customer row on success, or an error array on failure.
    public function login($email, $pass)
    {
        $customer = $this->customer->login($email, $pass);

        if ($customer) {
            return $customer;
        }

        return [
            'success' => false,
            'error'   => 'Incorrect email or password. Please try again.'
        ];
    }

}
