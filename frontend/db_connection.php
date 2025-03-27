<?php
$servername = "localhost"; // Change this if your database is on another server
$username = "root"; // Change this to your MySQL username
$password = ""; // Change this to your MySQL password
$database = "gotrack"; // Change this to your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
