<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";  // Change if different
$password = "";  // Change if set
$dbname = "gotrack";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// Ensure request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit();
}

// Validate required fields
if (!isset($_POST["user_type"], $_POST["full_name"], $_POST["phone"], $_POST["password"])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit();
}

// Sanitize and retrieve input
$user_type = $_POST["user_type"];
$full_name = trim($_POST["full_name"]);
$phone = trim($_POST["phone"]);
$password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT); // Hash password

if ($user_type == "conductor") {
    if (!isset($_POST["employee_id"])) {
        echo json_encode(["status" => "error", "message" => "Employee ID is required for conductors"]);
        exit();
    }
    $employee_id = trim($_POST["employee_id"]);

    // Insert into Conductors table using prepared statement
    $stmt = $conn->prepare("INSERT INTO conductor(full_name, phone_number, employee_id, password_hash) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $phone, $employee_id, $password);
} else {
    // Insert into Passengers table using prepared statement
    $stmt = $conn->prepare("INSERT INTO passenger (full_name, phone_number, password_hash) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $full_name, $phone, $password);
}

// Execute the query
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Signup successful"]);
} else {
    echo json_encode(["status" => "error", "message" => "Signup failed: " . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
