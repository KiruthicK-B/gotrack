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

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    die(json_encode(['message' => 'Invalid JSON input', 'error' => json_last_error_msg()]));
}

// Validate required fields
$requiredFields = ['routeNo', 'departure', 'travelDate', 'passengerName', 'passengerPhone', 'seats'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        http_response_code(400);
        die(json_encode(['message' => 'Missing required field', 'error' => "Field '$field' is required"]));
    }
}

// Extract booking details
$routeNo = (int)$data['routeNo'];
$departure = $data['departure'];
$travelDate = $data['travelDate'];
$passengerName = $data['passengerName'];
$passengerPhone = $data['passengerPhone'];
$seats = $data['seats'];
$price = isset($data['price']) ? (float)$data['price'] : 0;

// Validate phone number
if (!preg_match('/^\d{10}$/', $passengerPhone)) {
    http_response_code(400);
    die(json_encode(['message' => 'Invalid phone number', 'error' => 'Phone number must be 10 digits']));
}

// Validate travel date
$travelTimestamp = strtotime($travelDate);
$todayTimestamp = strtotime(date('Y-m-d'));
if ($travelTimestamp < $todayTimestamp) {
    http_response_code(400);
    die(json_encode(['message' => 'Invalid travel date', 'error' => 'Travel date cannot be in the past']));
}

// Start transaction
$conn->begin_transaction();

try {
    // Check if seats are already booked
    $seatPlaceholders = implode(',', array_fill(0, count($seats), '?'));
    $checkStmt = $conn->prepare("
        SELECT seat_number FROM bookings 
        WHERE route_id = ? AND travel_date = ? AND departure_time = ? AND seat_number IN ($seatPlaceholders) AND is_booked = 1
    ");

    if (!$checkStmt) {
        throw new Exception('Prepare failed for seat check: ' . $conn->error);
    }

    // Create an array of parameters for binding
    $types = 'iss'; // 'i' for route_id, 's' for travel_date, 's' for departure_time
    $params = [$routeNo, $travelDate, $departure];

    // Add seat numbers to the parameters array
    foreach ($seats as $seat) {
        $types .= 'i'; // Add 'i' for each seat number
        $params[] = (int)$seat; // Convert seat number to integer
    }

    // Bind parameters dynamically
    $checkStmt->bind_param($types, ...$params);

    // Execute the statement to check for already booked seats
    if (!$checkStmt->execute()) {
        throw new Exception('Execute failed for seat check: ' . $checkStmt->error);
    }

    $result = $checkStmt->get_result();
    $bookedSeats = array();

    while ($row = $result->fetch_assoc()) {
        $bookedSeats[] = $row['seat_number'];
    }

    $checkStmt->close();

    // If any seats are already booked, return an error
    if (!empty($bookedSeats)) {
        throw new Exception('Some seats are already booked: ' . implode(', ', $bookedSeats));
    }

    // Insert booking details into the database
    $insertStmt = $conn->prepare('
        INSERT INTO bookings (route_id, departure_time, travel_date, passenger_name, passenger_phone, seat_number, price, is_booked)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1)
    ');

    if (!$insertStmt) {
        throw new Exception('Prepare failed for insert: ' . $conn->error);
    }

    foreach ($seats as $seat) {
        $seatNumber = (int)$seat;
        $insertStmt->bind_param('issssdi', $routeNo, $departure, $travelDate, $passengerName, $passengerPhone, $seatNumber, $price);

        if (!$insertStmt->execute()) {
            throw new Exception('Execute failed for insert: ' . $insertStmt->error);
        }
    }

    $insertStmt->close();

    // Commit transaction
    $conn->commit();

    echo json_encode(['message' => 'Booking successful', 'seats' => $seats]);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['message' => 'Booking failed', 'error' => $e->getMessage()]);
} finally {
    // Close connection
    $conn->close();
}
?>