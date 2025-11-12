<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>お問い合わせ</h1>

     <style>
        body {
            font-family: "Meiryo", sans-serif;
            margin: 40px;
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
        }
    </style>
</head>
<body>
    <form action="#" method="post">
        <label for="message">お問合せ内容:</label>
        <textarea id="message" name="message"></textarea>
    </form>
</body>
</html>
