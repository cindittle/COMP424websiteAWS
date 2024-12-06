<?php
include 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database
    $stmt = $pdo->prepare('SELECT id FROM users WHERE activation_token = :token AND is_active = 0');
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        // Activate the user and remove the token
        $stmt = $pdo->prepare('UPDATE users SET is_active = 1, activation_token = NULL WHERE id = :id');
        $stmt->execute(['id' => $user['id']]);

        echo 'Your email has been successfully verified! You can now log in.';
        header('refresh:5;url=login.php'); // Redirect to login after 5 seconds
    } else {
        echo 'Invalid or expired token.';
    }
} else {
    echo 'No verification token provided.';
}
?>
