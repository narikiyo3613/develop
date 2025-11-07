<?php
session_start();
// db-connect.phpがデータベース接続を$pdo変数に格納していると仮定
require_once 'db-connect.php';

// ログイン中のユーザーIDを取得
$user_id = $_SESSION['user_id'] ?? null;

// 未ログインの場合はエラーとするか、適切なページへリダイレクト
if (!$user_id) {
    // 実際はログインページなどへリダイレクトするのが適切
    header('Location: login.php');
    exit;
}

// POSTリクエストで削除対象の cart_id を受け取る
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'])) {
    
    // POSTされた cart_id を取得
    $cart_id = $_POST['cart_id'];

    try {
        // 削除用SQL：cart_id と user_id の両方が一致するレコードのみを削除
        $sql = "DELETE FROM carts WHERE cart_id = ? AND user_id = ?";
        
        // プリペアドステートメントでSQLインジェクションを防ぐ
        $stmt = $pdo->prepare($sql);
        
        // パラメータをバインドして実行
        // $user_id を含めることで、他のユーザーのカート商品を誤って削除するのを防ぐ
        $stmt->execute([$cart_id, $user_id]);

        // 削除が成功したら、カート一覧ページへリダイレクト
        // 成功メッセージはセッションなどを使ってリダイレクト先で表示することが多い
        header('Location: cart.php');
        exit;

    } catch (PDOException $e) {
        // データベースエラーが発生した場合の処理
        // 実際にはログに記録し、ユーザーには一般的なエラーメッセージを表示する
        echo "エラーが発生しました: " . $e->getMessage();
        // エラー発生時も処理を終了
        exit;
    }

} else {
    // POSTリクエストではない、または cart_id が渡されていない場合は、カート一覧ページに戻す
    header('Location: cart.php');
    exit;
}