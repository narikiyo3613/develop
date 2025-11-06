<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
    <link rel="stylesheet" href="../../css/login-style.css">
</head>
<body>
    <div class="container">

        <a href="top.html" class="back-btn">←</a>

        <h1 class="title">会員登録</h1>

        <form action="register-check.php" method="post" class="login-form">
            <label for="email">メールアドレスを入力してください</label>
            <input type="email" id="email" name="email" placeholder="例：abc@gmail.com" required>

            <label for="password">パスワードを入力してください</label>
            <input type="password" id="password" name="password" placeholder="例：aso1234" required>

            <button type="submit" class="login-btn">登録</button>
        </form>
    </div>
</body>
</html>
