<?php
session_start();
require "db-connect.php";

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$sql = "INSERT INTO contacts (name, email, message, created_at)
        VALUES (?, ?, ?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([$name, $email, $message]);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせ送信完了</title>
    <link rel="stylesheet" href="../css/inquiry.css">
</head>
<body>
    <div class="container" style="margin-top: 100px; text-align:center;">
        <h1>送信が完了しました！</h1>
        <p>お問い合わせありがとうございました。</p>
        <a href="login/login-top.php" class="return-top-btn">トップへ戻る</a>
    </div>
</body>
</html>
