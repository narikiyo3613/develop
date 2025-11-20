<?php
require "admin-check.php";
require "../db-connect.php";

$id = $_POST['product_id'];
$name = $_POST['name'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$cat = $_POST['category'];
$desc = $_POST['description'];

// 画像変更有無の確認
$image_sql = "";
$params = [$name, $price, $stock, $cat, $desc];

if (!empty($_FILES['image']['name'])) {

    $file = $_FILES['image'];
    $filename = time() . "_" . $file['name'];

    // 保存先（php/admin → 2階層戻って image フォルダ）
    $save_path = "../../image/" . $filename;

    move_uploaded_file($file['tmp_name'], $save_path);

    // DB は "../image/xxx" の形式で登録
    $image_url = "../image/" . $filename;

    $image_sql = ", image_url = ?";
    $params[] = $image_url;
}

// 最後に product_id をパラメータに追加
$params[] = $id;

$sql = "UPDATE products
        SET name=?, price=?, stock=?, category=?, description=? $image_sql
        WHERE product_id=?";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

header("Location: product-list.php");
exit;
