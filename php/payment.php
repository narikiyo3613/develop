<?php
session_start();

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>支払情報入力</title>
  <link rel="stylesheet" href="../css/payment.css">
  <link rel="icon" type="image/png" href="../image/もふもふアイコン.png">
</head>

<body>
  <div class="container">

  <a href="order-confirm.php" class="back-btn">←</a>
    <h1 class="title">支払情報入力</h1>

    <!-- 注文確認から POST される場合を想定（なにも無くても動作OK） -->
    <form action="payment_output.php" method="post" class="login-form">
        <input type="hidden" name="total_price" value="<?= htmlspecialchars($_POST['total_price'] ?? '') ?>">


      <label for="kanaName">お名前（フリガナ）</label>
      <input 
        type="text" 
        id="kanaName" 
        name="kana_name" 
        placeholder="タナカタロウ" 
        required
      >

      <label for="zipCode">郵便番号（ハイフンなし）</label>
      <input 
        type="text" 
        id="zipCode" 
        name="zip_code" 
        placeholder="1234567" 
        required 
        maxlength="7"
        onKeyUp="AjaxZip3.zip2addr(this,'','prefecture','addressMain');"
      >

      <label for="prefecture">都道府県</label>
      <input 
        type="text" 
        id="prefecture" 
        name="prefecture" 
        required
      >

      <label for="addressMain">住所</label>
      <input 
        type="text" 
        id="addressMain" 
        name="addressMain" 
        required
      >

      <label for="addressSub">マンション名など</label>
      <input 
        type="text" 
        id="addressSub" 
        name="address_sub" 
      >

      <label for="telNumber">電話番号</label>
      <input 
        type="tel" 
        id="telNumber" 
        name="tel_number" 
        required
      >

      <label for="cardNumber">クレジットカード番号</label>
      <input 
        type="text" 
        id="cardNumber" 
        name="card_number" 
        required
      >

      <label for="securityCode">セキュリティコード</label>
      <input 
        type="password" 
        id="securityCode" 
        name="security_code" 
        required
      >

      <label for="cardHolder">名義人</label>
      <input 
        type="text" 
        id="cardHolder" 
        name="card_holder" 
        required
      >

      <button type="submit" class="login-btn">購入</button>
      
    </form>
  </div>

  <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

</body>

</html>
