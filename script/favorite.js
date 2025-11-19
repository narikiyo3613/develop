document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".favorite-btn").forEach(btn => {

        btn.addEventListener("click", async function (e) {
            e.preventDefault();
            e.stopPropagation(); // カード遷移防止（検索結果用）

            const userId = this.dataset.userId;
            const productId = this.dataset.productId;

            // 🔒 未ログインならログインページへ
            if (!userId) {
                alert("お気に入りにはログインが必要です");
                window.location.href = "../php/login/login.php";
                return;
            }

            const fd = new FormData();
            fd.append("product_id", productId);

            const res = await fetch("../php/add_favorite.php", {
                method: "POST",
                body: fd
            });

            const json = await res.json();

            // 成功時
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

});
