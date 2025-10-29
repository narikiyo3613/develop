// 要素の取得
const openBtn = document.getElementById('openPopupBtn');
const closeBtn = document.getElementById('closePopupBtn');
const popup = document.getElementById('popup');

// ポップアップを開く関数
function openPopup() {
    popup.classList.add('show');
    // または popup.style.display = 'flex';
}

// ポップアップを閉じる関数
function closePopup() {
    popup.classList.remove('show');
    // または popup.style.display = 'none';
}

// ボタンにイベントリスナーを設定
openBtn.addEventListener('click', openPopup);
closeBtn.addEventListener('click', closePopup);

// (オプション) ポップアップの外側をクリックして閉じる
window.addEventListener('click', (event) => {
    if (event.target === popup) {
        closePopup();
    }
});