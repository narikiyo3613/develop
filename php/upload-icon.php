<?php
session_start();
require 'db-connect.php'; // データベース接続ファイル

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
// 💡 画像の保存先: 現在のファイルの一つ上の階層にあるimageフォルダ
$upload_dir = '../image/'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['user_icon'])) {
    $file = $_FILES['user_icon'];

    // 1. エラーチェック
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'ファイルのアップロードに失敗しました。エラーコード: ' . $file['error'];
        header("Location: user_detail.php");
        exit;
    }

    // 2. ファイルタイプのチェック (画像ファイルのみ許可)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['error'] = '画像ファイル (JPEG, PNG, GIF) のみアップロード可能です。';
        header("Location: user_detail.php");
        exit;
    }

    // 3. 安全なファイル名の生成
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    // ユーザーIDとタイムスタンプを使ってユニークなファイル名を生成
    $new_file_name = $user_id . '-' . time() . '.' . $extension;
    $save_path = $upload_dir . $new_file_name; // サーバー上でのファイルの物理パス

    // 4. ファイルをサーバーに移動
    if (move_uploaded_file($file['tmp_name'], $save_path)) {
        
        try {
            // 5. DBのアイコンパスを更新（user_detail.phpからアクセスできる相対パス）
            $db_path = $upload_dir . $new_file_name; 
            
            // 💡 古いアイコンパスを取得
            $stmt_old = $pdo->prepare("SELECT icon_path FROM users WHERE user_id = ?");
            $stmt_old->execute([$user_id]);
            $old_path = $stmt_old->fetchColumn();

            // 6. DBを更新
            $stmt = $pdo->prepare("UPDATE users SET icon_path = ? WHERE user_id = ?");
            $stmt->execute([$db_path, $user_id]);

            $_SESSION['success'] = 'アイコンが正常に更新されました。';
            
            // 7. 古いアイコンファイルを削除 (オプション: サーバー容量の節約)
            if ($old_path && file_exists($old_path)) {
                unlink($old_path);
            }

        } catch (PDOException $e) {
            // エラーログを記録 (開発用)
            // error_log("DB Error: " . $e->getMessage()); 
            $_SESSION['error'] = 'データベースの更新中にエラーが発生しました。';
        }
        
    } else {
        $_SESSION['error'] = 'サーバーへのファイル保存に失敗しました。ディレクトリの書き込み権限を確認してください。';
    }

    header("Location: user_detail.php"); // マイページに戻る
    exit;

} else {
    // POSTリクエストではない、またはファイルがアップロードされていない場合はマイページに戻す
    header("Location: user_detail.php");
    exit;
}
?>