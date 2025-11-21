<?php
session_start();
require_once "db-connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    echo "注文IDがありません。";
    exit;
}

$user_id = $_SESSION['user_id'];

// 注文情報取得
$sql_order = "
    SELECT * FROM orders
    WHERE order_id = ? AND user_id = ?
";
$stmt = $pdo->prepare($sql_order);
$stmt->execute([$order_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "注文が見つかりません。";
    exit;
}

// 注文商品の取得
$sql_items = "
    SELECT oi.quantity, oi.price, p.name, p.image_url
    FROM order_items AS oi
    JOIN products AS p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
";
$stmt2 = $pdo->prepare($sql_items);
$stmt2->execute([$order_id]);
$items = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>注文詳細</title>
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
    <link rel="stylesheet" href="../css/order-detail.css">
</head>

<body>

<div class="container">

    <a href="order-history.php" class="back-btn">←</a>

    <h1 class="title">注文詳細</h1>

    <div class="order-info">
        <p><strong>注文番号：</strong> #<?= htmlspecialchars($order['order_id']) ?></p>
        <p><strong>注文日：</strong> <?= date('Y年n月j日', strtotime($order['created_at'])) ?></p>
        <p class="total"><strong>合計金額：</strong> <?= number_format($order['total_price']) ?>円</p>
    </div>

    <h2 class="sub-title">購入商品</h2>

    <?php foreach ($items as $item): ?>
        <div class="item-card">
            <img src="<?= htmlspecialchars($item['image_url']) ?>" class="item-img">

            <div class="item-info">
                <p class="name"><?= htmlspecialchars($item['name']) ?></p>
                <p>単価：<?= number_format($item['price']) ?>円</p>
                <p>数量：<?= $item['quantity'] ?></p>
                <p class="subtotal">小計：<?= number_format($item['price'] * $item['quantity']) ?>円</p>
            </div>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>
