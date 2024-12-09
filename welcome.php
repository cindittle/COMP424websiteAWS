<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['first_name'])) {
    header("Location: login.php");
    exit();
}

// AWS Database connection setup
$servername = "project.cac1orfaomky.us-east-1.rds.amazonaws.com";
$db_username = "admin";
$db_password = "RootUserPassword123!#";
$dbname = "Project";

// Establish database connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . htmlspecialchars($conn->connect_error));
}

// Retrieve user data from the database
$first_name = $_SESSION['first_name'];
$stmt = $conn->prepare("SELECT first_name, last_name, count, last_login, is_verified FROM users WHERE first_name = ?");
if (!$stmt) {
    die("Database statement preparation failed: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("s", $first_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    // Fetch user details
    $user = $result->fetch_assoc();
    $full_name = $user['first_name'] . " " . $user['last_name'];
    $login_count = $user['count'];
    $last_login = $user['last_login'] ? $user['last_login'] : "N/A"; // Show "N/A" if no login date is recorded
    $is_verified = $user['is_verified'];
} else {
    // Redirect if no valid user data is found
    header("Location: login.php");
    exit();
}

// Close the database connection
$stmt->close();
$conn->close();
?>
