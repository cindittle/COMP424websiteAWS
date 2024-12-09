<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $servername = "project.cac1orfaomky.us-east-1.rds.amazonaws.com";
    $db_username = "admin";
    $db_password = "RootUserPassword123!#";
    $dbname = "Project";

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve and sanitize form data
        $first_name = filter_var(trim($_POST['first_name']), FILTER_SANITIZE_STRING);
        $last_name = filter_var(trim($_POST['last_name']), FILTER_SANITIZE_STRING);
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);
        $question1 = filter_var(trim($_POST['question1']), FILTER_SANITIZE_STRING);
        $answer1 = filter_var(trim($_POST['answer1']), FILTER_SANITIZE_STRING);
        $question2 = filter_var(trim($_POST['question2']), FILTER_SANITIZE_STRING);
        $answer2 = filter_var(trim($_POST['answer2']), FILTER_SANITIZE_STRING);

        // Validate required fields
        if (!$first_name || !$last_name || !$email || !$password || !$question1 || !$answer1 || !$question2 || !$answer2) {
            echo "All fields are required.";
            exit();
        }

        // Check if email is already registered
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $check_stmt->execute([$email]);
        if ($check_stmt->fetchColumn() > 0) {
            echo "The email is already registered. Please use another email.";
            exit();
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Generate a unique activation token
        $activation_token = bin2hex(random_bytes(16));

        // Insert the user into the database
        $stmt = $pdo->prepare(
            "INSERT INTO users (first_name, last_name, email, password, question1, answer1, question2, answer2, activation_token) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $first_name, $last_name, $email, $hashed_password,
            $question1, $answer1, $question2, $answer2, $activation_token
        ]);

        // Send the activation email
        $domain = $_SERVER['HTTP_HOST'];
        $activation_link = "http://$domain/activate.php?token=$activation_token";
        $subject = "Activate your account";
        $message = "Click the link to activate your account: $activation_link";
        $headers = "From: no-reply@$domain\r\n" .
                   "Reply-To: no-reply@$domain\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        if (mail($email, $subject, $message, $headers)) {
            echo "Registration successful. Please check your email to activate your account.";
        } else {
            echo "Registration successful, but we couldn't send the activation email.";
        }

    } catch (PDOException $e) {
        echo "Database error: " . htmlspecialchars($e->getMessage());
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #6c63ff;
            outline: none;
            box-shadow: 0 0 4px rgba(108, 99, 255, 0.3);
        }
        button {
            background-color: #6c63ff;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #574bff;
        }
        .form-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .form-footer a {
            color: #6c63ff;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <form method="POST" action="register.php">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Register</button>
        </form>
        <div class="form-footer">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>
</body>
</html>
