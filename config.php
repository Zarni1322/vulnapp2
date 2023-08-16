<?php
// Database configuration
$dbHost = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "crm";

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPassword);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if ($conn->query($sql) === TRUE) {
    // Select the database
    $conn->select_db($dbName);

    // Create the users table if it doesn't exist
    $createTableSQL = "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(30) NOT NULL,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL
    )";
    if ($conn->query($createTableSQL) === TRUE) {
        // Table created successfully
    } else {
        echo "Error creating table: " . $conn->error;
    }
} else {
    echo "Error creating database: " . $conn->error;
}

// User registration
$registration_success = "";
$registration_error = "";
?>
