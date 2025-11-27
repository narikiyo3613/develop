<?php
require "admin-check.php";
require "../db-connect.php";

try {
    $sql = "SELECT contact_id, name, email, created_at FROM contacts ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "DBエラー：" . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせ一覧</title>
    <link rel="stylesheet" href="../../css/admin-contact.css">
    <link rel="icon" type="image/png" href="../../image/admin.png">
</head>
<body>

<h1>お問い合わせ一覧</h1>

<a href="dashboard.php" class="back-btn">← 管理トップへ</a>

<div class="contact-table">

    <?php if (empty($contacts)): ?>
        <p>お問い合わせはまだありません。</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>名前</th>
                <th>メール</th>
                <th>日時</th>
                <th></th>
            </tr>

            <?php foreach ($contacts as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['contact_id']) ?></td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= htmlspecialchars($c['created_at']) ?></td>
                    <td>
                        <a href="contact-detail.php?id=<?= $c['contact_id'] ?>" class="detail-btn">詳細</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</div>

</body>
</html>
