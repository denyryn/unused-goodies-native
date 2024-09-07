<?php
require __DIR__ . '/../../../root.php';
require ROOT_DIR . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_DIR);
$dotenv->load();

$host = $_ENV['HOST'];
$username = $_ENV['USERNAME'];
$password = $_ENV['PASSWORD'];
$database = $_ENV['DATABASE'];

try {
    $pdo = new PDO("mysql:host={$host};dbname={$database}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}