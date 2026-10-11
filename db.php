<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "feedback_system";

// Create database connection
$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

// Server-side database error handling
if ($conn->connect_error) {
    die("Database connection failed. Please try again later.");
}

// Set character encoding
$conn->set_charset("utf8mb4");

?>
