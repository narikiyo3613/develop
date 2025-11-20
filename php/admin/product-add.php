<?php require "admin-check.php"; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品追加</title>
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>

<h1>商品追加</h1>

<form action="product-add-check.php" method="post" enctype="multipart/form-data" class="admin-form">

    <label>商品名</label>
    <input type="text" name="name" required>

    <label>価格</label>
    <input type="number" name="price" required>

    <label>在庫</label>
    <input type="number" name="stock" required>

    <label>カテゴリ</label>
    <input type="text" name="category" required>

    <label>説明</label>
    <textarea name="description" required></textarea>

    <label>画像</label>
    <input type="file" name="image" accept="../../image/*" required>

    <button type="submit">登録する</button>
</form>

</body>
</html>
