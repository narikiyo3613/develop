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
        p.price,
        p.stock
    FROM carts c
    JOIN products p ON c.product_id = p.product_id
    WHERE c.user_id = ?
    AND p.delete_flag = 1
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cart) {
    exit("カートが空です");
}

// ● 在庫チェック（足りない商品がある場合はエラー）
foreach ($cart as $item) {
    if ($item['stock'] < $item['quantity']) {
        exit("在庫が不足している商品があります: 商品ID " . $item['product_id']);
    }
}

// 合計金額計算
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

// 在庫を減らす処理
$stmt_stock = $pdo->prepare("
    UPDATE products
    SET stock = stock - ?
    WHERE product_id = ?
");

foreach ($cart as $item) {
    // 注文詳細を追加
    $stmt_item->execute([
        $order_id,
        $item['product_id'],
        $item['quantity'],
        $item['price']
    ]);
    echo "在庫減算を試行中: 商品ID " . $item['product_id'] . ", 数量: " . $item['quantity'] . "<br>";
    // 在庫の減算
    $stmt_stock->execute([
        $item['quantity'],
        $item['product_id']
    ]);
}

// カートをクリア
$pdo->prepare("DELETE FROM carts WHERE user_id = ?")->execute([$user_id]);

header("Location: order-thanks.php");
exit;

