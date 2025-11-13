<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'db-connect.php';

// ログイン状態確認
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? null;

// 入力取得
$keyword = $_GET['keyword'] ?? '';
$genre = $_GET['genre'] ?? '';

// SQL生成
$sql = "SELECT product_id, name, price, category, image_url FROM products WHERE 1";
$params = [];

if ($keyword !== '') {
    $sql .= " AND name LIKE :keyword";
    $params[':keyword'] = '%' . $keyword . '%';
}
if ($genre !== '') {
    $sql .= " AND category = :genre";
    $params[':genre'] = $genre;
}

// データ取得
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>検索結果</title>
    <link rel="stylesheet" href="../css/searchresults-style.css">
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
    <style>
        .star {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background-color: #6ec6a3;
            color: white;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            transition: 0.2s;
        }

        /* 押した後の黄色状態 */
        .star.active {
            background-color: #FFD700;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="login/login-top.php" class="back-btn">←</a>

        <form class="search-form" method="get">
            <input type="text" name="keyword" placeholder="🔍 ペットフード" value="<?= htmlspecialchars($keyword) ?>">
            <select name="genre">
                <option value="">ジャンルを選択</option>
                <option value="犬" <?= $genre === '犬' ? 'selected' : '' ?>>犬</option>
                <option value="猫" <?= $genre === '猫' ? 'selected' : '' ?>>猫</option>
                <option value="小動物" <?= $genre === '小動物' ? 'selected' : '' ?>>小動物</option>
                <option value="鳥" <?= $genre === '鳥' ? 'selected' : '' ?>>鳥</option>
                <option value="ペットフード" <?= $genre === 'ペットフード' ? 'selected' : '' ?>>ペットフード</option>
            </select>
            <button type="submit">検索</button>
        </form>

        <h2 class="count">全 <?= count($products) ?> 件</h2>

        <div class="grid">
            <?php if (count($products) === 0): ?>
                <p>該当する商品が見つかりませんでした。</p>
            <?php else: ?>
                <?php foreach ($products as $item): ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <h3><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="price"><?= number_format($item['price']) ?>円</p>

                        <button class="star"
                            data-product-id="<?= htmlspecialchars($item['product_id']) ?>"
                            data-user-id="<?= $user_id ?? '' ?>">
                            ★
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

<script src="../script/searchresult.js"></script>
</body>
</html>
