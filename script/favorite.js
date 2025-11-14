document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".favorite-btn").forEach(btn => {
        btn.addEventListener("click", async function() {

            const id = this.dataset.productId;

            const fd = new FormData();
            fd.append("product_id", id);

            const res = await fetch("../add-favorite.php", {
                method: "POST",
                body: fd
            });

            const json = await res.json();

            if (json.success) {
                if (json.mode === "added") {
                    this.classList.add("favorited");  // 金色に
                } else if (json.mode === "removed") {
                    this.classList.remove("favorited"); // ピンクに戻す
                }
            } else {
                alert("エラー：" + json.error);
            }
        });
    });

});
