<?php
session_start();

// Database connection setup (AWS parameters)
$servername = "your-aws-rds-endpoint";  // Replace with your AWS RDS endpoint
$username = "your-aws-username";        // Replace with your AWS RDS username
$password = "your-aws-password";        // Replace with your AWS RDS password
$dbname = "your-database-name";         // Replace with your AWS database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: User has submitted a username and security answer
    if (isset($_POST['username']) && isset($_POST['security_answer'])) {
        $username = $_POST['username'];
        $security_answer = $_POST['security_answer'];

        // Retrieve the stored security answer
        $stmt = $conn->prepare("SELECT userid, security_answer FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($userid, $stored_answer);
            $stmt->fetch();

            // Verify if the security answer matches
            if ($security_answer === $stored_answer) {
                // Step 2: Generate a reset token and expiration timestamp
                $token = bin2hex(random_bytes(16));
                $expires = date("Y-m-d H:i:s", strtotime('+15 minutes'));

                // Update the user's record with the token and expiration
                $token_stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE userid = ?");
                $token_stmt->bind_param("ssi", $token, $expires, $userid);
                $token_stmt->execute();
                $token_stmt->close();
                
                // Create the reset link
                $reset_link = "https://yourwebsite.com/forgot_password.php?token=$token"; 
                echo "<p>A reset link has been sent to your email. <a href='$reset_link'>Reset Password</a></p>";
            } else {
                echo "<p>Incorrect security answer. Please try again.</p>";
            }
        } else {
            echo "<p>No user found with that username.</p>";
        }

        $stmt->close();

    // Step 3: User has submitted a new password and token for reset
    } elseif (isset($_POST['new_password']) && isset($_POST['token'])) {
        $token = $_POST['token'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        // Validate the reset token
        $stmt = $conn->prepare("SELECT userid FROM users WHERE reset_token = ? AND reset_expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($userid);
            $stmt->fetch();

            // Update the password, clear reset token and expiration
            $update_stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE userid = ?");
            $update_stmt->bind_param("si", $new_password, $userid);

            if ($update_stmt->execute()) {
                echo "<p>Your password has been reset successfully. You may now <a href='login.php'>log in</a>.</p>";
            } else {
                echo "<p>Error updating password. Please try again.</p>";
            }

            $update_stmt->close();
        } else {
            echo "<p>Invalid or expired token.</p>";
        }

        $stmt->close();
    }
} elseif (isset($_GET['token'])) {
    // Step 4: Display the password reset form if a token is provided in the URL
    $token = $_GET['token'];
    ?>

    <!-- Password Reset Form -->
    <div class="form-container">
        <form method="POST" action="forgot_password.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label>New Password:
                <input type="password" name="new_password" required>
            </label>
            <input type="submit" value="Reset Password">
        </form>
    </div>

    <?php
} else {
    // Step 5: Display the initial form to enter username and security answer
    ?>

    <!-- Request Reset Form -->
    <div class="form-container">
        <form method="POST" action="forgot_password.php">
            <label>Username:
                <input type="text" name="username" required>
            </label>
            <label>Security Answer:
                <input type="text" name="security_answer" required>
            </label>
            <input type="submit" value="Request Password Reset">
        </form>
    </div>

    <?php
}
$conn->close();
?>

<!-- CSS Styling for the Form -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f2f5;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .form-container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 300px;
        text-align: center;
    }
    form label {
        display: block;
        margin: 10px 0 5px;
        font-size: 14px;
    }
    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        border: none;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    p {
        font-size: 14px;
        color: #333;
    }
</style>

