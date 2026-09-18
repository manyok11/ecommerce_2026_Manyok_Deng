<?php
// database connection settings
// replace the values below with your actual server credentials

$host    = "localhost";
$db_user = "your_username";   // e.g. manyok.deng
$db_pass = "your_password";   // the password you set for MySQL
$db_name = "your_database";   // e.g. ecommerce_2026A_manyok_deng

// connect to the database
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// stop and show error if connection fails
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
