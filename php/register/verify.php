<?php
require '../db-connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE token = ? AND is_verified = 0");
    $stmt->execute([$token]);

    if ($stmt->rowCount() > 0) {
        $update = $pdo->prepare("UPDATE users SET is_verified = 1, token = NULL WHERE token = ?");
        $update->execute([$token]);

        echo "本登録が完了しました！ログインページへお進みください。";
    } else {
        echo "このリンクは無効か、すでに登録済みです。";
    }
} else {
    echo "不正なアクセスです。";
}
?>
