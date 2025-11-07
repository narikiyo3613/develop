// search.js

// Google Mapオブジェクト本体
let map;
// 地図上に表示されているマーカーを保持する配列
let markers = [];

// 1. ダミーの店舗データ (緯度・経度はダミーですが、店舗ごとに異なる値に変更しました)
const stores = [
    {
        name: "ペッツ 東京本店",
        area: "tokyo",
        lat: 35.658034, 
        lng: 139.701636, // 渋谷近辺
        keyword: "犬,猫,ペットホテル,大型",
        address: "東京都渋谷区公園通り1-1",
        service: "トリミング、ホテル、用品販売"
    },
    {
        name: "わんにゃん 大阪梅田店",
        area: "osaka",
        lat: 34.702485, 
        lng: 135.495964, // 大阪駅近辺
        keyword: "犬,トリミング,駅近",
        address: "大阪府大阪市北区梅田2-2",
        service: "トリミング、フード販売"
    },
    {
        name: "アニマルライフ 福岡天神",
        area: "fukuoka",
        lat: 33.589139, 
        lng: 130.395729, // 福岡天神近辺
        keyword: "エキゾチック,鳥,小動物,専門店",
        address: "福岡県福岡市中央区天神3-3",
        service: "生体販売（鳥・小動物）、用品"
    },
    {
        name: "ワンコのお宿 新宿",
        area: "tokyo",
        lat: 35.688226, 
        lng: 139.699763, // 新宿近辺
        keyword: "ペットホテル,犬専用,送迎",
        address: "東京都新宿区西新宿4-4",
        service: "ペットホテル（犬限定）、一時預かり"
    },
    {
        name: "Cat's Garden 難波",
        area: "osaka",
        lat: 34.664421, 
        lng: 135.500249, // 難波近辺
        keyword: "猫,キャットカフェ,限定グッズ",
        address: "大阪府大阪市浪速区難波5-5",
        service: "キャットカフェ、猫用品販売"
    },
];

// 2. HTML要素の取得（変更なし）
const form = document.getElementById('store-search-form');
const areaSelect = document.getElementById('area-select');
const keywordInput = document.getElementById('keyword-input');
const storeList = document.getElementById('store-list');

// 3. フォーム送信時のイベントリスナー
form.addEventListener('submit', function (event) {
    event.preventDefault();
    performSearch();
});

// 4. 検索処理のメイン関数
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
            const searchFields = [
                store.name.toLowerCase(),
                store.keyword.toLowerCase(), 
                store.address.toLowerCase(),
                store.service.toLowerCase()
            ].join(' ');

            isMatch = isMatch && searchFields.includes(keyword);
        }

        return isMatch;
    });

    // HTMLリストの表示を更新
    displayResults(filteredStores);
    
    // 【★地図の表示を更新★】
    displayResultsOnMap(filteredStores); 
}

// 5. 結果をHTMLに表示する関数
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
            <a href="#" onclick="map.setCenter({lat: ${store.lat}, lng: ${store.lng}}); map.setZoom(15); return false;">地図の中心にする</a>
        `;
        storeList.appendChild(li);
    });
}

// ページロード時にも一度検索処理を実行（初期表示用）
document.addEventListener('DOMContentLoaded', () => {
    areaSelect.value = "";
    keywordInput.value = "";
    // performSearch()はinitMap()の完了後にも実行されるため、ここでは省略可能ですが、
    // マップAPIが遅延する場合に備えて実行しておいても構いません。
    // performSearch(); 
});

// --- 地図関連関数 (グローバルスコープに移動) ---

/**
 * 地図を初期化し、map-containerに表示する (Google Maps APIによってコールバックされる)
 */
function initMap() {
    const mapContainer = document.getElementById('map-container');

    // 初期表示の中心座標（ここでは日本の中心付近を使用）
    const initialLocation = { lat: 35.5, lng: 137.5 };

    map = new google.maps.Map(mapContainer, {
        center: initialLocation,
        zoom: 5, // 日本全体が概ね見えるズームレベル
    });

    // 地図が初期化されたら、初期状態（全件）で検索を実行し、マーカーを表示する
    performSearch();
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

/**
 * 既存のマーカーを消し、検索結果に基づいて新しいマーカーを配置する
 * @param {Array} results - 検索でフィルタリングされた店舗データの配列
 */
function displayResultsOnMap(results) {
    // 既存のマーカーを全て削除
    clearMarkers();

    if (results.length === 0) {
        return;
    }

    const bounds = new google.maps.LatLngBounds();

    results.forEach(store => {
        // stores配列の緯度・経度を使用
        const position = {
            lat: store.lat, 
            lng: store.lng 
        };

        const marker = new google.maps.Marker({
            position: position,
            map: map,
            title: store.name,
        });
        
        // (任意) マーカーにクリックイベントを追加
        marker.addListener('click', () => {
             alert(`${store.name}の情報を表示します`);
        });

        markers.push(marker);
        bounds.extend(position); // 地図の表示範囲をマーカーに合わせて拡大
    });

    // すべてのマーカーが見えるように地図の表示範囲を調整
    map.fitBounds(bounds);
}