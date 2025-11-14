document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".favorite-btn").forEach(btn => {
        btn.addEventListener("click", async function(e) {
            e.stopPropagation(); // カード移動を防ぐ
            e.preventDefault();

            const productId = this.dataset.productId;

            const fd = new FormData();
            fd.append("product_id", productId);

            const res = await fetch("add_favorite.php", {
                method: "POST",
                body: fd
            });

            const json = await res.json();

            if (json.success) {
                if (json.mode === "added") {
                    this.classList.add("favorited");  // 黄色
                } else if (json.mode === "removed") {
                    this.classList.remove("favorited"); // ピンク
                }
            } else {
                alert("エラー：" + json.error);
            }
        });
    });

});
