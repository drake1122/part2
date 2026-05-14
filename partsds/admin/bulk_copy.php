<?php
/**
 * 파츠디에스 - 타제조사 상품 일괄 복사 등록
 * 경로: /partsds/admin/bulk_copy.php
 *
 * 기능:
 *  1. 기존 상품(원본 ca_id/브랜드)을 선택
 *  2. 새 브랜드/차종 카테고리로 복사
 *  3. 복사본의 가격·이미지·상세페이지 일괄 수정
 *  4. 자동으로 item_car 매핑도 복사(+변경)
 */
if (!defined('_EYOOM_IS_ADMIN_')) {
    include_once('../../_common.php');
}

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    exit;
}

define('PDS_COPY_SHOP_ITEM', G5_TABLE_PREFIX . 'shop_item');
define('PDS_COPY_ITEM_CAR',  G5_TABLE_PREFIX . 'item_car');

/* ─────────────────────────────────────────────────────────
   AJAX 엔드포인트
───────────────────────────────────────────────────────── */
$ajax = isset($_GET['ajax']) ? $_GET['ajax'] : '';
header_remove('X-Frame-Options');

// Ajax: 카테고리 목록 (브랜드 ca_id 기준)
if ($ajax === 'get_categories') {
    header('Content-Type: application/json');
    $brand_ca = isset($_GET['brand_ca']) ? preg_replace('/[^0-9]/', '', $_GET['brand_ca']) : '';
    // 해당 브랜드 하위 카테고리 (차종/시리즈)
    $rows = [];
    if ($brand_ca) {
        $res = sql_query("SELECT ca_id, ca_name FROM `" . G5_TABLE_PREFIX . "shop_category` WHERE ca_id LIKE '{$brand_ca}%' AND ca_id != '{$brand_ca}' ORDER BY ca_id ASC LIMIT 200");
        while ($r = sql_fetch_array($res)) $rows[] = $r;
    }
    echo json_encode($rows);
    exit;
}

// Ajax: 선택된 카테고리의 상품 목록
if ($ajax === 'get_items') {
    header('Content-Type: application/json');
    $ca_id = isset($_GET['ca_id']) ? preg_replace('/[^0-9a-zA-Z]/', '', $_GET['ca_id']) : '';
    $rows = [];
    if ($ca_id) {
        $res = sql_query("SELECT it_id, it_name, it_price, it_sell_price, ca_id, it_id_code FROM `" . PDS_COPY_SHOP_ITEM . "` WHERE ca_id = '{$ca_id}' AND it_use = '1' ORDER BY it_name ASC LIMIT 500");
        while ($r = sql_fetch_array($res)) $rows[] = $r;
    }
    echo json_encode($rows);
    exit;
}

// Ajax: 상품 1개 상세 조회 (복사 미리보기)
if ($ajax === 'get_item_detail') {
    header('Content-Type: application/json');
    $it_id = isset($_GET['it_id']) ? preg_replace('/[^0-9a-zA-Z_\-]/', '', $_GET['it_id']) : '';
    if (!$it_id) { echo json_encode(['ok' => false]); exit; }
    $item = sql_fetch("SELECT * FROM `" . PDS_COPY_SHOP_ITEM . "` WHERE it_id = '" . sql_escape_string($it_id) . "'");
    // item_car 매핑 조회
    $car_res = sql_query("SELECT ic.*, cb.brand_name, cs.series_name, cm.model_name FROM `" . PDS_COPY_ITEM_CAR . "` ic LEFT JOIN `" . G5_TABLE_PREFIX . "car_brand`  cb ON ic.brand_id  = cb.id LEFT JOIN `" . G5_TABLE_PREFIX . "car_series` cs ON ic.series_id = cs.id LEFT JOIN `" . G5_TABLE_PREFIX . "car_model`  cm ON ic.model_id  = cm.id WHERE ic.it_id = '" . sql_escape_string($it_id) . "'");
    $car_maps = [];
    while ($r = sql_fetch_array($car_res)) $car_maps[] = $r;
    $item['car_maps'] = $car_maps;
    echo json_encode(['ok' => true, 'item' => $item]);
    exit;
}

// Ajax: 실제 복사 실행
if ($ajax === 'do_copy' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $source_it_ids  = isset($_POST['source_it_ids'])  ? (array)$_POST['source_it_ids']  : [];
    $new_ca_id      = isset($_POST['new_ca_id'])       ? preg_replace('/[^0-9a-zA-Z]/', '', $_POST['new_ca_id'])      : '';
    $new_brand_id   = isset($_POST['new_brand_id'])    ? (int)$_POST['new_brand_id']    : 0;
    $new_series_id  = isset($_POST['new_series_id'])   ? (int)$_POST['new_series_id']   : 0;
    $new_model_id   = isset($_POST['new_model_id'])    ? (int)$_POST['new_model_id']    : 0;
    $price_type     = isset($_POST['price_type'])      ? $_POST['price_type']            : 'keep';  // keep / fixed / rate
    $price_val      = isset($_POST['price_val'])       ? (float)$_POST['price_val']      : 0;
    $new_img_url    = isset($_POST['new_img_url'])     ? trim($_POST['new_img_url'])      : '';
    $new_detail     = isset($_POST['new_detail'])      ? trim($_POST['new_detail'])       : '';
    $keep_car_maps  = isset($_POST['keep_car_maps'])   ? (int)$_POST['keep_car_maps']    : 0; // 원본 차종매핑 유지여부
    $suffix         = isset($_POST['name_suffix'])     ? trim(strip_tags($_POST['name_suffix'])) : '';

    if (!$new_ca_id || empty($source_it_ids)) {
        echo json_encode(['ok' => false, 'msg' => '복사 대상 카테고리와 원본 상품을 선택해주세요.']);
        exit;
    }

    $results = [];
    $success = 0;
    $fail    = 0;

    foreach ($source_it_ids as $src_id) {
        $src_id = preg_replace('/[^0-9a-zA-Z_\-]/', '', $src_id);
        if (!$src_id) continue;

        $src = sql_fetch("SELECT * FROM `" . PDS_COPY_SHOP_ITEM . "` WHERE it_id = '" . sql_escape_string($src_id) . "'");
        if (!$src['it_id']) {
            $results[] = ['it_id' => $src_id, 'ok' => false, 'msg' => '원본 없음'];
            $fail++;
            continue;
        }

        // 새 it_id 생성 (원본 it_id + _CA새카테고리 + 타임스탬프)
        $new_it_id = substr($src_id, 0, 14) . '_' . $new_ca_id . '_' . substr(time(), -4);
        // 중복 체크 후 suffix 추가
        $exist = sql_fetch("SELECT it_id FROM `" . PDS_COPY_SHOP_ITEM . "` WHERE it_id = '" . sql_escape_string($new_it_id) . "'");
        if ($exist['it_id']) {
            $new_it_id .= rand(10, 99);
        }

        // 가격 계산
        $new_price = (int)$src['it_price'];
        if ($price_type === 'fixed' && $price_val > 0) {
            $new_price = (int)$price_val;
        } elseif ($price_type === 'rate' && $price_val != 0) {
            $new_price = (int)round($src['it_price'] * (1 + $price_val / 100));
        }
        $new_sell  = $new_price;
        $new_supply = (int)$src['it_supply_price'];

        // 이미지
        $img1 = $new_img_url ?: $src['it_img1'];
        $img2 = $new_img_url ?: $src['it_img2'];
        $img3 = $new_img_url ?: $src['it_img3'];
        $img4 = $new_img_url ?: $src['it_img4'];
        $img5 = $src['it_img5'];

        // 상세 HTML
        $detail = $new_detail ?: $src['it_detail'];

        // 상품명 처리
        $new_name = $src['it_name'];
        if ($suffix) {
            $new_name .= ' ' . $suffix;
        }

        // INSERT 복사
        $esc = function($v) { return sql_escape_string($v); };

        $sql = "INSERT INTO `" . PDS_COPY_SHOP_ITEM . "`
            (it_id, ca_id, it_name, it_price, it_sell_price, it_supply_price,
             it_id_code, it_img1, it_img2, it_img3, it_img4, it_img5,
             it_detail, it_maker, it_origin, it_brand,
             it_stock_qty, it_use, it_basic_price_display, it_type, it_noti,
             it_point_type, it_point, it_point_percent,
             it_delivery, it_delivery_price, it_delivery_add,
             it_tax_type, it_tax_rate, it_order, it_hit,
             it_reg_dt, it_update_dt)
        SELECT
            '{$new_it_id}', '{$new_ca_id}', '" . $esc($new_name) . "',
            {$new_price}, {$new_sell}, {$new_supply},
            '" . $esc($src['it_id_code']) . "',
            '" . $esc($img1) . "', '" . $esc($img2) . "',
            '" . $esc($img3) . "', '" . $esc($img4) . "',
            '" . $esc($img5) . "',
            '" . $esc($detail) . "',
            it_maker, it_origin, it_brand,
            it_stock_qty, it_use, it_basic_price_display, it_type, it_noti,
            it_point_type, it_point, it_point_percent,
            it_delivery, it_delivery_price, it_delivery_add,
            it_tax_type, it_tax_rate, it_order, '0',
            NOW(), NOW()
        FROM `" . PDS_COPY_SHOP_ITEM . "`
        WHERE it_id = '" . $esc($src_id) . "'";

        if (!sql_query($sql)) {
            $results[] = ['it_id' => $src_id, 'new_it_id' => $new_it_id, 'ok' => false, 'msg' => sql_error()];
            $fail++;
            continue;
        }

        // item_car 매핑 추가
        if ($new_brand_id) {
            // 새 차종 매핑
            $exist_map = sql_fetch("SELECT id FROM `" . PDS_COPY_ITEM_CAR . "` WHERE it_id='" . $esc($new_it_id) . "' AND brand_id={$new_brand_id} AND series_id={$new_series_id} AND model_id={$new_model_id}");
            if (!$exist_map['id']) {
                sql_query("INSERT INTO `" . PDS_COPY_ITEM_CAR . "` (it_id, brand_id, series_id, model_id)
                    VALUES ('" . $esc($new_it_id) . "', {$new_brand_id}, {$new_series_id}, {$new_model_id})");
            }
        }

        // 원본 차종 매핑 유지 옵션
        if ($keep_car_maps) {
            $map_res = sql_query("SELECT * FROM `" . PDS_COPY_ITEM_CAR . "` WHERE it_id = '" . $esc($src_id) . "'");
            while ($m = sql_fetch_array($map_res)) {
                $exist_map = sql_fetch("SELECT id FROM `" . PDS_COPY_ITEM_CAR . "` WHERE it_id='" . $esc($new_it_id) . "' AND brand_id={$m['brand_id']} AND series_id={$m['series_id']} AND model_id={$m['model_id']}");
                if (!$exist_map['id']) {
                    sql_query("INSERT INTO `" . PDS_COPY_ITEM_CAR . "` (it_id, brand_id, series_id, model_id)
                        VALUES ('" . $esc($new_it_id) . "', {$m['brand_id']}, {$m['series_id']}, {$m['model_id']})");
                }
            }
        }

        $results[] = ['it_id' => $src_id, 'new_it_id' => $new_it_id, 'ok' => true, 'name' => $new_name];
        $success++;
    }

    echo json_encode([
        'ok'      => true,
        'success' => $success,
        'fail'    => $fail,
        'results' => $results,
    ]);
    exit;
}

// Ajax: 복사된 상품 일괄 필드 수정
if ($ajax === 'bulk_update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $it_ids    = isset($_POST['it_ids'])    ? (array)$_POST['it_ids']  : [];
    $field     = isset($_POST['field'])     ? preg_replace('/[^a-z0-9_]/', '', $_POST['field']) : '';
    $value     = isset($_POST['value'])     ? trim($_POST['value'])     : '';
    $allowed_fields = ['it_price', 'it_sell_price', 'it_supply_price',
                       'it_img1',  'it_img2', 'it_img3', 'it_img4',
                       'it_detail','it_maker','it_brand','ca_id','it_delivery_price'];

    if (!in_array($field, $allowed_fields) || empty($it_ids)) {
        echo json_encode(['ok' => false, 'msg' => '잘못된 요청']);
        exit;
    }

    $count = 0;
    $val_esc = sql_escape_string($value);

    foreach ($it_ids as $it_id) {
        $it_id = preg_replace('/[^0-9a-zA-Z_\-]/', '', $it_id);
        if (!$it_id) continue;
        sql_query("UPDATE `" . PDS_COPY_SHOP_ITEM . "` SET `{$field}` = '{$val_esc}' WHERE it_id = '" . sql_escape_string($it_id) . "'");
        $count++;
    }

    // it_price 변경 시 it_sell_price도 동기화
    if ($field === 'it_price') {
        foreach ($it_ids as $it_id) {
            $it_id = preg_replace('/[^0-9a-zA-Z_\-]/', '', $it_id);
            if (!$it_id) continue;
            sql_query("UPDATE `" . PDS_COPY_SHOP_ITEM . "` SET it_sell_price = '{$val_esc}' WHERE it_id = '" . sql_escape_string($it_id) . "'");
        }
    }

    echo json_encode(['ok' => true, 'count' => $count]);
    exit;
}

/* ─────────────────────────────────────────────────────────
   브랜드 목록 (g5_shop_category에서 최상위 브랜드)
───────────────────────────────────────────────────────── */
// 브랜드 ca_id: 10=벤츠, 20=BMW, 30=아우디 ... (2자리)
$brand_cats = [];
$res = sql_query("SELECT ca_id, ca_name FROM `" . G5_TABLE_PREFIX . "shop_category`
                  WHERE LENGTH(ca_id) = 2 AND ca_id REGEXP '^[0-9]+$' ORDER BY ca_id ASC LIMIT 50");
while ($r = sql_fetch_array($res)) $brand_cats[] = $r;

// car_brand 테이블에서 브랜드 목록
$car_brands = [];
$res2 = sql_query("SELECT id, brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` ORDER BY brand_name ASC");
while ($r = sql_fetch_array($res2)) $car_brands[] = $r;

// 파츠 카테고리 (5001~5041)
$parts_cats = [];
$res3 = sql_query("SELECT ca_id, ca_name FROM `" . G5_TABLE_PREFIX . "shop_category` WHERE ca_id BETWEEN '5001' AND '5041' ORDER BY ca_id ASC");
while ($r = sql_fetch_array($res3)) $parts_cats[] = $r;

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>상품 복사 등록 - 파츠디에스 관리</title>
<style>
/* ── 기본 ── */
*, *::before, *::after { box-sizing: border-box; }
body { font-family: -apple-system, "Malgun Gothic", sans-serif; background: #f4f6f9; margin: 0; padding: 0; color: #222; font-size: 14px; }
.pds-wrap { max-width: 1400px; margin: 0 auto; padding: 20px; }

/* ── 타이틀 ── */
.pds-title { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
.pds-title h1 { font-size: 22px; font-weight: 700; margin: 0; }
.pds-title .badge { background: #2563eb; color: #fff; font-size: 11px; padding: 3px 8px; border-radius: 20px; }

/* ── 카드 ── */
.card { background: #fff; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.08); padding: 24px; margin-bottom: 20px; }
.card-title { font-size: 15px; font-weight: 700; margin: 0 0 16px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb; color: #1e40af; display: flex; align-items: center; gap: 8px; }
.card-title .step { background: #1e40af; color: #fff; border-radius: 50%; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0; }

/* ── 그리드 ── */
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
@media (max-width: 900px) { .grid-2, .grid-3 { grid-template-columns: 1fr; } }

/* ── 폼 요소 ── */
label { display: block; font-size: 12px; font-weight: 600; color: #6b7280; margin-bottom: 5px; }
input[type="text"], input[type="number"], select, textarea {
    width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px;
    background: #fff; color: #222; transition: border-color .15s;
}
input:focus, select:focus, textarea:focus { outline: none; border-color: #2563eb; }
textarea { resize: vertical; min-height: 100px; font-family: monospace; font-size: 12px; }
.form-group { margin-bottom: 14px; }
.help-text { font-size: 11px; color: #9ca3af; margin-top: 4px; }

/* ── 상품 목록 (체크박스 테이블) ── */
.item-list-wrap { max-height: 400px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; }
.item-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.item-table th { background: #f8fafc; padding: 10px 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb; position: sticky; top: 0; z-index: 1; }
.item-table td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
.item-table tr:hover td { background: #f0f4ff; }
.item-table tr.selected td { background: #eff6ff; }
.item-table input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; }
.select-all-row { background: #f8fafc; }

/* ── 버튼 ── */
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s; }
.btn-primary   { background: #2563eb; color: #fff; }
.btn-primary:hover { background: #1d4ed8; }
.btn-success   { background: #16a34a; color: #fff; }
.btn-success:hover { background: #15803d; }
.btn-warning   { background: #d97706; color: #fff; }
.btn-warning:hover { background: #b45309; }
.btn-danger    { background: #dc2626; color: #fff; }
.btn-danger:hover  { background: #b91c1c; }
.btn-secondary { background: #6b7280; color: #fff; }
.btn-secondary:hover { background: #4b5563; }
.btn-outline   { background: transparent; border: 1px solid #d1d5db; color: #374151; }
.btn-outline:hover { background: #f3f4f6; }
.btn-sm { padding: 5px 12px; font-size: 12px; }
.btn:disabled { opacity: .5; cursor: not-allowed; }

/* ── 라디오 그룹 ── */
.radio-group { display: flex; gap: 16px; flex-wrap: wrap; }
.radio-group label { display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 400; color: #374151; margin: 0; cursor: pointer; }
.radio-group input[type="radio"] { width: 15px; height: 15px; }

/* ── 결과 패널 ── */
.result-panel { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px; display: none; }
.result-panel.error { background: #fef2f2; border-color: #fecaca; }
.result-stats { display: flex; gap: 20px; margin-bottom: 12px; }
.result-stat { text-align: center; }
.result-stat .num { font-size: 24px; font-weight: 700; color: #16a34a; }
.result-stat .num.fail { color: #dc2626; }
.result-stat .lbl { font-size: 11px; color: #6b7280; }
.result-table { font-size: 12px; width: 100%; border-collapse: collapse; }
.result-table th, .result-table td { padding: 5px 10px; border-bottom: 1px solid #e5e7eb; }
.result-table th { background: #f8fafc; }
.ok-badge  { background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 600; }
.fail-badge { background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 600; }

/* ── 미리보기 팝업 ── */
.preview-box { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; display: none; }
.preview-box img { max-width: 80px; max-height: 80px; object-fit: contain; border-radius: 4px; border: 1px solid #e5e7eb; }
.preview-info { display: flex; gap: 16px; align-items: flex-start; }
.preview-text { flex: 1; font-size: 13px; }
.preview-text strong { display: block; margin-bottom: 4px; }

/* ── 탭 ── */
.tab-nav { display: flex; border-bottom: 2px solid #e5e7eb; margin-bottom: 20px; }
.tab-btn { padding: 10px 20px; cursor: pointer; font-size: 14px; font-weight: 600; color: #6b7280; border-bottom: 3px solid transparent; margin-bottom: -2px; background: none; border-top: none; border-left: none; border-right: none; transition: all .15s; }
.tab-btn.active { color: #2563eb; border-bottom-color: #2563eb; }
.tab-content { display: none; }
.tab-content.active { display: block; }

/* ── 알림 ── */
.alert { padding: 10px 16px; border-radius: 6px; margin-bottom: 14px; font-size: 13px; }
.alert-info    { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
.alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid #e5e7eb; border-top-color: #2563eb; border-radius: 50%; animation: spin .6s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── 뱃지 카운터 ── */
.count-badge { background: #ef4444; color: #fff; border-radius: 10px; font-size: 11px; padding: 1px 6px; font-weight: 700; }
</style>
</head>
<body>
<div class="pds-wrap">
    <!-- 타이틀 -->
    <div class="pds-title">
        <h1>상품 복사 등록</h1>
        <span class="badge">PartsDS Admin</span>
        <a href="parts_master.php" class="btn btn-outline btn-sm" style="margin-left:auto;">← 마스터 관리</a>
        <a href="item_car_manage.php" class="btn btn-outline btn-sm">차종 매핑</a>
    </div>

    <!-- 안내 -->
    <div class="alert alert-info">
        💡 <strong>사용법:</strong>
        원본 카테고리에서 상품을 선택 → 복사할 새 카테고리/차종 지정 → 가격·이미지·상세페이지 일괄 변경 옵션 설정 → 복사 실행
        <br>복사된 상품은 새 ca_id로 등록되며, 자체상품코드(OEM번호)는 유지되어 파츠 마스터와 계속 연동됩니다.
    </div>

    <!-- 탭 -->
    <div class="tab-nav">
        <button class="tab-btn active" data-tab="tab-copy">📋 상품 복사 등록</button>
        <button class="tab-btn" data-tab="tab-bulk-edit">✏️ 일괄 필드 수정</button>
    </div>

    <!-- ══ TAB 1: 상품 복사 ══ -->
    <div id="tab-copy" class="tab-content active">

        <!-- STEP 1: 원본 선택 -->
        <div class="card">
            <div class="card-title"><span class="step">1</span> 원본 상품 선택 (복사할 상품)</div>
            <div class="grid-2">
                <!-- 원본 브랜드 카테고리 -->
                <div class="form-group">
                    <label>원본 브랜드 카테고리</label>
                    <select id="src-brand-ca" onchange="loadSrcCategories()">
                        <option value="">── 브랜드 선택 ──</option>
                        <?php foreach ($brand_cats as $bc): ?>
                        <option value="<?= htmlspecialchars($bc['ca_id']) ?>"><?= htmlspecialchars($bc['ca_name']) ?> (ca_id: <?= $bc['ca_id'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- 원본 차종/시리즈 카테고리 -->
                <div class="form-group">
                    <label>원본 차종/시리즈 카테고리</label>
                    <select id="src-ca-id" onchange="loadSrcItems()">
                        <option value="">── 브랜드 먼저 선택 ──</option>
                    </select>
                </div>
            </div>

            <!-- 원본 상품 목록 -->
            <div style="margin-top: 12px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <label style="margin: 0; font-size: 13px; font-weight: 600;">원본 상품 목록</label>
                    <span class="count-badge" id="src-count">0</span>
                    <button class="btn btn-outline btn-sm" onclick="selectAllItems(true)">전체 선택</button>
                    <button class="btn btn-outline btn-sm" onclick="selectAllItems(false)">전체 해제</button>
                    <span style="margin-left: auto; font-size: 12px; color: #6b7280;" id="sel-count-text">선택: 0개</span>
                </div>
                <div class="item-list-wrap" id="src-item-list">
                    <table class="item-table">
                        <thead>
                            <tr>
                                <th style="width:40px;"><input type="checkbox" id="chk-all" onchange="selectAllItems(this.checked)"></th>
                                <th>상품코드</th>
                                <th>상품명</th>
                                <th>판매가</th>
                                <th>OEM번호</th>
                                <th>미리보기</th>
                            </tr>
                        </thead>
                        <tbody id="src-item-tbody">
                            <tr><td colspan="6" style="text-align:center; color:#9ca3af; padding: 30px;">카테고리를 선택하면 상품이 표시됩니다.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- STEP 2: 복사 대상 설정 -->
        <div class="card">
            <div class="card-title"><span class="step">2</span> 복사 대상 설정 (새 카테고리 & 차종)</div>
            <div class="grid-3">
                <!-- 새 브랜드 카테고리 -->
                <div class="form-group">
                    <label>새 브랜드 카테고리</label>
                    <select id="dst-brand-ca" onchange="loadDstCategories()">
                        <option value="">── 브랜드 선택 ──</option>
                        <?php foreach ($brand_cats as $bc): ?>
                        <option value="<?= htmlspecialchars($bc['ca_id']) ?>"><?= htmlspecialchars($bc['ca_name']) ?> (ca_id: <?= $bc['ca_id'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <div class="help-text">복사본이 등록될 브랜드</div>
                </div>
                <!-- 새 차종 카테고리 -->
                <div class="form-group">
                    <label>새 차종/시리즈 카테고리 <span style="color:#ef4444;">*</span></label>
                    <select id="dst-ca-id">
                        <option value="">── 브랜드 먼저 선택 ──</option>
                    </select>
                    <div class="help-text">복사본이 등록될 ca_id</div>
                </div>
                <!-- 파츠 카테고리 (5001~5041) -->
                <div class="form-group">
                    <label>파츠 종류 카테고리 (선택)</label>
                    <select id="dst-parts-ca">
                        <option value="">── 파츠 카테고리 유지 ──</option>
                        <?php foreach ($parts_cats as $pc): ?>
                        <option value="<?= htmlspecialchars($pc['ca_id']) ?>"><?= htmlspecialchars($pc['ca_name']) ?> (<?= $pc['ca_id'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <div class="help-text">비워두면 원본 파츠 카테고리 유지</div>
                </div>
            </div>

            <!-- 차종 매핑 -->
            <div class="grid-3">
                <div class="form-group">
                    <label>차종 브랜드 (item_car 매핑)</label>
                    <select id="new-brand-id" onchange="loadNewSeries()">
                        <option value="0">── 브랜드 선택 ──</option>
                        <?php foreach ($car_brands as $cb): ?>
                        <option value="<?= $cb['id'] ?>"><?= htmlspecialchars($cb['brand_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="help-text">item_car 테이블에 등록될 차종 브랜드</div>
                </div>
                <div class="form-group">
                    <label>차종 시리즈</label>
                    <select id="new-series-id" onchange="loadNewModels()">
                        <option value="0">── 브랜드 먼저 선택 ──</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>차종 모델 (선택)</label>
                    <select id="new-model-id">
                        <option value="0">── 전체 시리즈 ──</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" id="keep-car-maps" value="1">
                    &nbsp;원본 상품의 차종 매핑도 복사본에 유지 (새 매핑 추가)
                </label>
                <div class="help-text">원본이 BMW 3시리즈에 매핑되어 있을 때 복사본도 그 매핑을 유지하려면 체크</div>
            </div>
        </div>

        <!-- STEP 3: 변경 옵션 -->
        <div class="card">
            <div class="card-title"><span class="step">3</span> 복사본 수정 옵션</div>
            <div class="grid-2">
                <!-- 가격 옵션 -->
                <div>
                    <div class="form-group">
                        <label>가격 처리</label>
                        <div class="radio-group">
                            <label><input type="radio" name="price_type" value="keep" checked onchange="togglePriceInput()"> 원본 가격 유지</label>
                            <label><input type="radio" name="price_type" value="fixed" onchange="togglePriceInput()"> 고정 가격 입력</label>
                            <label><input type="radio" name="price_type" value="rate" onchange="togglePriceInput()"> 비율 조정 (%)</label>
                        </div>
                    </div>
                    <div class="form-group" id="price-input-wrap" style="display:none;">
                        <label id="price-input-label">새 가격 (원)</label>
                        <input type="number" id="price-val" placeholder="예: 35000" min="0">
                        <div class="help-text" id="price-input-help">고정 가격을 원 단위로 입력하세요.</div>
                    </div>
                </div>
                <!-- 상품명 접미사 -->
                <div>
                    <div class="form-group">
                        <label>상품명 접미사 (선택)</label>
                        <input type="text" id="name-suffix" placeholder="예: (벤츠용), [신형], 등 → 상품명 뒤에 붙음">
                        <div class="help-text">빈칸이면 원본 상품명 그대로 사용</div>
                    </div>
                </div>
            </div>

            <!-- 이미지 변경 -->
            <div class="form-group">
                <label>새 이미지 URL (선택) — 비워두면 원본 이미지 유지</label>
                <input type="text" id="new-img-url" placeholder="https://ecimg.cafe24img.com/pg/... 또는 //ecimg.cafe24img.com/...">
                <div class="help-text">CDN URL 입력 시 복사본의 it_img1~4 모두 이 URL로 교체됩니다.</div>
            </div>

            <!-- 상세페이지 변경 -->
            <div class="form-group">
                <label>새 상세페이지 HTML (선택) — 비워두면 원본 상세 유지</label>
                <textarea id="new-detail" placeholder="<!-- 상세페이지 HTML 입력 (비워두면 원본 유지) -->"></textarea>
            </div>
        </div>

        <!-- STEP 4: 실행 -->
        <div class="card">
            <div class="card-title"><span class="step">4</span> 복사 실행</div>
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <button class="btn btn-primary" id="btn-copy" onclick="doCopy()">
                    📋 선택 상품 복사 등록
                </button>
                <span id="copy-spinner" style="display:none;"><span class="spinner"></span> 처리 중...</span>
                <span id="copy-msg" style="font-size: 13px; color: #6b7280;"></span>
            </div>

            <!-- 결과 -->
            <div class="result-panel" id="copy-result" style="margin-top: 16px;">
                <div class="result-stats">
                    <div class="result-stat">
                        <div class="num" id="res-success">0</div>
                        <div class="lbl">복사 성공</div>
                    </div>
                    <div class="result-stat">
                        <div class="num fail" id="res-fail">0</div>
                        <div class="lbl">실패</div>
                    </div>
                </div>
                <table class="result-table" id="res-detail-table">
                    <thead>
                        <tr><th>원본 코드</th><th>새 상품코드</th><th>상품명</th><th>결과</th><th>메모</th></tr>
                    </thead>
                    <tbody id="res-detail-tbody"></tbody>
                </table>
            </div>
        </div>

    </div><!-- /tab-copy -->

    <!-- ══ TAB 2: 일괄 필드 수정 ══ -->
    <div id="tab-bulk-edit" class="tab-content">
        <div class="card">
            <div class="card-title">✏️ 복사된 상품 일괄 필드 수정</div>
            <div class="alert alert-warning">
                ⚠️ 이미 등록된 상품의 특정 필드를 일괄 변경합니다. 상품코드(it_id) 목록을 직접 입력하거나 복사 결과에서 자동으로 가져올 수 있습니다.
            </div>
            <div class="grid-2">
                <div>
                    <div class="form-group">
                        <label>수정할 상품코드 (it_id) — 한 줄에 하나씩</label>
                        <textarea id="bulk-it-ids" placeholder="예:&#10;11428593186_2020_1234&#10;11428593186_2021_5678" style="min-height: 140px;"></textarea>
                    </div>
                    <div class="form-group">
                        <label>수정할 필드</label>
                        <select id="bulk-field" onchange="updateBulkFieldHelp()">
                            <option value="it_price">판매가 (it_price)</option>
                            <option value="it_sell_price">실판매가 (it_sell_price)</option>
                            <option value="it_supply_price">공급가 (it_supply_price)</option>
                            <option value="it_img1">대표 이미지 URL (it_img1)</option>
                            <option value="it_img2">이미지2 (it_img2)</option>
                            <option value="it_img3">이미지3 (it_img3)</option>
                            <option value="it_img4">이미지4 (it_img4)</option>
                            <option value="it_detail">상세페이지 HTML (it_detail)</option>
                            <option value="it_maker">제조사 (it_maker)</option>
                            <option value="it_brand">브랜드 (it_brand)</option>
                            <option value="it_delivery_price">배송비 (it_delivery_price)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label id="bulk-val-label">새 값</label>
                        <textarea id="bulk-value" placeholder="새 값 입력" style="min-height: 80px;"></textarea>
                        <div class="help-text" id="bulk-val-help">가격 필드는 숫자(원 단위)만 입력하세요.</div>
                    </div>
                    <button class="btn btn-warning" onclick="doBulkUpdate()">✏️ 일괄 수정 실행</button>
                    <span id="bulk-msg" style="font-size: 13px; color: #6b7280; margin-left: 12px;"></span>
                </div>
                <div>
                    <div class="form-group">
                        <label>마지막 복사 결과 상품코드 자동 불러오기</label>
                        <button class="btn btn-outline btn-sm" onclick="loadLastCopyIds()">📋 복사 결과에서 불러오기</button>
                        <div class="help-text" style="margin-top: 6px;">복사 탭에서 등록한 상품코드를 자동으로 채웁니다.</div>
                    </div>
                    <div class="alert alert-info" style="margin-top: 20px; font-size: 12px;">
                        <strong>필드별 입력 예시:</strong><br>
                        • 판매가: <code>35000</code> (숫자, 원 단위)<br>
                        • 이미지: <code>https://ecimg.cafe24img.com/pg/...</code><br>
                        • 제조사: <code>MANN-FILTER</code><br>
                        • 브랜드: <code>만필터</code><br>
                        • 배송비: <code>3000</code> (숫자, 0=무료)
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /tab-bulk-edit -->

</div><!-- /pds-wrap -->

<!-- 미리보기 모달 -->
<div id="preview-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius: 12px; max-width: 600px; width: 90%; padding: 24px; max-height: 80vh; overflow-y:auto; position:relative;">
        <button onclick="document.getElementById('preview-modal').style.display='none';"
            style="position:absolute; top:12px; right:12px; background:#e5e7eb; border:none; border-radius:50%; width:28px; height:28px; cursor:pointer; font-size:16px;">✕</button>
        <h3 style="margin:0 0 16px; font-size:16px;">상품 미리보기</h3>
        <div id="preview-content"></div>
    </div>
</div>

<script>
/* ── 전역 상태 ── */
let lastCopyIds = [];

/* ── 탭 ── */
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});

/* ── 원본 브랜드 선택 → 하위 카테고리 로드 ── */
async function loadSrcCategories() {
    const brandCa = document.getElementById('src-brand-ca').value;
    const sel = document.getElementById('src-ca-id');
    sel.innerHTML = '<option value="">로딩 중...</option>';
    if (!brandCa) { sel.innerHTML = '<option value="">── 브랜드 먼저 선택 ──</option>'; return; }
    const data = await fetchJson('?ajax=get_categories&brand_ca=' + brandCa);
    sel.innerHTML = '<option value="">── 카테고리 선택 ──</option>';
    data.forEach(r => {
        sel.innerHTML += `<option value="${r.ca_id}">${escHtml(r.ca_name)} (${r.ca_id})</option>`;
    });
    document.getElementById('src-item-tbody').innerHTML = '<tr><td colspan="6" style="text-align:center;color:#9ca3af;padding:20px;">카테고리를 선택하면 상품이 표시됩니다.</td></tr>';
    document.getElementById('src-count').textContent = '0';
}

/* ── 원본 카테고리 선택 → 상품 목록 로드 ── */
async function loadSrcItems() {
    const caId = document.getElementById('src-ca-id').value;
    const tbody = document.getElementById('src-item-tbody');
    if (!caId) return;
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;"><span class="spinner"></span> 로딩...</td></tr>';
    const data = await fetchJson('?ajax=get_items&ca_id=' + caId);
    document.getElementById('src-count').textContent = data.length;
    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#9ca3af;padding:20px;">상품이 없습니다.</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(r => `
        <tr>
            <td><input type="checkbox" class="item-chk" value="${escHtml(r.it_id)}" onchange="updateSelCount()"></td>
            <td style="font-family:monospace;font-size:12px;">${escHtml(r.it_id)}</td>
            <td>${escHtml(r.it_name)}</td>
            <td style="text-align:right;">${Number(r.it_price).toLocaleString()}원</td>
            <td style="font-family:monospace;font-size:12px;color:#6b7280;">${escHtml(r.it_id_code)}</td>
            <td><button class="btn btn-outline btn-sm" onclick="previewItem('${escHtml(r.it_id)}')">👁</button></td>
        </tr>
    `).join('');
    updateSelCount();
}

function updateSelCount() {
    const cnt = document.querySelectorAll('.item-chk:checked').length;
    document.getElementById('sel-count-text').textContent = `선택: ${cnt}개`;
}

function selectAllItems(checked) {
    document.querySelectorAll('.item-chk').forEach(c => c.checked = checked);
    document.getElementById('chk-all').checked = checked;
    updateSelCount();
}

/* ── 대상 브랜드 → 하위 카테고리 로드 ── */
async function loadDstCategories() {
    const brandCa = document.getElementById('dst-brand-ca').value;
    const sel = document.getElementById('dst-ca-id');
    sel.innerHTML = '<option value="">로딩 중...</option>';
    if (!brandCa) { sel.innerHTML = '<option value="">── 브랜드 먼저 선택 ──</option>'; return; }
    const data = await fetchJson('?ajax=get_categories&brand_ca=' + brandCa);
    sel.innerHTML = '<option value="">── 카테고리 선택 ──</option>';
    data.forEach(r => {
        sel.innerHTML += `<option value="${r.ca_id}">${escHtml(r.ca_name)} (${r.ca_id})</option>`;
    });
}

/* ── 새 차종 브랜드 → 시리즈 ── */
async function loadNewSeries() {
    const brandId = document.getElementById('new-brand-id').value;
    const sel = document.getElementById('new-series-id');
    sel.innerHTML = '<option value="0">로딩 중...</option>';
    if (!brandId || brandId === '0') { sel.innerHTML = '<option value="0">── 브랜드 먼저 선택 ──</option>'; return; }
    const data = await fetchJson('../../partsds/car_api.php?action=series&brand_id=' + brandId);
    sel.innerHTML = '<option value="0">── 전체 브랜드 ──</option>';
    (data.series || data || []).forEach(r => {
        sel.innerHTML += `<option value="${r.id}">${escHtml(r.series_name)}</option>`;
    });
    document.getElementById('new-model-id').innerHTML = '<option value="0">── 전체 시리즈 ──</option>';
}

async function loadNewModels() {
    const seriesId = document.getElementById('new-series-id').value;
    const sel = document.getElementById('new-model-id');
    sel.innerHTML = '<option value="0">로딩 중...</option>';
    if (!seriesId || seriesId === '0') { sel.innerHTML = '<option value="0">── 전체 시리즈 ──</option>'; return; }
    const data = await fetchJson('../../partsds/car_api.php?action=models&series_id=' + seriesId);
    sel.innerHTML = '<option value="0">── 전체 시리즈 ──</option>';
    (data.models || data || []).forEach(r => {
        sel.innerHTML += `<option value="${r.id}">${escHtml(r.model_name)}</option>`;
    });
}

/* ── 가격 입력 토글 ── */
function togglePriceInput() {
    const t = document.querySelector('input[name="price_type"]:checked').value;
    const wrap = document.getElementById('price-input-wrap');
    const lbl  = document.getElementById('price-input-label');
    const help = document.getElementById('price-input-help');
    if (t === 'keep') {
        wrap.style.display = 'none';
    } else {
        wrap.style.display = 'block';
        if (t === 'fixed') { lbl.textContent = '새 가격 (원)'; help.textContent = '모든 복사본에 이 가격을 적용합니다.'; document.getElementById('price-val').placeholder = '예: 35000'; }
        else { lbl.textContent = '가격 조정 비율 (%)'; help.textContent = '+10% 인상: 10, -5% 인하: -5 입력'; document.getElementById('price-val').placeholder = '예: 10 (10% 인상)'; }
    }
}

/* ── 상품 미리보기 ── */
async function previewItem(itId) {
    const modal   = document.getElementById('preview-modal');
    const content = document.getElementById('preview-content');
    modal.style.display = 'flex';
    content.innerHTML = '<span class="spinner"></span> 로딩 중...';
    const res = await fetchJson('?ajax=get_item_detail&it_id=' + encodeURIComponent(itId));
    if (!res.ok) { content.innerHTML = '상품 정보를 불러올 수 없습니다.'; return; }
    const item = res.item;
    const cars  = (item.car_maps || []).map(m => `${m.brand_name || ''} ${m.series_name || ''} ${m.model_name || ''}`).join(', ');
    content.innerHTML = `
        <div class="preview-info">
            ${item.it_img1 ? `<img src="${escHtml(item.it_img1)}" alt="" onerror="this.style.display='none'">` : ''}
            <div class="preview-text">
                <strong>${escHtml(item.it_name)}</strong>
                <table style="font-size:12px; border-collapse:collapse; margin-top:8px;">
                    <tr><td style="padding:3px 8px 3px 0;color:#6b7280;white-space:nowrap;">상품코드</td><td style="font-family:monospace;">${escHtml(item.it_id)}</td></tr>
                    <tr><td style="padding:3px 8px 3px 0;color:#6b7280;">OEM번호</td><td style="font-family:monospace;">${escHtml(item.it_id_code)}</td></tr>
                    <tr><td style="padding:3px 8px 3px 0;color:#6b7280;">카테고리</td><td>${escHtml(item.ca_id)}</td></tr>
                    <tr><td style="padding:3px 8px 3px 0;color:#6b7280;">판매가</td><td>${Number(item.it_price).toLocaleString()}원</td></tr>
                    <tr><td style="padding:3px 8px 3px 0;color:#6b7280;">차종매핑</td><td style="font-size:11px;">${escHtml(cars) || '없음'}</td></tr>
                </table>
            </div>
        </div>
        ${item.it_detail ? `<details style="margin-top:12px;"><summary style="cursor:pointer;font-size:12px;color:#2563eb;">상세페이지 HTML 보기</summary><pre style="font-size:11px;overflow:auto;max-height:200px;background:#f8fafc;padding:12px;border-radius:6px;border:1px solid #e5e7eb;">${escHtml(item.it_detail.substring(0, 2000))}${item.it_detail.length > 2000 ? '...(이하 생략)' : ''}</pre></details>` : ''}
    `;
}

/* ── 복사 실행 ── */
async function doCopy() {
    const checked = [...document.querySelectorAll('.item-chk:checked')].map(c => c.value);
    if (!checked.length) { alert('복사할 상품을 선택하세요.'); return; }

    const dstCa = document.getElementById('dst-ca-id').value;
    // 파츠 ca_id가 지정되어 있으면 그걸 사용, 아니면 dst-ca-id 사용
    const partsCa = document.getElementById('dst-parts-ca').value;
    const finalCa = partsCa || dstCa;
    if (!finalCa) { alert('복사 대상 카테고리를 선택하세요.'); return; }

    const priceType = document.querySelector('input[name="price_type"]:checked').value;
    const priceVal  = document.getElementById('price-val').value;
    const newBrandId  = document.getElementById('new-brand-id').value;
    const newSeriesId = document.getElementById('new-series-id').value;
    const newModelId  = document.getElementById('new-model-id').value;
    const keepMaps    = document.getElementById('keep-car-maps').checked ? 1 : 0;
    const nameSuffix  = document.getElementById('name-suffix').value;
    const newImgUrl   = document.getElementById('new-img-url').value;
    const newDetail   = document.getElementById('new-detail').value;

    if (!confirm(`${checked.length}개 상품을 ca_id="${finalCa}"로 복사합니다. 계속하시겠습니까?`)) return;

    setLoading('copy', true);

    const body = new FormData();
    checked.forEach(id => body.append('source_it_ids[]', id));
    body.append('new_ca_id',      finalCa);
    body.append('new_brand_id',   newBrandId);
    body.append('new_series_id',  newSeriesId);
    body.append('new_model_id',   newModelId);
    body.append('price_type',     priceType);
    body.append('price_val',      priceVal);
    body.append('new_img_url',    newImgUrl);
    body.append('new_detail',     newDetail);
    body.append('keep_car_maps',  keepMaps);
    body.append('name_suffix',    nameSuffix);

    try {
        const res = await fetch('?ajax=do_copy', { method: 'POST', body });
        const data = await res.json();

        if (!data.ok) { alert(data.msg || '복사 중 오류가 발생했습니다.'); setLoading('copy', false); return; }

        document.getElementById('res-success').textContent = data.success;
        document.getElementById('res-fail').textContent    = data.fail;

        const tbody = document.getElementById('res-detail-tbody');
        tbody.innerHTML = data.results.map(r => `
            <tr>
                <td style="font-family:monospace;font-size:11px;">${escHtml(r.it_id)}</td>
                <td style="font-family:monospace;font-size:11px;">${escHtml(r.new_it_id || '-')}</td>
                <td>${escHtml(r.name || '-')}</td>
                <td>${r.ok ? '<span class="ok-badge">성공</span>' : '<span class="fail-badge">실패</span>'}</td>
                <td style="font-size:11px;color:#6b7280;">${escHtml(r.msg || '')}</td>
            </tr>
        `).join('');

        const panel = document.getElementById('copy-result');
        panel.style.display = 'block';
        panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        // 복사된 상품코드 저장 (일괄수정 탭에서 사용)
        lastCopyIds = data.results.filter(r => r.ok).map(r => r.new_it_id);

        document.getElementById('copy-msg').textContent = `완료: 성공 ${data.success}개, 실패 ${data.fail}개`;

    } catch (e) {
        alert('오류: ' + e.message);
    }
    setLoading('copy', false);
}

/* ── 일괄 수정 ── */
async function doBulkUpdate() {
    const raw  = document.getElementById('bulk-it-ids').value;
    const ids  = raw.split('\n').map(s => s.trim()).filter(Boolean);
    const field = document.getElementById('bulk-field').value;
    const value = document.getElementById('bulk-value').value.trim();

    if (!ids.length) { alert('수정할 상품코드를 입력하세요.'); return; }
    if (!value)      { alert('새 값을 입력하세요.'); return; }
    if (!confirm(`${ids.length}개 상품의 "${field}" 필드를 일괄 수정합니다. 계속하시겠습니까?`)) return;

    const body = new FormData();
    ids.forEach(id => body.append('it_ids[]', id));
    body.append('field', field);
    body.append('value', value);

    try {
        const res = await fetch('?ajax=bulk_update', { method: 'POST', body });
        const data = await res.json();
        if (data.ok) {
            document.getElementById('bulk-msg').textContent = `✅ ${data.count}개 상품이 수정되었습니다.`;
            document.getElementById('bulk-msg').style.color = '#16a34a';
        } else {
            document.getElementById('bulk-msg').textContent = '❌ 오류: ' + data.msg;
            document.getElementById('bulk-msg').style.color = '#dc2626';
        }
    } catch(e) { alert('오류: ' + e.message); }
}

function loadLastCopyIds() {
    if (!lastCopyIds.length) { alert('먼저 복사 탭에서 상품을 복사해주세요.'); return; }
    document.getElementById('bulk-it-ids').value = lastCopyIds.join('\n');
}

function updateBulkFieldHelp() {
    const field = document.getElementById('bulk-field').value;
    const help  = document.getElementById('bulk-val-help');
    const helps = {
        'it_price':          '숫자만 입력 (원 단위). 판매가 변경 시 실판매가도 자동 동기화.',
        'it_sell_price':     '숫자만 입력 (원 단위).',
        'it_supply_price':   '숫자만 입력 (원 단위).',
        'it_img1':           'CDN 이미지 URL 입력. 예: https://ecimg.cafe24img.com/pg/...',
        'it_img2':           'CDN 이미지 URL 입력.',
        'it_img3':           'CDN 이미지 URL 입력.',
        'it_img4':           'CDN 이미지 URL 입력.',
        'it_detail':         '상세페이지 HTML 전체 입력. 기존 상세페이지가 교체됩니다.',
        'it_maker':          '제조사명 입력. 예: MANN-FILTER, MAHLE, BOSCH',
        'it_brand':          '브랜드명 입력.',
        'it_delivery_price': '배송비 숫자 입력 (0 = 무료배송).',
    };
    help.textContent = helps[field] || '';
}

/* ── 유틸 ── */
function setLoading(key, loading) {
    document.getElementById('btn-' + key).disabled = loading;
    document.getElementById(key + '-spinner').style.display = loading ? 'inline-flex' : 'none';
    if (!loading) document.getElementById(key + '-msg').textContent = '';
}

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
