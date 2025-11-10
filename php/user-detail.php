<div class="user-detail-container">

    <!-- サイドメニュー -->
    <div class="side-menu">
        <ul>
            <li><a href="#">個人の情報</a></li>
            <li><a href="favorite.php">お気に入り</a></li>
            <li><a href="history.php">購入履歴</a></li>
        </ul>
    </div>

    <!-- メイン部分 -->
    <div class="profile-section">

        <!-- プロフィール情報 -->
        <div class="profile-card">
            <h2>プロフィール情報</h2>
            <hr>
            <form action="update-profile.php" method="post">
                <label for="name">名前</label>
                <input type="text" id="name" name="name"
                        value="<?php echo htmlspecialchars($user['name']); ?>">

                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($user['email']); ?>">

                <label for="address">住所</label>
                <input type="text" id="address" name="address"
                        value="<?php echo htmlspecialchars($user['address']); ?>">

                <label for="phone">電話番号</label>
                <input type="text" id="phone" name="phone"
                        value="<?php echo htmlspecialchars($user['phone']); ?>">

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
