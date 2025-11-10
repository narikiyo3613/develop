<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<?php
// ====== データベース接続 ======
require_once 'db-connect.php';


// ====== 入力を取得 ======
$keyword = $_GET['keyword'] ?? '';
$genre = $_GET['genre'] ?? '';

// ====== SQL生成 ======
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

// ====== データ取得 ======
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
</head>
<body>
    <div class="container">

        <a href="top.php" class="back-btn">←</a>

        <form class="search-form" method="get">
            <input type="text" name="keyword" placeholder="🔍 ペットフード" value="<?= htmlspecialchars($keyword) ?>">
            <select name="genre">
                <option value="">ジャンルを選択</option>
                <option value="犬" <?= $genre === '犬' ? 'selected' : '' ?>>犬</option>
                <option value="猫" <?= $genre === '猫' ? 'selected' : '' ?>>猫</option>
                <option value="ハムスター" <?= $genre === 'ハムスター' ? 'selected' : '' ?>>ハムスター</option>
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

        <form method="post" class="star-form" action="favorite.php">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['product_id']) ?>">
            <button type="submit" class="star">★</button>
        </form>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
