<?php
session_start();
require_once "db-connect.php";

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 注文を取得
$sql = "
    SELECT order_id, total_price, status, created_at 
    FROM orders 
    WHERE user_id = ?
    ORDER BY created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>注文履歴</title>
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
    <link rel="stylesheet" href="../css/order-history.css">
</head>

<body>

<div class="container">

    <a href="#" onclick="history.back(); return false;" class="back-btn">←</a>

    <h1 class="title">注文履歴</h1>

    <?php if (empty($orders)): ?>

        <p class="empty">まだ注文履歴がありません🐾</p>

    <?php else: ?>

        <?php foreach ($orders as $order): ?>

            <div class="order-card">
                <h2>注文番号：#<?= htmlspecialchars($order['order_id']) ?></h2>
                <p class="date">購入日：<?= date('Y年n月j日', strtotime($order['created_at'])) ?></p>
                <p class="price">合計金額：<?= number_format($order['total_price']) ?>円</p>
                
                <a href="order-detail.php?id=<?= $order['order_id'] ?>" class="detail-btn">
                    詳細を見る
                </a>
            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

</body>
</html>
