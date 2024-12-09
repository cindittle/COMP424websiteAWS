<?php
session_start();
$timeout_duration = 600; // Timeout after 10 minutes of inactivity

// Session timeout handling
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
    session_unset();
    session_destroy();
    header("Location: login.php?message=session_timeout");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['userid'])) {
    header("Location: index.html");
    exit();
}

// Regenerate session ID for security after user is verified
session_regenerate_id(true);

// AWS Database connection
$servername = "project.cac1orfaomky.us-east-1.rds.amazonaws.com";
$db_username = "admin";
$db_password = "RootUserPassword123!#";
$dbname = "Project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Main Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
        }
        h1 {
            color: black;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            color: black;
            margin-bottom: 20px;
        }
        a {
            display: block;
            margin-top: 20px;
            font-size: 14px;
            color: blueviolet;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['first_name'] . " " . $_SESSION['last_name']); ?>!</h1>
        <p>You have logged in <?php echo $_SESSION['count']; ?> times</p>
        <p>Last login date: <?php echo $_SESSION['last_login']; ?></p>
        <a href="company_confidential_file.txt" download>Download Confidential File</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>


