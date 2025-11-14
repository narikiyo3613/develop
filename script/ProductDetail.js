document.addEventListener('DOMContentLoaded', function() {
    // 必要なDOM要素の取得
    const stars = document.querySelectorAll('.star');
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    const productIdInput = document.querySelector('.star-form input[name="product_id"]');
    const quantityInput = document.getElementById('quantity'); // HTMLに追加した数量入力欄のID

    // 🌟 商品IDの取得（カート処理で使用）
    const productId = productIdInput ? productIdInput.value : null;

    // --- 1. お気に入り（星）ボタンの処理 ---
    stars.forEach(star => {
        star.addEventListener('click', function(e) {
            e.preventDefault(); // フォームの送信を一旦停止

            // data属性からユーザーIDを取得。未ログインの場合は null, undefined, 'null'など
            const userId = this.dataset.userId; 
            
            // 🔒 ログインチェック
            if (!userId || userId === 'null' || userId === '') {
                // 🔐 未ログインの場合: アラートを出してログイン画面へ
                alert('お気に入り登録にはログインが必要です。');
                // 適切なログイン画面のパスを指定してください（ここでは '../php/login/login.php' が正しいと仮定）
                window.location.href = '../php/login/login.php'; 
                return;
            }
            
            // ✅ ログイン済みの場合:
            // 1. 星マークを「アクティブ」にするクラスを追加し、見た目を一時的に変化させる
            this.classList.add('active'); 
            
            // 2. フォームを明示的に送信し、favorite.php へ処理を委ねる
            this.closest('form').submit(); 
        });
    });
    
    // --- 2. カートに追加ボタンの処理 (非同期通信/Ajax) ---
    if (addToCartBtn && productId) {
        addToCartBtn.addEventListener('click', function() {
            // お気に入りボタンからユーザーIDを再取得（ログインチェック用）
            const userId = stars[0] ? stars[0].dataset.userId : null;
            
            // 🔒 ログインチェック
            if (!userId || userId === 'null' || userId === '') {
                alert('カートに商品を追加するにはログインが必要です。');
                // ログイン画面のパスを統一
                window.location.href = '../php/login/login.php'; 
                return;
            }

            // 在庫切れチェック
            if (this.disabled) {
                alert('この商品は現在、在庫切れです。');
                return;
            }

            // 🌟 選択された数量を取得 🌟
            // quantityInput が存在しない場合はデフォルトで 1
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1; 
            
            // 数量の簡易チェック
            if (isNaN(quantity) || quantity <= 0) {
                alert('追加する数量は1以上の数値を入力してください。');
                return;
            }

            // 非同期通信を実行（add_to_cart.phpへ）
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                // product_id と 数量(quantity) を送信
                body: `product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}` 
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`カートに商品${quantity}個を追加しました！`);
                    // ★ 成功後、カートの数を更新するなどの追加処理をここで行えます ★
                } else {
                    alert('カートへの追加に失敗しました: ' + (data.message || '不明なエラー'));
                }
            })
            .catch(error => {
                console.error('通信エラー:', error);
                alert('通信エラーが発生し、カートに追加できませんでした。');
            });
        });
    }
});