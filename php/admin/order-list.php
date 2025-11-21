<?php
require "admin-check.php";
require "../db-connect.php";

// 全注文 + ユーザー名
$sql = "
    SELECT o.order_id, o.total_price, o.status, o.created_at,
            u.name AS user_name
    FROM orders AS o
    JOIN users AS u ON o.user_id = u.user_id
    ORDER BY o.created_at DESC
";
$stmt = $pdo->query($sql);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>注文一覧</title>
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>

<h1>注文一覧</h1>
<a href="dashboard.php" class="back-btn">← 管理トップへ</a>

<table class="admin-table">
    <tr>
        <th>注文ID</th>
        <th>ユーザー名</th>
        <th>金額</th>
        <th>状態</th>
        <th>注文日</th>
        <th>詳細</th>
    </tr>

    <?php foreach ($orders as $o): ?>
        <tr>
            <td><?= $o['order_id'] ?></td>
            <td><?= htmlspecialchars($o['user_name']) ?></td>
            <td><?= number_format($o['total_price']) ?>円</td>
            <td><?= htmlspecialchars($o['status']) ?></td>
            <td><?= date('Y-m-d H:i', strtotime($o['created_at'])) ?></td>
            <td>
                <a href="order-detail.php?id=<?= $o['order_id'] ?>" class="btn">見る</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
