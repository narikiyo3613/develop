<?php
session_start();
require 'db-connect.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// DBからユーザー情報取得
$stmt = $pdo->prepare("SELECT name, email, address, phone FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <link rel="stylesheet" href="../css/user_detail.css">
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
</head>

<body>

    <div class="user-detail-container">

        <!-- 左上の戻るボタン -->
        <a href="#" onclick="history.back(); return false;" class="back-btn">←</a>

        <!-- ユーザー情報ヘッダー -->
        <div class="user-header">
            <label class="clickable-header">
                <input type="file" id="user-icon-input" class="user-icon" alt="ユーザーアイコン" accept="image/*">

                <div id="preview-icon" class="icon-preview"></div>
            </label>
            <span class="user-name">
                <?php echo htmlspecialchars($user['name'] ?? '名無しのユーザー'); ?>
            </span>
        </div>

        <!-- サイドメニュー -->
        <div class="side-menu">
            <ul>
                <li><a href="#">個人の情報</a></li>
                <li><a href="favorite.php">お気に入り</a></li>
                <li><a href="history.php">購入履歴</a></li>
            </ul>
        </div>

        <div class="profile-section">

            <!-- プロフィール編集フォーム -->

            <div class="profile-card">
                <h2>プロフィール情報</h2>
                <hr>
                <form action="update-profile.php" method="post">
                    <label for="name">名前</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">

                    <label for="email">メールアドレス</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

                    <label for="address">住所</label>
                    <input type="text" id="address" name="address"
                        value="<?php echo htmlspecialchars($user['address']); ?>">

                    <label for="phone">電話番号</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

                    <button type="submit">変更を保存</button>
                </form>
            </div>

            <!-- パスワード変更 -->
            <div class="profile-card">
                <h2>パスワード変更</h2>
                <hr>
                <form action="update-password.php" method="post">
                    <label for="current_password">現在のパスワード</label>
                    <input type="password" id="current_password" name="current_password" required>

                    <label for="new_password">新しいパスワード</label>
                    <input type="password" id="new_password" name="new_password" required>

                    <label for="confirm_password">新しいパスワード（確認）</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <button type="submit">パスワードを変更</button>
                </form>
            </div>

        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userIconInput = document.getElementById('user-icon-input');
            const previewIcon = document.getElementById('preview-icon');

            userIconInput.addEventListener('change', function (event) {
                const file = event.target.files[0]; // 選択されたファイルを取得

                if (file) {
                    const reader = new FileReader(); // FileReaderオブジェクトを作成

                    reader.onload = function (e) {
                        // ファイルが読み込まれたら、プレビュー要素の背景画像に設定
                        previewIcon.style.backgroundImage = `url('${e.target.result}')`;
                        previewIcon.style.backgroundColor = 'transparent'; // 画像が設定されたら背景色は不要
                    };

                    reader.readAsDataURL(file); // ファイルをData URLとして読み込む
                } else {
                    // ファイルが選択されなかった場合（キャンセルなど）
                    previewIcon.style.backgroundImage = 'none';
                    previewIcon.style.backgroundColor = '#ccc'; // デフォルトの背景色に戻す
                }
            });
        });
    </script>
</body>

</html>