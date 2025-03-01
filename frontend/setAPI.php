<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bus_no = $_POST['bus_no'];
    $seat_no = $_POST['seat_no'];
    $user_id = $_POST['user_id'];

    $sql = "UPDATE BusSeats SET seat_status='Occupied', user_id=?, booked_at=NOW() WHERE bus_no=? AND seat_number=? AND seat_status='Available'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $user_id, $bus_no, $seat_no);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Seat Booked Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Seat Booking Failed"]);
    }
}
?>
