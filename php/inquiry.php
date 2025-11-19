<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>
    <link rel="stylesheet" href="../css/inquiry.css">
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
</head>

<body>

    <a href="#" onclick="history.back(); return false;" class="back-btn">←</a>

    <div class="container">
        <h1>お問い合わせ</h1>

        <form action="inquiry-thanks.php" method="post" class="inquiry-form">
            
            <label for="name">お名前:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">メールアドレス:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">お問い合わせ内容:</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">送信</button>

        </form>
    </div>

</body>
</html>
