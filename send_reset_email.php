<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure the POST request contains an email
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['email'])) {
    // Sanitize and validate the email
    $user_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        // Generate a unique token
        $token = bin2hex(random_bytes(16)); // Generate a 32-character token

        // Save token and email to the database (example query)
        // Assuming you have a PDO connection in $pdo
        // $pdo->query("INSERT INTO password_resets (email, token) VALUES ('$user_email', '$token')");SQL PASSWORDRESETTABLE.SQL

        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com';
            $mail->Password = 'your_gmail_app_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('your_email@gmail.com', 'googleultron.com');
            $mail->addAddress($user_email); // Dynamic recipient

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = '<p>Click <a href="http://yourdomain.com/reset_password.php?token=' . $token . '">here</a> to reset your password.</p>';
            $mail->AltBody = 'Click this link to reset your password: http://yourdomain.com/reset_password.php?token=' . $token;

            $mail->send();
            echo 'Password reset email has been sent to ' . htmlspecialchars($user_email) . '.';
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Invalid email address.";
    }
} else {
    echo "No email provided.";
}
?>
