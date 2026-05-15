<?php
/**
 * file: /partsds/pds_parts_category_bar.php
 * 파츠 카테고리 이미지 그리드 바 — 상품 리스트 / 검색 결과 상단에 삽입
 * v3: 메인에서 선택한 차종을 상단 입력창에 표시 + 파츠 클릭 시 차종+부품명 검색
 *
 * include 방법:
 *   $pds_bar_file = G5_PATH . '/partsds/pds_parts_category_bar.php';
 *   if (file_exists($pds_bar_file)) include($pds_bar_file);
 */
if (!defined('_GNUBOARD_') && !defined('_EYOOM_')) exit;

/* ── 카테고리 이미지 BASE URL ─────────────────────────────────────────── */
if (!defined('PDS_CAT_IMG_BASE')) {
    define('PDS_CAT_IMG_BASE', '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor');
}

/* ── 파츠종류 목록 (ca_id2 코드 → 이미지 + 표시명) ─────────────────── */
$pds_cat_items = array(
    array('ca_id'=>'5001', 'name'=>'오일필터',         'img'=>'2024/09/06/3d7bf681d7979f8c353deb988eca1cb1.png'),
    array('ca_id'=>'5002', 'name'=>'에어필터',         'img'=>'2024/09/06/f8bb08e3ef6b2cabea71c85ced3c278c.png'),
    array('ca_id'=>'5003', 'name'=>'에어컨필터',       'img'=>'2024/09/06/4d15b07280f8c733b4b2e367e3a2bf68.png'),
    array('ca_id'=>'5004', 'name'=>'연료필터',         'img'=>'2024/09/11/80d524d2ead5ecf3ba1d30f89a7cfad7.png'),
    array('ca_id'=>'5005', 'name'=>'미션오일필터',     'img'=>'2024/09/06/df38071e2a6a7f0f9a96aaf54a016a42.png'),
    array('ca_id'=>'5006', 'name'=>'오일필터하우징',   'img'=>'2024/09/06/66580b342b4b2eb5f27e294e35004a14.png'),
    array('ca_id'=>'5007', 'name'=>'미션오일',         'img'=>'2024/09/06/2e2f7417b690b5507f4ba3069d6f36fb.png'),
    array('ca_id'=>'5008', 'name'=>'엔진오일',         'img'=>'2024/09/06/91af54e1b49990ff928a9ff5540e10eb.png'),
    array('ca_id'=>'5009', 'name'=>'부동액',           'img'=>'2024/09/06/7801fe711fe6da296403b50a552542f6.png'),
    array('ca_id'=>'5010', 'name'=>'브레이크오일',     'img'=>'2024/09/11/3e840e76a0433dcf04111685b2c16ac9.png'),
    array('ca_id'=>'5011', 'name'=>'브레이크디스크',   'img'=>'2024/09/11/ea7c7b751ed5cb4ff5eb187630d65d76.png'),
    array('ca_id'=>'5012', 'name'=>'브레이크패드',     'img'=>'2024/09/06/f6e679d16cd532a616b67f015fbd6926.png'),
    array('ca_id'=>'5013', 'name'=>'브레이크센서',     'img'=>'2024/09/06/a661ac58f6968301d1a5cc1c2533cf9f.png'),
    array('ca_id'=>'5014', 'name'=>'브레이크캘리퍼',   'img'=>'2024/09/07/47200c715596bcce41c283de7654170e.png'),
    array('ca_id'=>'5015', 'name'=>'엔진마운트',       'img'=>'2024/09/06/ef35247c9c232dcdd744c819b1165c3d.png'),
    array('ca_id'=>'5016', 'name'=>'미션마운트',       'img'=>'2024/09/06/62ff3f6bd11547f8920466ab5857e484.png'),
    array('ca_id'=>'5017', 'name'=>'V벨트',            'img'=>'2024/09/06/039ae4c1fd2fd7a3476cad013c44ec7c.png'),
    array('ca_id'=>'5018', 'name'=>'댐퍼풀리',         'img'=>'2024/09/06/a479b106c44a907fed73f874219ffcc8.png'),
    array('ca_id'=>'5019', 'name'=>'벨트텐셔너',       'img'=>'2024/09/06/0464e3ccf781610aef5992e3e52584f9.png'),
    array('ca_id'=>'5020', 'name'=>'워터펌프',         'img'=>'2024/09/11/a72fa4022daea7de867f60d94f2871eb.png'),
    array('ca_id'=>'5021', 'name'=>'써머스탯',         'img'=>'2024/09/11/6780f6f25c9e810f2f6c5f3daacc9171.png'),
    array('ca_id'=>'5022', 'name'=>'라디에이터 관련',  'img'=>'2024/09/06/b5a032d855d3d5e67a209f11e064bfe9.png'),
    array('ca_id'=>'5023', 'name'=>'알터네이터',       'img'=>'2024/09/06/cb5b2fd547a858c29c7acb2cbe33f375.png'),
    array('ca_id'=>'5024', 'name'=>'에어컨콤프레셔',   'img'=>'2024/09/06/e5193eefb12b0209b9781da558bb94b2.png'),
    array('ca_id'=>'5025', 'name'=>'스타트모터',       'img'=>'2024/09/06/58f35144e075f7c6d9ff770b3591a8a2.png'),
    array('ca_id'=>'5026', 'name'=>'흡기 매니폴드 관련','img'=>'2025/09/30/5ac767b494f4788e17ebff2180c7f09b.png'),
    array('ca_id'=>'5027', 'name'=>'고압펌프',         'img'=>'2024/09/06/515bf4268bb194484cf8660aed32ff72.png'),
    array('ca_id'=>'5028', 'name'=>'인젝터',           'img'=>'2024/09/06/d7ff28bbfe981cd2b196bc3679a13116.png'),
    array('ca_id'=>'5029', 'name'=>'와이퍼',           'img'=>'2024/09/09/40895e20cda09def43843192b044104c.png'),
    array('ca_id'=>'5030', 'name'=>'드라이브샤프트',   'img'=>'2024/09/06/5c62bf8211c75f49256cdce4a4a2dcee.png'),
    array('ca_id'=>'5031', 'name'=>'쇼바',             'img'=>'2024/09/06/36c066aa4771d6995505af6f6cb4a07a.png'),
    array('ca_id'=>'5032', 'name'=>'유니버셜조인트',   'img'=>'2024/09/06/7f8ec4a75e1892165b357ec00853f44e.png'),
    array('ca_id'=>'5033', 'name'=>'허브베어링',       'img'=>'2024/09/11/9a2fa9b4176fddaae08c4b1819150262.png'),
    array('ca_id'=>'5034', 'name'=>'휠볼트',           'img'=>'2024/09/11/%ED%9C%A0%EB%B3%B4%ED%8A%B8.png'),
    array('ca_id'=>'5035', 'name'=>'프로펠러샤프트',   'img'=>'2024/09/06/25dc65b7d9ec5ffb3e681ca699c96079.png'),
    array('ca_id'=>'5036', 'name'=>'하체부품',         'img'=>'2024/09/06/017304d00624d995d62034d436845ac2.png'),
    array('ca_id'=>'5037', 'name'=>'산소센서',         'img'=>'2024/09/06/9f736b0c6eb0f4b001a03b866227671a.png'),
    array('ca_id'=>'5038', 'name'=>'점화플러그(예열) 배선 관련','img'=>'2024/09/09/a47437577d11c9b6510446320a155451.png'),
    array('ca_id'=>'5039', 'name'=>'라이트모듈 관련',  'img'=>'2024/09/06/0c3e28e579009aa84b201c38653672b4.png'),
    array('ca_id'=>'5040', 'name'=>'자동차용품 관련',  'img'=>'2024/09/06/kar.png'),
    array('ca_id'=>'5041', 'name'=>'기타 관련',        'img'=>'logg2.png'),
);

/* ── 현재 선택된 ca_id (상품 리스트 페이지의 경우) ─────────────────── */
$pds_bar_active_ca = isset($ca_id) ? $ca_id : (isset($_GET['ca_id']) ? $_GET['ca_id'] : '');

/* ── URL에서 차종 파라미터 수집 ─────────────────────────────────────── */
$pds_sel_brand  = isset($_GET['pds_brand_id'])  ? (int)$_GET['pds_brand_id']  : 0;
$pds_sel_series = isset($_GET['pds_series_id']) ? (int)$_GET['pds_series_id'] : 0;
$pds_sel_model  = isset($_GET['pds_model_id'])  ? (int)$_GET['pds_model_id']  : 0;

// 하위 호환: 이전 파라미터명도 지원
if (!$pds_sel_brand  && isset($_GET['pds_brand']))  $pds_sel_brand  = (int)$_GET['pds_brand'];
if (!$pds_sel_series && isset($_GET['pds_series'])) $pds_sel_series = (int)$_GET['pds_series'];
if (!$pds_sel_model  && isset($_GET['pds_model']))  $pds_sel_model  = (int)$_GET['pds_model'];

/* ── 로그인 회원 차종 자동 적용 ─────────────────────────────────────── */
$pds_auto_member_car = false;
if (!$pds_sel_brand && $is_member && empty($_GET['pds_no_filter'])) {
    $mb4 = (int)($member['mb_4'] ?? 0);
    $mb5 = (int)($member['mb_5'] ?? 0);
    $mb6 = (int)($member['mb_6'] ?? 0);
    if ($mb4) {
        $pds_sel_brand  = $mb4;
        $pds_sel_series = $mb5;
        $pds_sel_model  = $mb6;
        $pds_auto_member_car = true;
    }
}

/* ── 차량 DB에서 선택된 차종 이름 조회 ─────────────────────────────── */
$pds_sel_brand_name  = '';
$pds_sel_series_name = '';
$pds_sel_model_name  = '';
$_pds_has_cardb = false;
$pds_brands_arr = array();
$pds_series_arr = array();
$pds_models_arr = array();

if (function_exists('sql_fetch')) {
    $pds_tbl_chk = @sql_query("SHOW TABLES LIKE '" . G5_TABLE_PREFIX . "car_brand'");
    if ($pds_tbl_chk && @mysql_num_rows($pds_tbl_chk) > 0) {
        $_pds_has_cardb = true;

        // 브랜드명
        if ($pds_sel_brand) {
            $r = sql_fetch("SELECT brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_id=" . (int)$pds_sel_brand);
            if ($r) $pds_sel_brand_name = $r['brand_name'];
        }
        // 시리즈명
        if ($pds_sel_series) {
            $r = sql_fetch("SELECT series_name FROM `" . G5_TABLE_PREFIX . "car_series` WHERE series_id=" . (int)$pds_sel_series);
            if ($r) $pds_sel_series_name = $r['series_name'];
        }
        // 모델명
        if ($pds_sel_model) {
            $r = sql_fetch("SELECT model_name FROM `" . G5_TABLE_PREFIX . "car_model` WHERE model_id=" . (int)$pds_sel_model);
            if ($r) $pds_sel_model_name = $r['model_name'];
        }

        // 브랜드 목록 (셀렉트박스용)
        $pds_brand_q = sql_query("SELECT brand_id AS id, brand_name AS name FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_use=1 ORDER BY brand_order ASC, brand_id ASC");
        while ($row = sql_fetch_array($pds_brand_q)) {
            $pds_brands_arr[] = array('id' => (int)$row['id'], 'name' => $row['name']);
        }

        // 시리즈 전체 목록
        $pds_series_q = sql_query("SELECT series_id AS id, brand_id, series_name AS name FROM `" . G5_TABLE_PREFIX . "car_series` WHERE series_use=1 ORDER BY series_order ASC, series_id ASC");
        while ($row = sql_fetch_array($pds_series_q)) {
            $bid = (int)$row['brand_id'];
            if (!isset($pds_series_arr[$bid])) $pds_series_arr[$bid] = array();
            $pds_series_arr[$bid][] = array('id' => (int)$row['id'], 'name' => $row['name']);
        }

        // 모델 전체 목록
        $pds_models_q = sql_query("SELECT model_id AS id, series_id, model_name AS name FROM `" . G5_TABLE_PREFIX . "car_model` WHERE model_use=1 ORDER BY model_order ASC, model_id ASC");
        while ($row = sql_fetch_array($pds_models_q)) {
            $sid = (int)$row['series_id'];
            if (!isset($pds_models_arr[$sid])) $pds_models_arr[$sid] = array();
            $pds_models_arr[$sid][] = array('id' => (int)$row['id'], 'name' => $row['name']);
        }
    }
}

// PHP 배열 → JS JSON 변환
$pds_brands_json = json_encode($pds_brands_arr, JSON_UNESCAPED_UNICODE);
$pds_series_json = json_encode($pds_series_arr, JSON_UNESCAPED_UNICODE);
$pds_models_json = json_encode($pds_models_arr, JSON_UNESCAPED_UNICODE);

// 현재 선택 차종 텍스트 조합
$pds_car_label_parts = array();
if ($pds_sel_brand_name)  $pds_car_label_parts[] = $pds_sel_brand_name;
if ($pds_sel_series_name) $pds_car_label_parts[] = $pds_sel_series_name;
if ($pds_sel_model_name)  $pds_car_label_parts[] = $pds_sel_model_name;
$pds_car_label = implode(' > ', $pds_car_label_parts);

// 현재 URL 기반 차종 파라미터 문자열 (링크 생성용)
$pds_car_params = '';
if ($pds_sel_brand)  $pds_car_params .= '&pds_brand_id='  . $pds_sel_brand;
if ($pds_sel_series) $pds_car_params .= '&pds_series_id=' . $pds_sel_series;
if ($pds_sel_model)  $pds_car_params .= '&pds_model_id='  . $pds_sel_model;

/* ── 링크 생성 함수 ─────────────────────────────────────────────────── */
if (!function_exists('pds_cat_link')) {
    /**
     * 파츠 카테고리 클릭 시 링크 생성
     * - 차종 선택 상태가 있으면: 검색창에서 [차종 + 부품명] 검색
     * - 차종 선택 상태가 없으면: 해당 ca_id 상품 목록으로 이동
     */
    function pds_cat_link($ca_id_val, $part_name, $brand_id=0, $series_id=0, $model_id=0, $brand_name='', $series_name='', $model_name='') {
        if ($brand_id && $brand_name) {
            // 차종 + 부품명 검색 키워드 조합
            $keyword_parts = array();
            if ($brand_name)  $keyword_parts[] = $brand_name;
            if ($series_name) $keyword_parts[] = $series_name;
            if ($model_name)  $keyword_parts[] = $model_name;
            $keyword_parts[] = $part_name;
            $keyword = implode(' ', $keyword_parts);

            // 검색 URL 생성 (카테고리 내 검색)
            $url = G5_SHOP_URL . '/search.php?stx=' . urlencode($keyword);
            if ($ca_id_val) $url .= '&category_no=' . urlencode($ca_id_val);
            $url .= '&pds_brand_id=' . (int)$brand_id;
            if ($series_id) $url .= '&pds_series_id=' . (int)$series_id;
            if ($model_id)  $url .= '&pds_model_id='  . (int)$model_id;
            return $url;
        } else {
            // 차종 미선택: ca_id 기반 상품 목록
            return G5_SHOP_URL . '/list.php?ca_id=' . $ca_id_val;
        }
    }
}
?>

<?php /* ── 차종 선택 바 (상단 표시) ──────────────────────────────────── */ ?>
<div class="pds-topbar-wrap">
    <!-- 선택된 차종 + 입력창 영역 -->
    <div class="pds-topbar-inner">
        <?php if ($pds_sel_brand): ?>
        <!-- 차종 선택 상태: 차종명 표시 + 변경 가능 -->
        <div class="pds-topbar-car-info">
            <span class="pds-topbar-car-icon"><i class="fas fa-car"></i></span>
            <span class="pds-topbar-car-name">
                <strong><?php echo htmlspecialchars($pds_sel_brand_name); ?></strong>
                <?php if ($pds_sel_series_name): ?>
                &nbsp;<span class="pds-topbar-sep">›</span>&nbsp;<strong><?php echo htmlspecialchars($pds_sel_series_name); ?></strong>
                <?php endif; ?>
                <?php if ($pds_sel_model_name): ?>
                &nbsp;<span class="pds-topbar-sep">›</span>&nbsp;<strong><?php echo htmlspecialchars($pds_sel_model_name); ?></strong>
                <?php endif; ?>
            </span>
            <?php if ($pds_auto_member_car): ?>
            <span class="pds-topbar-auto-badge">내 차량</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- 차종 셀렉트박스 (변경 가능) -->
        <?php if ($_pds_has_cardb && !empty($pds_brands_arr)): ?>
        <div class="pds-topbar-selects">
            <select id="pds_top_brand" class="pds-top-sel" onchange="pdsTopBrandChange(this.value)">
                <option value="">브랜드 선택</option>
                <?php foreach ($pds_brands_arr as $b): ?>
                <option value="<?php echo (int)$b['id']; ?>"<?php echo ($pds_sel_brand === (int)$b['id']) ? ' selected' : ''; ?>>
                    <?php echo htmlspecialchars($b['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
            <select id="pds_top_series" class="pds-top-sel" onchange="pdsTopSeriesChange(this.value)"<?php echo $pds_sel_brand ? '' : ' disabled'; ?>>
                <option value="">시리즈/연식 선택</option>
                <?php if ($pds_sel_brand && isset($pds_series_arr[$pds_sel_brand])): ?>
                    <?php foreach ($pds_series_arr[$pds_sel_brand] as $s): ?>
                    <option value="<?php echo (int)$s['id']; ?>"<?php echo ($pds_sel_series === (int)$s['id']) ? ' selected' : ''; ?>>
                        <?php echo htmlspecialchars($s['name']); ?>
                    </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <select id="pds_top_model" class="pds-top-sel"<?php echo $pds_sel_series ? '' : ' disabled'; ?>>
                <option value="">모델 선택</option>
                <?php if ($pds_sel_series && isset($pds_models_arr[$pds_sel_series])): ?>
                    <?php foreach ($pds_models_arr[$pds_sel_series] as $m): ?>
                    <option value="<?php echo (int)$m['id']; ?>"<?php echo ($pds_sel_model === (int)$m['id']) ? ' selected' : ''; ?>>
                        <?php echo htmlspecialchars($m['name']); ?>
                    </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <button type="button" class="pds-top-search-btn" onclick="pdsTopSearch()">
                <i class="fas fa-search"></i> 검색
            </button>
            <?php if ($pds_sel_brand): ?>
            <a href="<?php echo G5_SHOP_URL; ?>/list.php?ca_id=<?php echo htmlspecialchars($pds_bar_active_ca); ?>&pds_no_filter=1"
               class="pds-top-clear-btn" title="차종 초기화">✕ 차종 초기화</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- 부품번호 직접 검색 -->
        <form class="pds-topbar-search-form" action="<?php echo G5_SHOP_URL; ?>/search.php" method="get">
            <?php if ($pds_sel_brand): ?>
            <input type="hidden" name="pds_brand_id"  value="<?php echo (int)$pds_sel_brand; ?>">
            <?php if ($pds_sel_series): ?>
            <input type="hidden" name="pds_series_id" value="<?php echo (int)$pds_sel_series; ?>">
            <?php endif; ?>
            <?php if ($pds_sel_model): ?>
            <input type="hidden" name="pds_model_id"  value="<?php echo (int)$pds_sel_model; ?>">
            <?php endif; ?>
            <?php endif; ?>
            <div class="pds-topbar-search-inner">
                <input type="text" name="stx" id="pdsTopStxInput"
                       value="<?php echo isset($_GET['stx']) ? htmlspecialchars($_GET['stx']) : ''; ?>"
                       placeholder="예) 부품번호" class="pds-topbar-stx-input">
                <button type="submit" class="pds-topbar-stx-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<?php /* ── 파츠 카테고리 그리드 바 출력 ──────────────────────────────── */ ?>
<div class="pds-cat-bar-wrap">
    <p class="pds-cat-bar-title">PARTS</p>
    <div class="pds-cat-bar-grid">
        <?php foreach ($pds_cat_items as $pds_cat): ?>
        <?php $pds_cat_active = ($pds_bar_active_ca === $pds_cat['ca_id']) ? ' active' : ''; ?>
        <a href="<?php echo pds_cat_link(
            $pds_cat['ca_id'],
            $pds_cat['name'],
            $pds_sel_brand,
            $pds_sel_series,
            $pds_sel_model,
            $pds_sel_brand_name,
            $pds_sel_series_name,
            $pds_sel_model_name
        ); ?>" class="pds-cat-item<?php echo $pds_cat_active; ?>">
            <img src="<?php echo PDS_CAT_IMG_BASE . '/' . $pds_cat['img']; ?>"
                 alt="<?php echo htmlspecialchars($pds_cat['name']); ?>"
                 loading="lazy">
            <span><?php echo htmlspecialchars($pds_cat['name']); ?></span>
        </a>
        <?php endforeach; ?>
    </div>
    <p class="pds-cat-bar-note">
        그 외의 부품은 문의 주세요&nbsp;&nbsp;|&nbsp;&nbsp;이미지는 실제 제품과 다를 수 있으나, 제품명을 기준으로 구매 부탁드립니다
    </p>
</div>

<script>
(function(){
    var PDS_SERIES = <?php echo $pds_series_json; ?>;
    var PDS_MODELS = <?php echo $pds_models_json; ?>;
    var pdsCurrentCaId = '<?php echo addslashes($pds_bar_active_ca); ?>';

    window.pdsTopBrandChange = function(brandId) {
        var seriesSel = document.getElementById('pds_top_series');
        var modelSel  = document.getElementById('pds_top_model');

        seriesSel.innerHTML = '<option value="">시리즈/연식 선택</option>';
        modelSel.innerHTML  = '<option value="">모델 선택</option>';
        seriesSel.disabled  = true;
        modelSel.disabled   = true;

        if (!brandId) return;

        var seriesList = PDS_SERIES[parseInt(brandId)] || [];
        seriesList.forEach(function(s) {
            var opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.name;
            seriesSel.appendChild(opt);
        });
        seriesSel.disabled = (seriesList.length === 0);
    };

    window.pdsTopSeriesChange = function(seriesId) {
        var modelSel = document.getElementById('pds_top_model');

        modelSel.innerHTML = '<option value="">모델 선택</option>';
        modelSel.disabled  = true;

        if (!seriesId) return;

        var modelList = PDS_MODELS[parseInt(seriesId)] || [];
        modelList.forEach(function(m) {
            var opt = document.createElement('option');
            opt.value = m.id;
            opt.textContent = m.name;
            modelSel.appendChild(opt);
        });
        modelSel.disabled = (modelList.length === 0);
    };

    window.pdsTopSearch = function() {
        var brandId  = document.getElementById('pds_top_brand').value;
        var seriesId = document.getElementById('pds_top_series').value;
        var modelId  = document.getElementById('pds_top_model') ? document.getElementById('pds_top_model').value : '';

        var url = new URL(location.href);
        if (brandId)  { url.searchParams.set('pds_brand_id',  brandId);  }
        else          { url.searchParams.delete('pds_brand_id'); url.searchParams.delete('pds_no_filter'); }
        if (seriesId) { url.searchParams.set('pds_series_id', seriesId); }
        else          { url.searchParams.delete('pds_series_id'); }
        if (modelId)  { url.searchParams.set('pds_model_id',  modelId);  }
        else          { url.searchParams.delete('pds_model_id'); }
        url.searchParams.delete('page');
        location.href = url.toString();
    };
})();
</script>

<style>
/* ── PDS 상단 차종 바 ──────────────────────────────────────────────── */
.pds-topbar-wrap {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 14px 18px;
    margin-bottom: 14px;
}
.pds-topbar-inner {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.pds-topbar-car-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #333;
    background: #fff8f8;
    border: 1px solid #f5c6c0;
    border-radius: 6px;
    padding: 8px 14px;
}
.pds-topbar-car-icon { color: #c0392b; font-size: 15px; }
.pds-topbar-car-name strong { color: #c0392b; }
.pds-topbar-sep { color: #bbb; }
.pds-topbar-auto-badge {
    background: #c0392b;
    color: #fff;
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 11px;
    font-weight: 700;
    margin-left: 4px;
}
.pds-topbar-selects {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}
.pds-top-sel {
    height: 38px;
    min-width: 130px;
    padding: 0 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 13px;
    background: #fff;
    color: #333;
    cursor: pointer;
    flex: 1 1 130px;
    max-width: 220px;
}
.pds-top-sel:disabled { background: #f5f5f5; color: #aaa; cursor: not-allowed; }
.pds-top-search-btn {
    height: 38px;
    padding: 0 18px;
    background: #1a73e8;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: background .18s;
    flex-shrink: 0;
}
.pds-top-search-btn:hover { background: #1558c0; }
.pds-top-clear-btn {
    height: 38px;
    line-height: 38px;
    padding: 0 12px;
    background: #fff;
    color: #888;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 12px;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: color .18s, border-color .18s;
    flex-shrink: 0;
}
.pds-top-clear-btn:hover { color: #c0392b; border-color: #c0392b; text-decoration: none; }

/* 부품번호 검색 입력창 */
.pds-topbar-search-form { width: 100%; }
.pds-topbar-search-inner {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
    background: #fff;
    max-width: 500px;
}
.pds-topbar-stx-input {
    flex: 1;
    height: 40px;
    border: none;
    outline: none;
    padding: 0 14px;
    font-size: 14px;
    color: #333;
    background: transparent;
}
.pds-topbar-stx-input::placeholder { color: #bbb; }
.pds-topbar-stx-btn {
    width: 44px;
    height: 40px;
    border: none;
    background: transparent;
    color: #888;
    font-size: 16px;
    cursor: pointer;
    transition: color .18s;
    flex-shrink: 0;
}
.pds-topbar-stx-btn:hover { color: #c0392b; }

@media (max-width: 767px) {
    .pds-top-sel { min-width: 100%; max-width: 100%; flex: 1 1 100%; }
    .pds-top-search-btn { width: 100%; }
    .pds-top-clear-btn { width: 100%; text-align: center; }
    .pds-topbar-search-inner { max-width: 100%; }
}

/* ── PDS 파츠 카테고리 바 ────────────────────────────────────────── */
.pds-cat-bar-wrap {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 6px;
    padding: 20px 20px 14px;
    margin-bottom: 30px;
}
.pds-cat-bar-title {
    font-size: 1rem;
    font-weight: 700;
    color: #222;
    letter-spacing: .08em;
    margin: 0 0 14px;
    padding-bottom: 10px;
    border-bottom: 2px solid #222;
}
.pds-cat-bar-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 6px 4px;
}
@media (max-width: 1199px) {
    .pds-cat-bar-grid { grid-template-columns: repeat(5, 1fr); }
}
@media (max-width: 767px) {
    .pds-cat-bar-grid { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 479px) {
    .pds-cat-bar-grid { grid-template-columns: repeat(3, 1fr); }
}
.pds-cat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 8px 4px 7px;
    border: 1px solid #eee;
    border-radius: 5px;
    text-decoration: none;
    color: #444;
    font-size: 0.72rem;
    text-align: center;
    transition: border-color .18s, background .18s, color .18s;
    background: #fafafa;
    line-height: 1.35;
    word-break: keep-all;
}
.pds-cat-item:hover,
.pds-cat-item.active {
    border-color: #c0392b;
    background: #fff5f5;
    color: #c0392b;
    text-decoration: none;
}
.pds-cat-item img {
    width: 56px;
    height: 56px;
    object-fit: contain;
    margin-bottom: 5px;
    display: block;
}
@media (max-width: 767px) {
    .pds-cat-item img { width: 44px; height: 44px; }
    .pds-cat-item { font-size: 0.67rem; }
}
.pds-cat-bar-note {
    font-size: 0.75rem;
    color: #888;
    text-align: center;
    margin: 14px 0 0;
    padding-top: 10px;
    border-top: 1px solid #f0f0f0;
}
</style>
