<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gotrack";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

$travelDate = $_GET['travelDate'] ?? '';
$fromLocation = $_GET['from'] ?? '';
$toLocation = $_GET['to'] ?? '';
$busId = $_GET['busId'] ?? '';

if (empty($travelDate)) {
    die(json_encode(["success" => false, "message" => "Missing travel date."]));
}

// Fetch booked seats based on travelDate, from, to, and busId
$sql = "SELECT seats FROM bookings1 WHERE travel_date = ? AND from_location = ? AND to_location = ? AND bus_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(["success" => false, "message" => "SQL Prepare Error: " . $conn->error]));
}
$stmt->bind_param("ssss", $travelDate, $fromLocation, $toLocation, $busId);
$stmt->execute();
$result = $stmt->get_result();

$bookedSeats = [];
while ($row = $result->fetch_assoc()) {
    $bookedSeats = array_merge($bookedSeats, explode(',', $row['seats']));
}

echo json_encode(["success" => true, "bookedSeats" => $bookedSeats]);

$stmt->close();
$conn->close();
?>