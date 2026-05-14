<?php
/**
 * 파츠디에스 - 메인 홈 통합 위젯
 * 경로: /partsds/pds_home_widget.php
 *
 * 기능:
 *  1. 차종 선택 UI (브랜드 → 시리즈 → 모델 드롭다운 + 차량검색 버튼)
 *  2. OEM 부품번호 직접 검색창
 *  3. 파츠 카테고리 이미지 그리드 (41개)
 *  4. 브랜드 로고 가로 바
 *  5. 모든 요소 관리자 설정(pds_config)으로 ON/OFF 제어
 *
 * 사용법:
 *  <?php include_once(G5_PATH.'/partsds/pds_home_widget.php'); ?>
 *
 * 사전조건:
 *  - install_partsds_config.sql 실행 (pds_config 테이블)
 *  - install_car_tables.php 실행 (car_brand/series/model 테이블)
 *  - install_parts_categories.sql 실행 (ca_id 5001~5041)
 */
if (!defined('_GNUBOARD_') && !defined('_EYOOM_')) exit;

/* ── 설정 로드 ──────────────────────────────────────────── */
define('_PDS_HOME_WIDGET_', true);
define('PDS_HW_CFG', G5_TABLE_PREFIX . 'pds_config');

function _pds_hw_cfg($key, $default = '') {
    static $cache = [];
    if (!isset($cache[$key])) {
        $r = @sql_fetch("SELECT cfg_val FROM `" . PDS_HW_CFG . "` WHERE cfg_key = '" . sql_escape_string($key) . "'");
        $cache[$key] = ($r && isset($r['cfg_val'])) ? $r['cfg_val'] : $default;
    }
    return $cache[$key];
}

// 테이블 존재 여부 확인
$_hw_tbl = @sql_fetch("SELECT COUNT(*) AS cnt FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . PDS_HW_CFG . "'");
if (empty($_hw_tbl['cnt'])) return; // 테이블 없으면 위젯 전체 스킵

// 위젯 사용 여부
if (_pds_hw_cfg('home_widget_use', 'y') !== 'y') return;

/* ── 차종 데이터 로드 ───────────────────────────────────── */
$_hw_use_car     = (_pds_hw_cfg('home_car_selector_use', 'y') === 'y');
$_hw_use_oem     = (_pds_hw_cfg('home_oem_search_use',   'y') === 'y');
$_hw_use_grid    = (_pds_hw_cfg('home_parts_grid_use',   'y') === 'y');
$_hw_use_brand   = (_pds_hw_cfg('home_brand_bar_use',    'y') === 'y');
$_hw_grid_cols   = (int)_pds_hw_cfg('home_parts_grid_cols', '6');
if (!in_array($_hw_grid_cols, [4,5,6])) $_hw_grid_cols = 6;
$_hw_url_mode    = _pds_hw_cfg('search_url_mode', 'ca_id');

// 차종 브랜드 목록
$_hw_brands = [];
$_hw_brand_check = @sql_fetch("SELECT COUNT(*) AS cnt FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . G5_TABLE_PREFIX . "car_brand'");
$_hw_has_car_table = !empty($_hw_brand_check['cnt']);

if ($_hw_has_car_table) {
    $r = sql_query("SELECT brand_id, brand_name, brand_name_en, brand_logo, ca_id
                    FROM `" . G5_TABLE_PREFIX . "car_brand`
                    WHERE brand_use = 1
                    ORDER BY brand_order, brand_id LIMIT 50");
    while ($row = sql_fetch_array($r)) $_hw_brands[] = $row;

    // 시리즈 전체 (브랜드별 JS 딕셔너리용)
    $_hw_all_series = [];
    $r2 = sql_query("SELECT series_id, brand_id, series_name, ca_id
                     FROM `" . G5_TABLE_PREFIX . "car_series`
                     WHERE series_use = 1
                     ORDER BY series_order, series_id");
    while ($row = sql_fetch_array($r2)) {
        $_hw_all_series[(int)$row['brand_id']][] = [
            'id' => (int)$row['series_id'], 'name' => $row['series_name'], 'ca_id' => $row['ca_id']
        ];
    }

    // 모델 전체 (시리즈별 JS 딕셔너리용)
    $_hw_all_models = [];
    $r3 = sql_query("SELECT model_id, series_id, model_name, model_year, ca_id
                     FROM `" . G5_TABLE_PREFIX . "car_model`
                     WHERE model_use = 1
                     ORDER BY model_order, model_id");
    while ($row = sql_fetch_array($r3)) {
        $dname = $row['model_name'];
        if ($row['model_year']) $dname .= ' (' . $row['model_year'] . ')';
        $_hw_all_models[(int)$row['series_id']][] = [
            'id' => (int)$row['model_id'], 'name' => $dname, 'ca_id' => $row['ca_id']
        ];
    }

    // 로그인 회원 저장 차종 자동선택
    $_hw_member_car = ['brand_id'=>0,'series_id'=>0,'model_id'=>0];
    if ($is_member) {
        $_hw_member_car = [
            'brand_id'  => (int)($member['mb_4'] ?? 0),
            'series_id' => (int)($member['mb_5'] ?? 0),
            'model_id'  => (int)($member['mb_6'] ?? 0),
        ];
    }
}

/* ── 파츠 카테고리 정의 ─────────────────────────────────── */
$_hw_parts_cats = [
    ['ca_id'=>'5001','name'=>'오일필터',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/3d7bf681d7979f8c353deb988eca1cb1.png'],
    ['ca_id'=>'5002','name'=>'에어필터',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/f8bb08e3ef6b2cabea71c85ced3c278c.png'],
    ['ca_id'=>'5003','name'=>'에어컨필터',         'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/4d15b07280f8c733b4b2e367e3a2bf68.png'],
    ['ca_id'=>'5004','name'=>'연료필터',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/80d524d2ead5ecf3ba1d30f89a7cfad7.png'],
    ['ca_id'=>'5005','name'=>'미션오일필터',       'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/df38071e2a6a7f0f9a96aaf54a016a42.png'],
    ['ca_id'=>'5006','name'=>'오일필터하우징',     'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/66580b342b4b2eb5f27e294e35004a14.png'],
    ['ca_id'=>'5007','name'=>'미션오일',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/2e2f7417b690b5507f4ba3069d6f36fb.png'],
    ['ca_id'=>'5008','name'=>'엔진오일',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/91af54e1b49990ff928a9ff5540e10eb.png'],
    ['ca_id'=>'5009','name'=>'부동액',             'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/7801fe711fe6da296403b50a552542f6.png'],
    ['ca_id'=>'5010','name'=>'브레이크오일',       'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/3e840e76a0433dcf04111685b2c16ac9.png'],
    ['ca_id'=>'5011','name'=>'브레이크디스크',     'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/ea7c7b751ed5cb4ff5eb187630d65d76.png'],
    ['ca_id'=>'5012','name'=>'브레이크패드',       'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/f6e679d16cd532a616b67f015fbd6926.png'],
    ['ca_id'=>'5013','name'=>'브레이크센서',       'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/a661ac58f6968301d1a5cc1c2533cf9f.png'],
    ['ca_id'=>'5014','name'=>'브레이크캘리퍼',     'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/07/47200c715596bcce41c283de7654170e.png'],
    ['ca_id'=>'5015','name'=>'엔진마운트',         'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/ef35247c9c232dcdd744c819b1165c3d.png'],
    ['ca_id'=>'5016','name'=>'미션마운트',         'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/62ff3f6bd11547f8920466ab5857e484.png'],
    ['ca_id'=>'5017','name'=>'V벨트',              'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/039ae4c1fd2fd7a3476cad013c44ec7c.png'],
    ['ca_id'=>'5018','name'=>'댐퍼풀리',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/a479b106c44a907fed73f874219ffcc8.png'],
    ['ca_id'=>'5019','name'=>'벨트텐셔너',         'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/0464e3ccf781610aef5992e3e52584f9.png'],
    ['ca_id'=>'5020','name'=>'워터펌프',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/a72fa4022daea7de867f60d94f2871eb.png'],
    ['ca_id'=>'5021','name'=>'써머스탯',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/6780f6f25c9e810f2f6c5f3daacc9171.png'],
    ['ca_id'=>'5022','name'=>'라디에이터 관련',    'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/b5a032d855d3d5e67a209f11e064bfe9.png'],
    ['ca_id'=>'5023','name'=>'알터네이터',         'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/cb5b2fd547a858c29c7acb2cbe33f375.png'],
    ['ca_id'=>'5024','name'=>'에어컨콤프레셔',     'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/e5193eefb12b0209b9781da558bb94b2.png'],
    ['ca_id'=>'5025','name'=>'스타트모터',         'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/58f35144e075f7c6d9ff770b3591a8a2.png'],
    ['ca_id'=>'5026','name'=>'흡기 매니폴드 관련', 'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2025/09/30/5ac767b494f4788e17ebff2180c7f09b.png'],
    ['ca_id'=>'5027','name'=>'고압펌프',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/515bf4268bb194484cf8660aed32ff72.png'],
    ['ca_id'=>'5028','name'=>'인젝터',             'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/d7ff28bbfe981cd2b196bc3679a13116.png'],
    ['ca_id'=>'5029','name'=>'와이퍼',             'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/09/40895e20cda09def43843192b044104c.png'],
    ['ca_id'=>'5030','name'=>'드라이브샤프트',     'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/5c62bf8211c75f49256cdce4a4a2dcee.png'],
    ['ca_id'=>'5031','name'=>'쇼바',               'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/36c066aa4771d6995505af6f6cb4a07a.png'],
    ['ca_id'=>'5032','name'=>'유니버셜조인트',     'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/7f8ec4a75e1892165b357ec00853f44e.png'],
    ['ca_id'=>'5033','name'=>'허브베어링',         'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/9a2fa9b4176fddaae08c4b1819150262.png'],
    ['ca_id'=>'5034','name'=>'휠볼트',             'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/%ED%9C%A0%EB%B3%B4%ED%8A%B8.png'],
    ['ca_id'=>'5035','name'=>'프로펠러샤프트',     'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/25dc65b7d9ec5ffb3e681ca699c96079.png'],
    ['ca_id'=>'5036','name'=>'하체부품',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/017304d00624d995d62034d436845ac2.png'],
    ['ca_id'=>'5037','name'=>'산소센서',           'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/9f736b0c6eb0f4b001a03b866227671a.png'],
    ['ca_id'=>'5038','name'=>'점화플러그(예열) 배선 관련','img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/09/a47437577d11c9b6510446320a155451.png'],
    ['ca_id'=>'5039','name'=>'라이트모듈 관련',    'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/0c3e28e579009aa84b201c38653672b4.png'],
    ['ca_id'=>'5040','name'=>'자동차용품 관련',    'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/kar.png'],
    ['ca_id'=>'5041','name'=>'기타 관련',          'img'=>'//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/logg2.png'],
];

/* ── 현재 선택된 차종 파라미터 ─────────────────────────── */
$_hw_sel_brand  = isset($_GET['pds_brand_id'])  ? (int)$_GET['pds_brand_id']  : 0;
$_hw_sel_series = isset($_GET['pds_series_id']) ? (int)$_GET['pds_series_id'] : 0;
$_hw_sel_model  = isset($_GET['pds_model_id'])  ? (int)$_GET['pds_model_id']  : 0;

// OEM 검색 처리 (GET)
$_hw_oem_q = isset($_GET['it_id_code']) ? trim($_GET['it_id_code']) : '';

// 추가 CSS
$_hw_css_extra = _pds_hw_cfg('home_widget_css_extra', '');
?>

<!-- ════════════════════════════════════════════════════════
     PartsDS 메인 홈 위젯
════════════════════════════════════════════════════════ -->
<section class="pds-home-widget">

<?php if ($_hw_css_extra): ?>
<style><?= strip_tags($_hw_css_extra) ?></style>
<?php endif; ?>

<style>
/* ── PDS 홈 위젯 기본 스타일 ── */
.pds-home-widget { width: 100%; background: #fff; padding: 0; margin: 0; }

/* ── 차종 선택 섹션 ── */
.pds-hw-car-section {
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
    padding: 24px 20px 20px;
}
.pds-hw-car-inner { max-width: 1100px; margin: 0 auto; }
.pds-hw-car-guide {
    font-size: 13px; color: #6b7280; text-align: center; margin-bottom: 14px;
}
/* 차종 선택 드롭다운 행 */
.pds-hw-selector-row {
    display: flex; align-items: center; gap: 8px; flex-wrap: wrap; justify-content: center;
}
.pds-hw-sel {
    flex: 1; min-width: 140px; max-width: 240px;
    padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px;
    font-size: 14px; color: #374151; background: #fff; cursor: pointer;
    transition: border-color .15s;
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 12px center;
    padding-right: 36px;
}
.pds-hw-sel:focus { outline: none; border-color: #2563eb; }
.pds-hw-sel:disabled { background: #f3f4f6; color: #9ca3af; cursor: not-allowed; }
.pds-hw-search-btn {
    padding: 10px 22px; background: #111; color: #fff; border: none;
    border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer;
    transition: background .15s; white-space: nowrap;
}
.pds-hw-search-btn:hover { background: #374151; }

/* OEM 검색 행 */
.pds-hw-oem-row {
    display: flex; align-items: center; gap: 8px; margin-top: 12px;
    max-width: 640px; margin-left: auto; margin-right: auto;
}
.pds-hw-oem-input {
    flex: 1; padding: 9px 16px; border: 1px solid #d1d5db; border-radius: 8px;
    font-size: 14px; color: #374151; background: #fff;
}
.pds-hw-oem-input:focus { outline: none; border-color: #2563eb; }
.pds-hw-oem-btn {
    padding: 9px 18px; background: #fff; border: 1px solid #d1d5db;
    border-radius: 8px; cursor: pointer; color: #374151; font-size: 14px;
    transition: all .15s;
}
.pds-hw-oem-btn:hover { border-color: #111; color: #111; }

/* 내 차종 태그 */
.pds-hw-mycar-tag {
    display: inline-flex; align-items: center; gap: 6px;
    background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;
    padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
    margin-top: 10px; cursor: pointer;
}
.pds-hw-mycar-tag:hover { background: #dbeafe; }
.pds-hw-mycar-tag .reset { color: #9ca3af; margin-left: 4px; font-weight: 400; }

/* ── 브랜드 로고 바 ── */
.pds-hw-brand-bar {
    background: #fff; padding: 16px 20px;
    border-bottom: 1px solid #e5e7eb;
}
.pds-hw-brand-bar-inner { max-width: 1100px; margin: 0 auto; }
.pds-hw-brand-bar-title {
    font-size: 11px; font-weight: 700; color: #9ca3af; letter-spacing: 1px;
    text-transform: uppercase; text-align: center; margin-bottom: 12px;
}
.pds-hw-brand-logos {
    display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;
}
.pds-hw-brand-logo-item {
    display: flex; flex-direction: column; align-items: center; gap: 4px;
    padding: 8px 14px; border: 1px solid #e5e7eb; border-radius: 8px;
    cursor: pointer; transition: all .15s; text-decoration: none; color: #374151;
    min-width: 70px;
}
.pds-hw-brand-logo-item:hover,
.pds-hw-brand-logo-item.active { border-color: #2563eb; background: #eff6ff; }
.pds-hw-brand-logo-item img {
    height: 28px; width: auto; max-width: 60px; object-fit: contain;
    filter: grayscale(30%); transition: filter .15s;
}
.pds-hw-brand-logo-item:hover img,
.pds-hw-brand-logo-item.active img { filter: grayscale(0%); }
.pds-hw-brand-logo-item span { font-size: 10px; font-weight: 600; }

/* ── 파츠 그리드 섹션 ── */
.pds-hw-grid-section { padding: 24px 20px; background: #fff; }
.pds-hw-grid-inner { max-width: 1100px; margin: 0 auto; }
.pds-hw-grid-title {
    font-size: 16px; font-weight: 700; color: #111; margin-bottom: 16px;
    padding-left: 4px; letter-spacing: .5px;
}
.pds-hw-parts-grid {
    display: grid;
    gap: 10px;
}
.pds-hw-parts-grid[data-cols="4"] { grid-template-columns: repeat(4, 1fr); }
.pds-hw-parts-grid[data-cols="5"] { grid-template-columns: repeat(5, 1fr); }
.pds-hw-parts-grid[data-cols="6"] { grid-template-columns: repeat(6, 1fr); }

.pds-hw-part-item {
    display: flex; flex-direction: column; align-items: center;
    padding: 12px 8px 10px;
    border: 1px solid #e5e7eb; border-radius: 8px;
    text-decoration: none; color: #374151;
    transition: all .15s; cursor: pointer; background: #fff;
}
.pds-hw-part-item:hover { border-color: #2563eb; box-shadow: 0 2px 8px rgba(37,99,235,.1); }
.pds-hw-part-item.is-active { border-color: #2563eb; background: #eff6ff; }
.pds-hw-part-item.no-items { opacity: .45; }
.pds-hw-part-item.no-items:hover { border-color: #e5e7eb; box-shadow: none; }

.pds-hw-part-img-wrap { position: relative; width: 72px; height: 72px; margin-bottom: 6px; }
.pds-hw-part-img-wrap img {
    width: 100%; height: 100%; object-fit: contain;
    transition: transform .2s;
}
.pds-hw-part-item:hover .pds-hw-part-img-wrap img { transform: scale(1.05); }
.pds-hw-part-name {
    font-size: 11px; font-weight: 600; color: #374151;
    text-align: center; line-height: 1.4; word-break: keep-all;
}
.pds-hw-part-item.is-active .pds-hw-part-name { color: #1e40af; }

/* 그리드 하단 안내 */
.pds-hw-grid-notice {
    font-size: 11px; color: #9ca3af; text-align: center; margin-top: 16px;
}

/* 반응형 */
@media (max-width: 768px) {
    .pds-hw-parts-grid[data-cols="6"],
    .pds-hw-parts-grid[data-cols="5"] { grid-template-columns: repeat(4, 1fr); }
    .pds-hw-parts-grid[data-cols="4"] { grid-template-columns: repeat(3, 1fr); }
    .pds-hw-sel { min-width: 100px; max-width: 180px; }
}
@media (max-width: 480px) {
    .pds-hw-parts-grid { grid-template-columns: repeat(3, 1fr); }
    .pds-hw-selector-row { flex-direction: column; }
    .pds-hw-sel { max-width: 100%; width: 100%; }
    .pds-hw-search-btn { width: 100%; text-align: center; }
}
</style>

<!-- ① 차종 선택 섹션 -->
<?php if ($_hw_use_car && $_hw_has_car_table): ?>
<div class="pds-hw-car-section">
    <div class="pds-hw-car-inner">

        <?php $guide = _pds_hw_cfg('home_car_selector_title',''); if ($guide): ?>
        <p class="pds-hw-car-guide"><?= htmlspecialchars($guide) ?></p>
        <?php endif; ?>

        <!-- 드롭다운 선택 행 -->
        <div class="pds-hw-selector-row">
            <!-- 브랜드 -->
            <select id="pdsHwBrand" class="pds-hw-sel" onchange="pdsHwLoadSeries(this.value)">
                <option value="">① 브랜드 선택</option>
                <?php foreach ($_hw_brands as $b): ?>
                <option value="<?= (int)$b['brand_id'] ?>"
                    data-ca-id="<?= htmlspecialchars($b['ca_id']) ?>"
                    <?= ($_hw_sel_brand && $_hw_sel_brand == $b['brand_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['brand_name']) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <!-- 시리즈 -->
            <select id="pdsHwSeries" class="pds-hw-sel" disabled onchange="pdsHwLoadModels(this.value)">
                <option value="">② 시리즈/연식</option>
            </select>

            <!-- 모델 -->
            <select id="pdsHwModel" class="pds-hw-sel" disabled>
                <option value="">③ 모델(선택)</option>
            </select>

            <button type="button" class="pds-hw-search-btn" onclick="pdsHwDoSearch()">
                차량 검색
            </button>
        </div>

        <!-- 내 차종 자동선택 태그 -->
        <?php if ($is_member && $_hw_member_car['brand_id']): ?>
        <div style="text-align:center;">
            <span class="pds-hw-mycar-tag" onclick="pdsHwApplyMyCar()">
                🚗 내 차종으로 검색
                <span class="reset" onclick="event.stopPropagation();pdsHwResetCar()">✕ 초기화</span>
            </span>
        </div>
        <?php endif; ?>

        <!-- OEM 번호 검색 -->
        <?php if ($_hw_use_oem): ?>
        <div class="pds-hw-oem-row">
            <input type="text" id="pdsHwOemInput" class="pds-hw-oem-input"
                placeholder="<?= htmlspecialchars(_pds_hw_cfg('home_oem_placeholder','예) 부품번호 (OEM No.)')) ?>"
                value="<?= htmlspecialchars($_hw_oem_q) ?>"
                onkeydown="if(event.key==='Enter') pdsHwOemSearch()">
            <button type="button" class="pds-hw-oem-btn" onclick="pdsHwOemSearch()">
                🔍 검색
            </button>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php endif; ?>

<!-- ② 브랜드 로고 바 -->
<?php if ($_hw_use_brand && $_hw_has_car_table && !empty($_hw_brands)): ?>
<div class="pds-hw-brand-bar">
    <div class="pds-hw-brand-bar-inner">
        <?php $bar_title = _pds_hw_cfg('home_brand_bar_title',''); if ($bar_title): ?>
        <div class="pds-hw-brand-bar-title"><?= htmlspecialchars($bar_title) ?></div>
        <?php endif; ?>
        <div class="pds-hw-brand-logos">
            <?php foreach ($_hw_brands as $b):
                $logo = $b['brand_logo'] ? (G5_URL . '/' . ltrim($b['brand_logo'], '/')) : '';
                $ca   = $b['ca_id'];
                $url  = $ca ? (G5_SHOP_URL . '/list.php?ca_id=' . urlencode($ca)) : '#';
                $is_active = ($_hw_sel_brand == $b['brand_id']);
            ?>
            <a href="<?= htmlspecialchars($url) ?>" class="pds-hw-brand-logo-item <?= $is_active ? 'active' : '' ?>"
               onclick="pdsHwSelectBrand(<?= (int)$b['brand_id'] ?>,event)">
                <?php if ($logo): ?>
                <img src="<?= htmlspecialchars($logo) ?>" alt="<?= htmlspecialchars($b['brand_name']) ?>"
                     onerror="this.style.display='none'">
                <?php endif; ?>
                <span><?= htmlspecialchars($b['brand_name']) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ③ 파츠 카테고리 그리드 -->
<?php if ($_hw_use_grid): ?>
<div class="pds-hw-grid-section">
    <div class="pds-hw-grid-inner">
        <?php $grid_title = _pds_hw_cfg('home_parts_grid_title','PARTS'); if ($grid_title): ?>
        <div class="pds-hw-grid-title"><?= htmlspecialchars($grid_title) ?></div>
        <?php endif; ?>

        <div class="pds-hw-parts-grid" id="pdsHwPartsGrid" data-cols="<?= $_hw_grid_cols ?>">
            <?php foreach ($_hw_parts_cats as $_hwcat):
                $_caid   = $_hwcat['ca_id'];
                $_caname = $_hwcat['name'];
                $_caimg  = $hwcat['img'] ?? $_hwcat['img'];

                // 현재 ca_id 선택 여부 (URL ca_id 파라미터)
                $_is_active = (isset($_GET['ca_id']) && $_GET['ca_id'] === $_caid);

                // 차종 선택 시 해당 부품 존재 여부 체크 (item_car 테이블)
                $_has_items = true;
                if ($_hw_sel_brand) {
                    $ic_q = "SELECT COUNT(*) AS cnt FROM `" . G5_TABLE_PREFIX . "item_car` ic
                              JOIN `" . G5_TABLE_PREFIX . "shop_item` si ON si.it_id = ic.it_id
                              WHERE ic.brand_id = {$_hw_sel_brand}";
                    if ($_hw_sel_series) $ic_q .= " AND ic.series_id = {$_hw_sel_series}";
                    if ($_hw_sel_model)  $ic_q .= " AND ic.model_id = {$_hw_sel_model}";
                    $ic_q .= " AND (si.ca_id2 = '" . sql_escape_string($_caid) . "'"
                            . " OR si.ca_id = '" . sql_escape_string($_caid) . "')";
                    $ic_r = @sql_fetch($ic_q);
                    $_has_items = ($ic_r && $ic_r['cnt'] > 0);
                }

                // URL 생성
                if ($_hw_url_mode === 'ca_id') {
                    $_href = G5_SHOP_URL . '/list.php?ca_id=' . urlencode($_caid);
                    if ($_hw_sel_brand)  $_href .= '&pds_brand_id='  . $_hw_sel_brand;
                    if ($_hw_sel_series) $_href .= '&pds_series_id=' . $_hw_sel_series;
                    if ($_hw_sel_model)  $_href .= '&pds_model_id='  . $_hw_sel_model;
                } else {
                    $_href = G5_SHOP_URL . '/list.php?pds_parts_ca=' . urlencode($_caid);
                    if ($_hw_sel_brand)  $_href .= '&pds_brand_id='  . $_hw_sel_brand;
                    if ($_hw_sel_series) $_href .= '&pds_series_id=' . $_hw_sel_series;
                    if ($_hw_sel_model)  $_href .= '&pds_model_id='  . $_hw_sel_model;
                }

                $_cls = 'pds-hw-part-item';
                if ($_is_active)  $_cls .= ' is-active';
                if (!$_has_items && $_hw_sel_brand) $_cls .= ' no-items';
            ?>
            <a href="<?= htmlspecialchars($_href) ?>"
               class="<?= $_cls ?>"
               data-ca-id="<?= htmlspecialchars($_caid) ?>"
               data-name="<?= htmlspecialchars($_caname) ?>"
               data-has-items="<?= $_has_items ? '1' : '0' ?>">
                <div class="pds-hw-part-img-wrap">
                    <img src="<?= htmlspecialchars($_hwcat['img']) ?>"
                         alt="<?= htmlspecialchars($_caname) ?>"
                         loading="lazy"
                         onerror="this.style.opacity=0.2">
                </div>
                <span class="pds-hw-part-name"><?= htmlspecialchars($_caname) ?></span>
            </a>
            <?php endforeach; ?>
        </div>

        <?php $notice = _pds_hw_cfg('home_parts_notice',''); if ($notice): ?>
        <p class="pds-hw-grid-notice"><?= htmlspecialchars($notice) ?></p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

</section>
<!-- /PartsDS 홈 위젯 -->

<!-- ── JavaScript ── -->
<script>
(function() {
'use strict';

/* PHP → JS 데이터 주입 */
var PDS_SERIES = <?php echo json_encode($_hw_all_series ?? [], JSON_UNESCAPED_UNICODE); ?>;
var PDS_MODELS = <?php echo json_encode($_hw_all_models ?? [], JSON_UNESCAPED_UNICODE); ?>;
var PDS_MEMBER_CAR = <?php echo json_encode($_hw_member_car ?? [], JSON_UNESCAPED_UNICODE); ?>;
var PDS_URL_MODE = '<?php echo $_hw_url_mode; ?>';
var PDS_SHOP_URL = '<?php echo G5_SHOP_URL; ?>';
var SERIES_CA_MAP = {};
var MODEL_CA_MAP  = {};

/* ── 시리즈 로드 ── */
window.pdsHwLoadSeries = function(brandId) {
    var sel = document.getElementById('pdsHwSeries');
    var selM = document.getElementById('pdsHwModel');
    if (!sel) return;
    sel.innerHTML = '<option value="">② 시리즈/연식</option>';
    selM.innerHTML = '<option value="">③ 모델(선택)</option>';
    selM.disabled = true;
    SERIES_CA_MAP = {};

    if (!brandId) { sel.disabled = true; return; }
    var list = PDS_SERIES[brandId] || [];
    list.forEach(function(s) {
        var opt = document.createElement('option');
        opt.value = s.id; opt.textContent = s.name;
        if (s.ca_id) { opt.setAttribute('data-ca-id', s.ca_id); SERIES_CA_MAP[s.id] = s.ca_id; }
        sel.appendChild(opt);
    });
    sel.disabled = (list.length === 0);

    // 브랜드 로고 active 처리
    document.querySelectorAll('.pds-hw-brand-logo-item').forEach(function(el) {
        el.classList.toggle('active', el.getAttribute('data-brand-id') == brandId);
    });
};

/* ── 모델 로드 ── */
window.pdsHwLoadModels = function(seriesId) {
    var sel = document.getElementById('pdsHwModel');
    if (!sel) return;
    sel.innerHTML = '<option value="">③ 모델(선택)</option>';
    MODEL_CA_MAP = {};
    if (!seriesId) { sel.disabled = true; return; }
    var list = PDS_MODELS[seriesId] || [];
    list.forEach(function(m) {
        var opt = document.createElement('option');
        opt.value = m.id; opt.textContent = m.name;
        if (m.ca_id) { opt.setAttribute('data-ca-id', m.ca_id); MODEL_CA_MAP[m.id] = m.ca_id; }
        sel.appendChild(opt);
    });
    sel.disabled = (list.length === 0);
};

/* ── 검색 실행 ── */
window.pdsHwDoSearch = function() {
    var brandId  = document.getElementById('pdsHwBrand')  ? document.getElementById('pdsHwBrand').value  : '';
    var seriesId = document.getElementById('pdsHwSeries') ? document.getElementById('pdsHwSeries').value : '';
    var modelId  = document.getElementById('pdsHwModel')  ? document.getElementById('pdsHwModel').value  : '';

    if (!brandId) { alert('브랜드를 선택해주세요.'); return; }

    var targetCaId = '';
    if (modelId  && MODEL_CA_MAP[modelId])   targetCaId = MODEL_CA_MAP[modelId];
    else if (seriesId && SERIES_CA_MAP[seriesId]) targetCaId = SERIES_CA_MAP[seriesId];
    else {
        var bOpt = document.querySelector('#pdsHwBrand option[value="' + brandId + '"]');
        targetCaId = bOpt ? (bOpt.getAttribute('data-ca-id') || '') : '';
    }

    var url;
    if (PDS_URL_MODE === 'ca_id' && targetCaId) {
        url = PDS_SHOP_URL + '/list.php?ca_id=' + encodeURIComponent(targetCaId);
    } else {
        url = PDS_SHOP_URL + '/list.php?pds_brand_id=' + encodeURIComponent(brandId);
        if (seriesId) url += '&pds_series_id=' + encodeURIComponent(seriesId);
        if (modelId)  url += '&pds_model_id='  + encodeURIComponent(modelId);
    }
    window.location.href = url;
};

/* ── OEM 검색 ── */
window.pdsHwOemSearch = function() {
    var q = document.getElementById('pdsHwOemInput');
    if (!q || !q.value.trim()) { alert('부품번호를 입력해주세요.'); return; }
    window.location.href = PDS_SHOP_URL + '/search.php?it_id_code=' + encodeURIComponent(q.value.trim());
};

/* ── 브랜드 로고 클릭 ── */
window.pdsHwSelectBrand = function(brandId, e) {
    var sel = document.getElementById('pdsHwBrand');
    if (sel) {
        sel.value = brandId;
        pdsHwLoadSeries(brandId);
        e.preventDefault();
        // 스크롤 업
        var sec = document.querySelector('.pds-hw-car-section');
        if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
};

/* ── 내 차종 자동선택 ── */
window.pdsHwApplyMyCar = function() {
    if (!PDS_MEMBER_CAR.brand_id) return;
    var sel = document.getElementById('pdsHwBrand');
    if (!sel) return;
    sel.value = PDS_MEMBER_CAR.brand_id;
    pdsHwLoadSeries(PDS_MEMBER_CAR.brand_id);
    setTimeout(function() {
        var ss = document.getElementById('pdsHwSeries');
        if (ss && PDS_MEMBER_CAR.series_id) {
            ss.value = PDS_MEMBER_CAR.series_id;
            pdsHwLoadModels(PDS_MEMBER_CAR.series_id);
            setTimeout(function() {
                var sm = document.getElementById('pdsHwModel');
                if (sm && PDS_MEMBER_CAR.model_id) sm.value = PDS_MEMBER_CAR.model_id;
            }, 50);
        }
    }, 50);
};

/* ── 차종 초기화 ── */
window.pdsHwResetCar = function() {
    var sel = document.getElementById('pdsHwBrand');
    if (sel) sel.value = '';
    pdsHwLoadSeries('');
};

/* ── 파츠 그리드 클릭 처리 (차종 선택 시 없는 항목 안내) ── */
document.querySelectorAll('#pdsHwPartsGrid .pds-hw-part-item').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if (this.classList.contains('no-items')) {
            e.preventDefault();
            var name = this.getAttribute('data-name') || '선택한 카테고리';
            if (confirm('[' + name + '] 부품이 선택한 차종에 등록되어 있지 않습니다.\n\n차종 필터를 해제하고 전체 상품에서 검색하시겠습니까?')) {
                var href = this.getAttribute('href') || '';
                href = href.replace(/[?&]pds_brand_id=[^&]*/g, '')
                           .replace(/[?&]pds_series_id=[^&]*/g, '')
                           .replace(/[?&]pds_model_id=[^&]*/g, '')
                           .replace(/^([^?]*)&/, '$1?');
                window.location.href = href;
            }
        }
    });
});

/* ── 페이지 로드 시 URL 파라미터로 드롭다운 자동선택 ── */
(function() {
    var brandId  = <?php echo $_hw_sel_brand  ?: 0; ?>;
    var seriesId = <?php echo $_hw_sel_series ?: 0; ?>;
    var modelId  = <?php echo $_hw_sel_model  ?: 0; ?>;
    if (!brandId) return;
    var sel = document.getElementById('pdsHwBrand');
    if (!sel) return;
    sel.value = brandId;
    pdsHwLoadSeries(brandId);
    setTimeout(function() {
        var ss = document.getElementById('pdsHwSeries');
        if (ss && seriesId) {
            ss.value = seriesId;
            pdsHwLoadModels(seriesId);
            setTimeout(function() {
                var sm = document.getElementById('pdsHwModel');
                if (sm && modelId) sm.value = modelId;
            }, 30);
        }
    }, 30);
})();

})();
</script>
