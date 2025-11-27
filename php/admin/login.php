<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>管理者ログイン</title>
<link rel="icon" type="image/png" href="../../image/もふもふアイコン.png">
<link rel="icon" type="image/png" href="../../image/admin.png">
<style>
body {
    font-family: "Meiryo", sans-serif;
    background: #f2f2f2;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login-box {
    width: 340px;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    margin-bottom: 20px;
}
input {
    width: 100%;
    padding: 10px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
button {
    width: 100%;
    padding: 10px;
    background: #6ec6a3;
    color: #fff;
    border: none;
    font-size: 16px;
    border-radius: 5px;
}
button:hover {
    background: #5bb391;
}
</style>
</head>
<body>

<div class="login-box">
    <h2>管理者ログイン</h2>

    <form action="login-check.php" method="post">
        <input type="text" name="admin_id" placeholder="管理者ID" required>
        <input type="password" name="password" placeholder="パスワード" required>
        <button type="submit">ログイン</button>
    </form>
</div>

</body>
</html>
