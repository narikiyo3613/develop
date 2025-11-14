<?php
session_start();

// 初期データをセッションに保存（最初の1回だけ）
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [
        ["user_id" => 1001, "user_name" => "taro@example.com", "password" => "pass1"],
        ["user_id" => 1002, "user_name" => "hanako@example.com", "password" => "pass2"],
        ["user_id" => 1003, "user_name" => "jiro@example.com", "password" => "pass3"]
    ];
}

$users = $_SESSION['users'];

// 追加処理
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === "add") {
        // 入力値取得
        $new_user = [
            "user_id" => count($users) + 1001,
            "user_name" => $_POST['user_name'],
            "password" => $_POST['password']
        ];
        $users[] = $new_user;
    }

    if ($action === "update") {
        $user_id = $_POST['user_id'];
        foreach ($users as &$user) {
            if ($user['user_id'] == $user_id) {
                $user['user_name'] = $_POST['user_name'];
                $user['password'] = $_POST['password'];
                break;
            }
        }
        unset($user);
    }

    if ($action === "delete") {
        $user_id = $_POST['user_id'];
        $users = array_filter($users, function ($u) use ($user_id) {
            return $u['user_id'] != $user_id;
        });
    }

    // セッションに更新データを保存
    $_SESSION['users'] = array_values($users);

    // 再読み込みして二重送信防止
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー管理画面</title>
    <link rel="stylesheet" href="../css/user_admin.css">
    <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
</head>
<body>
    <header>
        <h1>管理者画面</h1>
    </header>

    <main>
        <h2 class="user-title">ユーザー管理</h2>

        <!-- 新規追加フォーム -->
        <form action="user_admin.php" method="post" class="user-form add-form">
            <input type="hidden" name="action" value="add">
            <div class="form-row">
                <div class="input-group">
                    <label>ユーザー名</label>
                    <input type="text" name="user_name" placeholder="###" required>
                </div>
                <div class="input-group">
                    <label>メールアドレス</label>
                    <input type="text" name="password" placeholder="###" required>
                </div>
                <div class="input-group">
                    <label>パスワード</label>
                    <input type="text" name="password" placeholder="###" required>
                </div>
                <button type="submit" class="btn btn-add">追加</button>
            </div>
        </form>

        <!-- 既存ユーザー一覧 -->
        <div class="user-list">
            <?php foreach ($users as $user): ?>
                <form action="user_admin.php" method="post" class="user-form">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                    <div class="form-row">
                        <div class="input-group">
                            <label>ユーザー名</label>
                            <input type="text" name="user_name" value="<?= htmlspecialchars($user['user_name']) ?>" required>
                        </div>
                        <div class="input-group">
                            <label>メールアドレス</label>
                            <input type="text" name="password" value="<?= htmlspecialchars($user['password']) ?>" required>
                        </div>
                        <div class="input-group">
                            <label>パスワード</label>
                            <input type="text" name="password" value="<?= htmlspecialchars($user['password']) ?>" required>
                        </div>
                        <button type="submit" name="action" value="update" class="btn btn-update">更新</button>
                        <button type="submit" name="action" value="delete" class="btn btn-delete">削除</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>



