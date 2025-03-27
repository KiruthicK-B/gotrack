<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "gotrack"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the form
$passengerName = $_POST['passengerName'];
$phoneNumber = $_POST['passengerPhone'];
$travelDate = $_POST['travelDate'];
$fromLocation = $_POST['fromLocation'];
$toLocation = $_POST['toLocation'];
$selectedSeats = json_decode($_POST['selectedSeats']); // Array of seat numbers

foreach ($selectedSeats as $seatNumber) {
    // Check if the seat is already booked for the same date & route
    $checkQuery = "SELECT * FROM seat_bookings WHERE travel_date='$travelDate' AND from_location='$fromLocation' AND to_location='$toLocation' AND seat_number=$seatNumber";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Seat $seatNumber is already booked for this date & route."]);
        exit;
    }

    // Insert booking details
    $insertQuery = "INSERT INTO seat_bookings (passenger_name, phone_number, travel_date, from_location, to_location, seat_number) 
                    VALUES ('$passengerName', '$phoneNumber', '$travelDate', '$fromLocation', '$toLocation', $seatNumber)";
    $conn->query($insertQuery);
}

echo json_encode(["status" => "success", "message" => "Booking successful!"]);
$conn->close();
?>
