<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "gotrack";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit();
}

if (!isset($_POST["phone"], $_POST["password"])) {
    echo json_encode(["status" => "error", "message" => "Missing phone number or password"]);
    exit();
}

$phone = trim($_POST["phone"]);
$password = trim($_POST["password"]);

function checkUser($conn, $table, $phone) {
    $sql = "SELECT full_name, password_hash FROM `$table` WHERE phone_number = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "SQL error: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

$user = checkUser($conn, "conductor", $phone) ?? checkUser($conn, "passenger", $phone);

if ($user && password_verify($password, $user["password_hash"])) {
    $_SESSION["username"] = $user["full_name"];
    echo json_encode(["status" => "success", "message" => "Login successful"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid phone number or password"]);
}

$conn->close();
?>
