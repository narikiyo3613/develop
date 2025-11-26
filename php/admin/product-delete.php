<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require "admin-check.php";
require "../db-connect.php";

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("UPDATE products SET delete_flag = 0 WHERE product_id = ?;");
    $stmt->execute([$id]);
}

header("Location: product-list.php");
exit;
