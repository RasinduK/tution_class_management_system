<?php
// Database connection configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "tuition_class";

// Establishing connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
