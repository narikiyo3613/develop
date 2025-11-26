<?php require "admin-check.php"; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>管理者ダッシュボード</title>
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>

<h1>管理者ダッシュボード</h1>

<div class="admin-menu">
    <a href="product-list.php">商品管理</a>
    <a href="product-add.php">商品追加</a>
    <a href="user-list.php">ユーザー管理</a>
    <a href="contact-list.php" class="menu-btn">お問い合わせ一覧</a>
    <a href="order-list.php">注文管理</a>
    <li><a href="product-trash.php">削除済み商品</a></li>
    <a href="logout.php" class="logout">ログアウト</a>
</div>

</body>
</html>
