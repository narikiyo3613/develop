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

    <!-- ✅ ポップアップメニュー -->
    <button id="openPopupBtn">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div id="popup" class="popup">
        <div class="popup-content">
            <form action="../searchresults.php" method="get" class="popup-search-form">
                <input type="text" name="keyword" maxlength="100" placeholder="気になる犬種や場所で探す" class="popupSearch" required>
                <button type="submit" class="search-icon-btn">🔍</button>
            </form>

            <p><a href="../user-detail.php">マイページ</a></p>
            <p><a href="../favorite.php">お気に入り</a></p>
            <p><a href="../cart.php">カートを見る</a></p>
            <p><a href="../inquiry.php">お問い合わせ</a></p>
            <p><a href="../login/logout.php" style="color:#ff7f7f;">ログアウト</a></p>

            <button id="closePopupBtn" class="close-button"></button>
        </div>
    </div>

<a href="#" onclick="history.back(); return false;" class="back-btn">←</a>
<h2>お気に入り一覧（<?= count($favorites) ?>件）</h2>


<div id="favorite-list">

    <div class="grid">
        <?php if (count($favorites) === 0): ?>
            <p>お気に入り商品がありません。</p>
        <?php else: ?>
            <?php foreach ($favorites as $fav): ?>
            
                <div class="card" id="fav-<?= $fav['favorite_id'] ?>">

                    <!-- カードをクリックで商品詳細 -->
                    <a class="card-link" href="ProductDetails.php?id=<?= $fav['product_id'] ?>">

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
// ===== お気に入り解除=====
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
<script>
        // ✅ ポップアップ開閉制御
        document.addEventListener('DOMContentLoaded', () => {
            const openBtn = document.getElementById('openPopupBtn');
            const closeBtn = document.getElementById('closePopupBtn');
            const popup = document.getElementById('popup');

            openBtn.addEventListener('click', () => popup.classList.add('active'));
            closeBtn.addEventListener('click', () => popup.classList.remove('active'));
        });
</script>
<script src="../../script/topScript.js"></script>
</body>
</html>