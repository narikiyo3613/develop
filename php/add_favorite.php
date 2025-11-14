<?php
session_start();
require_once 'db-connect.php';

header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'not_logged_in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;

if (!$product_id) {
    echo json_encode(['success' => false, 'error' => 'no_product_id']);
    exit;
}

// すでにお気に入りか確認
$sql = "SELECT favorite_id FROM favorites WHERE user_id = ? AND product_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $product_id]);
$exists = $stmt->fetch();

if ($exists) {
    // → 削除
    $sql = "DELETE FROM favorites WHERE user_id = ? AND product_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $product_id]);

    echo json_encode(['success' => true, 'mode' => 'removed']);
    exit;
}

// → 追加
$sql = "INSERT INTO favorites (user_id, product_id, created_at)
        VALUES (?, ?, NOW())";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $product_id]);

echo json_encode(['success' => true, 'mode' => 'added']);
exit;
