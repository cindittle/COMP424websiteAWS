<?php
// config.php
$dsn = 'mysql:host=localhost;dbname=your_database';
$username = 'root'; // Use your DB username
$password = ''; // Use your DB password

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
