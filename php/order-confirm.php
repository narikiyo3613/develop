<?php
session_start();
require_once 'db-connect.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo "ログインしてください。";
    exit;
}

// カート内容取得
$sql = "
    SELECT 
        c.cart_id,
        c.quantity,
        p.product_id,
        p.name,
        p.price,
        p.image_url
    FROM carts AS c
    JOIN products AS p ON c.product_id = p.product_id
    WHERE c.user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$items) {
    echo "<p>カートが空です。</p>";
    exit;
}

$total = 0;
foreach ($items as $i) {
    $total += $i['price'] * $i['quantity'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>注文確認</title>
<link rel="stylesheet" href="../css/order-confirm.css">
</head>
<body>

<h1>注文内容の確認</h1>

<div class="order-list">
<?php foreach ($items as $i): ?>
    <div class="order-item">
        <img src="<?= htmlspecialchars($i['image_url']) ?>" width="100">
        <p><?= htmlspecialchars($i['name']) ?></p>
        <p><?= $i['quantity'] ?>個</p>
        <p><?= number_format($i['price'] * $i['quantity']) ?>円</p>
    </div>
<?php endforeach; ?>
</div>

<h2>合計金額：<?= number_format($total) ?>円</h2>

<form action="order-complete.php" method="post">
    <button type="submit">注文を確定する</button>
</form>

</body>
</html>
