document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    
    // PHPで定義した product_id (hidden fieldから取得)
    const productIdInput = document.querySelector('.star-form input[name="product_id"]');
    const productId = productIdInput ? productIdInput.value : null;

    // 🌟 1. お気に入り（星）ボタンの処理 🌟
    stars.forEach(star => {
        star.addEventListener('click', function(e) {
            e.preventDefault(); // フォームの送信を一旦停止

            // data属性からユーザーIDを取得。未ログインなら 'null' 文字列または空
            const userId = this.dataset.userId; 
            
            // 🔒 ログインチェック
            if (!userId || userId === 'null' || userId === '') {
                // 🔐 未ログインの場合: アラートを出してログイン画面へ
                alert('お気に入り登録にはログインが必要です。');
                // 適切なログイン画面のパスを指定してください
                window.location.href = '../login/login.php'; 
                return; // ここで処理を終了
            }
            
            // ✅ ログイン済みの場合:
            
            // 1. 星マークを「アクティブ」にするクラスを追加し、見た目を変化させる
            //    (この見た目の変化を維持するには、favorite.php側で処理後、
            //     このページに戻ったときにPHP側でactiveクラスを付与する必要があります)
            this.classList.add('active'); 
            
            // 2. フォームを明示的に送信し、favorite.php へ処理を委ねる
            this.closest('form').submit(); 
        });
    });

    // 🛒 2. カートに追加ボタンの処理 (非同期通信/Ajax) 🛒
    if (addToCartBtn && productId) {
        addToCartBtn.addEventListener('click', function() {
            // お気に入りボタンからユーザーIDを取得
            const userId = stars[0] ? stars[0].dataset.userId : null;
            
            // 🔒 ログインチェック
            if (!userId || userId === 'null' || userId === '') {
                alert('カートに商品を追加するにはログインが必要です。');
                window.location.href = '../login/login.php';
                return;
            }

            // 在庫チェック (PHP側で無効化されている場合は処理しない)
            if (this.disabled) {
                alert('この商品は現在、在庫切れです。');
                return;
            }

            // 1個追加として非同期通信を実行 (quantity=1)
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${encodeURIComponent(productId)}&quantity=1` 
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('カートに商品を追加しました！');
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