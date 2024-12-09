<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate CSRF token
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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verify CSRF token
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token. Sent: " . $_POST['csrf_token'] . " | Expected: " . $_SESSION['csrf_token']);
    }

    // Verify CAPTCHA
    $recaptchaSecret = "6LfgNJYqAAAAAFlXnLwfsSmlLWPUt6-LEKyQjVAc";
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $recaptchaSecret,
        'response' => $recaptchaResponse
    ];
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $verifyResponse = file_get_contents($url, false, $context);
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        die("CAPTCHA verification failed. Please try again.");
    }

    // Sanitize input
    $first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
    $last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if first_name or email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE first_name = ? OR email = ?");
    $stmt->bind_param("ss", $first_name, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("First name or email already exists. Please try a different one.");
    }
    $stmt->close();

    // Insert user into the database
    $verification_token = bin2hex(random_bytes(16));
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, verification_token, is_verified) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $password, $verification_token);

    if ($stmt->execute()) {
        // Send verification email
        $verification_link = "https://googleultron.com/verifyEmail.php?token=" . $verification_token;
        $subject = "Email Verification";
        $message = "Hi $first_name,\n\nPlease click the link below to verify your email:\n\n$verification_link\n\nThank you!";
        $headers = "From: no-reply@googleultron.com";

        if (mail($email, $subject, $message, $headers)) {
            header("Location: confirmRegistration.php");
            exit();
        } else {
            error_log("Failed to send verification email to $email");
            echo "Registration successful, but we couldn't send the verification email.";
        }
    } else {
        error_log("Database error: " . $stmt->error);
        echo "Error: " . $stmt->error;
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
        <p>First name or email already taken!</p>
        <a href="index.html">Click here to go back to Registration</a>
    </div>
</body>
</html>

<?php
// Auto redirect to index.html after 10 seconds
header("refresh:10;url=index.html");
exit();
?>
