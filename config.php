<?php
// config.php
$dsn = 'project.cac1orfaomky.us-east-1.rds.amazonaws.com';
$username = 'admin'; // Use your DB username
$password = 'RootUserPassword123!#'; // Use your DB password

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
