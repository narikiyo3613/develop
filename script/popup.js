document.addEventListener('DOMContentLoaded', () => {
    const openBtn = document.getElementById('openPopupBtn');
    const closeBtn = document.getElementById('closePopupBtn');
    const popup = document.getElementById('popup');

    function openPopup() {
        popup.classList.add('is-active');

        // ▼ ボタンを隠す（topと同じ動作）
        if (openBtn) openBtn.style.display = "none";
    }

    function closePopup() {
        popup.classList.remove('is-active');

        // ▼ ボタンを戻す
        if (openBtn) openBtn.style.display = "flex";
    }

    if (openBtn) openBtn.addEventListener('click', openPopup);
    if (closeBtn) closeBtn.addEventListener('click', closePopup);

    // ▼ 背景クリックでも閉じる
    window.addEventListener('click', (event) => {
        if (event.target === popup) closePopup();
    });
});
