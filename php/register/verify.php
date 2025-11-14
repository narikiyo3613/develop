<?php
require '../db-connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE token = ? AND is_verified = 0");
    $stmt->execute([$token]);

    if ($stmt->rowCount() > 0) {
        $update = $pdo->prepare("UPDATE users SET is_verified = 1, token = NULL WHERE token = ?");
        $update->execute([$token]);

        // ✅ 見た目を整えた出力
        echo '<link rel="icon" type="image/png" href="../../image/もふもふアイコン.png">';
        echo "
        <div style='
            font-family: \"Hiragino Kaku Gothic ProN\", sans-serif;
            background-color:#f8fbff;
            text-align:center;
            padding:80px 20px;
        '>
            <h1 style='color:#6ec6a3;'>本登録が完了しました！</h1>
            <p style='font-size:1.1rem;'>
                ご登録ありがとうございます。<br>
                これでMofuMofuの会員登録が完了しました。<br><br>
                さっそくログインしてサービスをお楽しみください。
            </p>
            <a href='../login/login.php' style='
                display:inline-block;
                margin-top:30px;
                background-color:#6ec6a3;
                color:white;
                padding:12px 30px;
                border-radius:30px;
                text-decoration:none;
                font-weight:bold;
                transition:0.3s;
            '>ログインページへ</a>
        </div>
        ";
    } else {
        // 無効トークンの場合の見た目
        echo "
        <div style='
            font-family: \"Hiragino Kaku Gothic ProN\", sans-serif;
            background-color:#fff4f4;
            text-align:center;
            padding:80px 20px;
        '>
            <h1 style='color:#ff7f7f;'>このリンクは無効です</h1>
            <p style='font-size:1.1rem;'>
                すでに登録が完了しているか、<br>
                無効なURLの可能性があります。
            </p>
            <a href='../top.php' style='
                display:inline-block;
                margin-top:30px;
                background-color:#ff7f7f;
                color:white;
                padding:12px 30px;
                border-radius:30px;
                text-decoration:none;
                font-weight:bold;
                transition:0.3s;
            '>トップページに戻る</a>
        </div>
        ";
    }
} else {
    // 不正アクセスの場合の見た目
    echo "
    <div style='
        font-family: \"Hiragino Kaku Gothic ProN\", sans-serif;
        background-color:#fff4f4;
        text-align:center;
        padding:80px 20px;
    '>
        <h1 style='color:#ff7f7f;'>不正なアクセスです</h1>
        <p style='font-size:1.1rem;'>
            登録メールに記載されたURLからアクセスしてください。
        </p>
        <a href='../top.php' style='
            display:inline-block;
            margin-top:30px;
            background-color:#ff7f7f;
            color:white;
            padding:12px 30px;
            border-radius:30px;
            text-decoration:none;
            font-weight:bold;
            transition:0.3s;
        '>トップページに戻る</a>
    </div>
    ";
}
?>
