<?php
require "admin-check.php";
require "../db-connect.php";

$contact_id = $_GET['id'] ?? null;

if (!$contact_id) {
    echo "IDが指定されていません。";
    exit;
}

try {
    $sql = "SELECT * FROM contacts WHERE contact_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$contact_id]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "DBエラー：" . $e->getMessage();
    exit;
}

if (!$contact) {
    echo "お問い合わせが見つかりません。";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせ詳細</title>
    <link rel="stylesheet" href="../../css/admin-contact.css">
</head>
<body>

<h1>お問い合わせ詳細</h1>

<a href="contact-list.php" class="back-btn">← 一覧へ戻る</a>

<div class="detail-box">
    <p><strong>ID:</strong> <?= htmlspecialchars($contact['contact_id']) ?></p>
    <p><strong>名前:</strong> <?= htmlspecialchars($contact['name']) ?></p>
    <p><strong>メール:</strong> <?= htmlspecialchars($contact['email']) ?></p>
    <p><strong>送信日時:</strong> <?= htmlspecialchars($contact['created_at']) ?></p>

    <h3>メッセージ内容</h3>
    <div class="msg-box">
        <?= nl2br(htmlspecialchars($contact['message'])) ?>
    </div>
</div>

</body>
</html>
