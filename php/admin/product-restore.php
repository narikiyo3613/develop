<?php
require "admin-check.php";
require "../db-connect.php";

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("UPDATE products SET is_deleted = 0 WHERE product_id = ?");
    $stmt->execute([$id]);
}

header("Location: product-trash.php");
exit;
