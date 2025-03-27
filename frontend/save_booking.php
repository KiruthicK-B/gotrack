<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gotrack";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// Get JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);
if ($data === null) {
    die(json_encode(["success" => false, "message" => "Invalid JSON data"]));
}

// Extract data from JSON
$name = $data['name'] ?? '';
$phone = $data['phone'] ?? '';
$travelDate = $data['travelDate'] ?? '';
$seats = $data['seats'] ?? [];
$fromLocation = $data['from'] ?? '';
$toLocation = $data['to'] ?? '';
$busId = $data['busId'] ?? '';

// Validate input data
if (empty($name) || empty($phone) || empty($travelDate) || empty($seats) || empty($fromLocation) || empty($toLocation) || empty($busId)) {
    die(json_encode(["success" => false, "message" => "All fields are required."]));
}

// Convert seats array to a comma-separated string
$seatsString = implode(',', $seats);

// Check if any seat is already booked
$seatCheckQuery = "SELECT seats FROM bookings1 WHERE travel_date = ? AND from_location = ? AND to_location = ? AND bus_id = ?";
$stmt = $conn->prepare($seatCheckQuery);
if (!$stmt) {
    die(json_encode(["success" => false, "message" => "SQL Prepare Error (Seat Check): " . $conn->error]));
}
$stmt->bind_param("ssss", $travelDate, $fromLocation, $toLocation, $busId);
$stmt->execute();
$result = $stmt->get_result();

$alreadyBooked = [];
while ($row = $result->fetch_assoc()) {
    $bookedSeats = explode(',', $row['seats']);
    $alreadyBooked = array_merge($alreadyBooked, $bookedSeats);
}

// Check for conflicting seats
$selectedSeats = $seats; // No need to decode since $seats is already an array
$conflictingSeats = array_intersect($alreadyBooked, $selectedSeats);

if (!empty($conflictingSeats)) {
    echo json_encode(["success" => false, "message" => "Seats already booked: " . implode(',', $conflictingSeats)]);
    exit();
}

// Insert booking into the database
$sql = "INSERT INTO bookings1 (name, phone, travel_date, seats, from_location, to_location, bus_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(["success" => false, "message" => "SQL Prepare Error (Insert): " . $conn->error]));
}

$stmt->bind_param("sssssss", $name, $phone, $travelDate, $seatsString, $fromLocation, $toLocation, $busId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Booking successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Booking failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>