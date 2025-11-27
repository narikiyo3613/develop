<?php
session_start();
require '../db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // 入力チェック
    if (empty($email) || empty($password)) {
        echo "メールアドレスとパスワードを入力してください。";
        exit;
    }

    // 登録済みユーザーを検索（認証済みの人のみ）
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_verified = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // 認証成功 → セッションに情報を保存
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['email'] = $user['email'];

        // ログイン後のページへリダイレクト
        header("Location: login-top.php");
        exit;
    } else {
        echo "
        <div style='
            font-family:\"Hiragino Kaku Gothic ProN\",sans-serif;
            text-align:center;
            padding:60px;
            color:#ff7f7f;
        '>
            <h2>メールアドレスまたはパスワードが間違っています。</h2>
            <a href='login.php' style='color:#ff7f7f;text-decoration:underline;'>ログインページに戻る</a>
        </div>
        ";
    }
}
?>