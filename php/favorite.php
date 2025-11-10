<?php
session_start();
$user_id = $_SESSION['user_id'];

require_once 'db_connect.php';

// お気に入り一覧の取得
$sql = "SELECT f.favorite_id, p.*
        FROM favorites AS f
        JOIN product AS p ON f.product_id = p.product_id
        WHERE f.user_id = ?
        ORDER BY f.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

$count = count($favorites);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お気に入り一覧</title>
    <link rel="stylesheet" href="favorite.css">

    <script>
        // ★ お気に入り削除（Ajax）
        function removeFavorite(favorite_id) {

            if (!confirm("お気に入りから削除しますか？")) return;

            let formData = new FormData();
            formData.append("favorite_id", favorite_id);

            fetch("remove_favorite.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {

                // カード削除
                const card = document.getElementById("fav-" + favorite_id);
                if (card) card.remove();

                // 件数更新
                const countElem = document.getElementById("item-count");
                countElem.innerText = Number(countElem.innerText) - 1;
            });
        }
    </script>

</head>
<body>

    <div class="header">
        <!-- トップページに戻るボタン-->
        <a href="top.html" class="back-btn">←</a>

        <div class="title">
            <span>全</span>
            <span id="item-count"><?= $count ?></span>
            <span>件</span>
        </div>
    </div>

    <div class="favorite-list">
        <?php foreach ($favorites as $item): ?>
            <div class="item-card" id="fav-<?= $item['favorite_id'] ?>">

                <a href="product_detail.php?id=<?= $item['product_id'] ?>">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" class="item-img">
                </a>

                <div class="item-info">
                    <p class="item-name"><?= htmlspecialchars($item['name']) ?></p>
                    <p class="item-category"><?= htmlspecialchars($item['category']) ?></p>
                    <p class="item-price"><?= number_format($item['price']) ?>円</p>
                    <p class="item-birth"><?= htmlspecialchars($item['birthday']) ?> 生まれ</p>
                </div>

                <div class="favorite-icon"
                    onclick="removeFavorite(<?= $item['favorite_id'] ?>)">★</div>

            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
