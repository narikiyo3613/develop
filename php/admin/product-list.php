<?php
require "admin-check.php";
require "../db-connect.php";

$stmt = $pdo->query("SELECT user_id, name, email, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー一覧</title>
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>

<?php include "dashboard.php"; ?>

<div class="admin-container">
    <h1>ユーザー一覧</h1>

    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>名前</th>
            <th>メール</th>
            <th>登録日</th>
            <th>操作</th>
        </tr>

        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['user_id'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['created_at'] ?></td>
            <td>
                <a href="user-detail.php?id=<?= $u['user_id'] ?>" class="admin-btn-sm">詳細</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>
