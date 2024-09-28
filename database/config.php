<?php
$host = 'localhost';
$dbname = 'schedai';
$username = 'root';
$password = '';
if(!isset($_SESSION)){
    session_start();
}
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
