// search.js
// Google Mapオブジェクト本体
let map;
// 地図上に表示されているマーカーを保持する配列
let markers = [];
// 1. ダミーの店舗データ (ペットショップ仕様に更新)
const stores = [
    {
        name: "ペッツ 東京本店",
        area: "tokyo",
        lat: 35.658034, 
        lng: 139.701636,
        keyword: "犬,猫,ペットホテル,大型",
        address: "東京都渋谷区公園通り1-1",
        service: "トリミング、ホテル、用品販売"
    },
    {
        name: "わんにゃん 大阪梅田店",
        area: "osaka",
        lat: 35.658034, 
        lng: 139.701636,
        keyword: "犬,トリミング,駅近",
        address: "大阪府大阪市北区梅田2-2",
        service: "トリミング、フード販売"
    },
    {
        name: "アニマルライフ 福岡天神",
        area: "fukuoka",
        lat: 35.658034, 
        lng: 139.701636,
        keyword: "エキゾチック,鳥,小動物,専門店",
        address: "福岡県福岡市中央区天神3-3",
        service: "生体販売（鳥・小動物）、用品"
    },
    {
        name: "ワンコのお宿 新宿",
        area: "tokyo",
        lat: 35.658034, 
        lng: 139.701636,
        keyword: "ペットホテル,犬専用,送迎",
        address: "東京都新宿区西新宿4-4",
        service: "ペットホテル（犬限定）、一時預かり"
    },
    {
        name: "Cat's Garden 難波",
        area: "osaka",
        lat: 35.658034, 
        lng: 139.701636,
        keyword: "猫,キャットカフェ,限定グッズ",
        address: "大阪府大阪市浪速区難波5-5",
        service: "キャットカフェ、猫用品販売"
    },
    // 必要に応じて店舗データを追加してください
];

// 2. HTML要素の取得（変更なし）
const form = document.getElementById('store-search-form');
const areaSelect = document.getElementById('area-select');
const keywordInput = document.getElementById('keyword-input');
const storeList = document.getElementById('store-list');

// 3. フォーム送信時のイベントリスナー（変更なし）
form.addEventListener('submit', function (event) {
    event.preventDefault();
    performSearch();
});

// 4. 検索処理のメイン関数（検索対象フィールドを調整）
function performSearch() {
    const selectedArea = areaSelect.value;
    const keyword = keywordInput.value.toLowerCase().trim();

    const filteredStores = stores.filter(store => {
        let isMatch = true;

        // エリアでの絞り込み
        if (selectedArea && selectedArea !== "") {
            isMatch = isMatch && (store.area === selectedArea);
        }

        // キーワードでの絞り込み
        if (keyword) {
            // 検索対象フィールドを店舗名、keyword、address、serviceに変更
            const searchFields = [
                store.name.toLowerCase(),
                store.keyword.toLowerCase(), // 例: "犬,猫,ペットホテル"
                store.address.toLowerCase(),
                store.service.toLowerCase() // 例: "トリミング、ホテル、用品販売"
            ].join(' ');

            isMatch = isMatch && searchFields.includes(keyword);
        }

        return isMatch;
        // search.js に追加

        /**
         * 既存のマーカーを消し、検索結果に基づいて新しいマーカーを配置する
         * @param {Array} results - 検索でフィルタリングされた店舗データの配列
         */
        function displayResultsOnMap(results) {
            // 既存のマーカーを全て削除
            clearMarkers();

            if (results.length === 0) {
                // 結果がない場合は、初期位置に戻すか、地図をそのままにする
                return;
            }

            const bounds = new google.maps.LatLngBounds();

            results.forEach(store => {
                // 注: 実際には、緯度・経度(lat/lng)のデータがstores配列に必要です！
                // 今回はダミーデータとして緯度・経度を仮定します
                const position = {
                    lat: 35.6 + Math.random() * 0.1, // ダミーの緯度
                    lng: 139.6 + Math.random() * 0.1  // ダミーの経度
                };

                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: store.name,
                });

                markers.push(marker);
                bounds.extend(position); // 地図の表示範囲をマーカーに合わせて拡大
            });

            // すべてのマーカーが見えるように地図の表示範囲を調整
            map.fitBounds(bounds);
        }

        /**
         * 全てのマーカーを地図から削除し、markers配列をクリアする
         */
        function clearMarkers() {
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        }
    });

    displayResults(filteredStores);
}

// 5. 結果をHTMLに表示する関数（表示項目をサービス内容に合わせて調整）
function displayResults(results) {
    storeList.innerHTML = '';

    if (results.length === 0) {
        storeList.innerHTML = `<li class="initial-message">該当する店舗は見つかりませんでした。</li>`;
        return;
    }

    results.forEach(store => {
        const li = document.createElement('li');
        li.innerHTML = `
            <h3>${store.name}</h3>
            <p><strong>サービス内容:</strong> ${store.service}</p>
            <p><strong>住所:</strong> ${store.address}</p>
            <p><strong>特徴タグ:</strong> ${store.keyword.split(',').map(tag => `<span>#${tag.trim()}</span>`).join(' ')}</p>
            <a href="#" onclick="alert('${store.name}の地図を表示')">地図を見る</a>
        `;
        storeList.appendChild(li);
    });
}

// ページロード時にも一度検索処理を実行（初期表示用）
document.addEventListener('DOMContentLoaded', () => {
    areaSelect.value = "";
    keywordInput.value = "";
    performSearch();
});
/**
 * 地図を初期化し、map-containerに表示する
 */
function initMap() {
    const mapContainer = document.getElementById('map-container');

    // 初期表示の中心座標（例：東京の適当な場所）
    const initialLocation = { lat: 35.6895, lng: 139.6917 };

    map = new google.maps.Map(mapContainer, {
        center: initialLocation,
        zoom: 10, // ズームレベル
    });

    // 検索結果の初期表示（マーカーがない状態）
    displayResultsOnMap([]);
}