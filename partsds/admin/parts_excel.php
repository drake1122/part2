<?php
/**
 * 파츠디에스 - 엑셀 일괄등록 (Cafe24/BMW 양식 호환)
 * 경로: /partsds/admin/parts_excel.php
 *
 * 기능:
 *  1. BMW-cafe24.xlsx 형식(자체상품코드, 카테고리번호, 상품명, 판매가, 이미지경로 등) 파싱
 *  2. Cafe24 카테고리번호 → Gnuboard ca_id 매핑 테이블
 *  3. 미리보기 후 선택적 등록
 *  4. 이미지는 CDN URL(//ecimg.cafe24img.com/...) 그대로 사용
 *  5. 파츠 마스터(pds_parts_master) 자동 연동
 *
 * 의존성:
 *  - PhpSpreadsheet (없으면 CSV fallback)
 *  - install_parts_master.sql 실행 완료 필요
 */
include_once('../../_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    exit;
}

define('PDS_EXCEL_SHOP_ITEM', G5_TABLE_PREFIX . 'shop_item');
define('PDS_EXCEL_ITEM_CAR',  G5_TABLE_PREFIX . 'item_car');
define('PDS_EXCEL_MASTER',    G5_TABLE_PREFIX . 'pds_parts_master');

/* ─────────────────────────────────────────────────────────
   Cafe24 카테고리번호 → Gnuboard ca_id 매핑 테이블
   (관리자가 이 페이지에서 직접 편집 가능)
   기본값: BMW-cafe24.xlsx 기준 추정 매핑
───────────────────────────────────────────────────────── */
$DEFAULT_CAT_MAP = [
    // Cafe24분류번호 => ['ca_id' => Gnuboard ca_id, 'label' => 설명]
    '35670' => ['ca_id' => '2010', 'label' => 'BMW 3시리즈 F30'],
    '35671' => ['ca_id' => '2010', 'label' => 'BMW 3시리즈 F30'],
    '35672' => ['ca_id' => '2010', 'label' => 'BMW 3시리즈 F30'],
    '35673' => ['ca_id' => '2011', 'label' => 'BMW 3시리즈 E90'],
    '35677' => ['ca_id' => '2017', 'label' => 'BMW 5시리즈 F10'],
    '35678' => ['ca_id' => '2017', 'label' => 'BMW 5시리즈 F10'],
    '35680' => ['ca_id' => '2017', 'label' => 'BMW 5시리즈 F10'],
    '37032' => ['ca_id' => '2016', 'label' => 'BMW 5시리즈 G30'],
    '37033' => ['ca_id' => '2017', 'label' => 'BMW 5시리즈 F10'],
];

/* ─────────────────────────────────────────────────────────
   세션 기반 매핑 설정 (관리자 수정 가능)
───────────────────────────────────────────────────────── */
if (!isset($_SESSION['pds_cat_map'])) {
    $_SESSION['pds_cat_map'] = $DEFAULT_CAT_MAP;
}

/* ─────────────────────────────────────────────────────────
   AJAX 핸들러
───────────────────────────────────────────────────────── */
$ajax = isset($_GET['ajax']) ? $_GET['ajax'] : '';

// 매핑 설정 저장
if ($ajax === 'save_map' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $new_map = [];
    $cafe24_keys = isset($_POST['cafe24_key']) ? (array)$_POST['cafe24_key'] : [];
    $ca_ids      = isset($_POST['ca_id'])      ? (array)$_POST['ca_id']      : [];
    $labels      = isset($_POST['label'])      ? (array)$_POST['label']      : [];
    foreach ($cafe24_keys as $i => $k) {
        $k = preg_replace('/[^0-9]/', '', $k);
        if (!$k) continue;
        $new_map[$k] = [
            'ca_id' => preg_replace('/[^0-9a-zA-Z]/', '', $ca_ids[$i] ?? ''),
            'label' => strip_tags($labels[$i] ?? ''),
        ];
    }
    $_SESSION['pds_cat_map'] = $new_map;
    echo json_encode(['ok' => true, 'count' => count($new_map)]);
    exit;
}

// 매핑 초기화
if ($ajax === 'reset_map') {
    header('Content-Type: application/json');
    $_SESSION['pds_cat_map'] = $DEFAULT_CAT_MAP;
    echo json_encode(['ok' => true]);
    exit;
}

// 상품 등록 실행
if ($ajax === 'do_register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $rows        = isset($_POST['rows'])         ? (array)$_POST['rows']        : [];
    $parts_ca    = isset($_POST['parts_ca'])      ? preg_replace('/[^0-9]/', '',     $_POST['parts_ca'])    : '';
    $parts_ca2   = isset($_POST['parts_ca2'])     ? preg_replace('/[^0-9a-zA-Z]/', '', $_POST['parts_ca2'])   : ''; // 2차분류 (파츠종류)
    $parts_ca3   = isset($_POST['parts_ca3'])     ? preg_replace('/[^0-9a-zA-Z]/', '', $_POST['parts_ca3'])   : ''; // 3차분류 (부품브랜드)
    $brand_id    = isset($_POST['brand_id'])      ? (int)$_POST['brand_id']    : 0;
    $series_id   = isset($_POST['series_id'])     ? (int)$_POST['series_id']   : 0;
    $model_id    = isset($_POST['model_id'])      ? (int)$_POST['model_id']    : 0;
    $use_master  = isset($_POST['use_master'])    ? (int)$_POST['use_master']  : 1;
    $overwrite   = isset($_POST['overwrite'])     ? (int)$_POST['overwrite']   : 0; // 기존 상품 덮어쓰기

    $cat_map = $_SESSION['pds_cat_map'] ?? $DEFAULT_CAT_MAP;

    $success = 0; $skip = 0; $fail = 0; $results = [];

    foreach ($rows as $row_json) {
        $r = json_decode($row_json, true);
        if (!$r) continue;

        $oem       = trim($r['oem'] ?? '');
        $cafe24_ca = trim($r['cafe24_ca'] ?? '');
        $name      = trim($r['name'] ?? '');
        $price     = (int)($r['price'] ?? 0);
        $img_url   = trim($r['img'] ?? '');
        $detail    = trim($r['detail'] ?? '');

        if (!$oem || !$name) {
            $results[] = ['row' => $r, 'ok' => false, 'msg' => 'OEM번호 또는 상품명 없음'];
            $fail++;
            continue;
        }

        // ca_id 결정 (다중분류 지원)
        // ca_id  = 차종분류 (주분류: Cafe24 매핑 or 직접지정)
        // ca_id2 = 파츠종류 (5001~5041, parts_ca2로 지정 or 상품명 자동감지)
        // ca_id3 = 부품브랜드 (B01~B99, parts_ca3로 지정)
        $target_ca = $parts_ca;
        if (!$target_ca) {
            $mapped = $cat_map[$cafe24_ca] ?? null;
            $target_ca = $mapped ? $mapped['ca_id'] : '';
        }
        if (!$target_ca) {
            $results[] = ['row' => $r, 'ok' => false, 'msg' => "Cafe24 분류 {$cafe24_ca} 에 매핑된 ca_id 없음"];
            $fail++;
            continue;
        }

        // 2차분류(파츠종류) 자동감지: parts_ca2 미지정 시 상품명으로 추정
        $target_ca2 = $parts_ca2;
        if (!$target_ca2 && $name) {
            $parts_keyword_map = [
                '오일필터'=>'5001','에어필터'=>'5002','에어컨필터'=>'5003','연료필터'=>'5004',
                '미션오일필터'=>'5005','오일필터하우징'=>'5006','미션오일'=>'5007','엔진오일'=>'5008',
                '부동액'=>'5009','브레이크오일'=>'5010','브레이크디스크'=>'5011','브레이크패드'=>'5012',
                '브레이크센서'=>'5013','브레이크캘리퍼'=>'5014','엔진마운트'=>'5015','미션마운트'=>'5016',
                'V벨트'=>'5017','댐퍼풀리'=>'5018','벨트텐셔너'=>'5019','워터펌프'=>'5020','써머스탯'=>'5021',
                '라디에이터'=>'5022','알터네이터'=>'5023','에어컨콤프레셔'=>'5024','스타트모터'=>'5025',
                '흡기매니폴드'=>'5026','고압펌프'=>'5027','인젝터'=>'5028','와이퍼'=>'5029',
                '드라이브샤프트'=>'5030','쇼바'=>'5031','유니버셜조인트'=>'5032','허브베어링'=>'5033',
                '휠볼트'=>'5034','프로펠러샤프트'=>'5035','하체부품'=>'5036','산소센서'=>'5037',
                '점화플러그'=>'5038','예열플러그'=>'5038','라이트'=>'5039','자동차용품'=>'5040',
            ];
            foreach ($parts_keyword_map as $kw => $pca) {
                if (mb_strpos($name, $kw) !== false) {
                    $target_ca2 = $pca;
                    break;
                }
            }
            if (!$target_ca2) $target_ca2 = '5041'; // 기타
        }
        $target_ca3 = $parts_ca3; // 부품브랜드 (직접지정만, 자동감지 불가)

        // it_id 생성: oem번호 + _ca + 타임스탬프(4자리)
        $it_id_base = preg_replace('/[^0-9a-zA-Z]/', '', $oem);
        $it_id_base = substr($it_id_base, 0, 16);
        $it_id = $it_id_base . '_' . $target_ca;

        // 중복 체크
        $exist = sql_fetch("SELECT it_id FROM `" . PDS_EXCEL_SHOP_ITEM . "` WHERE it_id = '" . sql_escape_string($it_id) . "'");

        if ($exist['it_id'] && !$overwrite) {
            $results[] = ['row' => $r, 'ok' => false, 'msg' => "이미 등록됨 ({$it_id})", 'skip' => true];
            $skip++;
            continue;
        }

        // 이미지 URL 정규화 (Cafe24 형식: /pg/pg239...A.png → CDN URL)
        $img_url_full = '';
        if ($img_url) {
            if (strpos($img_url, 'http') === 0 || strpos($img_url, '//') === 0) {
                $img_url_full = $img_url;
            } elseif (strpos($img_url, '/') === 0) {
                $img_url_full = 'https://ecimg.cafe24img.com' . $img_url;
            } else {
                $img_url_full = 'https://ecimg.cafe24img.com/' . $img_url;
            }
        }

        $esc = function($v) { return sql_escape_string($v); };
        $price_esc    = (int)$price;
        $it_id_esc    = $esc($it_id);
        $ca_esc       = $esc($target_ca);
        $name_esc     = $esc($name);
        $oem_esc      = $esc($oem);
        $img_esc      = $esc($img_url_full);
        $detail_esc   = $esc($detail);

        if ($exist['it_id'] && $overwrite) {
            // UPDATE
            $ca2_esc = sql_escape_string($target_ca2);
            $ca3_esc = sql_escape_string($target_ca3);
            $sql = "UPDATE `" . PDS_EXCEL_SHOP_ITEM . "` SET
                ca_id  = '{$ca_esc}',
                ca_id2 = '{$ca2_esc}',
                ca_id3 = '{$ca3_esc}',
                it_name    = '{$name_esc}',
                it_price   = {$price_esc},
                it_id_code = '{$oem_esc}',
                it_img1 = '{$img_esc}',
                it_img2 = '{$img_esc}',
                it_img3 = '{$img_esc}',
                it_img4 = '{$img_esc}',
                it_content = '{$detail_esc}',
                it_update  = NOW()
                WHERE it_id = '{$it_id_esc}'";
        } else {
            // INSERT (다중분류 ca_id2/ca_id3 포함)
            $ca2_esc = sql_escape_string($target_ca2);
            $ca3_esc = sql_escape_string($target_ca3);
            $sql = "INSERT INTO `" . PDS_EXCEL_SHOP_ITEM . "`
                (it_id, ca_id, ca_id2, ca_id3,
                 it_name, it_info, it_content,
                 it_price, it_cust_price, it_supply_price,
                 it_id_code, it_img1, it_img2, it_img3, it_img4,
                 it_sell_display, it_sell_use,
                 it_hit, it_order, it_point, it_point_type,
                 it_minimum, it_maximum,
                 it_regdate, it_update)
                VALUES
                ('{$it_id_esc}', '{$ca_esc}', '{$ca2_esc}', '{$ca3_esc}',
                 '{$name_esc}', '', '{$detail_esc}',
                 {$price_esc}, 0, 0,
                 '{$oem_esc}', '{$img_esc}', '{$img_esc}', '{$img_esc}', '{$img_esc}',
                 1, 1,
                 0, 0, 0, '%',
                 1, 0,
                 NOW(), NOW())";
        }

        if (!sql_query($sql)) {
            $results[] = ['row' => $r, 'ok' => false, 'msg' => sql_error()];
            $fail++;
            continue;
        }

        // item_car 매핑
        if ($brand_id) {
            $ex2 = sql_fetch("SELECT id FROM `" . PDS_EXCEL_ITEM_CAR . "`
                WHERE it_id='" . $esc($it_id) . "' AND brand_id={$brand_id}
                AND series_id={$series_id} AND model_id={$model_id}");
            if (!$ex2['id']) {
                sql_query("INSERT INTO `" . PDS_EXCEL_ITEM_CAR . "` (it_id, brand_id, series_id, model_id)
                    VALUES ('" . $esc($it_id) . "', {$brand_id}, {$series_id}, {$model_id})");
            }
        }

        // 파츠 마스터 자동 등록
        if ($use_master) {
            $pm_parts_ca = $parts_ca ?: ''; // 파츠 종류 ca_id
            $ex3 = sql_fetch("SELECT pm_id FROM `" . PDS_EXCEL_MASTER . "`
                WHERE pm_part_code = '{$oem_esc}' LIMIT 1");
            if (!$ex3['pm_id']) {
                sql_query("INSERT INTO `" . PDS_EXCEL_MASTER . "`
                    (pm_part_code, pm_parts_ca, pm_name, pm_price, pm_img_url, pm_sync_yn, pm_reg_dt)
                    VALUES ('{$oem_esc}', '{$pm_parts_ca}', '{$name_esc}', {$price_esc}, '{$img_esc}', 'Y', NOW())");
            }
        }

        $results[] = [
            'row' => $r, 'ok' => true, 'it_id' => $it_id,
            'msg' => $exist['it_id'] ? '업데이트' : '신규등록',
        ];
        $success++;
    }

    echo json_encode([
        'ok'      => true,
        'success' => $success,
        'skip'    => $skip,
        'fail'    => $fail,
        'results' => $results,
    ]);
    exit;
}

/* ─────────────────────────────────────────────────────────
   HTML 출력
───────────────────────────────────────────────────────── */

// 현재 매핑
$cat_map = $_SESSION['pds_cat_map'] ?? $DEFAULT_CAT_MAP;

// 브랜드 목록 (car_brand)
$car_brands = [];
$res = sql_query("SELECT id, brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` ORDER BY brand_name ASC");
while ($r = sql_fetch_array($res)) $car_brands[] = $r;

// 파츠 카테고리 (5001~5041)
$parts_cats = [];
$res2 = sql_query("SELECT ca_id, ca_name FROM `" . G5_TABLE_PREFIX . "shop_category` WHERE ca_id BETWEEN '5001' AND '5041' ORDER BY ca_id ASC");
while ($r = sql_fetch_array($res2)) $parts_cats[] = $r;

// 부품브랜드 카테고리 (B01~B99) — install_brand_categories.sql 실행 후 로드
$brand_cats = [];
$res3 = sql_query("SELECT ca_id, ca_name FROM `" . G5_TABLE_PREFIX . "shop_category` WHERE ca_id LIKE 'B%' AND ca_id != 'B0' ORDER BY ca_id ASC");
while ($r = sql_fetch_array($res3)) $brand_cats[] = $r;

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>엑셀 일괄등록 - 파츠디에스 관리</title>
<style>
*, *::before, *::after { box-sizing: border-box; }
body { font-family: -apple-system, "Malgun Gothic", sans-serif; background: #f4f6f9; margin: 0; padding: 0; color: #222; font-size: 14px; }
.pds-wrap { max-width: 1400px; margin: 0 auto; padding: 20px; }
.pds-title { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
.pds-title h1 { font-size: 22px; font-weight: 700; margin: 0; }
.badge { background: #2563eb; color: #fff; font-size: 11px; padding: 3px 8px; border-radius: 20px; }

.card { background: #fff; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.08); padding: 24px; margin-bottom: 20px; }
.card-title { font-size: 15px; font-weight: 700; margin: 0 0 16px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb; color: #1e40af; display: flex; align-items: center; gap: 8px; }
.card-title .step { background: #1e40af; color: #fff; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; }

.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
@media (max-width: 900px) { .grid-2, .grid-3 { grid-template-columns: 1fr; } }

label { display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 5px; }
input[type="text"], input[type="number"], input[type="file"], select, textarea {
    width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;
    background: #fff; color: #222; transition: border-color .15s;
}
input:focus, select:focus, textarea:focus { outline: none; border-color: #2563eb; }
.form-group { margin-bottom: 14px; }
.help-text { font-size: 11px; color: #9ca3af; margin-top: 4px; }

.btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s; }
.btn-primary   { background: #2563eb; color: #fff; }
.btn-primary:hover { background: #1d4ed8; }
.btn-success   { background: #16a34a; color: #fff; }
.btn-success:hover { background: #15803d; }
.btn-warning   { background: #d97706; color: #fff; }
.btn-danger    { background: #dc2626; color: #fff; }
.btn-secondary { background: #6b7280; color: #fff; }
.btn-outline   { background: transparent; border: 1px solid #d1d5db; color: #374151; }
.btn-outline:hover { background: #f3f4f6; }
.btn-sm { padding: 5px 12px; font-size: 12px; }
.btn:disabled { opacity: .5; cursor: not-allowed; }

.tab-nav { display: flex; border-bottom: 2px solid #e5e7eb; margin-bottom: 20px; }
.tab-btn { padding: 10px 20px; cursor: pointer; font-size: 14px; font-weight: 600; color: #6b7280; border-bottom: 3px solid transparent; margin-bottom: -2px; background: none; border-top: none; border-left: none; border-right: none; }
.tab-btn.active { color: #2563eb; border-bottom-color: #2563eb; }
.tab-content { display: none; }
.tab-content.active { display: block; }

.alert { padding: 10px 16px; border-radius: 6px; margin-bottom: 14px; font-size: 13px; }
.alert-info    { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
.alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

/* 파일 드롭 영역 */
.drop-zone {
    border: 2px dashed #d1d5db; border-radius: 10px; padding: 40px; text-align: center;
    cursor: pointer; transition: all .2s; background: #fafafa;
}
.drop-zone:hover, .drop-zone.drag-over { border-color: #2563eb; background: #eff6ff; }
.drop-zone .icon { font-size: 48px; margin-bottom: 12px; }
.drop-zone .txt  { color: #6b7280; font-size: 14px; }
.drop-zone .txt strong { color: #2563eb; }

/* 미리보기 테이블 */
.preview-wrap { max-height: 500px; overflow: auto; border: 1px solid #e5e7eb; border-radius: 8px; }
.preview-table { width: 100%; border-collapse: collapse; font-size: 12px; white-space: nowrap; }
.preview-table th { background: #f8fafc; padding: 8px 10px; text-align: left; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; font-size: 11px; }
.preview-table td { padding: 6px 10px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
.preview-table tr.selected td { background: #eff6ff; }
.preview-table tr:hover td { background: #f0f4ff; }
.preview-table input[type="checkbox"] { width: 15px; height: 15px; cursor: pointer; }
.img-thumb { width: 40px; height: 40px; object-fit: contain; border: 1px solid #e5e7eb; border-radius: 3px; }
.tag-ok    { background: #dcfce7; color: #16a34a; padding: 2px 6px; border-radius: 8px; font-size: 10px; font-weight: 700; }
.tag-warn  { background: #fef9c3; color: #854d0e; padding: 2px 6px; border-radius: 8px; font-size: 10px; font-weight: 700; }
.tag-err   { background: #fee2e2; color: #dc2626; padding: 2px 6px; border-radius: 8px; font-size: 10px; font-weight: 700; }

/* 매핑 테이블 */
.map-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.map-table th { background: #f8fafc; padding: 8px 12px; border-bottom: 1px solid #e5e7eb; font-weight: 600; }
.map-table td { padding: 7px 12px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
.map-table input { padding: 5px 8px; font-size: 12px; }

/* 결과 */
.result-stats { display: flex; gap: 24px; padding: 16px; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0; margin-bottom: 12px; }
.result-stat .num { font-size: 28px; font-weight: 700; }
.result-stat .lbl { font-size: 11px; color: #6b7280; }

.spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid #e5e7eb; border-top-color: #2563eb; border-radius: 50%; animation: spin .6s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* 진행 바 */
.progress-bar { height: 6px; background: #e5e7eb; border-radius: 3px; overflow: hidden; margin: 8px 0; }
.progress-fill { height: 100%; background: linear-gradient(90deg, #2563eb, #16a34a); transition: width .3s; border-radius: 3px; }
</style>
</head>
<body>
<div class="pds-wrap">

    <!-- 타이틀 -->
    <div class="pds-title">
        <h1>엑셀 일괄등록</h1>
        <span class="badge">BMW·Cafe24 양식 호환</span>
        <a href="parts_master.php" class="btn btn-outline btn-sm" style="margin-left:auto;">마스터 관리</a>
        <a href="bulk_copy.php" class="btn btn-outline btn-sm">상품 복사</a>
    </div>

    <!-- 안내 -->
    <div class="alert alert-info">
        💡 BMW-cafe24.xlsx 형식의 엑셀 파일을 업로드하면 자동으로 파싱합니다.
        Cafe24 분류번호는 아래 매핑 설정에서 Gnuboard ca_id로 변환됩니다.
        <br>📌 브라우저에서 직접 파싱하므로 서버에 PhpSpreadsheet 없이도 동작합니다.
    </div>

    <div class="tab-nav">
        <button class="tab-btn active" data-tab="tab-upload">📁 업로드 & 미리보기</button>
        <button class="tab-btn" data-tab="tab-mapping">🗂️ 카테고리 매핑 설정</button>
        <button class="tab-btn" data-tab="tab-result">📊 등록 결과</button>
    </div>

    <!-- ══ TAB 1: 업로드 ══ -->
    <div id="tab-upload" class="tab-content active">

        <!-- STEP 1: 파일 업로드 -->
        <div class="card">
            <div class="card-title"><span class="step">1</span> 엑셀 파일 선택</div>
            <div class="drop-zone" id="drop-zone" onclick="document.getElementById('excel-file').click();">
                <div class="icon">📊</div>
                <div class="txt">
                    <strong>클릭하거나 파일을 드래그하세요</strong><br>
                    .xlsx, .xls, .csv 파일 지원<br>
                    <small style="color:#9ca3af;">BMW-cafe24.xlsx 형식 권장</small>
                </div>
            </div>
            <input type="file" id="excel-file" accept=".xlsx,.xls,.csv" style="display:none;" onchange="handleFileSelect(this.files[0])">
            <div id="file-info" style="margin-top: 12px; font-size: 13px; color: #6b7280;"></div>

            <!-- 컬럼 매핑 안내 (접힘) -->
            <details style="margin-top:12px;">
                <summary style="cursor:pointer; font-size:13px; color:#2563eb; font-weight:600;">📋 BMW-cafe24.xlsx 컬럼 구조 보기</summary>
                <div style="margin-top:8px; background:#f8fafc; border-radius:8px; padding:12px; font-size:12px;">
                    <table style="border-collapse:collapse; width:100%;">
                        <tr style="background:#e5e7eb;"><th style="padding:5px 10px;">열 번호</th><th style="padding:5px 10px;">컬럼명</th><th style="padding:5px 10px;">설명</th></tr>
                        <tr><td style="padding:4px 10px;">B (2)</td><td>자체상품코드</td><td>OEM 부품번호 (it_id_code)</td></tr>
                        <tr><td style="padding:4px 10px;">E (5)</td><td>상품분류번호</td><td>Cafe24 카테고리 (35670~37033)</td></tr>
                        <tr><td style="padding:4px 10px;">H (8)</td><td>상품명</td><td>it_name</td></tr>
                        <tr><td style="padding:4px 10px;">W (23)</td><td>판매가</td><td>it_price</td></tr>
                        <tr><td style="padding:4px 10px;">AV (48)</td><td>대표이미지(소)</td><td>it_img1 (경로)</td></tr>
                        <tr><td style="padding:4px 10px;">AW (49)</td><td>대표이미지(중)</td><td>it_img2</td></tr>
                        <tr><td style="padding:4px 10px;">AX (50)</td><td>대표이미지(대)</td><td>it_img3</td></tr>
                        <tr><td style="padding:4px 10px;">AY (51)</td><td>추가이미지1</td><td>it_img4</td></tr>
                    </table>
                    <div style="margin-top:8px; color:#6b7280;">
                        ※ 첫 행은 헤더로 스킵됩니다. 이미지 경로는 CDN URL로 자동 변환됩니다.
                    </div>
                </div>
            </details>
        </div>

        <!-- STEP 2: 등록 옵션 -->
        <div class="card" id="step2-card" style="display:none;">
            <div class="card-title"><span class="step">2</span> 등록 옵션 설정</div>
            <div class="grid-3">
                <!-- 파츠 카테고리 강제 지정 -->
                <div class="form-group">
                    <label>파츠 카테고리 강제 지정 (선택)</label>
                    <select id="reg-parts-ca">
                        <option value="">── 엑셀 Cafe24분류 매핑 사용 ──</option>
                        <?php foreach ($parts_cats as $pc): ?>
                        <option value="<?= htmlspecialchars($pc['ca_id']) ?>"><?= htmlspecialchars($pc['ca_name']) ?> (<?= $pc['ca_id'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <div class="help-text">선택 시 모든 상품이 이 파츠 카테고리로 등록</div>
                </div>
                <!-- 차종 브랜드 -->
                <div class="form-group">
                    <label>차종 브랜드 매핑 (item_car)</label>
                    <select id="reg-brand-id" onchange="loadRegSeries()">
                        <option value="0">── 차종 매핑 안함 ──</option>
                        <?php foreach ($car_brands as $cb): ?>
                        <option value="<?= $cb['id'] ?>"><?= htmlspecialchars($cb['brand_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- 시리즈 -->
                <div class="form-group">
                    <label>차종 시리즈</label>
                    <select id="reg-series-id" onchange="loadRegModels()">
                        <option value="0">── 브랜드 먼저 선택 ──</option>
                    </select>
                </div>
            </div>
            <div class="grid-3">
                <div class="form-group">
                    <label>차종 모델 (선택)</label>
                    <select id="reg-model-id">
                        <option value="0">── 전체 시리즈 ──</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="reg-use-master" checked>
                        &nbsp;파츠 마스터 자동 연동 (pds_parts_master 등록)
                    </label>
                    <div class="help-text">체크 시 OEM번호 기준 마스터가 없으면 자동 생성</div>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="reg-overwrite">
                        &nbsp;기존 상품 덮어쓰기 (it_id 중복 시)
                    </label>
                    <div class="help-text">비체크 시 중복 상품은 건너뜀</div>
                </div>
            </div>
            <div class="grid-3">
                <!-- 2차분류: 파츠종류 (5001~5041) -->
                <div class="form-group">
                    <label>파츠종류 (2차분류, ca_id2) &mdash; 선택</label>
                    <select id="reg-parts-ca2">
                        <option value="">── 상품명 키워드 자동감지 사용 ──</option>
                        <?php foreach ($parts_cats as $pc): ?>
                        <option value="<?= htmlspecialchars($pc['ca_id']) ?>"><?= htmlspecialchars($pc['ca_name']) ?> (<?= $pc['ca_id'] ?>)</option>
                        <?php endforeach; ?>
                        <option value="5041">기타 (5041)</option>
                    </select>
                    <div class="help-text">선택 시 모든 상품에 강제 적용 / 빈칸이면 상품명 키워드로 자동감지</div>
                </div>
                <!-- 3차분류: 부품브랜드 (B01~B99) -->
                <div class="form-group">
                    <label>부품브랜드 (3차분류, ca_id3) &mdash; 선택</label>
                    <select id="reg-parts-ca3">
                        <option value="">── 부품브랜드 지정 안함 ──</option>
                        <?php if (empty($brand_cats)): ?>
                        <option value="" disabled style="color:#9ca3af;">⚠️ install_brand_categories.sql 실행 후 사용가능</option>
                        <?php else: ?>
                        <?php foreach ($brand_cats as $bc): ?>
                        <option value="<?= htmlspecialchars($bc['ca_id']) ?>"><?= htmlspecialchars($bc['ca_name']) ?> (<?= $bc['ca_id'] ?>)</option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div class="help-text">선택 시 모든 상품에 부품브랜드 분류 적용 (B01=BMW OEM, B10=모빌...)</div>
                </div>
                <!-- 범주 체계 안내 -->
                <div class="form-group">
                    <label style="color:#6b7280;">다중분류 범주 안내</label>
                    <div class="alert alert-info" style="margin:0;padding:8px 12px;font-size:11px;line-height:1.8;">
                        <strong>ca_id</strong>&nbsp; = 차종분류 (2010=BMW 3시리즈 F30...)<br>
                        <strong>ca_id2</strong> = 파츠종류 (5001=오일필터, 5008=엔진오일...)<br>
                        <strong>ca_id3</strong> = 부품브랜드 (B01=BMW OEM, B10=모빌...)
                    </div>
                </div>
            </div>
        </div>

        <!-- STEP 3: 미리보기 & 선택 -->
        <div class="card" id="step3-card" style="display:none;">
            <div class="card-title">
                <span class="step">3</span> 미리보기 &amp; 등록 상품 선택
                <span id="preview-count-badge" style="margin-left:8px; background:#ef4444; color:#fff; border-radius:10px; font-size:11px; padding:2px 8px; font-weight:700;"></span>
            </div>
            <div style="display:flex; gap:10px; align-items:center; margin-bottom:12px; flex-wrap:wrap;">
                <button class="btn btn-outline btn-sm" onclick="previewSelectAll(true)">전체 선택</button>
                <button class="btn btn-outline btn-sm" onclick="previewSelectAll(false)">전체 해제</button>
                <button class="btn btn-outline btn-sm" onclick="previewSelectMapped()">매핑된 것만</button>
                <span style="font-size:12px; color:#6b7280;" id="sel-preview-count">선택: 0개</span>
                <div style="margin-left:auto; display:flex; gap:8px;">
                    <button class="btn btn-success" id="btn-register" onclick="doRegister()">
                        ✅ 선택 상품 등록
                    </button>
                    <span id="reg-spinner" style="display:none;"><span class="spinner"></span></span>
                </div>
            </div>

            <!-- 진행 바 -->
            <div id="reg-progress-wrap" style="display:none;">
                <div class="progress-bar"><div class="progress-fill" id="reg-progress-fill" style="width:0%"></div></div>
                <div id="reg-progress-text" style="font-size:12px;color:#6b7280;text-align:center;margin-top:4px;"></div>
            </div>

            <div class="preview-wrap">
                <table class="preview-table" id="preview-table">
                    <thead>
                        <tr>
                            <th style="width:36px;"><input type="checkbox" id="chk-all-preview" onchange="previewSelectAll(this.checked)"></th>
                            <th>#</th>
                            <th>OEM번호</th>
                            <th>상품명</th>
                            <th>가격</th>
                            <th>Cafe24분류</th>
                            <th>→ ca_id</th>
                            <th>이미지</th>
                            <th>상태</th>
                        </tr>
                    </thead>
                    <tbody id="preview-tbody">
                        <tr><td colspan="9" style="text-align:center;color:#9ca3af;padding:30px;">파일을 업로드하면 미리보기가 표시됩니다.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /tab-upload -->

    <!-- ══ TAB 2: 카테고리 매핑 설정 ══ -->
    <div id="tab-mapping" class="tab-content">
        <div class="card">
            <div class="card-title">🗂️ Cafe24 분류번호 → Gnuboard ca_id 매핑 설정</div>
            <div class="alert alert-warning">
                ⚠️ 이 매핑은 현재 세션 동안만 유지됩니다. 서버 재시작 시 기본값으로 초기화됩니다.
                영구 저장이 필요하면 파일 상단의 <code>$DEFAULT_CAT_MAP</code> 배열을 직접 수정하세요.
            </div>
            <div style="display:flex; gap:10px; margin-bottom:16px;">
                <button class="btn btn-primary btn-sm" onclick="saveMapping()">💾 매핑 저장</button>
                <button class="btn btn-outline btn-sm" onclick="resetMapping()">↩️ 기본값으로 초기화</button>
                <button class="btn btn-outline btn-sm" onclick="addMapRow()">➕ 행 추가</button>
                <span id="map-save-msg" style="font-size:12px;color:#16a34a;margin-left:8px;"></span>
            </div>
            <table class="map-table" id="map-table">
                <thead>
                    <tr>
                        <th style="width:140px;">Cafe24 분류번호</th>
                        <th style="width:140px;">Gnuboard ca_id</th>
                        <th>설명 (메모)</th>
                        <th style="width:60px;">삭제</th>
                    </tr>
                </thead>
                <tbody id="map-tbody">
                    <?php foreach ($cat_map as $cafe24_k => $v): ?>
                    <tr>
                        <td><input type="text" class="map-cafe24" value="<?= htmlspecialchars($cafe24_k) ?>" placeholder="예: 35670"></td>
                        <td><input type="text" class="map-caid" value="<?= htmlspecialchars($v['ca_id']) ?>" placeholder="예: 2010"></td>
                        <td><input type="text" class="map-label" value="<?= htmlspecialchars($v['label'] ?? '') ?>" placeholder="예: BMW 3시리즈 F30"></td>
                        <td><button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">✕</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div><!-- /tab-mapping -->

    <!-- ══ TAB 3: 등록 결과 ══ -->
    <div id="tab-result" class="tab-content">
        <div class="card">
            <div class="card-title">📊 최근 등록 결과</div>
            <div id="result-container">
                <div style="text-align:center;color:#9ca3af;padding:40px;">등록을 실행하면 결과가 여기에 표시됩니다.</div>
            </div>
        </div>
    </div>

</div><!-- /pds-wrap -->

<!-- SheetJS CDN (브라우저에서 Excel 파싱) -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
/* ── 전역 ── */
let parsedRows = [];   // [{oem, cafe24_ca, name, price, img, detail}, ...]
let catMap = {};       // Cafe24번호 → ca_id (서버 설정값 복사)

// 서버 매핑값 가져오기
<?php
$js_map = [];
foreach ($cat_map as $k => $v) {
    $js_map[$k] = $v['ca_id'];
}
echo "catMap = " . json_encode($js_map, JSON_UNESCAPED_UNICODE) . ";\n";
?>

/* ── 탭 ── */
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});

/* ── 드롭존 ── */
const dz = document.getElementById('drop-zone');
dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
dz.addEventListener('drop', e => {
    e.preventDefault(); dz.classList.remove('drag-over');
    const f = e.dataTransfer.files[0];
    if (f) handleFileSelect(f);
});

/* ── 파일 파싱 ── */
function handleFileSelect(file) {
    if (!file) return;
    document.getElementById('file-info').innerHTML = `<span class="spinner"></span> 파싱 중: ${escHtml(file.name)} (${(file.size/1024).toFixed(1)} KB)`;

    const reader = new FileReader();

    // CSV 처리
    if (file.name.endsWith('.csv')) {
        reader.onload = e => {
            const lines = e.target.result.split('\n');
            parseCSVRows(lines);
        };
        reader.readAsText(file, 'UTF-8');
        return;
    }

    // XLSX/XLS 처리 (SheetJS)
    reader.onload = e => {
        try {
            const wb = XLSX.read(e.target.result, { type: 'array', cellDates: true });
            const ws = wb.Sheets[wb.SheetNames[0]];
            const data = XLSX.utils.sheet_to_json(ws, { header: 1, defval: '' });
            parseExcelRows(data);
            document.getElementById('file-info').innerHTML =
                `✅ <strong>${escHtml(file.name)}</strong> 파싱 완료 — ${parsedRows.length}개 행 감지`;
        } catch(err) {
            document.getElementById('file-info').innerHTML = `❌ 파싱 오류: ${err.message}`;
        }
    };
    reader.readAsArrayBuffer(file);
}

/* ── Excel 행 파싱 (BMW-cafe24.xlsx 컬럼 기준) ── */
function parseExcelRows(data) {
    parsedRows = [];
    // 헤더 행 찾기 (자체상품코드 또는 상품코드 열 찾기)
    let dataStart = 1; // 기본: 첫 행 헤더
    let colMap = { oem: 1, cafe24_ca: 4, name: 7, price: 22, img1: 47, img2: 48, img3: 49, img4: 50 };

    // 헤더 자동 감지
    for (let i = 0; i < Math.min(5, data.length); i++) {
        const row = data[i];
        for (let j = 0; j < row.length; j++) {
            const cell = String(row[j] || '').trim();
            if (cell === '자체상품코드' || cell === '상품코드') { colMap.oem = j; dataStart = i+1; }
            if (cell === '상품분류번호' || cell === '분류번호') { colMap.cafe24_ca = j; }
            if (cell === '상품명') { colMap.name = j; }
            if (cell === '판매가') { colMap.price = j; }
            if (cell.includes('대표이미지') && cell.includes('소')) { colMap.img1 = j; }
        }
    }

    for (let i = dataStart; i < data.length; i++) {
        const row = data[i];
        if (!row || !row.length) continue;

        const oem      = String(row[colMap.oem]       || '').trim();
        const cafe24ca = String(row[colMap.cafe24_ca]  || '').trim();
        const name     = String(row[colMap.name]       || '').trim();
        const price    = parseInt(String(row[colMap.price] || '0').replace(/[^0-9]/g, '')) || 0;
        const img1     = String(row[colMap.img1]       || '').trim();
        const img2     = String(row[colMap.img2]       || '').trim();
        const img3     = String(row[colMap.img3 || colMap.img2] || '').trim();

        if (!oem && !name) continue; // 빈 행 스킵

        // 이미지 URL 정규화
        const normalizeImg = (p) => {
            if (!p) return '';
            if (p.startsWith('http') || p.startsWith('//')) return p;
            if (p.startsWith('/')) return 'https://ecimg.cafe24img.com' + p;
            return 'https://ecimg.cafe24img.com/' + p;
        };

        parsedRows.push({
            rowNum:   i + 1,
            oem:      oem,
            cafe24_ca: cafe24ca,
            name:     name,
            price:    price,
            img:      normalizeImg(img1) || normalizeImg(img2),
            img2:     normalizeImg(img2),
            img3:     normalizeImg(img3),
            detail:   '',
        });
    }

    renderPreview();
}

/* ── CSV 행 파싱 ── */
function parseCSVRows(lines) {
    parsedRows = [];
    const header = (lines[0] || '').split(',');
    const getCol = (name) => header.findIndex(h => h.trim() === name.trim());
    const oemCol  = getCol('자체상품코드') !== -1 ? getCol('자체상품코드') : 1;
    const caCol   = getCol('상품분류번호') !== -1 ? getCol('상품분류번호') : 4;
    const nameCol = getCol('상품명')       !== -1 ? getCol('상품명')       : 7;
    const priceCol= getCol('판매가')       !== -1 ? getCol('판매가')       : 22;
    const imgCol  = getCol('대표이미지(소)')!== -1 ? getCol('대표이미지(소)') : 47;

    for (let i = 1; i < lines.length; i++) {
        const cols = lines[i].split(',');
        if (cols.length < 5) continue;
        const oem  = (cols[oemCol] || '').trim().replace(/^"(.*)"$/, '$1');
        const name = (cols[nameCol]|| '').trim().replace(/^"(.*)"$/, '$1');
        const img  = (cols[imgCol] || '').trim().replace(/^"(.*)"$/, '$1');
        const price= parseInt((cols[priceCol]||'0').replace(/[^0-9]/g,'')) || 0;
        const cafe24ca = (cols[caCol]||'').trim().replace(/^"(.*)"$/, '$1');
        if (!oem && !name) continue;
        parsedRows.push({ rowNum: i+1, oem, cafe24_ca: cafe24ca, name, price, img, detail: '' });
    }
    renderPreview();
}

/* ── 미리보기 렌더링 ── */
function renderPreview() {
    document.getElementById('step2-card').style.display = 'block';
    document.getElementById('step3-card').style.display = 'block';
    document.getElementById('preview-count-badge').textContent = parsedRows.length + '개';

    const tbody = document.getElementById('preview-tbody');
    if (!parsedRows.length) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#9ca3af;padding:20px;">파싱된 데이터가 없습니다.</td></tr>';
        return;
    }

    tbody.innerHTML = parsedRows.map((r, idx) => {
        const mappedCa = catMap[r.cafe24_ca] || '';
        const status = !r.oem ? '<span class="tag-err">OEM없음</span>'
                     : !mappedCa ? '<span class="tag-warn">매핑없음</span>'
                     : '<span class="tag-ok">등록가능</span>';
        const imgHtml = r.img
            ? `<img src="${escHtml(r.img)}" class="img-thumb" alt="" onerror="this.style.opacity=0.3">`
            : '<span style="color:#9ca3af;font-size:11px;">없음</span>';
        return `<tr>
            <td><input type="checkbox" class="preview-chk" data-idx="${idx}" onchange="updatePreviewSelCount()"></td>
            <td style="color:#9ca3af;">${r.rowNum}</td>
            <td style="font-family:monospace;font-size:11px;">${escHtml(r.oem)}</td>
            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;">${escHtml(r.name)}</td>
            <td style="text-align:right;">${r.price.toLocaleString()}원</td>
            <td style="font-family:monospace;">${escHtml(r.cafe24_ca)}</td>
            <td style="font-family:monospace;color:${mappedCa ? '#2563eb' : '#dc2626'};">${mappedCa || '─'}</td>
            <td>${imgHtml}</td>
            <td>${status}</td>
        </tr>`;
    }).join('');

    updatePreviewSelCount();
    scrollToStep3();
}

function scrollToStep3() {
    setTimeout(() => document.getElementById('step3-card').scrollIntoView({ behavior: 'smooth', block: 'nearest' }), 100);
}

function updatePreviewSelCount() {
    const cnt = document.querySelectorAll('.preview-chk:checked').length;
    document.getElementById('sel-preview-count').textContent = `선택: ${cnt}개`;
}

function previewSelectAll(checked) {
    document.querySelectorAll('.preview-chk').forEach(c => c.checked = checked);
    document.getElementById('chk-all-preview').checked = checked;
    updatePreviewSelCount();
}

function previewSelectMapped() {
    document.querySelectorAll('.preview-chk').forEach((c, i) => {
        const r = parsedRows[parseInt(c.dataset.idx)];
        c.checked = r && r.oem && catMap[r.cafe24_ca];
    });
    updatePreviewSelCount();
}

/* ── 차종 시리즈/모델 로드 ── */
async function loadRegSeries() {
    const brandId = document.getElementById('reg-brand-id').value;
    const sel = document.getElementById('reg-series-id');
    sel.innerHTML = '<option value="0">로딩 중...</option>';
    if (!brandId || brandId === '0') { sel.innerHTML = '<option value="0">── 브랜드 먼저 선택 ──</option>'; return; }
    const data = await fetchJson('../../partsds/car_api.php?action=series&brand_id=' + brandId);
    sel.innerHTML = '<option value="0">── 전체 브랜드 ──</option>';
    (data.series || data || []).forEach(r => {
        sel.innerHTML += `<option value="${r.id}">${escHtml(r.series_name)}</option>`;
    });
    document.getElementById('reg-model-id').innerHTML = '<option value="0">── 전체 시리즈 ──</option>';
}

async function loadRegModels() {
    const seriesId = document.getElementById('reg-series-id').value;
    const sel = document.getElementById('reg-model-id');
    sel.innerHTML = '<option value="0">로딩 중...</option>';
    if (!seriesId || seriesId === '0') { sel.innerHTML = '<option value="0">── 전체 시리즈 ──</option>'; return; }
    const data = await fetchJson('../../partsds/car_api.php?action=models&series_id=' + seriesId);
    sel.innerHTML = '<option value="0">── 전체 시리즈 ──</option>';
    (data.models || data || []).forEach(r => {
        sel.innerHTML += `<option value="${r.id}">${escHtml(r.model_name)}</option>`;
    });
}

/* ── 등록 실행 ── */
async function doRegister() {
    const checked = [...document.querySelectorAll('.preview-chk:checked')];
    if (!checked.length) { alert('등록할 상품을 선택하세요.'); return; }

    if (!confirm(`${checked.length}개 상품을 등록합니다. 계속하시겠습니까?`)) return;

    const partsCa   = document.getElementById('reg-parts-ca').value;
    const partsCa2  = document.getElementById('reg-parts-ca2') ? document.getElementById('reg-parts-ca2').value : '';
    const partsCa3  = document.getElementById('reg-parts-ca3') ? document.getElementById('reg-parts-ca3').value : '';
    const brandId   = document.getElementById('reg-brand-id').value;
    const seriesId  = document.getElementById('reg-series-id').value;
    const modelId   = document.getElementById('reg-model-id').value;
    const useMaster = document.getElementById('reg-use-master').checked ? 1 : 0;
    const overwrite = document.getElementById('reg-overwrite').checked ? 1 : 0;

    document.getElementById('btn-register').disabled = true;
    document.getElementById('reg-spinner').style.display = 'inline-flex';
    document.getElementById('reg-progress-wrap').style.display = 'block';

    // 청크 단위로 전송 (50개씩)
    const CHUNK = 50;
    const selectedRows = checked.map(c => parsedRows[parseInt(c.dataset.idx)]).filter(Boolean);
    let allResults = { success: 0, skip: 0, fail: 0, results: [] };

    for (let i = 0; i < selectedRows.length; i += CHUNK) {
        const chunk = selectedRows.slice(i, i + CHUNK);
        const pct = Math.round((i / selectedRows.length) * 100);
        document.getElementById('reg-progress-fill').style.width = pct + '%';
        document.getElementById('reg-progress-text').textContent = `처리 중... ${i}/${selectedRows.length}개`;

        const body = new FormData();
        chunk.forEach(r => body.append('rows[]', JSON.stringify(r)));
        body.append('parts_ca',   partsCa);
        body.append('parts_ca2',  partsCa2);
        body.append('parts_ca3',  partsCa3);
        body.append('brand_id',   brandId);
        body.append('series_id',  seriesId);
        body.append('model_id',   modelId);
        body.append('use_master', useMaster);
        body.append('overwrite',  overwrite);

        try {
            const res = await fetch('?ajax=do_register', { method: 'POST', body });
            const data = await res.json();
            if (data.ok) {
                allResults.success += data.success;
                allResults.skip    += data.skip;
                allResults.fail    += data.fail;
                allResults.results = allResults.results.concat(data.results || []);
            }
        } catch(e) {
            console.error(e);
        }
    }

    document.getElementById('reg-progress-fill').style.width = '100%';
    document.getElementById('reg-progress-text').textContent = '완료!';
    document.getElementById('btn-register').disabled = false;
    document.getElementById('reg-spinner').style.display = 'none';

    // 결과 탭 표시
    renderResult(allResults);
    document.querySelector('[data-tab="tab-result"]').click();
}

/* ── 결과 렌더링 ── */
function renderResult(data) {
    const container = document.getElementById('result-container');
    const detailRows = (data.results || []).slice(0, 200).map(r => {
        const row = r.row || {};
        return `<tr>
            <td>${r.ok ? (r.msg === '업데이트' ? '<span class="tag-warn">업데이트</span>' : '<span class="tag-ok">성공</span>') : (r.skip ? '<span style="background:#e5e7eb;color:#6b7280;padding:2px 6px;border-radius:8px;font-size:10px;">스킵</span>' : '<span class="tag-err">실패</span>')}</td>
            <td style="font-family:monospace;font-size:11px;">${escHtml(row.oem || '')}</td>
            <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;">${escHtml(row.name || '')}</td>
            <td style="font-family:monospace;font-size:11px;">${escHtml(r.it_id || '')}</td>
            <td style="font-size:11px;color:#6b7280;">${escHtml(r.msg || '')}</td>
        </tr>`;
    }).join('');

    container.innerHTML = `
        <div class="result-stats">
            <div class="result-stat">
                <div class="num" style="color:#16a34a;">${data.success}</div>
                <div class="lbl">등록 성공</div>
            </div>
            <div class="result-stat">
                <div class="num" style="color:#d97706;">${data.skip}</div>
                <div class="lbl">중복 스킵</div>
            </div>
            <div class="result-stat">
                <div class="num" style="color:#dc2626;">${data.fail}</div>
                <div class="lbl">실패</div>
            </div>
            <div class="result-stat">
                <div class="num" style="color:#6b7280;">${data.success + data.skip + data.fail}</div>
                <div class="lbl">전체</div>
            </div>
        </div>
        ${data.results && data.results.length > 200 ? '<div class="alert alert-warning">⚠️ 결과가 너무 많아 상위 200건만 표시됩니다.</div>' : ''}
        <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:12px;">
            <thead><tr style="background:#f8fafc;">
                <th style="padding:7px 10px;">상태</th>
                <th style="padding:7px 10px;">OEM번호</th>
                <th style="padding:7px 10px;">상품명</th>
                <th style="padding:7px 10px;">생성된 it_id</th>
                <th style="padding:7px 10px;">메모</th>
            </tr></thead>
            <tbody>${detailRows}</tbody>
        </table>
        </div>
    `;
}

/* ── 매핑 관리 ── */
async function saveMapping() {
    const rows = document.querySelectorAll('#map-tbody tr');
    const cafe24_keys = [], ca_ids = [], labels = [];
    rows.forEach(tr => {
        cafe24_keys.push(tr.querySelector('.map-cafe24').value);
        ca_ids.push(tr.querySelector('.map-caid').value);
        labels.push(tr.querySelector('.map-label').value);
    });
    const body = new FormData();
    cafe24_keys.forEach(k => body.append('cafe24_key[]', k));
    ca_ids.forEach(v => body.append('ca_id[]', v));
    labels.forEach(v => body.append('label[]', v));

    try {
        const res = await fetch('?ajax=save_map', { method: 'POST', body });
        const data = await res.json();
        if (data.ok) {
            // JS catMap 업데이트
            catMap = {};
            cafe24_keys.forEach((k, i) => { if (k) catMap[k] = ca_ids[i]; });
            document.getElementById('map-save-msg').textContent = `✅ ${data.count}개 매핑 저장됨`;
            setTimeout(() => document.getElementById('map-save-msg').textContent = '', 3000);
            if (parsedRows.length) renderPreview(); // 미리보기 갱신
        }
    } catch(e) { alert('저장 오류: ' + e.message); }
}

async function resetMapping() {
    if (!confirm('기본값으로 초기화합니까?')) return;
    await fetch('?ajax=reset_map');
    location.reload();
}

function addMapRow() {
    const tbody = document.getElementById('map-tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" class="map-cafe24" placeholder="예: 35681"></td>
        <td><input type="text" class="map-caid"   placeholder="예: 2018"></td>
        <td><input type="text" class="map-label"  placeholder="예: BMW 5시리즈 E60"></td>
        <td><button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">✕</button></td>
    `;
    tbody.appendChild(tr);
    tr.querySelector('.map-cafe24').focus();
}

/* ── 유틸 ── */
async function fetchJson(url) {
    const res = await fetch(url);
    return await res.json();
}

function escHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
</body>
</html>
