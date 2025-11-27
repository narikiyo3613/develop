<?php
require "admin-check.php";
require "../db-connect.php";

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    die("ユーザーIDが不正です");
}

// ユーザー情報
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("ユーザーが存在しません");
}

// お気に入り一覧
$stmtFav = $pdo->prepare("
    SELECT p.product_id, p.name, p.price, p.image_url
    FROM favorites f
    JOIN products p ON f.product_id = p.product_id
    WHERE f.user_id = ?
");
$stmtFav->execute([$id]);
$favorites = $stmtFav->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー詳細</title>
    <link rel="stylesheet" href="../../css/admin.css">
    <link rel="icon" type="image/png" href="../../image/admin.png">
</head>
<body>

<?php include "dashboard.php"; ?>

<div class="admin-container">

    <h1>ユーザー詳細</h1>

    <div class="user-box">
        <p><strong>ID:</strong> <?= $user['user_id'] ?></p>
        <p><strong>名前:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>メール:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>登録日:</strong> <?= $user['created_at'] ?></p>
    </div>

    <h2>お気に入り商品</h2>

    <?php if (empty($favorites)): ?>
        <p>お気に入りはありません。</p>
    <?php else: ?>
        <div class="favorite-grid">
            <?php foreach ($favorites as $f): ?>
                <div class="fav-card">
                    <img src="../<?= $f['image_url'] ?>" width="120">
                    <p><?= htmlspecialchars($f['name']) ?></p>
                    <p><?= number_format($f['price']) ?>円</p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
