<?php
session_start();
// データベース接続ファイル
require 'db-connect.php';

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// DBからユーザー情報取得（icon_pathを追加取得）
$stmt = $pdo->prepare("SELECT name, email, address, phone, icon_path FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// エラー/成功メッセージの表示用
//$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : '';
//$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';
//unset($_SESSION['success'], $_SESSION['error']);
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

        <a href="#" onclick="history.back(); return false;" class="back-btn">←</a>

        <?php if ($success_message): ?>
            <div style="color: green; text-align: center; margin-bottom: 10px;">
                <?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div style="color: red; text-align: center; margin-bottom: 10px;">
                <?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="user-header">
            <form action="upload-icon.php" method="POST" enctype="multipart/form-data" id="icon-upload-form">
                <label class="clickable-header">
                    <input type="file" id="user-icon-input" name="user_icon" class="user-icon" alt="ユーザーアイコン"
                        accept="image/*">

                    <div id="preview-icon" class="icon-preview" <?php if (!empty($user['icon_path'])): ?>
                            style="background-image: url('<?php echo htmlspecialchars($user['icon_path']); ?>'); background-size: cover;"
                        <?php else: ?>
                            style="background-image: url('../image/default_icon.png'); background-size: cover;" <?php endif; ?>>
                    </div>
                </label>
                <button type="submit" style="display: none;" id="icon-submit-btn">アップロード</button>
            </form>

            <span class="user-name">
                <?php echo htmlspecialchars($user['name'] ?? '名無しのユーザー'); ?>
            </span>
        </div>

        <div class="side-menu">
            <ul>
                <li><a href="#">個人の情報</a></li>
                <li><a href="favorite.php">お気に入り</a></li>
                <li><a href="order-history.php">注文履歴</a></li>
            </ul>
        </div>

        <div class="profile-section">

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
            const iconUploadForm = document.getElementById('icon-upload-form'); // フォーム要素を取得

            userIconInput.addEventListener('change', function (event) {
                const file = event.target.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        // プレビュー表示
                        previewIcon.style.backgroundImage = `url('${e.target.result}')`;
                        previewIcon.style.backgroundColor = 'transparent';

                        // ファイル選択後、自動でフォームを送信 (upload-icon.phpへ送信)
                        iconUploadForm.submit();
                    };

                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>

</html>