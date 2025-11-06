<?php
require '../db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(16));

    // 重複チェック
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "このメールアドレスはすでに登録されています。";
        exit;
    }

    // 仮登録（名前や住所などは空でOK）
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, token, is_verified, created_at)
        VALUES ('', ?, ?, ?, 0, NOW())
    ");
    $stmt->execute([$email, $hashed, $token]);

    // 認証用URL
    $url = "http://localhost/verify.php?token=" . $token;

    // メール送信内容
    $subject = "【MofuMofu】メールアドレスの確認をお願いします";
    $message = "MofuMofuへの仮登録ありがとうございます。\n以下のリンクから本登録を完了してください。\n\n{$url}\n\n";
    $headers = "From: noreply@aso2401367.cocotte.jp";

    mb_language("Japanese");
    mb_internal_encoding("UTF-8");

    $result = mb_send_mail($email, $subject, $message, $headers);
if ($result) {
    echo "メール送信成功";
} else {
    echo "メール送信失敗";
}

}
?>
