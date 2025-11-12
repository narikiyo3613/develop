<?php

session_start();

require_once 'db-connect.php';
 
header('Content-Type: application/json; charset=UTF-8');
 
if (!isset($_SESSION['user_id'])) {

    echo json_encode(['success' => false, 'error' => 'not_logged_in']);

    exit;

}
 
$user_id = $_SESSION['user_id'];

$product_id = $_POST['product_id'] ?? null;
 
// 商品IDが送られていない場合

if ($product_id === null) {

    echo json_encode(['success' => false, 'error' => 'no_product_id']);

    exit;

}
 
// すでにお気に入り登録済みか確認

$sql = "SELECT favorite_id FROM favorites WHERE user_id = ? AND product_id = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([$user_id, $product_id]);
 
if ($stmt->fetch()) {

    echo json_encode(['success' => true, 'message' => 'already_exists']);

    exit;

}
 
// favorites に追加

$sql = "INSERT INTO favorites (user_id, product_id, created_at) VALUES (?, ?, NOW())";

$stmt = $pdo->prepare($sql);

$success = $stmt->execute([$user_id, $product_id]);
 
if ($success) {

    echo json_encode(['success' => true]);

} else {

    echo json_encode(['success' => false, 'error' => 'db_insert_failed']);

}

exit;

 