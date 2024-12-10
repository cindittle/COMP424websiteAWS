<?php
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Connect to the database
    $servername = "project.c5wsgw6cudhr.us-east-1.rds.amazonaws.com";
    $db_username = "admin";
    $db_password = "RootUserPassword123!#";
    $dbname = "Project";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Validate the token
    $stmt = $conn->prepare("SELECT id FROM users WHERE verification_token = ? AND is_verified = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Mark the user as verified
        $update_stmt = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
        $update_stmt->bind_param("i", $user['id']);
        if ($update_stmt->execute()) {
            echo "Email verified successfully!";
        } else {
            echo "Failed to verify email. Please try again.";
        }
        $update_stmt->close();
    } else {
        echo "Invalid or expired token.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>