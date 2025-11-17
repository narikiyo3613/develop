document.addEventListener('DOMContentLoaded', () => {
    const openBtn = document.getElementById('openPopupBtn');
    const closeBtn = document.getElementById('closePopupBtn');
    const popup = document.getElementById('popup');

    function openPopup() {
        popup.classList.add('is-active');
    }

    function closePopup() {
        popup.classList.remove('is-active');
    }

    if (openBtn) openBtn.addEventListener('click', openPopup);
    if (closeBtn) closeBtn.addEventListener('click', closePopup);

    // 背景クリックで閉じる
    window.addEventListener('click', (event) => {
        if (event.target === popup) closePopup();
    });
});
