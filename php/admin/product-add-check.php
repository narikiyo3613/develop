<?php
require "admin-check.php";
require "../db-connect.php";

$name = $_POST['name'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$cat = $_POST['category'];
$desc = $_POST['description'];

$image = $_FILES['image'];

$filename = "img_" . time() . "_" . $image['name'];
$save_path = "../../image/products/" . $filename;

move_uploaded_file($image['tmp_name'], $save_path);

$sql = "INSERT INTO products(name, price, stock, category, description, image_url)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $name, $price, $stock, $cat, $desc,
    "image/products/" . $filename
]);

header("Location: product-list.php");
exit;
