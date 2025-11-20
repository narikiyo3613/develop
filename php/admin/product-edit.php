<?php
require "admin-check.php";
require "../db-connect.php";

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    die("商品IDが不正です");
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("商品が存在しません");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品編集</title>
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>

<?php include "dashboard.php"; ?>

<div class="admin-container">
    <h1>商品編集</h1>

    <form action="product-edit-check.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

        <label>商品名</label>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

        <label>価格</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" required>

        <label>在庫</label>
        <input type="number" name="stock" value="<?= $product['stock'] ?>" min="0" required>

        <label>説明文</label>
        <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

        <label>画像（変更しない場合は空のままでOK）</label><br>
        <img src="../<?= $product['image_url'] ?>" width="120"><br>
        <input type="file" name="image">

        <button type="submit" class="admin-btn">更新する</button>
    </form>

</div>

</body>
</html>
