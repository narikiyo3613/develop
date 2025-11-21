document.addEventListener('DOMContentLoaded', function () {

    const addToCartBtn = document.querySelector('.add-to-cart-btn');
    const quantityInput = document.getElementById('quantity');

    const productId = addToCartBtn ? addToCartBtn.dataset.productId : null;
    const userId = addToCartBtn ? addToCartBtn.dataset.userId : null;

    if (addToCartBtn && productId) {

        addToCartBtn.addEventListener('click', function () {

            // 🔒 ログインチェック
            if (!userId) {
                alert('カートに商品を追加するにはログインが必要です。');
                window.location.href = '../php/login/login.php';
                return;
            }

            if (this.disabled) {
                alert('この商品は現在、在庫切れです。');
                return;
            }

            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

            if (isNaN(quantity) || quantity <= 0) {
                alert('追加する数量は1以上の数値を入力してください。');
                return;
            }

            fetch('../php/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `product_id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`カートに商品${quantity}個を追加しました！`);
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

// =====================================
// ★ 商品詳細ページ：お気に入り登録
// =====================================
document.addEventListener("DOMContentLoaded", () => {
    const favBtn = document.querySelector(".favorite-btn");
    if (!favBtn) return;

    favBtn.addEventListener("click", async function () {

        const productId = this.dataset.productId;

        const fd = new FormData();
        fd.append("product_id", productId);

        const res = await fetch("../php/add_favorite.php", {
            method: "POST",
            body: fd
        });

        const json = await res.json();

        if (json.success) {
            if (json.mode === "added") {
                this.classList.add("favorited");
            } else if (json.mode === "removed") {
                this.classList.remove("favorited");
            }
        } else {
            alert("エラー：" + json.error);
        }
    });
});
