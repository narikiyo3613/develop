<?php
session_start();
require "../db-connect.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: inquiry.php");
    exit;
}

// フォームデータ取得
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';

// DB へ保存
$sql = "INSERT INTO contacts (name, email, message, created_at)
        VALUES (?, ?, ?, NOW())";
$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $email, $message]);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>送信完了</title>
    <link rel="stylesheet" href="../css/inquiry.css">
</head>

<body>

    <div class="container" style="text-align:center;">
        <h1>お問い合わせを送信しました！</h1>
        <p>内容を確認のうえ、担当者よりご連絡いたします。</p>
        <br>
        <a href="login-top.php" class="back-btn" style="position:static;">トップに戻る</a>
    </div>

</body>
</html>
