<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 500px;
            padding: 20px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
            color: white;
            background-color: purple;
            padding: 10px 20px;
            border-radius: 5px;
        }
        a:hover {
            background-color: blueviolet;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Email Verification Required</h2>
        <p>Thank you for registering! Please check your email for a verification link to activate your account.</p>
        <p>If you didn’t receive the email, <a href="resendEmail.php">click here to resend it</a>.</p>
    </div>
</body>
</html>
