<?php
session_start();
require_once 'db-connect.php';

// ログイン中のユーザーIDをセッションから取得（仮に1）
$user_id = $_SESSION['user_id'] ?? 1;

// carts と products を結合して取得する
$sql = "
    SELECT 
        c.cart_id,
        c.quantity,
        p.product_id,
        p.name,
        p.price,
        p.image_url,
        p.shop,
        p.birthday
    FROM carts AS c
    JOIN products AS p ON c.product_id = p.product_id
    WHERE c.user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カート商品一覧</title>
  <link rel="stylesheet" href="cart.css">
</head>
<body>

  <div class="cart-container">

    <!-- ホーム画面に戻るボタン -->
    <a href="top.html" class="back-btn">←</a>

    <h1 class="title">カート</h1>

    <!-- カート内商品を表示する -->
    <div class="product-grid">
      <?php if (empty($cart_items)): ?>
        <p class="empty">カートに商品はありません。</p>
      <?php else: ?>
        <?php foreach ($cart_items as $item): ?>
          <div class="product-card">
            <img src="<?= htmlspecialchars($item['image_url'], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>">
            <h3><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></h3>
            <p><?= htmlspecialchars($item['shop'], ENT_QUOTES) ?></p>
            <p class="price"><?= number_format($item['price']) ?>円</p>
            <p><?= date('Y年n月j日', strtotime($item['birthday'])) ?>生まれ</p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <?php if (!empty($cart_items)): ?>
      <form action="payment_form.html" method="post" class="purchase-form">
        <button type="submit" class="purchase-btn">購入する</button>
      </form>
    <?php endif; ?>

  </div>

</body>
</html>