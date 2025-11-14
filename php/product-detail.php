<?php
// エラー表示（開発時向け）
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ====== データベース接続とセッション ======
// 実際のファイル名に合わせてください
require_once 'db-connect.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 応答をJSON形式に設定
header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

// ====== データの取得とチェック ======
$user_id = $_SESSION['user_id'] ?? null;
// POSTデータから商品IDと数量を取得
$product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$quantity = (int)(filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT) ?? 1); 

// ログインチェック
if (!$user_id) {
    $response['message'] = 'ログインしていません。';
    echo json_encode($response);
    exit;
}

// 入力データのチェック
if (!$product_id || $quantity <= 0) {
    $response['message'] = '無効な商品情報または数量です。';
    echo json_encode($response);
    exit;
}

// ====== データベース処理 ======
try {
    // 1. 既存のカートアイテムをチェック（同じ商品が既に入っているか）
    $sql_check = "SELECT cart_id, quantity FROM carts WHERE user_id = :user_id AND product_id = :product_id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_check->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt_check->execute();
    $existing_item = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        // 既存の場合: 数量を更新 (加算)
        $new_quantity = $existing_item['quantity'] + $quantity;
        $sql_update = "UPDATE carts SET quantity = :quantity WHERE cart_id = :cart_id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
        $stmt_update->bindParam(':cart_id', $existing_item['cart_id'], PDO::PARAM_INT);
        $stmt_update->execute();
    } else {
        // 新規の場合: 挿入
        $sql_insert = "INSERT INTO carts (user_id, product_id, quantity, added_at) 
                       VALUES (:user_id, :product_id, :quantity, NOW())";
        $stmt_insert = $pdo->prepare($sql_insert);
        $stmt_insert->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_insert->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt_insert->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt_insert->execute();
    }
    
    $response['success'] = true;
    $response['message'] = 'カートに商品を追加しました。';

} catch (PDOException $e) {
    // DBエラー
    $response['message'] = 'データベースエラーが発生しました。';
    // 開発時に確認するためにエラーメッセージを出力
    // $response['debug'] = $e->getMessage(); 
}

echo json_encode($response);
exit;
?>