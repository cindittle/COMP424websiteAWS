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
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birth_date = $_POST['birth_date'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Retrieve security questions and answers
    $question1 = $_POST['question1'];
    $answer1 = password_hash($_POST['answer1'], PASSWORD_BCRYPT);

    $question2 = $_POST['question2'];
    $answer2 = password_hash($_POST['answer2'], PASSWORD_BCRYPT);

    $question3 = $_POST['question3'];
    $answer3 = password_hash($_POST['answer3'], PASSWORD_BCRYPT);

    // Check if username is taken
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: index.html?error=username_taken");
        exit();
    } else {
        $stmt->close();

        // Generate a unique verification token
        $verification_token = bin2hex(random_bytes(16));

        // Insert user data (with verification status as pending) into the database
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, birth_date, email, username, password, count, last_login, question1, answer1, question2, answer2, question3, answer3, verification_token, is_verified) VALUES (?, ?, ?, ?, ?, ?, 1, NOW(), ?, ?, ?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("sssssssssssssss", $first_name, $last_name, $birth_date, $email, $username, $password, $question1, $answer1, $question2, $answer2, $question3, $answer3, $verification_token);

        if ($stmt->execute()) {
            // Send verification email
            $verification_link = "https://googleultron.com/verifyEmail.php?token=" . $verification_token;
            $subject = "Email Verification";
            $message = "Hi $first_name,\n\nPlease click the link below to verify your email address:\n\n$verification_link\n\nThank you!";
            $headers = "From: no-reply@googleultron.com";

            if (mail($email, $subject, $message, $headers)) {
                // Redirect to the email verification confirmation page
                header("Location: confirmRegistration.php");
                exit();
            } else {
                echo "Registration successful, but we couldn't send the verification email.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        p {
            font-size: 24px;
            color: rebeccapurple;
            font-weight: bold;
            margin-bottom: 20px;
        }
        a {
            font-size: 18px;
            color: blueviolet;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="error-message">
        <p>Username already taken!</p>
        <a href="index.html">Click here to go back to Registration</a>
    </div>
</body>
</html>

<?php
// Auto redirect to index.html after 10 seconds
header("refresh:10;url=index.html");
exit();
?>


