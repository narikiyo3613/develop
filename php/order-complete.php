<?php
session_start();
require_once "db-connect.php";

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    exit("ログインしてください");
}

// カート内容取得
$sql = "
    SELECT 
        c.product_id,
        c.quantity,
        p.price
    FROM carts c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cart) {
    exit("カートが空です");
}

// 合計金額
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// orders に追加
$stmt = $pdo->prepare("
    INSERT INTO orders (user_id, total_price, status, created_at)
    VALUES (?, ?, 'pending', NOW())
");
$stmt->execute([$user_id, $total]);

$order_id = $pdo->lastInsertId();

// order_items に追加
$stmt_item = $pdo->prepare("
    INSERT INTO order_items (order_id, product_id, quantity, price)
    VALUES (?, ?, ?, ?)
");

foreach ($cart as $item) {
    $stmt_item->execute([
        $order_id,
        $item['product_id'],
        $item['quantity'],
        $item['price']
    ]);
}

// カートをクリア
$pdo->prepare("DELETE FROM carts WHERE user_id = ?")->execute([$user_id]);

header("Location: order-thanks.php");
exit;
