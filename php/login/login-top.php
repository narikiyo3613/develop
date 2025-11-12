<?php
session_start();

// 未ログインならログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ようこそ <?php echo htmlspecialchars($_SESSION['user_name'] ?: 'ユーザー'); ?> さん</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="../../css/top.css">
    <link rel="stylesheet" href="../../css/intro.css">
    
</head>

<body>
    <!-- ナビバー -->
    <nav class="navbar is-info" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a role="button" id="navbarBurgerBtn" class="navbar-burger" aria-label="menu" aria-expanded="false"
                data-target="navbarMenu">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarMenu" class="navbar-menu">
            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <span class="navbar-item has-text-white">
                            ようこそ、<strong><?php echo htmlspecialchars($_SESSION['user_name'] ?: 'ユーザー'); ?></strong> さん
                        </span>
                        <a class="button is-primary" href="../user-detail.php">マイページ</a>
                        <a class="button is-light" href="../login/logout.php">ログアウト</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- ✅ ポップアップメニュー -->
    <button id="openPopupBtn">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div id="popup" class="popup">
        <div class="popup-content">
            <form action="../searchresults.php" method="get" class="popup-search-form">
                <input type="text" name="query" maxlength="100" placeholder="気になる犬種や場所で探す" class="popupSearch" required>
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

    <!-- メイン -->
    <section class="section has-text-centered">
        <h1>こんにちは、<?php echo htmlspecialchars($_SESSION['user_name'] ?: 'ユーザー'); ?> さん！</h1>
        <p>あなたにぴったりのペットを探してみましょう🐶🐱</p>

        <form action="../searchresults.php" method="get">
            <div class="field has-addons is-justify-content-center">
                <div class="control is-expanded">
                    <input class="input is-large" type="text" name="query" maxlength="100"
                        placeholder="気になる犬種や場所で探す" required>
                </div>
                <div class="control">
                    <button type="submit" class="button is-primary is-large">検索</button>
                </div>
            </div>
        </form>
    </section>
    

    <!-- フッター -->
    <footer class="footer">
        <div class="about-container">
            <div class="logo-area">
                <img src="../../image/もふもふアイコン.png" alt="MofuMofuロゴ" class="main-logo">
                <h1 class="site-title">MofuMofu</h1>
            </div>

            <div class="description">
                <p>
                    もふもふシステムズは全国の<br>
                    優良ペットショップからかわいい子犬・子猫を<br>
                    検索できるポータルサイトです。
                </p>
                <p>
                    日本最大級の掲載数の中から<br>
                    ペットとの素敵な出会いをお手伝いいたします。
                </p>
            </div>
        </div>

        <div class="footer-links has-text-centered" style="margin-top:20px;">
            <a href="../favorite.php">お気に入り</a> |
            <a href="../cart.php">カート</a> |
            <a href="../inquiry.php">お問い合わせ</a>
        </div>
    </footer>

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
