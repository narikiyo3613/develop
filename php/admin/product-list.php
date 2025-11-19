<?php
require "admin-check.php";
require "../db-connect.php";

$stmt = $pdo->query("SELECT * FROM products ORDER BY product_id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品一覧</title>
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>

<h1>商品一覧</h1>
<a href="dashboard.php">← 戻る</a>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th><th>商品名</th><th>価格</th><th>操作</th>
</tr>

<?php foreach ($products as $p): ?>
<tr>
    <td><?= $p['product_id'] ?></td>
    <td><?= htmlspecialchars($p['name']) ?></td>
    <td><?= htmlspecialchars($p['price']) ?>円</td>
    <td>
        <a href="product-edit.php?id=<?= $p['product_id'] ?>">編集</a> |
        <a href="delete-product.php?id=<?= $p['product_id'] ?>"
            onclick="return confirm('削除しますか？')">削除</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
