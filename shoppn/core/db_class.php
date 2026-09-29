<?php

// db_class.php is the base class for all model classes.
// CustomerClass, ProductClass, and CartClass all extend this class,
// which means they all automatically get the database connection
// and the helper query methods below.
//
// This class only handles the connection — it does not know about
// customers, products, or any other business logic.

class Database
{
    // $conn holds the MySQLi connection.
    // It is protected so child classes (like CustomerClass) can use it.
    protected $conn;

    // __construct() runs automatically when we do new CustomerClass()
    // because CustomerClass extends Database.
    // It opens the database connection straight away.
    public function __construct()
    {
        // bring in the credentials from db_cred.php
        require_once __DIR__ . '/db_cred.php';

        // create the MySQLi connection using the constants from db_cred.php
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // if the connection fails, log the error and stop the script
        if ($this->conn->connect_error) {
            error_log($this->conn->connect_error);
            die('Connection failed. Please try again later.');
        }

        // set the character set to utf8mb4 so all characters work correctly
        $this->conn->set_charset('utf8mb4');
    }

    // Use this for SELECT queries that return multiple rows.
    // $sql    = the query string with ? placeholders
    // $types  = a string like "ss" or "si" describing the param types
    // $params = array of values to fill the placeholders
    // Example: $this->fetchAll("SELECT * FROM customer WHERE customer_city = ?", "s", ["Accra"]);
    public function fetchAll($sql, $types = '', $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Use this for SELECT queries that return only one row.
    // Example: looking up one customer by email.
    public function fetchOne($sql, $types = '', $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    // Use this for INSERT, UPDATE, and DELETE queries.
    // Returns true if the query worked, false if it didn't.
    public function execute($sql, $types = '', $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
