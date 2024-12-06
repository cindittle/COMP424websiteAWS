<?php
include 'config.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);

    try {
        // Check if user exists and is not verified
        $stmt = $pdo->prepare('SELECT activation_token, first_name FROM users WHERE email = :email AND is_active = 0');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate verification link
            $verification_link = "http://yourdomain.com/verifyEmail.php?token=" . $user['activation_token'];

            // Send the email
            $subject = 'Resend Verification Email';
            $message = "Hi " . $user['first_name'] . ",\n\nPlease click the link below to verify your email address:\n" . $verification_link;
            $headers = "From: no-reply@yourdomain.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "A new verification email has been sent to your email address.";
            } else {
                echo "Failed to send the verification email. Please try again.";
            }
        } else {
            echo "No unverified account found for this email.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
