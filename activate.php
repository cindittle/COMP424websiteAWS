<?php
// activate.php NEWLY MADE
session_start();
include 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if the token exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE activation_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Mark the user as activated
        $stmt = $pdo->prepare("UPDATE users SET is_active = 1 WHERE activation_token = ?");
        $stmt->execute([$token]);
        
        echo "Account activated successfully. Please check your email for a 2FA code.";
        
        // Generate and send the 2FA code
        $two_factor_code = rand(100000, 999999);
        $_SESSION['two_factor_code'] = $two_factor_code;
        
        $subject = "Your 2FA Code";
        $message = "Your 2FA code is: $two_factor_code";
        mail($user['email'], $subject, $message);
        
        // Redirect to 2FA verification
        header("Location: verify-2fa.php");
    } else {
        echo "Invalid activation token.";
    }
}
?>
