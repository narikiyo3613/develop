<?php
session_start();
require_once 'db-connect.php';

if (!isset($_SESSION['user_id'])) {
    exit("ログインしてください");
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;

// 商品IDが来てないとき
if ($product_id === null) {
    exit("商品が選択されていません");
}

// ✅ すでにお気に入り登録済みか確認
$sql = "SELECT favorite_id FROM favorites WHERE user_id = ? AND product_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $product_id]);

if ($stmt->fetch()) {
    // すでに登録 → 何もせず検索結果へ戻る
    header("Location: searchresults.php");
    exit;
}

// ✅ favorites に追加
$sql = "INSERT INTO favorites (user_id, product_id, created_at) VALUES (?, ?, NOW())";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $product_id]);

// ✅ 元の検索画面へ
header("Location: searchresults.php");
exit;
