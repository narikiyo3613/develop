<?php
session_start();
require "../php/db-connect.php";

$admin_id = $_POST['admin_id'] ?? "";
$password = $_POST['password'] ?? "";

if (!$admin_id || !$password) {
    echo "入力が不足しています";
    exit;
}

// 管理者をデータベースから取得
$sql = "SELECT * FROM admins WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin && password_verify($password, $admin['password'])) {

    // ログイン成功
    $_SESSION['admin_login'] = true;
    $_SESSION['admin_name'] = $admin['username'];
    $_SESSION['admin_id'] = $admin['admin_id'];

    header("Location: dashboard.php");
    exit;

} else {
    echo "
        <div style='text-align:center; margin-top:40px; color:#ff7f7f;'>
            <h3>管理者ID または パスワードが違います</h3>
            <a href='login.php' style='color:#ff7f7f;'>ログイン画面へ戻る</a>
        </div>
    ";
    exit;
}
