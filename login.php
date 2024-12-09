<?php
session_start();

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

$error_message = ""; // For displaying errors

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $username = trim(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'));
    $password = $_POST['password']; // Password doesn't need escaping as it's hashed and verified

    // Retrieve user data from the database
    $stmt = $conn->prepare("SELECT id, username, password, first_name, last_name, count, last_login FROM users WHERE username = ?");
    if (!$stmt) {
        die("Database error: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login: Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['count'] = $user['count'] + 1;
            $_SESSION['last_login'] = $user['last_login'];

            // Update login count and last login time
            $new_count = $user['count'] + 1;
            $now = date("Y-m-d H:i:s");
            $update_stmt = $conn->prepare("UPDATE users SET count = ?, last_login = ? WHERE id = ?");
            if ($update_stmt) {
                $update_stmt->bind_param("isi", $new_count, $now, $user['id']);
                $update_stmt->execute();
                $update_stmt->close();
            } else {
                error_log("Failed to update login count: " . $conn->error);
            }

            // Redirect to welcome page
            header("Location: welcome.php");
            exit();
        } else {
            // Invalid password
            $error_message = "Invalid username or password.";
        }
    } else {
        // Username not found
        $error_message = "User not found.";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        h2 {
            color: black;       
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="password"] {
            width: 27%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            padding: 10px 15px;
            background-color: purple;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: blueviolet;
        }
        .error {
            color: red;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="login.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
</body>
</html>

