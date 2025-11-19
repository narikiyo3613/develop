<?php
require "admin-check.php";
require "../db-connect.php";

$stmt = $pdo->query("SELECT user_id, name, email FROM users ORDER BY user_id DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ユーザー一覧</title>
<link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<h1>ユーザー一覧</h1>
<a href="dashboard.php">← 戻る</a>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th><th>名前</th><th>Email</th>
</tr>

<?php foreach ($users as $u): ?>
<tr>
    <td><?= $u['user_id'] ?></td>
    <td><?= htmlspecialchars($u['name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
