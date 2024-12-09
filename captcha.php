<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recaptchaSecret = '6LfgNJYqAAAAAFlXnLwfsSmlLWPUt6-LEKyQjVAc'; // Replace with your reCAPTCHA secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? ''; // Use null coalescing operator for safety

    // Ensure reCAPTCHA response is not empty
    if (empty($recaptchaResponse)) {
        die("CAPTCHA verification is required. Please try again.");
    }

    // Validate reCAPTCHA response with Google
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $recaptchaSecret,
        'response' => $recaptchaResponse,
        'remoteip' => $_SERVER['REMOTE_ADDR'] // Optional: Adds user's IP for validation
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    // Use stream_context_create for the request
    $context = stream_context_create($options);
    $verifyResponse = file_get_contents($url, false, $context);
    $responseData = json_decode($verifyResponse);

    // Check if CAPTCHA was successful
    if ($responseData && $responseData->success) {
        echo "CAPTCHA verification passed.";
        // Proceed with registration logic here
    } else {
        die("CAPTCHA verification failed. Please try again.");
    }
} else {
    die("Invalid request method.");
}
?>
