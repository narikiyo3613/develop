<?php
require "admin-check.php";
require "../db-connect.php";

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    echo "注文IDがありません";
    exit;
}

// 注文情報 + ユーザー情報
$sql_order = "
    SELECT o.*, u.name AS user_name, u.email
    FROM orders AS o
    JOIN users AS u ON o.user_id = u.user_id
    WHERE o.order_id = ?
";
$stmt = $pdo->prepare($sql_order);
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "注文が見つかりません。";
    exit;
}

// 注文アイテム
$sql_items = "
    SELECT oi.*, p.name, p.image_url
    FROM order_items AS oi
    JOIN products AS p ON oi.product_id = p.product_id
    WHERE order_id = ?
";
$stmt2 = $pdo->prepare($sql_items);
$stmt2->execute([$order_id]);
$items = $stmt2->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>注文詳細</title>
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>

<a href="order-list.php" class="back-btn">← 注文一覧へ</a>
<h1>注文詳細</h1>

<div class="detail-box">

    <h2>注文情報</h2>
    <p><strong>注文ID：</strong><?= $order['order_id'] ?></p>
    <p><strong>ユーザー：</strong><?= htmlspecialchars($order['user_name']) ?>（<?= htmlspecialchars($order['email']) ?>）</p>
    <p><strong>注文日：</strong><?= $order['created_at'] ?></p>
    <p><strong>ステータス：</strong><?= htmlspecialchars($order['status']) ?></p>
    <p><strong>合計金額：</strong><?= number_format($order['total_price']) ?>円</p>

    <h2>購入商品</h2>

    <?php foreach ($items as $i): ?>
        <div class="item-row">
            <img src="../<?= htmlspecialchars($i['image_url']) ?>" class="item-img">
            <div>
                <p><strong><?= htmlspecialchars($i['name']) ?></strong></p>
                <p>単価：<?= number_format($i['price']) ?>円</p>
                <p>数量：<?= $i['quantity'] ?></p>
                <p>小計：<?= number_format($i['price'] * $i['quantity']) ?>円</p>
            </div>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>
