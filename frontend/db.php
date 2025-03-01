<?php
$conn = new mysqli("localhost", "root", "Kiruthick@2006", "GoTrack_DB");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database Connection Failed"]));
}

header("Content-Type: application/json");
?>
