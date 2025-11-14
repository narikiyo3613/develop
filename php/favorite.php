<?php
session_start();
require_once 'db-connect.php';

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    exit("ログインしてください");
}

$user_id = $_SESSION['user_id'];
$is_logged_in = true;

// お気に入り一覧取得
$sql = "
    SELECT 
        f.favorite_id,
        p.product_id, 
        p.name, 
        p.price,
        p.category,
        p.birthday,
        p.image_url
    FROM favorites AS f
    INNER JOIN products AS p
        ON f.product_id = p.product_id
    WHERE f.user_id = ?
    ORDER BY f.created_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>お気に入り一覧</title>

<link rel="stylesheet" href="../css/searchresults-style.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
<style>
/* お気に入りボタン */
.star {
    position: absolute;
    bottom: 20px;
    right: 20px;
    background-color: #FFD700; /* 黄色で初期表示 */
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

/* カードをリンク化 */
.card-link {
    color: inherit;
    text-decoration: none;
    display: block;
}
.back-green {
    display: inline-block;
    padding: 10px 18px;
    background-color: #6ec6a3;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    margin-bottom: 20px;
}
</style>
</head>
<body>

<div class="container">

    <!-- 緑の戻るボタン -->
    <a href="top.php" class="back-green">← 戻る</a>

    <h2 class="count">お気に入り <?= count($favorites) ?> 件</h2>

    <div class="grid">
        <?php if (count($favorites) === 0): ?>
            <p>お気に入り商品がありません。</p>
        <?php else: ?>
            <?php foreach ($favorites as $fav): ?>
                
                <div class="card" id="fav-<?= $fav['favorite_id'] ?>">

                    <!-- カード全体をクリックで商品詳細へ -->
                    <a class="card-link" href="detail.php?id=<?= $fav['product_id'] ?>">

                        <img src="<?= htmlspecialchars($fav['image_url'] ?: 'noimage.png') ?>"
                             alt="<?= htmlspecialchars($fav['name']) ?>">

                        <h3><?= htmlspecialchars($fav['name']) ?></h3>

                        <p class="price"><?= number_format($fav['price']) ?>円</p>

                    </a>

                    <!-- ★ お気に入り解除（Ajax） -->
                    <button class="star delete-fav" data-id="<?= $fav['favorite_id'] ?>">★</button>

                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<script>
// ===== お気に入り解除（Ajax） =====
$(".delete-fav").on("click", function(event){
    event.stopPropagation(); // カードリンクのクリックを無効化
    event.preventDefault();

    let favoriteId = $(this).data("id");
    let target = $("#fav-" + favoriteId);

    $.ajax({
        url: "remove_favorite.php",
        type: "POST",
        data: { favorite_id: favoriteId },
        success: function(res){
            target.fadeOut(300, function(){
                $(this).remove();

                // 件数更新
                let count = $(".card").length;
                $(".count").text(`お気に入り ${count} 件`);
            });
        },
        error: function(){
            alert("削除に失敗しました");
        }
    });
});
</script>

</body>
</html>
