<?php
session_start();

// ログイン状態チェック
$logged_in = isset($_SESSION['user_id']);
$user_name = $logged_in ? $_SESSION['user_name'] : "";
$user_email = $logged_in ? $_SESSION['user_email'] : "";
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
    <title>お問い合わせフォーム</title>
    <link rel="stylesheet" href="../css/inquiry.css">
</head>

<body>
    <a href="#" onclick="history.back(); return false;" class="back-btn">←</a>

    <div class="container">
        <h1>お問い合わせ</h1>

        <form action="inquiry-thanks.php" method="post">

            <!-- 名前 -->
            <label for="name">お名前：</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($user_name) ?>"
                <?= $logged_in ? "readonly" : "" ?>
            >

            <!-- メール -->
            <label for="email">メールアドレス：</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($email) ?>"
                <?= $logged_in ? "readonly" : "" ?>
            >

            <!-- メッセージ -->
            <label for="message">お問い合わせ内容：</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">送信</button>
        </form>
    </div>

</body>
</html>
