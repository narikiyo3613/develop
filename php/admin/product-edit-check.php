<?php
require "admin-check.php";
require "../db-connect.php";

$id = $_POST['product_id'];
$name = $_POST['name'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$desc = $_POST['description'];

// 画像処理
$image_sql = "";
$params = [$name, $price, $stock, $desc, $id];

if (!empty($_FILES['image']['name'])) {
    $file = $_FILES['image'];
    $filename = "uploads/" . time() . "_" . $file['name'];
    move_uploaded_file($file['tmp_name'], "../" . $filename);
    $image_sql = ", image_url = ?";
    array_splice($params, 4, 0, [$filename]);
}

$sql = "UPDATE products
        SET name=?, price=?, stock=?, description=? $image_sql
        WHERE product_id=?";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

header("Location: product-list.php");
exit;
