<?php
session_start();
// db-connect.phpでPDO::ERRMODE_EXCEPTIONが設定されていることを前提とします
require_once "db-connect.php";

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    exit("ログインしてください");
}

// ★ トランザクション開始: 注文処理全体を一つの塊として扱います
$pdo->beginTransaction();

try {
    // カート内容取得（フラグ1＝公開中として処理）
    $sql = "
        SELECT 
            c.product_id,
            c.quantity,
            p.price,
            p.stock
        FROM carts c
        JOIN products p ON c.product_id = p.product_id
        WHERE c.user_id = ?
        AND p.delete_flag = 1  /* ★ フラグ1を公開中（購入対象）として扱う */
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$cart) {
        $pdo->rollBack(); // カートが空の場合はロールバック
        exit("カートが空です");
    }

    // ● 在庫チェック（足りない商品がある場合はエラー）
    foreach ($cart as $item) {
        if ($item['stock'] < $item['quantity']) {
            $pdo->rollBack(); // 在庫不足の場合はロールバック
            exit("在庫が不足している商品があります: 商品ID " . $item['product_id']);
        }
    }

    // 合計金額計算
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // 1. orders テーブルに追加
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, total_price, status, created_at)
        VALUES (?, ?, 'pending', NOW())
    ");
    $stmt->execute([$user_id, $total]);

    $order_id = $pdo->lastInsertId();

    // 2. order_items に追加 および 3. 在庫を減らす処理 の準備
    $stmt_item = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");

    $stmt_stock = $pdo->prepare("
        UPDATE products
        SET stock = stock - ?
        WHERE product_id = ?
    ");

    // 2.と3. を一つのループで実行（効率化）
    foreach ($cart as $item) {
        // 注文詳細を追加
        $stmt_item->execute([
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price']
        ]);

        //header("Location: top.php");
        // 在庫の減算
        $stmt_stock->execute([
            $item['quantity'],
            $item['product_id']
        ]);
    }

    // 4. カートをクリア
    $pdo->prepare("DELETE FROM carts WHERE user_id = ?")->execute([$user_id]);

    // ★ 全ての処理が成功したらコミット（データベースに反映）
    $pdo->commit();

    header("Location: top.php");
    //header("Location: order-thanks.php");
    exit;

} catch (Exception $e) {
    // ★ 途中でエラーが発生したらロールバック（全て取り消し）
    $pdo->rollBack();
    
    // エラー詳細をログに出力（ユーザーには見せない）
    error_log("注文処理エラー: " . $e->getMessage()); 
    
    // ユーザーにエラーを通知
    exit("注文処理中に予期せぬエラーが発生しました。時間を置いて再度お試しください。");
}