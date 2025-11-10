<?php
session_start();
require 'db-connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // 入力チェック
    if ($new !== $confirm) {
        $message = "新しいパスワードが一致しません。";
    } else {
        // 現在のパスワード確認
        $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($current, $user['password'])) {
            // 新しいパスワードをハッシュ化して更新
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $update->execute([$hashed, $user_id]);
            $message = "パスワードを変更しました！";
        } else {
            $message = "現在のパスワードが正しくありません。";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>パスワード変更</title>
    <link rel="stylesheet" href="../css/user_detail.css">
</head>
<body>
<div class="user-detail-container" style="text-align:center; margin-top:80px;">
    <h1><?php echo htmlspecialchars($message); ?></h1>
    <a href="user_detail.php" style="
        display:inline-block;
        background-color:#ff7f7f;
        color:#fff;
        padding:12px 30px;
        border-radius:30px;
        text-decoration:none;
        margin-top:20px;
        transition:0.3s;
    ">マイページに戻る</a>
</div>
</body>
</html>
