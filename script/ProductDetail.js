document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');

    stars.forEach(star => {
        star.addEventListener('click', function(e) {
            // フォームの送信（ページ遷移）を一旦停止
            e.preventDefault(); 

            // data属性からユーザーIDを取得
            const userId = this.dataset.userId; 
            
            // 🌟 ログインチェック 🌟
            if (userId) {
                // ✅ ログイン済みの場合: お気に入り登録ページへ（お気に入り処理を実行）
                // フォームの action に設定されている favorite.php へ遷移させる
                // ここでは submit を行い、form の action へ遷移させる
                this.classList.add('active'); 
                
            } else {
                // 🔒 未ログインの場合: ログインページへリダイレクト
                alert('お気に入り登録にはログインが必要です。');
                window.location.href = '../login/login.php'; // 適切なパスに修正してください
            }
        });
    });
});