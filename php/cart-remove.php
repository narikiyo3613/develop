<?php
session_start();
require_once 'db-connect.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'])) {

    $cart_id = $_POST['cart_id'];

    try {
        $sql = "DELETE FROM carts WHERE cart_id = ? AND user_id = ?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$cart_id, $user_id]);

        header('Location: cart.php');
        exit;

    } catch (PDOException $e) {
        echo "エラーが発生しました: " . $e->getMessage();
        exit;
    }

} else {
    header('Location: cart.php');
    exit;
}