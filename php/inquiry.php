<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
    <title>お問い合わせフォーム</title>
    <style>
        body {
            font-family: "Meiryo", sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        /* 戻るボタン（左上固定） */
        .back-btn {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 8px 14px;
            background: #eee;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            font-size: 16px;
            border: 1px solid #ccc;
        }

        /* 中央に配置するためのラッパー */
        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding-top: 40px; /* 戻るボタンと重ならないよう余白追加 */
            box-sizing: border-box;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-size: 18px;
            display: block;
            margin-bottom: 8px;
        }

        textarea {
            width: 500px;
            height: 120px;
            font-size: 16px;
            padding: 8px;
            border: 1px solid #999;
            border-radius: 4px;
            resize: both;
            /* サイズ変更可能 */
            background-color: #fff;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #0078d7;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #005fa3;
        }

        .back-btn {
            position: absolute;
            top: 30px;
            left: 40px;
            text-decoration: none;
            background-color: #6ec6a3;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <a href="#" onclick="history.back(); return false;" class="back-btn">←</a>
    <h1>お問い合わせ</h1>
    <form action="inquiry-thanks.php" method="post">
        <label for="message">お問合せ内容:</label>
        <textarea id="message" name="message"></textarea>
        <br>
        <button type="submit">送信</button>
    </form>
</body>

</html>
