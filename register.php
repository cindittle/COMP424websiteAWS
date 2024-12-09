<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $servername = "project.cac1orfaomky.us-east-1.rds.amazonaws.com";
    $username = "admin";
    $password = "RootUserPassword123!#";
    $dbname = "Project";

    // Retrieve form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    // Check if the first name is already taken
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE first_name = ?");
    $check_stmt->execute([$first_name]);
    if ($check_stmt->fetchColumn() > 0) {
        echo "The first name is already taken. Please choose another.";
        exit();
    }
    
    // Generate a unique activation token
    $activation_token = bin2hex(random_bytes(16));
    
    // Save the user to the database
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password, activation_token) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $email, $password, $activation_token]);

    // Send activation email with the token
    $subject = "Activate your account";
    $message = "Click the link to activate your account: http://localhost/activate.php?token=$activation_token";
    mail($email, $subject, $message);
    
    echo "Registration successful. Please check your email to activate your account.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <form method="POST" action="register.php">
        <label>First Name: <input type="text" name="first_name" required></label><br>
        <label>Last Name: <input type="text" name="last_name" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>