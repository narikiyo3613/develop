<?php
// エラー表示を有効にする（開発時向け）
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ====== データベース接続 ======
// 実際のファイル名に合わせてください
require_once 'db-connect.php';


// セッションを開始
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ユーザーIDをセッションから取得
// 実際のセッションキー（例: 'user_id'）に合わせてください
$current_user_id = $_SESSION['user_id'] ?? null;
// ====== 入力を取得 (商品ID) ======
// URLから商品IDを取得（例: product-detail.php?id=1）
// データベースの画像にある通り、IDのカラム名は 'product_id' です
$product_id = $_GET['id'] ?? null;
$product = null;
$error_message = '';

// IDが存在し、数字であることを確認
if ($product_id && is_numeric($product_id)) {

    // ====== SQL生成とデータ取得 ======
    // DB画像のカラム名 (product_id, name, description, price, stock, category, image_url, created_at) を使用
    $sql = "SELECT product_id, name, description, price, stock, category, image_url, created_at 
            FROM products 
            WHERE product_id = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        // データを1件取得
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            $error_message = "指定された商品が見つかりませんでした。";
        }else{
            $is_favorited = false;
            if ($current_user_id) {
                $sql_fav = "SELECT 1 FROM favorites WHERE user_id = ? AND product_id = ?";
                $stmt_fav = $pdo->prepare($sql_fav);
                $stmt_fav->execute([$current_user_id, $product_id]);
                $is_favorited = $stmt_fav->fetchColumn() ? true : false;
            }
        }

    } catch (PDOException $e) {
        $error_message = "データベースエラーが発生しました。";
        // 開発環境では $e->getMessage() も含める
    }

} else {
    $error_message = "商品IDが指定されていません。";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title><?= $product ? htmlspecialchars($product['name']) : '商品詳細' ?></title>
    <link rel="stylesheet" href="../css/searchresults-style.css">
    <link rel="stylesheet" href="../css/product-detail-style.css">
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
</head>

<body>
    <div class="container">

        <a href="#" onclick="history.back(); return false;" class="back-btn">←</a>

        <?php if ($error_message): ?>
            <div class="detail-card error-box">
                <h1>エラーが発生しました</h1>
                <p><?= htmlspecialchars($error_message) ?></p>
            </div>
        <?php elseif ($product): ?>
            <div class="detail-card">

                <div class="image-area">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>"
                        alt="<?= htmlspecialchars($product['name']) ?>">
                </div>

                <div class="detail-info">
                    <h1><?= htmlspecialchars($product['name']) ?></h1>

                    <p class="price">
                        **<?= number_format($product['price']) ?>円** (税込)
                    </p>

                    <div class="meta-data">
                        <p>ジャンル: **<?= htmlspecialchars($product['category']) ?>**</p>
                        <p>在庫: <span class="<?= $product['stock'] > 0 ? 'stock-ok' : 'stock-zero' ?>">
                                <?= $product['stock'] > 0 ? htmlspecialchars($product['stock']) . '点' : '在庫切れ' ?>
                            </span></p>
                        <p class="created">登録日: <?= date('Y/m/d H:i', strtotime($product['created_at'])) ?></p>
                    </div>

                    <div class="action-area">
                        <div class="quantity-control">
                            <label for="quantity">数量:</label>
                            <input type="number" id="quantity" value="1" min="1"
                                max="<?= htmlspecialchars($product['stock']) ?>" style="width: 50px;">
                        </div>
                        <button
                            class="add-to-cart-btn"
                            data-product-id="<?= $product['product_id'] ?>"
                            data-user-id="<?= $current_user_id ?>"
                            <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                            カートに入れる
                        </button>

                        <button 
                            class="favorite-btn <?= $is_favorited ? 'favorited' : '' ?>"
                            data-product-id="<?= $product['product_id'] ?>"
                        >★</button>


                    </div>

                    <div class="description">
                        <h2>商品の説明</h2>
                        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    </div>

                </div>
            </div>
        <?php endif; ?>
        <script src="../script/ProductDetail.js"></script>
        <script src="../script/searchresult.js"></script>
    </div>
</body>

</html>