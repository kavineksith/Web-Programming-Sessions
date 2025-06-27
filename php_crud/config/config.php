<?php

$hostname = 'localhost'; // MySQL server hostname
$username = 'root'; // MySQL username
$password = ''; // MySQL password
$database = 'school_management_system'; // Database name

$conn = new mysqli($hostname, $username, $password, $database); // Connect to MySQL server
// new mysqli() or mysqli_connect() is used to establish a connection to the MySQL database server.
// It takes four parameters: hostname, username, password, and database name.

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Check connection
}

?>