document.addEventListener('DOMContentLoaded', () => {

    // ==================================
    // ポップアップ関連のロジック (提供されたJSベース)
    // ==================================
    const openBtn = document.getElementById('openPopupBtn');
    const closeBtn = document.getElementById('closePopupBtn');
    const popup = document.getElementById('popup');

    // ポップアップを開く関数
    function openPopup() {
        // CSSで定義したアクティブクラスを使用
        popup.classList.add('is-active');
    }

    // ポップアップを閉じる関数
    function closePopup() {
        popup.classList.remove('is-active');
    }

    // ボタンにイベントリスナーを設定
    if (openBtn && closeBtn && popup) {
        openBtn.addEventListener('click', openPopup);
        closeBtn.addEventListener('click', closePopup);
    }

    // (オプション) ポップアップの外側をクリックして閉じる
    window.addEventListener('click', (event) => {
        if (event.target === popup) {
            closePopup();
        }
    });


    // ==================================
    // ナビゲーションメニューの開閉ロジック (Bulma/DOM操作)
    // ==================================
    const $navbarBurger = document.getElementById('navbarBurgerBtn');
    const $target = document.getElementById('navbarMenu');

    if ($navbarBurger && $target) {
        $navbarBurger.addEventListener('click', () => {
            // is-active クラスをトグルしてメニューを開閉する
            $navbarBurger.classList.toggle('is-active');
            $target.classList.toggle('is-active');
        });
    }
});