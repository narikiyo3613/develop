// search.js

// Google Mapオブジェクト本体
let map;
// 地図上に表示されているマーカーを保持する配列
let markers = [];

const stores = [
    {
        "name": "麻生情報ビジネス専門学校 本店",
        "area": "fukuoka_hakata",
        "lat": 33.583219,
        "lng": 130.421574,
        "keyword": "専門学校,IT,情報処理,ビジネス,資格取得",
        "address": "福岡県福岡市博多区博多駅南2-12-32",
        "service": "IT・情報・ゲーム・ビジネス系学科教育、資格取得支援、就職サポート"
    },
    {
        name: "ペットワールド 東京八重洲口店",
        area: "tokyo",
        lat: 35.681236,
        lng: 139.767125, // 東京駅八重洲口近辺
        keyword: "犬,猫,トリミング,用品販売,駅直結",
        address: "東京都千代田区丸の内1-5",
        service: "トリミング、用品販売、ペット保険相談"
    },
    {
        name: "にゃんこステーション 名古屋",
        area: "nagoya",
        lat: 35.170915,
        lng: 136.881534, // 名古屋駅近辺（西側）
        keyword: "猫,キャットホテル,一時預かり,駅近",
        address: "愛知県名古屋市中村区名駅4-20",
        service: "キャットホテル、猫の一時預かり"
    },
    {
        name: "どうぶつ広場 博多駅アミュ店",
        area: "fukuoka",
        lat: 33.590139,
        lng: 130.419137, // 博多駅ビル内想定
        keyword: "犬,猫,小動物,生体販売,フード",
        address: "福岡県福岡市博多区博多駅中央街1-1 JR博多シティ内",
        service: "生体販売（犬・猫・小動物）、フード・用品販売"
    },
    {
        name: "ドッグラン＆カフェ 東京丸の内",
        area: "tokyo",
        lat: 35.681283,
        lng: 139.765691, // 東京駅丸の内側近辺
        keyword: "犬,カフェ,ドッグラン,イベント",
        address: "東京都千代田区丸の内2-1",
        service: "ドッグカフェ、室内ドッグラン、しつけ教室"
    },
    {
        name: "エキゾチックペットの森 名古屋",
        area: "nagoya",
        lat: 35.172153,
        lng: 136.885661, // 名古屋駅東側近辺
        keyword: "エキゾチック,爬虫類,鳥,専門店,珍種",
        address: "愛知県名古屋市中村区名駅1-1-4",
        service: "生体販売（爬虫類・鳥類）、専用用品販売"
    },
    {
        name: "北のわんわんホテル 札幌駅前",
        area: "sapporo",
        lat: 43.069408,
        lng: 141.350369, // 札幌駅近辺
        keyword: "犬,ペットホテル,大型犬対応,送迎",
        address: "北海道札幌市北区北6条西4-1",
        service: "ペットホテル（犬専門）、送迎サービス"
    },
    {
        name: "ヨコハマ・グルーミングサロン",
        area: "yokohama",
        lat: 35.466030,
        lng: 139.622610, // 横浜駅西口近辺
        keyword: "トリミング,犬,猫,予約制,高級",
        address: "神奈川県横浜市西区高島2-18",
        service: "グルーミング・トリミング（犬・猫）、エステ"
    },
    {
        name: "バード＆小動物専門店 SAPPORO",
        area: "sapporo",
        lat: 43.067300,
        lng: 141.354000, // 札幌駅東側近辺
        keyword: "鳥,小動物,ハムスター,専門",
        address: "北海道札幌市中央区北5条東2-2",
        service: "生体販売（鳥・小動物）、飼育用品"
    },
    {
        name: "マリンペット 横浜東口",
        area: "yokohama",
        lat: 35.463210,
        lng: 139.626500, // 横浜駅東口近辺
        keyword: "熱帯魚,水槽,アクアリウム,海水魚",
        address: "神奈川県横浜市西区金港町1-10",
        service: "熱帯魚・海水魚の生体販売、水槽・関連用品設置サービス"
    },
    {
        name: "杜の都ペットクリニック",
        area: "sendai",
        lat: 38.261895,
        lng: 140.880467, // 仙台駅東口近辺
        keyword: "動物病院,緊急,夜間診療,犬,猫",
        address: "宮城県仙台市宮城野区榴岡4-1",
        service: "動物医療（診療・手術）、予防接種"
    },
    {
        name: "ひろしまワンニャン市場",
        area: "hiroshima",
        lat: 34.396550,
        lng: 132.478800, // 広島駅南口近辺
        keyword: "犬,猫,生体販売,フード,しつけ",
        address: "広島県広島市南区松原町5-1",
        service: "生体販売（犬・猫）、フード・用品、しつけ相談"
    },
    {
        name: "港町アニマルクリニック 神戸三宮",
        area: "kobe",
        lat: 34.693450,
        lng: 135.195000, // 三宮駅近辺を想定（神戸の中心部）
        keyword: "動物病院,老犬介護,健康診断",
        address: "兵庫県神戸市中央区加納町4-3",
        service: "動物医療、老犬介護相談、定期健診"
    },
    {
        name: "古都のキャットカフェ 祇園",
        area: "kyoto",
        lat: 35.003900,
        lng: 135.779700, // 京都駅からは少し離れるが、観光客向けを想定
        keyword: "猫,キャットカフェ,保護猫,観光",
        address: "京都府京都市東山区祇園町南側570",
        service: "キャットカフェ、猫用品販売、里親募集"
    },
    {
        name: "さいたまアニマルグッズ大宮店",
        area: "omiya",
        lat: 35.906950,
        lng: 139.623400, // 大宮駅西口近辺
        keyword: "用品専門店,大型店,フード,アウトレット",
        address: "埼玉県さいたま市大宮区桜木町1-10",
        service: "ペット用品・フード販売、アウトレットコーナー"
    }
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