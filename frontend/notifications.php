<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];

    $sql = "INSERT INTO Notifications (user_id, message) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $message);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Notification Sent"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Notification Failed"]);
    }
}
?>
