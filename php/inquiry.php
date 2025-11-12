<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせフォーム</title>
    <style>
        body {
            font-family: "Meiryo", sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
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
            resize: both; /* サイズ変更可能 */
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
    </style>
</head>
<body>
    <h1>お問い合わせ</h1>
    <form action="../register/inquiry-thanks.php" method="post">
        <label for="message">お問合せ内容:</label>
        <textarea id="message" name="message"></textarea>
        <br>
        <button type="submit">送信</button>
    </form>
</body>
</html>


