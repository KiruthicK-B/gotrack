<?php
include "db.php"; // Ensure this file correctly sets up $conn (database connection)
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case "register_passenger":
            registerPassenger($conn);
            break;

        case "register_driver":
            registerDriver($conn);
            break;

        case "signin":
            signIn($conn);
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Invalid action"]);
            break;
    }
}

function registerPassenger($conn) {
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($fname) || empty($lname) || empty($email) || empty($phone) || empty($password)) {
        responseError("All fields are required");
    }

    if (emailExists($conn, $email, "Users")) {
        responseError("Email already exists");
    }

    $hashPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO Users (fname, lname, email, phone, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $fname, $lname, $email, $phone, $hashPassword);

    if ($stmt->execute()) {
        responseSuccess("Passenger registered successfully");
    } else {
        responseError("Passenger registration failed");
    }
}

function registerDriver($conn) {
    $fname = $_POST['fname'] ?? '';
    $lname = $_POST['lname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $bus_no = $_POST['bus_no'] ?? '';
    $route_no = $_POST['route_no'] ?? '';
    $bus_type = $_POST['bus_type'] ?? '';
    $start_location = $_POST['start_location'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $stops = $_POST['intermediate_stops'] ?? '';

    if (empty($fname) || empty($lname) || empty($email) || empty($phone) || empty($password) || empty($bus_no) || empty($route_no)) {
        responseError("All fields are required");
    }

    if (emailExists($conn, $email, "Drivers")) {
        responseError("Email already exists");
    }

    $hashPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO Drivers (fname, lname, email, phone, password, bus_no, route_no, bus_type, start_location, destination, intermediate_stops) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $fname, $lname, $email, $phone, $hashPassword, $bus_no, $route_no, $bus_type, $start_location, $destination, $stops);

    if ($stmt->execute()) {
        responseSuccess("Driver registered successfully");
    } else {
        responseError("Driver registration failed");
    }
}

function signIn($conn) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        responseError("Email and password are required");
    }

    $sql = "SELECT user_id, password FROM Users WHERE email = ? UNION SELECT driver_id, password FROM Drivers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        responseError("Invalid credentials");
    }

    $stmt->bind_result($user_id, $hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        responseSuccess("Login successful", ["user_id" => $user_id, "email" => $email]);
    } else {
        responseError("Invalid credentials");
    }
}

function emailExists($conn, $email, $table) {
    $sql = "SELECT email FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

function responseSuccess($message, $data = []) {
    echo json_encode(array_merge(["status" => "success", "message" => $message], $data));
    exit();
}

function responseError($message) {
    echo json_encode(["status" => "error", "message" => $message]);
    exit();
}
?>
