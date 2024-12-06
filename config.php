<?php
// config.php
$dsn = 'mysql:host=project.cac1orfaomky.us-east-1.rds.amazonaws.com;dbname=Project;charset=utf8mb4';
$username = 'admin'; // Your DB username
$password = 'RootUserPassword123!#'; // Your DB password

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for error handling
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit; // Terminate script if connection fails
}
?>

