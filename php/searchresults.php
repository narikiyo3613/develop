<?php
session_start();

// 初期化（お気に入り状態を保持）
if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

// ダミーデータ
$products = [
    ["id" => 1, "name" => "ペットフード", "price" => 1600, "genre" => "犬", "date" => "2025年1月20日", "image" => "images/petfood.png"],
    ["id" => 2, "name" => "猫のごはん", "price" => 1500, "genre" => "猫", "date" => "2025年1月20日", "image" => "images/petfood.png"],
    ["id" => 3, "name" => "ペットフードA", "price" => 1600, "genre" => "犬", "date" => "2025年1月20日", "image" => "images/petfood.png"],
    ["id" => 4, "name" => "ハムスターごはん", "price" => 1200, "genre" => "小動物", "date" => "2025年1月20日", "image" => "images/petfood.png"]
];

// お気に入りトグル処理
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    if (in_array($id, $_SESSION['favorites'])) {
        $_SESSION['favorites'] = array_diff($_SESSION['favorites'], [$id]); // 削除
    } else {
        $_SESSION['favorites'][] = $id; // 追加
    }
    header("Location: search.php?" . http_build_query([
        'keyword' => $_GET['keyword'] ?? '',
        'genre' => $_GET['genre'] ?? ''
    ]));
    exit;
}

// 検索処理
$keyword = $_GET['keyword'] ?? '';
$genre = $_GET['genre'] ?? '';

$filtered = array_filter($products, function ($item) use ($keyword, $genre) {
    $matchName = $keyword === '' || mb_stripos($item['name'], $keyword) !== false;
    $matchGenre = $genre === '' || $item['genre'] === $genre;
    return $matchName && $matchGenre;
});
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

    <a href="login.php" class="back-btn">←</a>

    <form class="search-form" method="get">
        <input type="text" name="keyword" placeholder="🔍 ペットフード" value="<?= htmlspecialchars($keyword) ?>">
        <select name="genre">
            <option value="">ジャンルを選択</option>
            <option value="犬" <?= $genre === '犬' ? 'selected' : '' ?>>犬</option>
            <option value="猫" <?= $genre === '猫' ? 'selected' : '' ?>>猫</option>
            <option value="小動物" <?= $genre === '小動物' ? 'selected' : '' ?>>小動物</option>
        </select>
        <button type="submit">検索</button>
    </form>

    <h2 class="count">全 <?= count($filtered) ?> 件</h2>

    <div class="grid">
        <?php foreach ($filtered as $item): ?>
            <?php $isFavorite = in_array($item['id'], $_SESSION['favorites']); ?>
            <div class="card">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p class="price"><?= htmlspecialchars($item['price']) ?>円</p>
                <p class="date"><?= htmlspecialchars($item['date']) ?>に注文</p>

                <a href="?toggle=<?= $item['id'] ?>&keyword=<?= urlencode($keyword) ?>&genre=<?= urlencode($genre) ?>"
                   class="star <?= $isFavorite ? 'active' : '' ?>">★</a>
            </div>
        <?php endforeach; ?>
    </div>

</div>
</body>
</html>
