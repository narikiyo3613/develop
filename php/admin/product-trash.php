<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "admin-check.php";
require "../db-connect.php";

// 削除済み商品のみ取得
$stmt = $pdo->query("SELECT * FROM products WHERE delete_flag = 0 ORDER BY product_id DESC");
$deleted_products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>削除済み商品（ごみ箱）</title>
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>

<h1>削除済み商品（ごみ箱）</h1>
<a href="dashboard.php" class="back-btn">← 管理トップへ</a>

<table class="admin-table">
<tr>
    <th>ID</th><th>商品名</th><th>価格</th><th>操作</th>
</tr>

<?php foreach ($deleted_products as $p): ?>
<tr>
    <td><?= $p['product_id'] ?></td>
    <td><?= htmlspecialchars($p['name']) ?></td>
    <td><?= number_format($p['price']) ?>円</td>
    <td>
        <a href="product-restore.php?id=<?= $p['product_id'] ?>" class="admin-btn-sm">復元</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
