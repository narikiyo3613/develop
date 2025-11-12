<?php
session_start();
require_once 'db-connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(400);
    exit("ログインしてください");
}

$user_id = $_SESSION['user_id'];
$favorite_id = $_POST['favorite_id'] ?? null;

if ($favorite_id === null) {
    http_response_code(400);
    exit("favorite_id がありません");
}

$sql = "DELETE FROM favorites WHERE favorite_id = ? AND user_id = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$favorite_id, $user_id])) {
    echo "success";
} else {
    http_response_code(500);
    echo "error";
}
