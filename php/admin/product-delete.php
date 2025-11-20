<?php
require "admin-check.php";
require "../db-connect.php";

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
}

header("Location: product-list.php");
exit;
