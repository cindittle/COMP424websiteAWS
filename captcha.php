<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recaptchaSecret = '6LeKo5AqAAAAAMVnEFt_V0nv9EREr5X-cTZqKza2'; // Your secret key from Google reCAPTCHA

    // The response from reCAPTCHA
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Google reCAPTCHA API URL
    $url = 'https://www.google.com/recaptcha/api/siteverify';

    // Data to send to the API
    $data = [
        'secret' => $recaptchaSecret,
        'response' => $recaptchaResponse
    ];

    // Use cURL to verify the response
    $options = [
        'http' => [
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $verifyResponse = file_get_contents($url, false, $context);
    $responseKeys = json_decode($verifyResponse);

    // Check if the CAPTCHA was successful
    if ($responseKeys->success) {
        echo "CAPTCHA passed, proceed with registration.";
        // Proceed with the registration logic
    } else {
        echo "CAPTCHA failed, please try again.";
    }
}
?>
