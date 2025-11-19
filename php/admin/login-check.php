<?php
session_start();

// とりあえず簡易版の管理者ユーザー
$admin_id = "mofu";
$admin_pass = "mofumofu5";

$input_id = $_POST['admin_id'] ?? "";
$input_pass = $_POST['password'] ?? "";

if ($input_id === $admin_id && $input_pass === $admin_pass) {
    $_SESSION['admin_login'] = true;
    header("Location: dashboard.php");
    exit;
} else {
    echo "<p style='text-align:center;color:red;margin-top:40px;'>ログイン情報が間違っています。</p>";
    echo "<p style='text-align:center;'><a href='login.php'>戻る</a></p>";
}
