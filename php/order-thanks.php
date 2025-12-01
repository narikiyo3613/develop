<?php
session_start();

// 注文ID（必須）
$order_id = $_GET['order_id'] ?? null;

if (!$order_id) {
    echo "注文IDが存在しません。";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ご購入ありがとうございました！</title>
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png" width="30%">
    <link rel="stylesheet" href="../css/order-thanks.css">
</head>

<body>

<div class="thanks-container">

    
    <img src="../image/もふもふアイコン.png" alt="MofuMofuロゴ" class="main-logo" width="30%"><br>

    <h1 class="thanks-title">ご購入ありがとうございます！</h1>

    <p class="thanks-msg">
        ご注文を確認いたしました。<br>
        スタッフが心を込めて準備いたします🐾
    </p>

    <div class="order-box">
        <p>注文番号</p>
        <span>#<?= htmlspecialchars($order_id) ?></span>
    </div>

    <a href="login/login-top.php" class="back-home-btn">トップページに戻る</a>
</div>

</body>
</html>
