<?php
require "admin-check.php";
require "../db-connect.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "IDが指定されていません";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "商品が見つかりません。";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品編集</title>
    <link rel="stylesheet" href="../../css/admin-edit.css">
</head>
<body>

<a href="product-list.php" class="back-btn">← 商品一覧へ戻る</a>
<h1>商品編集</h1>

<div class="edit-container">

    <form action="product-edit-check.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

        <label>商品名</label>
        <input type="text" name="name" required value="<?= htmlspecialchars($product['name']) ?>">

        <label>価格</label>
        <input type="number" name="price" required value="<?= htmlspecialchars($product['price']) ?>">

        <label>カテゴリ</label>
        <input type="text" name="category" required value="<?= htmlspecialchars($product['category']) ?>">

        <label>在庫</label>
        <input type="number" name="stock" required value="<?= htmlspecialchars($product['stock']) ?>">

        <label>商品画像（変更する場合のみ選択）</label>
        <img src="../<?= htmlspecialchars($product['image_url']) ?>" alt="" class="preview-img">
        <input type="file" name="image">

        <label>説明文</label>
        <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

        <button type="submit" class="submit-btn">更新する</button>

    </form>

</div>

</body>
</html>
