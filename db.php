<?php
session_start();

$host = "127.0.0.1";
$db   = "movie_critics";
$user = "root";
$pass = "";
$port = 3306;

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
