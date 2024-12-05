<?php
// register.php NEWLY MADE IN CASE REG.PHP GIVES ISSUE
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
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
