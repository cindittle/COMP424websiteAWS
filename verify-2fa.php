<?php
// verify-2fa.php NEWLY MADE
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = $_POST['two_factor_code'];
    
    if ($entered_code == $_SESSION['two_factor_code']) {
        // Successful 2FA verification, allow login
        echo "2FA Verified. You can now access your account.";
        header("Location: login.php");
    } else {
        echo "Invalid 2FA code.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>2FA Verification</title>
</head>
<body>
    <form method="POST" action="verify-2fa.php">
        <label>Enter 2FA Code: <input type="text" name="two_factor_code" required></label><br>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
