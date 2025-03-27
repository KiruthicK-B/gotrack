<?php
$servername = "localhost";
$username = "root";  // Change if needed
$password = "";      // Change if needed
$database = "bus_booking";  // Ensure this matches your database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$name = $_POST['name'];
$phone = $_POST['phone'];
$bus_id = $_POST['bus_id'];
$route_from = $_POST['route_from'];
$route_to = $_POST['route_to'];
$seats = json_decode($_POST['seats'], true);

// Insert passenger details
$sql = "INSERT INTO passenger_details (name, phone) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $name, $phone);
$stmt->execute();
$passenger_id = $stmt->insert_id;  // Get last inserted ID
$stmt->close();

// Insert seat bookings
foreach ($seats as $seat) {
    $sql = "INSERT INTO seat_bookings (passenger_id, bus_id, seat_number, route_from, route_to, status) 
            VALUES (?, ?, ?, ?, ?, 'Booked')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiss", $passenger_id, $bus_id, $seat, $route_from, $route_to);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
echo "Booking confirmed for $name! Seats: " . implode(", ", $seats);
?>
