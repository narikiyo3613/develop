<?php
session_start();
require 'db-connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, address = ?, phone = ? WHERE user_id = ?");
    $stmt->execute([$name, $email, $address, $phone, $user_id]);

    header("Location: user_detail.php");
    exit;
}
?>
