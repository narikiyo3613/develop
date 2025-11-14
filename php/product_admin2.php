<?php
// PHPの処理開始
echo '<head><link rel="icon" type="image/png" href="../image/もふもふアイコン.png"></head>';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送信された 'action' パラメータを取得 (add, update, delete)
    $action = $_POST['action'] ?? '';

    // 送信されたデータ（商品ID、商品名、ジャンル）を取得
    $product_id = $_POST['product_id'] ?? '';
    $product_name = $_POST['product_name'] ?? '';
    $genre = $_POST['genre'] ?? '';

    // ここにデータベース接続とSQL実行処理を記述します
    // 例: $db = new PDO('...');

    // 処理の振り分け
    switch ($action) {
        case 'add':
            // 新規商品追加の処理
            echo "<h2>[ADD] 新規追加処理を実行:</h2>";
            echo "<p>商品名: " . htmlspecialchars($product_name) . "</p>";
            echo "<p>ジャンル: " . htmlspecialchars($genre) . "</p>";
            // 実際には: データベースにINSERT (例: $db->exec("INSERT INTO ..."))
            break;

        case 'update':
            // 既存商品更新の処理
            echo "<h2>[UPDATE] 更新処理を実行:</h2>";
            echo "<p>ID: " . htmlspecialchars($product_id) . "</p>";
            echo "<p>商品名: " . htmlspecialchars($product_name) . "</p>";
            echo "<p>ジャンル: " . htmlspecialchars($genre) . "</p>";
            // 実際には: データベースにUPDATE (例: $db->exec("UPDATE ... WHERE id = $product_id"))
            break;

        case 'delete':
            // 既存商品削除の処理
            echo "<h2>[DELETE] 削除処理を実行:</h2>";
            echo "<p>削除対象ID: " . htmlspecialchars($product_id) . "</p>";
            // 実際には: データベースにDELETE (例: $db->exec("DELETE FROM ... WHERE id = $product_id"))
            break;

        default:
            echo "<h2>不明な操作です。</h2>";
            break;
    }

    // 処理後、一覧画面にリダイレクトして結果を確認させるのが一般的です。
    // header('Location: product_admin.html');
    // exit;
} else {
    // POSTリクエスト以外でアクセスされた場合の処理
    echo "不正なアクセスです。このファイルはフォームからのデータ処理専用です。";
}
?>