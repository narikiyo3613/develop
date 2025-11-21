<?php
session_start();
require_once "db-connect.php";

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 入力受け取り
$kana = $_POST['kana_name'];
$zip = $_POST['zip_code'];
$pref = $_POST['prefecture'];
$addr1 = $_POST['address_main'];
$addr2 = $_POST['address_sub'];
$tel = $_POST['tel_number'];
$card = $_POST['card_number'];     // 本来は保存しない
$sec = $_POST['security_code'];    // 本来は保存しない
$holder = $_POST['card_holder'];

// ========================
// ① カート内容を取得
// ========================
$sql = "
    SELECT c.cart_id, c.quantity, p.product_id, p.price
    FROM carts AS c
    JOIN products AS p ON c.product_id = p.product_id
    WHERE c.user_id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cart_items)) {
    echo "カートが空です。";
    exit;
}

// ========================
// ② 合計金額を計算
// ========================
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// ========================
// ③ orders に登録
// ========================
$sql = "INSERT INTO orders (user_id, total_price, status, created_at)
        VALUES (?, ?, 'ordered', NOW())";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $total_price]);

$order_id = $pdo->lastInsertId();

// ========================
// ④ order_items に登録
// ========================
$sql = "INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

foreach ($cart_items as $item) {
    $stmt->execute([
        $order_id,
        $item['product_id'],
        $item['quantity'],
        $item['price']
    ]);
}

// ========================
// ⑤ カート削除
// ========================
$sql = "DELETE FROM carts WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);

// ========================
// ⑥ 完了ページへ
// ========================
header("Location: order-thanks.php?order_id=" . $order_id);
exit;
