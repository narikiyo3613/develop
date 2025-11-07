<?php
session_start();
require_once 'db_connect.php';

$favorite_id = $_POST['favorite_id'];

$sql = "DELETE FROM favorites WHERE favorite_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$favorite_id]);

echo "OK";
