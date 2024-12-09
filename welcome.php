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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="welcome.css">
    <script src="welcome.js" defer></script>
    <style>
        .email-button {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
            border-radius: 5px;
        }
        .email-button:hover {
            background-color: darkorange;
        }
        .content-container {
            max-width: 600px;
            margin: auto;
            text-align: center;
        }
        .download-container {
            margin-top: 20px;
        }
    </style>
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
                <a href="resendEmail.php" class="email-button">Verify Email</a>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
