<?php
// Database connection settings
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'test_db';

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate and sanitize user input
function validateInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>