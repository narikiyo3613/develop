<?php
require "admin-check.php";
require "../db-connect.php";

$name = $_POST['name'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$cat = $_POST['category'];
$desc = $_POST['description'];

$image = $_FILES['image'];

$filename = $image['name'];

$save_path = "../../image/" . $filename;

move_uploaded_file($image['tmp_name'], $save_path);

$image_url = "../image/" . $filename;

$sql = "INSERT INTO products(name, price, stock, category, description, image_url)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $name, $price, $stock, $cat, $desc,$image_url
]);

header("Location: product-list.php");
exit;
