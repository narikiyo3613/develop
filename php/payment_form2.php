<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name_furigana = isset($_POST['name_furigana']) ? htmlspecialchars($_POST['name_furigana']) : '未入力';
    $zip_code      = isset($_POST['zip_code'])      ? htmlspecialchars($_POST['zip_code'])      : '未入力';
    $prefecture    = isset($_POST['prefecture'])    ? htmlspecialchars($_POST['prefecture'])    : '未入力';
    $address_city  = isset($_POST['address_city'])  ? htmlspecialchars($_POST['address_city'])  : '未入力';
    $address_rest  = isset($_POST['address_rest'])  ? htmlspecialchars($_POST['address_rest'])  : '未入力';
    $phone_number  = isset($_POST['phone_number'])  ? htmlspecialchars($_POST['phone_number'])  : '未入力';
    
    // ⚠️ クレジットカード情報は機密性が非常に高いため、
    // 実際の運用では厳重なセキュリティ対策が必要です。
    // ここではデモンストレーションとして取得するのみに留めます。
    $card_number   = isset($_POST['card_number'])   ? htmlspecialchars($_POST['card_number'])   : '未入力';
    $security_code = isset($_POST['security_code']) ? '***' : '未入力'; // セキュリティコードは表示しない
    $card_holder   = isset($_POST['card_holder'])   ? htmlspecialchars($_POST['card_holder'])   : '未入力';


    // 2. データのバリデーション（検証）や整形（例：郵便番号の形式チェックなど）をここで行う

    // if (strlen($zip_code) !== 7 || !is_numeric($zip_code)) {
    //     // エラー処理
    //     die("郵便番号の形式が正しくありません。");
    // }


    // 3. データベースへの登録、決済処理APIの呼び出しなどのビジネスロジックを実行

    // 💡 実際の処理の代わりに、受け取ったデータを画面に出力してみます
    echo '<head><link rel="icon" type="image/png" href="../image/もふもふアイコン.png"></head>';
    echo "<h1>登録内容の確認</h1>";
    echo "<p>お名前（フリガナ）: $name_furigana</p>";
    echo "<p>郵便番号: $zip_code</p>";
    echo "<p>都道府県: $prefecture</p>";
    echo "<p>住所: $address_city $address_rest</p>";
    echo "<p>電話番号: $phone_number</p>";
    echo "<hr>";
    echo "<h2>クレジットカード情報</h2>";
    // 最後の4桁のみ表示するなど、マスク処理を推奨
    echo "<p>カード番号: " . substr($card_number, 0, 4) . "********" . substr($card_number, -4) . "</p>";
    echo "<p>セキュリティ番号: $security_code</p>";
    echo "<p>名義人: $card_holder</p>";
    echo "<hr>";
    echo "<p style='color: green;'>✅ 登録処理が完了しました。</p>";
    
    // 4. 成功ページへリダイレクト
    // header("Location: thank_you_page.html");
    // exit;

} else {
    // POST以外の方法でアクセスされた場合
    header("Location: payment_form.html"); // フォーム画面へ戻す
    exit;
}
?>