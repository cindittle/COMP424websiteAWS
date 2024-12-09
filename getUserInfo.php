<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['first_name'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

// AWS Database connection setup
$servername = "project.cac1orfaomky.us-east-1.rds.amazonaws.com";
$db_username = "admin";
$db_password = "RootUserPassword123!#";
$dbname = "Project";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// Retrieve user data from the database
$first_name = $_SESSION['first_name'];
$stmt = $conn->prepare("SELECT first_name, last_name, count, last_login FROM users WHERE first_name = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Failed to prepare statement"]);
    exit();
}
$stmt->bind_param("s", $first_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        "status" => "success",
        "data" => [
            "first_name" => $user['first_name'],
            "last_name" => $user['last_name'],
            "login_count" => $user['count'],
            "last_login" => $user['last_login'] ?: "N/A"
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "User not found"]);
}

$stmt->close();
$conn->close();
?>
