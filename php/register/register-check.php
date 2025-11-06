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
    $url = "https://aso2401367.cocotte.jp/2025/develop/php/register/verify.php?token=" . $token;

    // メール送信内容
    $subject = "【MofuMofu】メールアドレスの確認をお願いします";
    $message = "MofuMofuへの仮登録ありがとうございます。\n以下のリンクから本登録を完了してください。\n\n{$url}\n\n";
    $headers = "From: noreply@aso2401367.cocotte.jp";

    mb_language("Japanese");
    mb_internal_encoding("UTF-8");

    $result = mb_send_mail($email, $subject, $message, $headers);

    if ($result) {
        echo "
        <div style='
            font-family: \"Hiragino Kaku Gothic ProN\", sans-serif;
            background-color:#f8fbff;
            text-align:center;
            padding:80px 20px;
        '>
            <h1 style='color:#ff7f7f;'>仮登録が完了しました！</h1>
            <p style='font-size:1.1rem;'>
                ご登録いただいたメールアドレス宛に、<br>
                本登録用のリンクをお送りしました。<br><br>
                メールをご確認のうえ、<br>
                24時間以内に登録を完了してください。
            </p>
            <a href='../html/top.html' style='
                display:inline-block;
                margin-top:30px;
                background-color:#ff7f7f;
                color:white;
                padding:12px 30px;
                border-radius:30px;
                text-decoration:none;
                font-weight:bold;
                transition:0.3s;
            '>トップページに戻る</a>
        </div>
        ";
    } else {
        echo "<p>メール送信に失敗しました。時間をおいて再度お試しください。</p>";
    }


}
?>
