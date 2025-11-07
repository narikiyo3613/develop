<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ペット検索トップ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="../css/top.css">
    <link rel="stylesheet" href="../css/intro.css">
</head>

<body>
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
                        <a class="button is-light clickLogin" href="login/login.php">ログイン</a>
                        <a class="button is-info clickRegistration" href="register/register-input.php">会員登録</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <button id="openPopupBtn">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div id="popup" class="popup">
        <div class="popup-content">
            <form action="searchresults.php" method="get" class="popup-search-form">
                <input type="text" name="query" maxlength="100" placeholder="気になる犬種や場所で探す" class="popupSearch" required>
                <button type="submit" class="search-icon-btn">🔍</button>
            </form>
            
            <p><a href="favorite.php">お気に入り</a></p>
            <p><a href="cart.php">カートを見る</a></p>
            <p><a href="inquiry.php">お問い合わせ</a></p>
            
            <button id="closePopupBtn" class="close-button"></button>
        </div>
    </div>

    <section class="section has-text-centered">
        <h1>全国のペットを検索</h1>
        
        <form action="searchresults.php" method="get">
            <div class="field has-addons is-justify-content-center">
                <div class="control is-expanded">
                    <input class="input is-large" type="text" name="query" maxlength="100" placeholder="気になる犬種や場所で探す" required>
                </div>
                <div class="control">
                    <button type="submit" class="button is-primary is-large">検索</button> 
                </div>
            </div>
        </form>
    </section>

    <script src="../script/topScript.js"></script>
</body>
<footer class="footer">
    <div class="about-container">

        <div class="logo-area">
            <img src="../image/もふもふアイコン.png" alt="MofuMofuロゴ" class="main-logo">
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
    <a href="payment_form1.html">支払い画面</a>
    <a href="admin1.html">管理者画面</a>
    <a herf="user.detail.html">マイページ</a>
</footer>
</html>