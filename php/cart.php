<?php
session_start();
require_once 'db-connect.php';

// ログイン中のユーザーIDを取得
$user_id = $_SESSION['user_id'] ?? null;

// 未ログイン時の対応
if (!$user_id) {
    echo "<p style='text-align: center; margin-top: 50px;'>ログインしていません。カートを利用するにはログインしてください。</p>";
    // ※デバッグ用にID=1を使用する場合は下の行を有効にする
    // $user_id = 1; 
    exit;
}

// データベースからカート内容を取得する
$sql = "
    SELECT 
        c.cart_id,
        c.quantity,
        p.product_id,
        p.name,
        p.price,
        p.image_url,
        p.birthday
    FROM carts AS c
    JOIN products AS p ON c.product_id = p.product_id
    WHERE c.user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 合計金額の計算
$total_price = 0;
if (!empty($cart_items)) {
    foreach ($cart_items as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カート商品一覧</title>
    <link rel="stylesheet" href="../css/carts.css"> 
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
</head>
<body>

    <!-- ✅ ポップアップメニュー -->
    <button id="openPopupBtn">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div id="popup" class="popup">
        <div class="popup-content">
            <form action="searchresults.php" method="get" class="popup-search-form">
                <input type="text" name="keyword" maxlength="100" placeholder="気になる犬種や場所で探す" class="popupSearch" required>
                <button type="submit" class="search-icon-btn">🔍</button>
            </form>

            <p><a href="user-detail.php">マイページ</a></p>
            <p><a href="favorite.php">お気に入り</a></p>
            <p><a href="cart.php">カートを見る</a></p>
            <p><a href="inquiry.php">お問い合わせ</a></p>
            <p><a href="login/logout.php" style="color:#ff7f7f;">ログアウト</a></p>

            <button id="closePopupBtn" class="close-button"></button>
        </div>
    </div>

    <div class="cart-container">

    <!-- ホーム画面に戻るボタン -->
    <a href="#" onclick="history.back(); return false;" class="back-btn">←</a>
    <h1 class="title">カート</h1>

        <div class="product-grid">
            <?php if (empty($cart_items)): ?>
                <p class="empty">カートに商品はありません。</p>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="product-card">
                        <div class="product-info">
                            <img src="<?= htmlspecialchars($item['image_url'], ENT_QUOTES) ?>" alt="<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>">
                            <div class="details">
                                <h3><?= htmlspecialchars($item['name'], ENT_QUOTES) ?></h3>
                                <p class="shop-name"><?= htmlspecialchars($item['shop'], ENT_QUOTES) ?></p>
                                <p class="price-single"><?= number_format($item['price']) ?>円 (単価)</p>
                                <p class="birthday"><?= date('Y年n月j日', strtotime($item['birthday'])) ?>生まれ</p>
                                
                                <form action="cart-update.php" method="post" class="quantity-form">
                                    <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['cart_id'], ENT_QUOTES) ?>">
                                    <div class="quantity-control">
                                        <button type="submit" name="action" value="decrease" class="qty-btn decrease-btn" aria-label="数量を減らす" <?= $item['quantity'] <= 1 ? 'disabled' : '' ?>>-</button>
                                        
                                        <input type="text" name="quantity_display" value="<?= htmlspecialchars($item['quantity'], ENT_QUOTES) ?>" class="quantity-input" readonly>
                                        
                                        <button type="submit" name="action" value="increase" class="qty-btn increase-btn" aria-label="数量を増やす">+</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="action-area">
                            <form action="cart-remove.php" method="post" class="remove-form">
                                <input type="hidden" name="cart_id" value="<?= htmlspecialchars($item['cart_id'], ENT_QUOTES) ?>">
                                <button type="submit" class="remove-btn">削除</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (!empty($cart_items)): ?>
            <div class="cart-summary">
                <p class="total-price-label">合計金額</p>
                <p class="total-price-value">**<?= number_format($total_price) ?>円**</p>
            </div>

            <form action="payment_form.html" method="post" class="purchase-form">
                <button type="submit" class="purchase-btn">購入手続きへ</button>
            </form>
        <?php endif; ?>

    </div>
        <script src="../script/popup.js"></script>
</body>
</html>