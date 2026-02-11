<?php
require "../config/db.php";

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM movies WHERE id=?");
$stmt->execute([$id]);

header("Location: index.php");
exit;
