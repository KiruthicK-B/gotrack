<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'gotrack';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['message' => 'Database connection failed', 'error' => $conn->connect_error]));
}

// Validate input parameters
if (!isset($_GET['routeNo']) || !isset($_GET['travelDate']) || !isset($_GET['departure'])) {
    http_response_code(400);
    die(json_encode(['message' => 'Missing required parameters', 'error' => 'routeNo, travelDate, and departure are required']));
}

$routeNo = (int)$_GET['routeNo'];
$travelDate = $_GET['travelDate'];
$departure = $_GET['departure']; // Get departure time from query params

// Fetch booked seats
try {
    $stmt = $conn->prepare('
        SELECT seat_number 
        FROM bookings 
        WHERE route_id = ? 
        AND travel_date = ? 
        AND departure_time = ? 
        AND is_booked = 1
    ');

    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param('iss', $routeNo, $travelDate, $departure); // Bind departure time

    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    $bookedSeats = [];

    while ($row = $result->fetch_assoc()) {
        $bookedSeats[] = $row['seat_number'];
    }

    echo json_encode(['bookedSeats' => $bookedSeats]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Failed to fetch booked seats', 'error' => $e->getMessage()]);
} finally {
    if (isset($stmt) && $stmt) {
        $stmt->close();
    }
    $conn->close();
}
?>