<?php
session_start();
require_once 'db-connect.php';

$user_id = $_SESSION['user_id'] ?? null;

// 未ログインまたはPOSTリクエストでない場合はリダイレクト
if (!$user_id || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['cart_id']) || !isset($_POST['action'])) {
    header('Location: cart.php');
    exit;
}

$cart_id = $_POST['cart_id'];
$action = $_POST['action'];

try {
    // 現在の数量を取得
    $sql_select = "SELECT quantity FROM carts WHERE cart_id = ? AND user_id = ?";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute([$cart_id, $user_id]);
    $current_quantity = $stmt_select->fetchColumn();

    if ($current_quantity === false) {
        // カートアイテムが見つからない（または他ユーザーのアイテム）
        header('Location: cart.php');
        exit;
    }

    $new_quantity = $current_quantity;

    if ($action === 'increase') {
        // 数量を増やす
        $new_quantity++;
    } elseif ($action === 'decrease' && $current_quantity > 1) {
        // 数量を減らす（最低1まで）
        $new_quantity--;
    } else {
        // 数量が1以下でdecreaseが来た場合などは何もしない
        header('Location: cart.php');
        exit;
    }

    // 新しい数量で更新
    $sql_update = "UPDATE carts SET quantity = ? WHERE cart_id = ? AND user_id = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$new_quantity, $cart_id, $user_id]);

    // 更新後、カート一覧ページへリダイレクト
    header('Location: cart.php');
    exit;

} catch (PDOException $e) {
    // データベースエラー処理
    error_log("Cart update error: " . $e->getMessage());
    echo "<p style='text-align: center; margin-top: 50px; color: red;'>数量の更新中にエラーが発生しました。</p>";
    exit;
}