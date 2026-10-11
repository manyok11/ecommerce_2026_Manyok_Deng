<?php
// db_class.php - base database class
// All model classes extend this to get a shared PDO connection
// and three helper methods for running prepared statements.

require_once __DIR__ . '/db_cred.php';

class Database {

    private $conn;
    private $host     = DB_HOST;
    private $dbname   = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Run a SELECT that may return many rows.
    public function fetchAll($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Run a SELECT that returns one row (e.g. look up a single record by ID).
    public function fetchOne($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Run an INSERT, UPDATE, or DELETE. Returns true/false.
    public function execute($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // Expose the raw connection for edge cases (e.g. transactions).
    public function getConnection() {
        return $this->conn;
    }
}
