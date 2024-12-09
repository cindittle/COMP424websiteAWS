<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// AWS Database connection setup
$servername = "project.cac1orfaomky.us-east-1.rds.amazonaws.com";
$username = "admin";
$password = "RootUserPassword123!#";
$dbname = "Project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user data
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT first_name, last_name, count, last_login, is_verified FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $full_name = $user['first_name'] . " " . $user['last_name'];
    $login_count = $user['count'];
    $last_login = $user['last_login'] ? $user['last_login'] : "N/A";
    $is_verified = $user['is_verified'];
} else {
    header("Location: login.php");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="welcome.css">
</head>
<body>

    <!-- Logout Button -->
    <a href="logout.php" class="logout-button">Logout</a>

    <!-- Centered Content Container -->
    <div class="content-container">
        <h1>Welcome, <span id="user-name"><?php echo htmlspecialchars($full_name); ?></span>!</h1>

        <div class="user-info">
            <p id="login-info">You have logged in <strong><?php echo htmlspecialchars($login_count); ?></strong> times.</p>
            <p id="last-login">Last login date: <strong><?php echo htmlspecialchars($last_login); ?></strong></p>
        </div>

        <!-- Centered Download Link -->
        <div class="download-container">
            <a href="confidential.txt" class="button" download>Download Confidential File</a>
            <?php if (!$is_verified): ?>
                <a href="resendEmail.php" class="button email-button">Email Verification</a>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
