<?php
/**
 * 파츠디에스 - 파츠 마스터 통합 관리
 * 경로: /partsds/admin/parts_master.php
 *
 * 기능:
 *  1. 파츠 마스터 목록/등록/수정/삭제
 *  2. 가격/이미지 변경 시 연결된 모든 상품 자동 동기화
 *  3. 파츠 종류별 일괄 가격 변경
 *  4. 상품-마스터 연결 현황 조회
 */
include_once('../../_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    exit;
}

define('PDS_MASTER_TABLE', G5_TABLE_PREFIX . 'pds_parts_master');
define('PDS_SYNC_LOG',     G5_TABLE_PREFIX . 'pds_parts_sync_log');

// ── AJAX 응답 ─────────────────────────────────────────────
$ajax = isset($_GET['ajax']) ? $_GET['ajax'] : '';

// 마스터 삭제 AJAX
if ($ajax === 'delete' && isset($_POST['pm_id'])) {
    header('Content-Type: application/json');
    $pm_id = (int)$_POST['pm_id'];
    sql_query("DELETE FROM `" . PDS_MASTER_TABLE . "` WHERE pm_id = {$pm_id}");
    echo json_encode(['ok' => true]);
    exit;
}

// 단일 필드 즉시 수정 AJAX (인라인 에디트)
if ($ajax === 'quick_update' && isset($_POST['pm_id'])) {
    header('Content-Type: application/json');
    $pm_id = (int)$_POST['pm_id'];
    $field = preg_replace('/[^a-z_]/', '', $_POST['field'] ?? '');
    $value = trim($_POST['value'] ?? '');
    $allowed = ['pm_price', 'pm_supply', 'pm_img_url', 'pm_img_add', 'pm_memo', 'pm_sync_yn', 'pm_qty_unit'];

    if (!in_array($field, $allowed) || !$pm_id) {
        echo json_encode(['ok' => false, 'msg' => '잘못된 요청']);
        exit;
    }

    // 이전 값 가져오기
    $old = sql_fetch("SELECT {$field}, pm_part_code FROM `" . PDS_MASTER_TABLE . "` WHERE pm_id = {$pm_id}");
    $old_val = $old[$field] ?? '';
    $part_code = $old['pm_part_code'] ?? '';

    // 업데이트
    $val_escaped = sql_escape_string($value);
    sql_query("UPDATE `" . PDS_MASTER_TABLE . "` SET {$field} = '{$val_escaped}' WHERE pm_id = {$pm_id}");

    // 동기화 대상 필드면 상품 테이블 반영
    $sync_count = 0;
    $master = sql_fetch("SELECT * FROM `" . PDS_MASTER_TABLE . "` WHERE pm_id = {$pm_id}");

    if ($master['pm_sync_yn'] === 'Y') {
        $sync_count = pds_sync_to_items($master, $field, $value);
    }

    // 로그
    if ($old_val != $value) {
        sql_query("INSERT INTO `" . PDS_SYNC_LOG . "`
            (pm_id, pm_part_code, changed_col, old_val, new_val, sync_count, admin_id)
            VALUES ({$pm_id}, '{$part_code}', '{$field}',
                    '" . sql_escape_string($old_val) . "',
                    '" . sql_escape_string($value) . "', {$sync_count}, '" . sql_escape_string($member['mb_id']) . "')");
    }

    echo json_encode(['ok' => true, 'sync_count' => $sync_count, 'field' => $field]);
    exit;
}

// 일괄 가격 변경 AJAX
if ($ajax === 'bulk_price' && isset($_POST['parts_ca'])) {
    header('Content-Type: application/json');
    $parts_ca  = preg_replace('/[^0-9]/', '', $_POST['parts_ca']);
    $new_price = (int)$_POST['new_price'];
    $rate      = (float)($_POST['rate'] ?? 0); // 퍼센트 변경 (+10, -5 등)
    $mode      = $_POST['mode'] ?? 'fixed'; // fixed=고정가격, rate=비율변경

    if (!$parts_ca) {
        echo json_encode(['ok' => false, 'msg' => '파츠 종류를 선택하세요']);
        exit;
    }

    $masters = [];
    $res = sql_query("SELECT * FROM `" . PDS_MASTER_TABLE . "` WHERE pm_parts_ca = '{$parts_ca}' AND pm_sync_yn='Y'");
    while ($r = sql_fetch_array($res)) { $masters[] = $r; }

    $total_sync = 0;
    foreach ($masters as $m) {
        if ($mode === 'rate' && $rate != 0) {
            $apply_price = (int)round($m['pm_price'] * (1 + $rate / 100));
        } else {
            $apply_price = $new_price;
        }
        sql_query("UPDATE `" . PDS_MASTER_TABLE . "` SET pm_price = {$apply_price} WHERE pm_id = {$m['pm_id']}");
        $m['pm_price'] = $apply_price;
        $total_sync += pds_sync_to_items($m, 'pm_price', $apply_price);
    }

    echo json_encode(['ok' => true, 'masters' => count($masters), 'items' => $total_sync]);
    exit;
}

// ── 저장 처리 ─────────────────────────────────────────────
if (isset($_POST['act']) && $_POST['act'] === 'save') {
    $pm_id      = (int)($_POST['pm_id'] ?? 0);
    $part_code  = trim(strip_tags($_POST['pm_part_code'] ?? ''));
    $parts_ca   = preg_replace('/[^0-9]/', '', $_POST['pm_parts_ca'] ?? '');
    $name       = trim(strip_tags($_POST['pm_name'] ?? ''));
    $brand      = trim(strip_tags($_POST['pm_brand'] ?? ''));
    $price      = (int)$_POST['pm_price'];
    $supply     = (int)$_POST['pm_supply'];
    $img_url    = trim($_POST['pm_img_url'] ?? '');
    $img_add    = trim($_POST['pm_img_add'] ?? '');
    $detail     = $_POST['pm_detail_html'] ?? '';
    $sync_yn    = ($_POST['pm_sync_yn'] ?? 'Y') === 'Y' ? 'Y' : 'N';
    $qty_unit   = trim(strip_tags($_POST['pm_qty_unit'] ?? '1EA'));
    $memo       = trim(strip_tags($_POST['pm_memo'] ?? ''));

    if (!$part_code || !$parts_ca || !$name) {
        alert('부품번호, 파츠종류, 파츠명은 필수입력 항목입니다.');
    }

    $esc_part  = sql_escape_string($part_code);
    $esc_name  = sql_escape_string($name);
    $esc_brand = sql_escape_string($brand);
    $esc_img   = sql_escape_string($img_url);
    $esc_add   = sql_escape_string($img_add);
    $esc_det   = sql_escape_string($detail);
    $esc_unit  = sql_escape_string($qty_unit);
    $esc_memo  = sql_escape_string($memo);

    if ($pm_id) {
        // 수정 - 이전값 백업
        $old = sql_fetch("SELECT * FROM `" . PDS_MASTER_TABLE . "` WHERE pm_id = {$pm_id}");
        sql_query("UPDATE `" . PDS_MASTER_TABLE . "` SET pm_part_code='{$esc_part}', pm_parts_ca='{$parts_ca}', pm_name='{$esc_name}', pm_brand='{$esc_brand}', pm_price={$price}, pm_supply={$supply}, pm_img_url='{$esc_img}', pm_img_add='{$esc_add}', pm_detail_html='{$esc_det}', pm_sync_yn='{$sync_yn}', pm_qty_unit='{$esc_unit}', pm_memo='{$esc_memo}' WHERE pm_id = {$pm_id}");

        // 동기화 처리
        $master_new = sql_fetch("SELECT * FROM `" . PDS_MASTER_TABLE . "` WHERE pm_id = {$pm_id}");
        $sync_total = 0;
        if ($sync_yn === 'Y') {
            // 변경된 필드만 동기화
            $sync_fields = [];
            if ($old['pm_price']   != $price)   $sync_fields[] = 'pm_price';
            if ($old['pm_img_url'] != $img_url)  $sync_fields[] = 'pm_img_url';
            if ($old['pm_img_add'] != $img_add)  $sync_fields[] = 'pm_img_add';
            if ($old['pm_detail_html'] != $detail) $sync_fields[] = 'pm_detail_html';

            foreach ($sync_fields as $sf) {
                $cnt = pds_sync_to_items($master_new, $sf, $master_new[$sf]);
                $sync_total += $cnt;
                sql_query("INSERT INTO `" . PDS_SYNC_LOG . "`
                    (pm_id, pm_part_code, changed_col, old_val, new_val, sync_count, admin_id)
                    VALUES ({$pm_id}, '{$esc_part}', '{$sf}',
                            '" . sql_escape_string($old[$sf]) . "',
                            '" . sql_escape_string($master_new[$sf]) . "',
                            {$cnt}, '" . sql_escape_string($member['mb_id']) . "')");
            }
        }
        $save_msg = "수정 완료. " . ($sync_total > 0 ? "연결 상품 {$sync_total}개 동기화됨." : "");
    } else {
        // 신규 등록
        sql_query("INSERT INTO `" . PDS_MASTER_TABLE . "`
            (pm_part_code, pm_parts_ca, pm_name, pm_brand, pm_price, pm_supply,
             pm_img_url, pm_img_add, pm_detail_html, pm_sync_yn, pm_qty_unit, pm_memo)
            VALUES ('{$esc_part}','{$parts_ca}','{$esc_name}','{$esc_brand}',
                    {$price},{$supply},'{$esc_img}','{$esc_add}','{$esc_det}',
                    '{$sync_yn}','{$esc_unit}','{$esc_memo}')");
        $pm_id = sql_insert_id();
        $save_msg = "등록 완료.";
    }

    alert($save_msg, './parts_master.php');
    exit;
}

// ── 파츠 종류 목록 ────────────────────────────────────────
$parts_ca_list = [
    '5001'=>'오일필터', '5002'=>'에어필터', '5003'=>'에어컨필터', '5004'=>'연료필터',
    '5005'=>'미션오일필터', '5006'=>'오일필터하우징', '5007'=>'미션오일', '5008'=>'엔진오일',
    '5009'=>'부동액', '5010'=>'브레이크오일', '5011'=>'브레이크디스크', '5012'=>'브레이크패드',
    '5013'=>'브레이크센서', '5014'=>'브레이크캘리퍼', '5015'=>'엔진마운트', '5016'=>'미션마운트',
    '5017'=>'V벨트', '5018'=>'댐퍼풀리', '5019'=>'벨트텐셔너', '5020'=>'워터펌프',
    '5021'=>'써머스탯', '5022'=>'라디에이터 관련', '5023'=>'알터네이터', '5024'=>'에어컨콤프레셔',
    '5025'=>'스타트모터', '5026'=>'흡기 매니폴드 관련', '5027'=>'고압펌프', '5028'=>'인젝터',
    '5029'=>'와이퍼', '5030'=>'드라이브샤프트', '5031'=>'쇼바', '5032'=>'유니버셜조인트',
    '5033'=>'허브베어링', '5034'=>'휠볼트', '5035'=>'프로펠러샤프트', '5036'=>'하체부품',
    '5037'=>'산소센서', '5038'=>'점화플러그(예열) 배선', '5039'=>'라이트모듈 관련',
    '5040'=>'자동차용품 관련', '5041'=>'기타 관련',
];

// ── 목록 조회 ─────────────────────────────────────────────
$filter_ca    = isset($_GET['fca'])    ? preg_replace('/[^0-9]/', '', $_GET['fca']) : '';
$filter_brand = isset($_GET['fbrand']) ? trim(strip_tags($_GET['fbrand'])) : '';
$search       = isset($_GET['sq'])     ? trim(strip_tags($_GET['sq']))     : '';
$page         = max(1, (int)($_GET['page'] ?? 1));
$rows         = 50;

$where = "WHERE 1";
if ($filter_ca)    $where .= " AND pm_parts_ca = '" . sql_escape_string($filter_ca) . "'";
if ($filter_brand) $where .= " AND pm_brand LIKE '%" . sql_escape_string($filter_brand) . "%'";
if ($search)       $where .= " AND (pm_part_code LIKE '%" . sql_escape_string($search) . "%' OR pm_name LIKE '%" . sql_escape_string($search) . "%')";

$total = sql_fetch("SELECT COUNT(*) AS cnt FROM `" . PDS_MASTER_TABLE . "` {$where}");
$total_count = (int)$total['cnt'];
$total_pages = max(1, ceil($total_count / $rows));
$from = ($page - 1) * $rows;

$list = [];
$res = sql_query("SELECT * FROM `" . PDS_MASTER_TABLE . "` {$where} ORDER BY pm_parts_ca, pm_id LIMIT {$from}, {$rows}");
while ($r = sql_fetch_array($res)) { $list[] = $r; }

// 편집 대상
$edit = [];
if (isset($_GET['edit_id'])) {
    $eid = (int)$_GET['edit_id'];
    $edit = sql_fetch("SELECT * FROM `" . PDS_MASTER_TABLE . "` WHERE pm_id = {$eid}");
}

// 연결 상품 수 캐시 (표시용)
$linked_counts = [];
if ($list) {
    $codes = array_unique(array_map(fn($r)=>$r['pm_part_code'], $list));
    $in = implode("','", array_map('sql_escape_string', $codes));
    $res2 = sql_query("SELECT it_id_code, COUNT(*) AS cnt FROM `" . G5_TABLE_PREFIX . "shop_item`
                        WHERE it_id_code IN ('{$in}') GROUP BY it_id_code");
    while ($r = sql_fetch_array($res2)) {
        $linked_counts[$r['it_id_code']] = $r['cnt'];
    }
}

// ── 함수: 마스터 → 상품 동기화 ────────────────────────────
function pds_sync_to_items($master, $changed_field, $new_value) {
    $part_code = sql_escape_string($master['pm_part_code']);
    $count = 0;

    // 해당 부품번호(자체코드)를 가진 모든 상품 조회
    $res = sql_query("SELECT it_id FROM `" . G5_TABLE_PREFIX . "shop_item` WHERE it_id_code = '{$part_code}'");

    while ($row = sql_fetch_array($res)) {
        $it_id = sql_escape_string($row['it_id']);
        $update_sql = '';

        switch ($changed_field) {
            case 'pm_price':
                $price = (int)$new_value;
                $update_sql = "UPDATE `" . G5_TABLE_PREFIX . "shop_item`
                               SET it_price = {$price}, it_sell_price = {$price}
                               WHERE it_id = '{$it_id}'";
                break;

            case 'pm_supply':
                $supply = (int)$new_value;
                $update_sql = "UPDATE `" . G5_TABLE_PREFIX . "shop_item`
                               SET it_supply_price = {$supply}
                               WHERE it_id = '{$it_id}'";
                break;

            case 'pm_img_url':
                $img = sql_escape_string($new_value);
                $update_sql = "UPDATE `" . G5_TABLE_PREFIX . "shop_item`
                               SET it_img1 = '{$img}', it_img2 = '{$img}',
                                   it_img3 = '{$img}', it_img4 = '{$img}' WHERE it_id = '{$it_id}'";
                break;

            case 'pm_img_add':
                $img_add = sql_escape_string($new_value);
                $update_sql = "UPDATE `" . G5_TABLE_PREFIX . "shop_item`
                               SET it_img5 = '{$img_add}' WHERE it_id = '{$it_id}'";
                break;

            case 'pm_detail_html':
                $detail = sql_escape_string($new_value);
                $update_sql = "UPDATE `" . G5_TABLE_PREFIX . "shop_item`
                               SET it_detail = '{$detail}' WHERE it_id = '{$it_id}'";
                break;
        }

        if ($update_sql) {
            sql_query($update_sql);
            $count++;
        }
    }
    return $count;
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>파츠 마스터 관리 - 파츠디에스</title>
<link rel="stylesheet" href="<?php echo G5_URL; ?>/partsds/css/parts_category.css">
<style>
:root { --pds-primary:#222; --pds-accent:#e53935; --pds-green:#2e7d32; --pds-bg:#f5f5f5; }
* { box-sizing:border-box; margin:0; padding:0; }
body { font-family:'Noto Sans KR',sans-serif; font-size:13px; background:var(--pds-bg); color:#333; }

/* 레이아웃 */
.pds-admin-wrap { max-width:1400px; margin:0 auto; padding:20px 16px; }
.pds-admin-title { font-size:22px; font-weight:800; border-left:5px solid var(--pds-accent); padding-left:12px; margin-bottom:20px; }
.pds-admin-title small { font-size:13px; font-weight:400; color:#888; margin-left:8px; }

/* 탭 */
.pds-tabs { display:flex; gap:4px; margin-bottom:20px; border-bottom:2px solid #ddd; }
.pds-tab { padding:8px 20px; cursor:pointer; border-radius:4px 4px 0 0; border:1px solid #ddd; border-bottom:none; background:#f9f9f9; font-size:13px; text-decoration:none; color:#555; }
.pds-tab.active { background:#fff; border-bottom:2px solid #fff; margin-bottom:-2px; color:#222; font-weight:700; }

/* 검색/필터 바 */
.pds-filter-bar { display:flex; gap:8px; flex-wrap:wrap; align-items:center; background:#fff; padding:12px 16px; border-radius:6px; border:1px solid #e0e0e0; margin-bottom:16px; }
.pds-filter-bar select, .pds-filter-bar input[type=text] { padding:6px 10px; border:1px solid #ccc; border-radius:4px; font-size:12px; }
.pds-filter-bar select { min-width:140px; }
.btn { display:inline-flex; align-items:center; gap:4px; padding:6px 14px; border-radius:4px; border:none; cursor:pointer; font-size:12px; font-weight:600; }
.btn-primary { background:var(--pds-primary); color:#fff; }
.btn-primary:hover { background:#444; }
.btn-danger  { background:var(--pds-accent); color:#fff; }
.btn-success { background:var(--pds-green); color:#fff; }
.btn-gray    { background:#777; color:#fff; }
.btn-sm { padding:4px 10px; font-size:11px; }
.btn-xs { padding:2px 7px; font-size:11px; }

/* 통계 카드 */
.pds-stat-row { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:20px; }
.pds-stat-card { background:#fff; border-radius:8px; padding:16px 20px; border:1px solid #e8e8e8; }
.pds-stat-card .num { font-size:28px; font-weight:800; color:var(--pds-accent); }
.pds-stat-card .lbl { font-size:12px; color:#888; margin-top:4px; }

/* 테이블 */
.pds-table-wrap { background:#fff; border-radius:8px; border:1px solid #e0e0e0; overflow:hidden; }
.pds-table { width:100%; border-collapse:collapse; font-size:12px; }
.pds-table th { background:#f0f0f0; padding:10px 8px; text-align:center; border-bottom:2px solid #ddd; white-space:nowrap; font-weight:700; }
.pds-table td { padding:8px; border-bottom:1px solid #eee; vertical-align:middle; }
.pds-table tr:hover td { background:#fafafa; }
.pds-table .td-code { font-family:monospace; font-size:11px; color:#555; }
.pds-table .td-price { text-align:right; font-weight:700; color:var(--pds-accent); }
.pds-table .td-count { text-align:center; }
.badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:10px; font-weight:700; }
.badge-on  { background:#e8f5e9; color:#2e7d32; }
.badge-off { background:#fce4ec; color:#c62828; }

/* 인라인 에디트 */
.editable { cursor:pointer; border-bottom:1px dashed #aaa; padding:1px 3px; }
.editable:hover { background:#fff9c4; }
.edit-input { width:100%; padding:4px; border:1px solid var(--pds-accent); border-radius:3px; font-size:12px; }

/* 폼 */
.pds-form-wrap { background:#fff; border-radius:8px; border:1px solid #e0e0e0; padding:24px; margin-bottom:20px; }
.pds-form-title { font-size:16px; font-weight:700; margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid #eee; }
.form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.form-group { display:flex; flex-direction:column; gap:4px; }
.form-group.full { grid-column:1/-1; }
.form-group label { font-size:12px; font-weight:600; color:#555; }
.form-group input, .form-group select, .form-group textarea {
    padding:8px 10px; border:1px solid #ccc; border-radius:4px; font-size:13px; font-family:inherit;
}
.form-group textarea { resize:vertical; min-height:80px; }
.form-hint { font-size:11px; color:#999; margin-top:2px; }

/* 일괄 가격 변경 패널 */
.pds-bulk-panel { background:#fff3e0; border:1px solid #ffcc80; border-radius:8px; padding:16px 20px; margin-bottom:20px; }
.pds-bulk-panel h3 { font-size:14px; font-weight:700; margin-bottom:12px; color:#e65100; }
.bulk-row { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }

/* 동기화 알림 */
.sync-notice { display:inline-block; background:#e3f2fd; color:#1565c0; border-radius:4px; padding:3px 8px; font-size:11px; margin-left:6px; }

/* 페이징 */
.pds-paging { display:flex; justify-content:center; gap:4px; margin-top:16px; }
.pds-paging a, .pds-paging span {
    display:inline-block; padding:5px 10px; border:1px solid #ddd; border-radius:3px; font-size:12px; color:#555; text-decoration:none;
}
.pds-paging .cur { background:var(--pds-primary); color:#fff; border-color:var(--pds-primary); }

/* 로그 테이블 */
.log-table { font-size:11px; }
.log-table td { padding:5px 6px; }

/* 반응형 */
@media(max-width:900px) {
    .pds-stat-row { grid-template-columns:1fr 1fr; }
    .form-grid { grid-template-columns:1fr; }
}
</style>
</head>
<body>
<div class="pds-admin-wrap">

<h1 class="pds-admin-title">
    파츠 마스터 관리
    <small>부품번호 기준 가격·이미지 관리 → 연결 상품 자동 동기화</small>
</h1>

<!-- 탭 -->
<div class="pds-tabs">
    <a href="?tab=list"        class="pds-tab <?php echo (!isset($_GET['tab'])||$_GET['tab']=='list') ? 'active':''; ?>">📋 마스터 목록</a>
    <a href="?tab=new"         class="pds-tab <?php echo (($_GET['tab']??'')=='new') ? 'active':''; ?>">➕ 마스터 등록</a>
    <a href="?tab=bulk"        class="pds-tab <?php echo (($_GET['tab']??'')=='bulk') ? 'active':''; ?>">🔄 일괄 가격변경</a>
    <a href="?tab=log"         class="pds-tab <?php echo (($_GET['tab']??'')=='log') ? 'active':''; ?>">📝 동기화 로그</a>
    <a href="./bulk_copy.php"  class="pds-tab">📦 타제조사 복사등록</a>
    <a href="./parts_excel.php" class="pds-tab">📊 엑셀 일괄등록</a>
    <a href="./stock_alert.php" class="pds-tab">🔔 재고 알림설정</a>
</div>

<?php
$tab = $_GET['tab'] ?? 'list';

// ── 통계 ─────────────────────────────────────────────────
$stat_master  = sql_fetch("SELECT COUNT(*) AS cnt FROM `" . PDS_MASTER_TABLE . "`");
$stat_sync_on = sql_fetch("SELECT COUNT(*) AS cnt FROM `" . PDS_MASTER_TABLE . "` WHERE pm_sync_yn='Y'");
$stat_linked  = sql_fetch("SELECT COUNT(DISTINCT pm.pm_part_code) AS cnt FROM `" . PDS_MASTER_TABLE . "` pm
    INNER JOIN `" . G5_TABLE_PREFIX . "shop_item` si ON pm.pm_part_code = si.it_id_code");
$stat_log = sql_fetch("SELECT COUNT(*) AS cnt FROM `" . PDS_SYNC_LOG . "` WHERE DATE(sync_dt) = CURDATE()");
?>

<!-- 통계 카드 -->
<?php if ($tab === 'list'): ?>
<div class="pds-stat-row">
    <div class="pds-stat-card">
        <div class="num"><?php echo number_format($stat_master['cnt']); ?></div>
        <div class="lbl">등록된 파츠 마스터</div>
    </div>
    <div class="pds-stat-card">
        <div class="num" style="color:#2e7d32"><?php echo number_format($stat_sync_on['cnt']); ?></div>
        <div class="lbl">동기화 활성 마스터</div>
    </div>
    <div class="pds-stat-card">
        <div class="num" style="color:#1565c0"><?php echo number_format($stat_linked['cnt']); ?></div>
        <div class="lbl">연결된 고유 부품번호</div>
    </div>
    <div class="pds-stat-card">
        <div class="num" style="color:#6a1b9a"><?php echo number_format($stat_log['cnt']); ?></div>
        <div class="lbl">오늘 동기화 건수</div>
    </div>
</div>
<?php endif; ?>

<?php if ($tab === 'list'): ?>
<!-- ── 목록 탭 ──────────────────────────────────────── -->
<div class="pds-filter-bar">
    <form method="get" style="display:contents">
        <input type="hidden" name="tab" value="list">
        <select name="fca">
            <option value="">전체 파츠 종류</option>
            <?php foreach ($parts_ca_list as $cid => $cname): ?>
            <option value="<?php echo $cid; ?>" <?php echo $filter_ca==$cid?'selected':''; ?>>
                <?php echo htmlspecialchars($cname); ?>
            </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="sq" value="<?php echo htmlspecialchars($search); ?>" placeholder="부품번호 / 파츠명 검색" style="min-width:200px">
        <button type="submit" class="btn btn-primary">🔍 검색</button>
        <a href="?tab=list" class="btn btn-gray">초기화</a>
        <span style="margin-left:auto; color:#888; font-size:11px">총 <?php echo number_format($total_count); ?>건</span>
    </form>
</div>

<div class="pds-table-wrap">
<table class="pds-table" id="masterTable">
<thead>
<tr>
    <th width="40">No</th>
    <th width="100">파츠 종류</th>
    <th width="140">부품번호</th>
    <th>파츠명</th>
    <th width="80">제조사</th>
    <th width="90">판매가</th>
    <th width="90">공급가</th>
    <th width="160">이미지 URL</th>
    <th width="60">동기화</th>
    <th width="70">연결상품</th>
    <th width="110">관리</th>
</tr>
</thead>
<tbody>
<?php if (!$list): ?>
<tr><td colspan="11" style="text-align:center;padding:30px;color:#999">등록된 파츠 마스터가 없습니다. <a href="?tab=new">새로 등록하기</a></td></tr>
<?php endif; ?>
<?php foreach ($list as $i => $pm): ?>
<tr id="row-<?php echo $pm['pm_id']; ?>">
    <td style="text-align:center;color:#aaa"><?php echo $from + $i + 1; ?></td>
    <td style="text-align:center">
        <span style="font-size:11px;background:#f0f0f0;padding:2px 6px;border-radius:3px">
            <?php echo htmlspecialchars($parts_ca_list[$pm['pm_parts_ca']] ?? $pm['pm_parts_ca']); ?>
        </span>
    </td>
    <td class="td-code">
        <span class="editable" data-id="<?php echo $pm['pm_id']; ?>" data-field="pm_part_code"
              onclick="inlineEdit(this)">
            <?php echo htmlspecialchars($pm['pm_part_code']); ?>
        </span>
    </td>
    <td>
        <span class="editable" data-id="<?php echo $pm['pm_id']; ?>" data-field="pm_name"
              onclick="inlineEdit(this)">
            <?php echo htmlspecialchars($pm['pm_name']); ?>
        </span>
        <?php if ($pm['pm_memo']): ?>
        <span style="font-size:10px;color:#aaa;margin-left:4px" title="<?php echo htmlspecialchars($pm['pm_memo']); ?>">📝</span>
        <?php endif; ?>
    </td>
    <td style="text-align:center;font-size:11px"><?php echo htmlspecialchars($pm['pm_brand']); ?></td>
    <td class="td-price">
        <span class="editable" data-id="<?php echo $pm['pm_id']; ?>" data-field="pm_price"
              onclick="inlineEdit(this)">
            <?php echo number_format($pm['pm_price']); ?>
        </span>원
    </td>
    <td class="td-price" style="color:#555">
        <span class="editable" data-id="<?php echo $pm['pm_id']; ?>" data-field="pm_supply"
              onclick="inlineEdit(this)">
            <?php echo number_format($pm['pm_supply']); ?>
        </span>원
    </td>
    <td style="font-size:10px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
        <span class="editable" data-id="<?php echo $pm['pm_id']; ?>" data-field="pm_img_url"
              onclick="inlineEdit(this)" title="<?php echo htmlspecialchars($pm['pm_img_url']); ?>">
            <?php echo htmlspecialchars(mb_strimwidth($pm['pm_img_url'], 0, 40, '...')); ?>
        </span>
        <?php if ($pm['pm_img_url']): ?>
        <a href="<?php echo htmlspecialchars($pm['pm_img_url']); ?>" target="_blank" style="font-size:10px;color:#888">🖼</a>
        <?php endif; ?>
    </td>
    <td class="td-count">
        <span class="badge <?php echo $pm['pm_sync_yn']==='Y'?'badge-on':'badge-off'; ?>"
              style="cursor:pointer" onclick="toggleSync(<?php echo $pm['pm_id']; ?>, '<?php echo $pm['pm_sync_yn']==='Y'?'N':'Y'; ?>', this)">
            <?php echo $pm['pm_sync_yn']==='Y'?'ON':'OFF'; ?>
        </span>
    </td>
    <td class="td-count">
        <?php
        $lc = $linked_counts[$pm['pm_part_code']] ?? 0;
        echo "<span style='font-weight:700;color:" . ($lc>0?'#1565c0':'#bbb') . "'>{$lc}개</span>";
        ?>
    </td>
    <td style="text-align:center;white-space:nowrap">
        <a href="?tab=new&edit_id=<?php echo $pm['pm_id']; ?>" class="btn btn-gray btn-xs">수정</a>
        <button class="btn btn-danger btn-xs" onclick="deleteMaster(<?php echo $pm['pm_id']; ?>, '<?php echo htmlspecialchars(addslashes($pm['pm_name'])); ?>')">삭제</button>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>

<!-- 페이징 -->
<?php if ($total_pages > 1): ?>
<div class="pds-paging">
    <?php for ($p = max(1,$page-5); $p <= min($total_pages,$page+5); $p++): ?>
    <?php $purl = "?tab=list&fca={$filter_ca}&fbrand={$filter_brand}&sq={$search}&page={$p}"; ?>
    <?php if ($p === $page): ?>
        <span class="cur"><?php echo $p; ?></span>
    <?php else: ?>
        <a href="<?php echo $purl; ?>"><?php echo $p; ?></a>
    <?php endif; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php elseif ($tab === 'new'): ?>
<!-- ── 등록/수정 폼 ───────────────────────────────────── -->
<?php
$is_edit = !empty($edit);
$f = $edit ?: [];
?>
<div class="pds-form-wrap">
    <div class="pds-form-title"><?php echo $is_edit ? '파츠 마스터 수정' : '파츠 마스터 등록'; ?>
        <span class="sync-notice">💡 저장 시 동기화ON인 경우 연결된 모든 상품에 자동 반영</span>
    </div>

    <form method="post" action="?tab=new<?php echo $is_edit ? "&edit_id={$edit['pm_id']}" : ''; ?>">
    <input type="hidden" name="act" value="save">
    <?php if ($is_edit): ?>
    <input type="hidden" name="pm_id" value="<?php echo $edit['pm_id']; ?>">
    <?php endif; ?>

    <div class="form-grid">
        <div class="form-group">
            <label>부품번호 (자체상품코드) *</label>
            <input type="text" name="pm_part_code" value="<?php echo htmlspecialchars($f['pm_part_code']??''); ?>"
                   placeholder="예: 11428593186" required>
            <span class="form-hint">그누보드 상품의 '자체 상품코드'와 동일하게 입력</span>
        </div>
        <div class="form-group">
            <label>파츠 종류 *</label>
            <select name="pm_parts_ca" required>
                <option value="">선택</option>
                <?php foreach ($parts_ca_list as $cid => $cname): ?>
                <option value="<?php echo $cid; ?>" <?php echo (($f['pm_parts_ca']??'')==$cid)?'selected':''; ?>>
                    [<?php echo $cid; ?>] <?php echo htmlspecialchars($cname); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>파츠명 *</label>
            <input type="text" name="pm_name" value="<?php echo htmlspecialchars($f['pm_name']??''); ?>"
                   placeholder="예: 오일필터" required>
        </div>
        <div class="form-group">
            <label>제조사/브랜드 코드</label>
            <input type="text" name="pm_brand" value="<?php echo htmlspecialchars($f['pm_brand']??''); ?>"
                   placeholder="예: M00000BM (BMW)">
            <span class="form-hint">그누보드 쇼핑몰 제조사 코드</span>
        </div>
        <div class="form-group">
            <label>판매가 (원) ★ 변경 시 연결 상품 자동 반영</label>
            <input type="number" name="pm_price" value="<?php echo (int)($f['pm_price']??0); ?>"
                   placeholder="0" min="0" step="100">
        </div>
        <div class="form-group">
            <label>공급가 (원)</label>
            <input type="number" name="pm_supply" value="<?php echo (int)($f['pm_supply']??0); ?>"
                   placeholder="0" min="0" step="100">
        </div>
        <div class="form-group">
            <label>대표 이미지 URL ★ 변경 시 연결 상품 자동 반영</label>
            <input type="text" name="pm_img_url" value="<?php echo htmlspecialchars($f['pm_img_url']??''); ?>"
                   placeholder="//ecimg.cafe24img.com/.../파일명.png">
            <span class="form-hint">외부 이미지 URL 직접 입력 (트래픽 절감 - 이미지 서버 재사용)</span>
        </div>
        <div class="form-group">
            <label>추가(상세상단) 이미지 URL</label>
            <input type="text" name="pm_img_add" value="<?php echo htmlspecialchars($f['pm_img_add']??''); ?>"
                   placeholder="//ecimg.cafe24img.com/.../파일명.jpg">
        </div>
        <div class="form-group">
            <label>포장단위</label>
            <input type="text" name="pm_qty_unit" value="<?php echo htmlspecialchars($f['pm_qty_unit']??'1EA'); ?>"
                   placeholder="1EA">
        </div>
        <div class="form-group">
            <label>동기화 여부</label>
            <select name="pm_sync_yn">
                <option value="Y" <?php echo (($f['pm_sync_yn']??'Y')==='Y')?'selected':''; ?>>Y - 활성 (가격/이미지 변경 시 자동 반영)</option>
                <option value="N" <?php echo (($f['pm_sync_yn']??'Y')==='N')?'selected':''; ?>>N - 비활성 (수동 관리)</option>
            </select>
        </div>
        <div class="form-group full">
            <label>공통 상세 HTML ★ 변경 시 연결 상품 자동 반영</label>
            <textarea name="pm_detail_html" rows="6"
                      placeholder="<img src='...'> 형태의 상세 이미지 HTML..."><?php echo htmlspecialchars($f['pm_detail_html']??''); ?></textarea>
            <span class="form-hint">이미지 링크 방식으로 입력 → 서버 트래픽 절감 / CDN 이미지 재사용</span>
        </div>
        <div class="form-group full">
            <label>관리 메모</label>
            <input type="text" name="pm_memo" value="<?php echo htmlspecialchars($f['pm_memo']??''); ?>"
                   placeholder="내부 메모 (화면에 미표시)">
        </div>
    </div>

    <?php if ($is_edit): ?>
    <!-- 연결 상품 현황 -->
    <?php
    $linked = [];
    $res_l = sql_query("SELECT si.it_id, si.it_name, sc.ca_name, si.it_price FROM `" . G5_TABLE_PREFIX . "shop_item` si LEFT JOIN `" . G5_TABLE_PREFIX . "shop_category` sc ON si.ca_id = sc.ca_id WHERE si.it_id_code = '" . sql_escape_string($edit['pm_part_code']) . "' ORDER BY sc.ca_id, si.it_id LIMIT 100");
    while ($r = sql_fetch_array($res_l)) $linked[] = $r;
    ?>
    <?php if ($linked): ?>
    <div style="margin-top:20px;padding-top:16px;border-top:1px solid #eee">
        <h4 style="font-size:13px;margin-bottom:10px;color:#555">
            🔗 연결된 상품 (<?php echo count($linked); ?>개)
            <span style="font-size:11px;color:#888;font-weight:400"> - 가격/이미지 변경 시 아래 상품들이 모두 자동 업데이트됩니다</span>
        </h4>
        <table class="pds-table" style="font-size:11px">
            <tr><th>상품코드</th><th>상품명</th><th>분류명</th><th>현재 판매가</th></tr>
            <?php foreach ($linked as $lk): ?>
            <tr>
                <td class="td-code"><?php echo htmlspecialchars($lk['it_id']); ?></td>
                <td><?php echo htmlspecialchars($lk['it_name']); ?></td>
                <td style="color:#888"><?php echo htmlspecialchars($lk['ca_name']); ?></td>
                <td class="td-price"><?php echo number_format($lk['it_price']); ?>원</td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <div style="margin-top:20px;display:flex;gap:8px">
        <button type="submit" class="btn btn-primary">💾 저장 (연결상품 자동 동기화)</button>
        <a href="?tab=list" class="btn btn-gray">취소</a>
    </div>
    </form>
</div>

<?php elseif ($tab === 'bulk'): ?>
<!-- ── 일괄 가격변경 탭 ──────────────────────────────── -->
<div class="pds-bulk-panel">
    <h3>🔄 파츠 종류별 일괄 가격 변경</h3>
    <p style="font-size:12px;color:#666;margin-bottom:14px">
        선택한 파츠 종류의 마스터 가격을 일괄 변경합니다. <strong>동기화 ON인 마스터만</strong> 연결 상품에 자동 반영됩니다.
    </p>

    <div class="bulk-row">
        <select id="bulkCa" style="padding:8px 12px;border:1px solid #ccc;border-radius:4px;font-size:13px;min-width:180px">
            <option value="">파츠 종류 선택</option>
            <?php foreach ($parts_ca_list as $cid => $cname): ?>
            <option value="<?php echo $cid; ?>"><?php echo htmlspecialchars($cname); ?></option>
            <?php endforeach; ?>
        </select>

        <select id="bulkMode" style="padding:8px;border:1px solid #ccc;border-radius:4px;font-size:13px">
            <option value="fixed">고정가격으로 변경</option>
            <option value="rate">비율(%)로 변경</option>
        </select>

        <div id="fixedInput">
            <input type="number" id="bulkPrice" placeholder="새 판매가 (원)" min="0" step="100"
                   style="padding:8px;border:1px solid #ccc;border-radius:4px;width:150px;font-size:13px">
        </div>
        <div id="rateInput" style="display:none">
            <input type="number" id="bulkRate" placeholder="변경률 (예: +10, -5)" step="0.1"
                   style="padding:8px;border:1px solid #ccc;border-radius:4px;width:150px;font-size:13px"> %
        </div>

        <button class="btn btn-danger" onclick="doBulkPrice()">⚡ 일괄 변경 실행</button>
    </div>
    <div id="bulkResult" style="margin-top:12px;font-size:12px;color:#333"></div>
</div>

<!-- 파츠별 마스터 현황 -->
<div class="pds-table-wrap">
<table class="pds-table">
<thead>
<tr>
    <th>파츠 종류</th>
    <th>마스터 수</th>
    <th>동기화ON</th>
    <th>연결 상품 수</th>
    <th>최저가</th>
    <th>최고가</th>
    <th>평균가</th>
</tr>
</thead>
<tbody>
<?php
foreach ($parts_ca_list as $cid => $cname) {
    $row = sql_fetch("SELECT COUNT(*) AS cnt, SUM(pm_sync_yn='Y') AS sync_on, MIN(pm_price) AS min_p, MAX(pm_price) AS max_p, AVG(pm_price) AS avg_p FROM `" . PDS_MASTER_TABLE . "` WHERE pm_parts_ca = '{$cid}'");
    if (!$row['cnt']) continue;
    $linked_cnt = sql_fetch("SELECT COUNT(*) AS cnt FROM `" . G5_TABLE_PREFIX . "shop_item` si
                              INNER JOIN `" . PDS_MASTER_TABLE . "` pm ON si.it_id_code = pm.pm_part_code
                              WHERE pm.pm_parts_ca = '{$cid}'");
    echo "<tr>";
    echo "<td><strong>" . htmlspecialchars($cname) . "</strong> <span style='font-size:10px;color:#aaa'>[{$cid}]</span></td>";
    echo "<td style='text-align:center'>{$row['cnt']}개</td>";
    echo "<td style='text-align:center'><span class='badge badge-on'>{$row['sync_on']}</span></td>";
    echo "<td style='text-align:center;font-weight:700;color:#1565c0'>" . number_format($linked_cnt['cnt']) . "개</td>";
    echo "<td style='text-align:right'>" . number_format($row['min_p']) . "원</td>";
    echo "<td style='text-align:right'>" . number_format($row['max_p']) . "원</td>";
    echo "<td style='text-align:right;color:#888'>" . number_format((int)$row['avg_p']) . "원</td>";
    echo "</tr>";
}
?>
</tbody>
</table>
</div>

<?php elseif ($tab === 'log'): ?>
<!-- ── 동기화 로그 탭 ────────────────────────────────── -->
<div class="pds-table-wrap">
<table class="pds-table log-table">
<thead>
<tr>
    <th>시간</th>
    <th>부품번호</th>
    <th>변경 항목</th>
    <th>이전값</th>
    <th>새값</th>
    <th>동기화 상품수</th>
    <th>처리자</th>
</tr>
</thead>
<tbody>
<?php
$logs = [];
$res_lg = sql_query("SELECT * FROM `" . PDS_SYNC_LOG . "` ORDER BY log_id DESC LIMIT 200");
while ($r = sql_fetch_array($res_lg)) $logs[] = $r;
if (!$logs): ?>
<tr><td colspan="7" style="text-align:center;padding:20px;color:#aaa">로그가 없습니다</td></tr>
<?php else:
    foreach ($logs as $lg):
    $col_label = [
        'pm_price'=>'판매가', 'pm_supply'=>'공급가',
        'pm_img_url'=>'이미지URL', 'pm_img_add'=>'추가이미지',
        'pm_detail_html'=>'상세HTML', 'pm_sync_yn'=>'동기화여부',
    ][$lg['changed_col']] ?? $lg['changed_col'];
    $old_disp = mb_strimwidth($lg['old_val'], 0, 40, '...');
    $new_disp = mb_strimwidth($lg['new_val'], 0, 40, '...');
    if ($lg['changed_col'] === 'pm_price') {
        $old_disp = number_format($lg['old_val']) . '원';
        $new_disp = number_format($lg['new_val']) . '원';
    }
    ?>
    <tr>
        <td style="white-space:nowrap;color:#888"><?php echo date('m/d H:i', strtotime($lg['sync_dt'])); ?></td>
        <td class="td-code"><?php echo htmlspecialchars($lg['pm_part_code']); ?></td>
        <td style="font-weight:700;color:#e53935"><?php echo htmlspecialchars($col_label); ?></td>
        <td style="color:#999;text-decoration:line-through"><?php echo htmlspecialchars($old_disp); ?></td>
        <td style="color:#2e7d32;font-weight:700"><?php echo htmlspecialchars($new_disp); ?></td>
        <td style="text-align:center">
            <span style="font-weight:700;color:#1565c0"><?php echo number_format($lg['sync_count']); ?>개</span>
        </td>
        <td style="color:#888"><?php echo htmlspecialchars($lg['admin_id']); ?></td>
    </tr>
    <?php endforeach; endif; ?>
</tbody>
</table>
</div>
<?php endif; ?>

</div><!-- //pds-admin-wrap -->

<script>
// ── 인라인 에디트 ─────────────────────────────────────────
function inlineEdit(el) {
    if (el.querySelector('input')) return;
    var original = el.textContent.trim().replace(/[,원]/g, '');
    var field = el.dataset.field;
    var id = el.dataset.id;

    var input = document.createElement('input');
    input.className = 'edit-input';
    input.value = original;
    input.style.width = (el.offsetWidth + 60) + 'px';

    el.textContent = '';
    el.appendChild(input);
    input.focus();

    function save() {
        var newVal = input.value.trim();
        if (newVal === original) { el.textContent = original; return; }

        fetch('?ajax=quick_update', {
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body: 'pm_id='+id+'&field='+field+'&value='+encodeURIComponent(newVal)
        })
        .then(r=>r.json())
        .then(function(res) {
            if (res.ok) {
                // 가격 포맷
                var disp = newVal;
                if (field === 'pm_price' || field === 'pm_supply') {
                    disp = parseInt(newVal).toLocaleString();
                }
                el.textContent = disp;
                if (res.sync_count > 0) {
                    var notice = document.createElement('span');
                    notice.className = 'sync-notice';
                    notice.textContent = '✓ ' + res.sync_count + '개 동기화';
                    el.parentNode.appendChild(notice);
                    setTimeout(function(){ notice.remove(); }, 3000);
                }
            } else {
                alert(res.msg || '오류');
                el.textContent = original;
            }
        });
    }

    input.addEventListener('blur', save);
    input.addEventListener('keydown', function(e){
        if (e.key === 'Enter') { e.preventDefault(); save(); }
        if (e.key === 'Escape') { el.textContent = original; }
    });
}

// ── 동기화 토글 ───────────────────────────────────────────
function toggleSync(id, newVal, badgeEl) {
    if (!confirm('동기화 상태를 ' + (newVal==='Y'?'ON':'OFF') + '으로 변경하시겠습니까?')) return;
    fetch('?ajax=quick_update', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body: 'pm_id='+id+'&field=pm_sync_yn&value='+newVal
    })
    .then(r=>r.json())
    .then(function(res) {
        if (res.ok) {
            badgeEl.textContent = newVal==='Y' ? 'ON' : 'OFF';
            badgeEl.className = 'badge ' + (newVal==='Y' ? 'badge-on' : 'badge-off');
            badgeEl.onclick = function(){ toggleSync(id, newVal==='Y'?'N':'Y', badgeEl); };
        }
    });
}

// ── 마스터 삭제 ───────────────────────────────────────────
function deleteMaster(id, name) {
    if (!confirm('[' + name + '] 마스터를 삭제하시겠습니까?\n연결 상품의 데이터는 유지됩니다.')) return;
    fetch('?ajax=delete', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'pm_id='+id
    })
    .then(r=>r.json())
    .then(function(res){
        if(res.ok) {
            var row = document.getElementById('row-'+id);
            if (row) row.remove();
        }
    });
}

// ── 일괄 가격 변경 ─────────────────────────────────────────
document.getElementById('bulkMode') && document.getElementById('bulkMode').addEventListener('change', function(){
    document.getElementById('fixedInput').style.display = this.value==='fixed' ? '' : 'none';
    document.getElementById('rateInput').style.display  = this.value==='rate'  ? '' : 'none';
});

function doBulkPrice() {
    var ca = document.getElementById('bulkCa').value;
    var mode = document.getElementById('bulkMode').value;
    if (!ca) { alert('파츠 종류를 선택하세요'); return; }

    var body = 'parts_ca=' + ca + '&mode=' + mode;
    if (mode === 'fixed') {
        var price = parseInt(document.getElementById('bulkPrice').value);
        if (!price || price <= 0) { alert('가격을 입력하세요'); return; }
        body += '&new_price=' + price;
        if (!confirm('선택한 파츠 종류의 마스터 가격을 ' + price.toLocaleString() + '원으로 일괄 변경합니다.\n연결된 상품도 모두 업데이트됩니다.\n\n계속하시겠습니까?')) return;
    } else {
        var rate = parseFloat(document.getElementById('bulkRate').value);
        if (isNaN(rate)) { alert('변경률을 입력하세요'); return; }
        body += '&rate=' + rate;
        if (!confirm('선택한 파츠 종류의 마스터 가격을 ' + (rate > 0 ? '+' : '') + rate + '% 변경합니다.\n\n계속하시겠습니까?')) return;
    }

    var btn = event.target;
    btn.disabled = true; btn.textContent = '처리 중...';

    fetch('?ajax=bulk_price', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body: body
    })
    .then(r=>r.json())
    .then(function(res){
        btn.disabled = false; btn.textContent = '⚡ 일괄 변경 실행';
        if (res.ok) {
            document.getElementById('bulkResult').innerHTML =
                '<span style="color:#2e7d32;font-weight:700">✅ 완료! 마스터 ' + res.masters + '개, 연결 상품 ' + res.items + '개 업데이트됨</span>';
        } else {
            document.getElementById('bulkResult').innerHTML =
                '<span style="color:red">❌ ' + (res.msg||'오류') + '</span>';
        }
    });
}
</script>
</body>
</html>
