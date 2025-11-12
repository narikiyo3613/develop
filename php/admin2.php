<?php
// 管理メニュー項目
$menus = [
    ["title" => "商品管理", "link" => "product_manage.php"],
    ["title" => "ユーザー管理", "link" => "user_manage.php"]
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者画面</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
</head>
<body>
    <div class="admin-container">

        <a href="search.php" class="back-btn">←</a>

        <h1 class="admin-title">管理者画面</h1>

        <div class="admin-menu">
            <?php foreach ($menus as $menu): ?>
                <div class="menu-item">
                    <span><?= htmlspecialchars($menu['title']) ?></span>
                    <a href="<?= htmlspecialchars($menu['link']) ?>" class="link-icon">🔗</a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</body>
</html>


