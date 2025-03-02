<?php
$host = "localhost";
$user = "root";   // Change this if necessary
$pass = "";       // Change this if necessary
$db = "gotrack_db";
$port=3306;  // Make sure this database exists!

$conn = new mysqli($host, $user, $pass, $db,$port);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}
?>
