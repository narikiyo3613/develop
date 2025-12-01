<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

require "admin-check.php";
require "../db-connect.php";

$user_id = $_POST['user_id'] ?? null;

if (!$user_id) {
    exit("ユーザーIDが指定されていません。");
}

// 完全削除（物理削除）
$stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);

// ユーザー一覧に戻る
header("Location: user-list.php");
exit;
