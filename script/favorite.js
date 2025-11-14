document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".favorite-btn").forEach(btn => {
        btn.addEventListener("click", async function () {

            const productId = this.dataset.productId;

            const formData = new FormData();
            formData.append("product_id", productId);

            const response = await fetch("../add_favorite.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {

                // すでに登録済みだった場合
                if (result.message === "already_exists") {
                    this.classList.add("favorited");
                    return;
                }

                // 新規追加成功時
                this.classList.add("favorited");

            } else {
                alert("お気に入り追加に失敗しました…");
            }
        });
    });

});
