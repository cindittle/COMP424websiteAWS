<?php
session_start();

// AWS Database connection setup
$servername = "project.cac1orfaomky.us-east-1.rds.amazonaws.com";  // AWS RDS endpoint
$username = "admin";                                               // AWS username
$password = "RootUserPassword123!#";                               // AWS password
$dbname = "Project";                                               // AWS database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user data from the database
    $stmt = $conn->prepare("SELECT username, password, first_name, last_name, count, last_login FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['count'] = $user['count'] + 1;
            $_SESSION['last_login'] = $user['last_login'];

            // Update login count and last login time
            $new_count = $user['count'] + 1;
            $now = date("Y-m-d H:i:s");
            $update_stmt = $conn->prepare("UPDATE users SET count = ?, last_login = ? WHERE username = ?");
            $update_stmt->bind_param("iss", $new_count, $now, $username);
            $update_stmt->execute();

            // Redirect to welcome.html
            header("Location: welcome.html");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "User not found.";
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
        hr {
            margin: 40px 0;
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
</body>
</html>

