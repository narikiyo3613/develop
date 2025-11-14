<?php
session_start();
require_once 'db-connect.php';

if (!isset($_SESSION['user_id'])) {
    exit("ログインしてください");
}

$user_id = $_SESSION['user_id'];

// お気に入り一覧取得
$sql = "
    SELECT 
        f.favorite_id,
        f.user_id,
        p.product_id, 
        p.name, 
        p.description,
        p.price,
        p.stock,
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
<style>
.favorite-item{
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}
.favorite-item img{
    width: 100px;
    height: 100px;
    object-fit: cover;
    margin-right: 15px;
    border-radius: 5px;
}
</style>
</head>
<body>


<h2>お気に入り一覧（<?= count($favorites) ?>件）</h2>
<a href="#" onclick="history.back(); return false;" class="back-btn">←</a>

<div id="favorite-list">

<?php foreach ($favorites as $fav): ?>
    <div class="favorite-item" id="fav-<?= $fav['favorite_id'] ?>">

        <!-- 画像が無い場合のデフォルト画像 -->
        <?php
            $img = $fav['image_url'] ?: 'noimage.png';
        ?>
        <img src="<?= htmlspecialchars($img) ?>" alt="">

        <div>
            <p><?= htmlspecialchars($fav['name']) ?></p>
            <p>価格：<?= number_format($fav['price']) ?>円</p>

            <!-- Ajax お気に入り解除ボタン -->
            <button class="delete-fav" data-id="<?= $fav['favorite_id'] ?>">
                お気に入り解除
            </button>
        </div>
    </div>
<?php endforeach; ?>
</div>

<script>
// ✅ Ajaxでお気に入り解除
$(".delete-fav").on("click", function(){
    let favoriteId = $(this).data("id");
    let target = $("#fav-" + favoriteId);

    $.ajax({
        url: "remove_favorite.php",
        type: "POST",
        data: { favorite_id: favoriteId },
        success: function(res){
            console.log(res);

            target.fadeOut(300, function(){
                $(this).remove();

                // ✅ 件数を更新
                let count = $(".favorite-item").length;
                $("h2").text(`お気に入り一覧（${count}件）`);
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
