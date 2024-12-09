<?php
session_start();

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['first_name']) || empty($_SESSION['first_name'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

// AWS Database connection setup
$servername = "project.cac1orfaomky.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "RootUserPassword123!#";
$dbname = "Project";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

$first_name = trim($_SESSION['first_name']); // Trim to avoid leading/trailing spaces

// Update last login and increment login count
$updateStmt = $conn->prepare("UPDATE users SET last_login = NOW(), count = count + 1 WHERE first_name = ?");
if (!$updateStmt) {
    echo json_encode(["status" => "error", "message" => "Failed to prepare update query: " . $conn->error]);
    exit();
}
$updateStmt->bind_param("s", $first_name);
if (!$updateStmt->execute()) {
    echo json_encode(["status" => "error", "message" => "Failed to execute update query: " . $updateStmt->error]);
    exit();
}
$updateStmt->close(); // Close the update statement

// Fetch updated user data
$stmt = $conn->prepare("SELECT first_name, last_name, count, last_login, is_verified FROM users WHERE first_name = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Failed to prepare select query: " . $conn->error]);
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
            "login_count" => $user['count'] + 1, // Increment the count for display
            "last_login" => $user['last_login'] ?: "N/A",
            "is_verified" => $user['is_verified']
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "User not found"]);
}

$stmt->close();
$conn->close();
?>