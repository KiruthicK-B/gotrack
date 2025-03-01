<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case "register_passenger":
            registerPassenger($conn);
            break;

        case "register_driver":
            registerDriver($conn);
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Invalid Action"]);
    }
}

function registerPassenger($conn)
{
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO Users (fname, lname, phone, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fname, $lname, $phone, $email, $password);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Passenger Registered Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Passenger Registration Failed"]);
    }
}

function registerDriver($conn)
{
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $bus_no = $_POST['bus_no'];
    $bus_type = $_POST['bus_type'];
    $route_no = $_POST['route_no'];
    $total_seats = $_POST['total_seats'];
    $start_location = $_POST['start_location'];
    $destination = $_POST['destination'];
    $stops = $_POST['intermediate_stops'];

    $sql = "INSERT INTO Drivers (fname, lname, phone, email, password, bus_no, bus_type, route_no, total_seats, start_location, destination, intermediate_stops)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssisss", $fname, $lname, $phone, $email, $password, $bus_no, $bus_type, $route_no, $total_seats, $start_location, $destination, $stops);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Driver Registered Successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Driver Registration Failed"]);
    }
}
?>
