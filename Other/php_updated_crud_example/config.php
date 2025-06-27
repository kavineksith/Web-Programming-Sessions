<?php
// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "test_db";

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Common input validation function
function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Define upload directories
if (!file_exists('uploads/profile_images')) {
    mkdir('uploads/profile_images', 0777, true);
}

if (!file_exists('uploads/post_images')) {
    mkdir('uploads/post_images', 0777, true);
}
?>