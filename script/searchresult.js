document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');

    stars.forEach(star => {
        star.addEventListener('click', function(e) {
            e.preventDefault();

            const userId = this.dataset.userId;
            const productId = this.dataset.productId;

            // 🔒 未ログインならログインページへ
            if (!userId) {
                window.location.href = 'login/login.php';
                return;
            }

            // 押した瞬間に見た目を変更
            this.classList.add('active');

            // ✅ favorites に登録（非同期通信）
            fetch('add_favorite.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `product_id=${encodeURIComponent(productId)}`
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert('お気に入り登録に失敗しました');
                    this.classList.remove('active'); // 失敗時は元に戻す
                }
            })
            .catch(err => {
                console.error(err);
                this.classList.remove('active');
            });
        });
    });
});
