<?php
session_start();

// AWS Database connection setup
$servername = "project.cac1orfaomky.us-east-1.rds.amazonaws.com";
$db_username = "admin";
$db_password = "RootUserPassword123!#";
$dbname = "Project";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['first_name'])) {
    $first_name = $_SESSION['first_name'];

    $stmt = $conn->prepare("SELECT first_name, last_name, count, last_login FROM users WHERE first_name = ?");
    $stmt->bind_param("s", $first_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode([
            "status" => "success",
            "data" => [
                "first_name" => $user['first_name'],
                "last_name" => $user['last_name'],
                "login_count" => $user['count'],
                "last_login" => $user['last_login']
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
}

$conn->close();
?>
