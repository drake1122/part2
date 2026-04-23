<?php
/**
 * 파츠디에스 관리자 - 상품-차종 매핑 관리
 * 경로: /partsds/admin/item_car_manage.php
 */
include_once('../../_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    exit;
}

$action = isset($_POST['action']) ? preg_replace('/[^a-z_]/', '', $_POST['action']) : '';
$msg    = '';

// POST 처리
if ($action === 'add_mapping') {
    $it_id    = isset($_POST['it_id'])    ? trim(strip_tags($_POST['it_id']))    : '';
    $brand_id  = isset($_POST['brand_id'])  ? (int)$_POST['brand_id']  : 0;
    $series_id = isset($_POST['series_id']) ? (int)$_POST['series_id'] : 0;
    $model_id  = isset($_POST['model_id'])  ? (int)$_POST['model_id']  : 0;

    if ($it_id && $brand_id) {
        // 중복 체크
        $exist = sql_fetch("SELECT id FROM `" . G5_TABLE_PREFIX . "item_car` 
                            WHERE it_id='" . sql_escape_string($it_id) . "'
                            AND brand_id={$brand_id} AND series_id={$series_id} AND model_id={$model_id}");
        if (!$exist['id']) {
            sql_query("INSERT INTO `" . G5_TABLE_PREFIX . "item_car` (it_id, brand_id, series_id, model_id) VALUES
                      ('" . sql_escape_string($it_id) . "', {$brand_id}, {$series_id}, {$model_id})");
            $msg = '매핑이 추가되었습니다.';
        } else {
            $msg = '이미 등록된 매핑입니다.';
        }
    } else {
        $msg = '상품코드와 브랜드를 선택해주세요.';
    }
}

if ($action === 'del_mapping') {
    $map_id = isset($_POST['map_id']) ? (int)$_POST['map_id'] : 0;
    if ($map_id) {
        sql_query("DELETE FROM `" . G5_TABLE_PREFIX . "item_car` WHERE id = {$map_id}");
        $msg = '매핑이 삭제되었습니다.';
    }
}

// AJAX: 시리즈 목록
if (isset($_GET['ajax']) && $_GET['ajax'] === 'series') {
    header('Content-Type: application/json');
    $bid = (int)$_GET['brand_id'];
    $out = [];
    $res = sql_query("SELECT series_id, series_name FROM `" . G5_TABLE_PREFIX . "car_series` WHERE brand_id={$bid} AND series_use=1 ORDER BY series_order, series_id");
    while ($r = sql_fetch_array($res)) { $out[] = $r; }
    echo json_encode($out);
    exit;
}
if (isset($_GET['ajax']) && $_GET['ajax'] === 'models') {
    header('Content-Type: application/json');
    $sid = (int)$_GET['series_id'];
    $out = [];
    $res = sql_query("SELECT model_id, model_name, model_year FROM `" . G5_TABLE_PREFIX . "car_model` WHERE series_id={$sid} AND model_use=1 ORDER BY model_order, model_id");
    while ($r = sql_fetch_array($res)) { $out[] = $r; }
    echo json_encode($out);
    exit;
}

// 브랜드 목록
$brands = [];
$res = sql_query("SELECT brand_id, brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_use=1 ORDER BY brand_order, brand_id");
while ($r = sql_fetch_array($res)) { $brands[] = $r; }

// 매핑 목록 (최근 200개)
$filter_it = isset($_GET['filter_it']) ? trim(strip_tags($_GET['filter_it'])) : '';
$sql_m = "SELECT ic.*, b.brand_name, s.series_name, m.model_name, it.it_name
           FROM `" . G5_TABLE_PREFIX . "item_car` ic
           LEFT JOIN `" . G5_TABLE_PREFIX . "car_brand`  b ON ic.brand_id  = b.brand_id
           LEFT JOIN `" . G5_TABLE_PREFIX . "car_series` s ON ic.series_id = s.series_id
           LEFT JOIN `" . G5_TABLE_PREFIX . "car_model`  m ON ic.model_id  = m.model_id
           LEFT JOIN `" . G5_TABLE_PREFIX . "shop_item`  it ON ic.it_id    = it.it_id";
if ($filter_it) $sql_m .= " WHERE ic.it_id LIKE '%" . sql_escape_string($filter_it) . "%' OR it.it_name LIKE '%" . sql_escape_string($filter_it) . "%'";
$sql_m .= " ORDER BY ic.id DESC LIMIT 200";
$mappings = [];
$res = sql_query($sql_m);
while ($r = sql_fetch_array($res)) { $mappings[] = $r; }

$g5['title'] = '파츠디에스 - 상품-차종 매핑';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>
<link rel="stylesheet" href="<?php echo G5_URL; ?>/partsds/css/brand_selector.css">
<style>
.pds-table { width:100%; border-collapse:collapse; font-size:13px; }
.pds-table th,.pds-table td { border:1px solid #ddd; padding:7px 10px; }
.pds-table th { background:#f5f5f5; font-weight:700; }
.pds-form-row { display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin-bottom:12px; }
.pds-form-row label { font-size:13px; font-weight:600; min-width:70px; }
.pds-form-row select, .pds-form-row input[type=text] { border:1px solid #ddd; border-radius:4px; padding:6px 10px; font-size:13px; }
.btn-pds-add { background:#c0392b; color:#fff; border:none; border-radius:6px; padding:8px 18px; font-size:13px; font-weight:700; cursor:pointer; }
.btn-pds-del { background:#e74c3c; color:#fff; border:none; border-radius:4px; padding:3px 10px; font-size:12px; cursor:pointer; }
.pds-msg { padding:10px 14px; background:#d4edda; border-radius:6px; color:#155724; margin-bottom:14px; }
</style>

<div id="container">
<h1 class="adm_title">파츠디에스 - 상품-차종 매핑 관리</h1>
<p style="color:#888; font-size:13px; margin-bottom:20px;">
    상품코드와 차종(브랜드/시리즈/모델)을 연결합니다. 연결된 차종 선택 시 해당 상품이 검색됩니다.
</p>

<?php if ($msg): ?>
<div class="pds-msg"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<div class="adm_content_title">매핑 추가</div>
<form method="post">
<input type="hidden" name="action" value="add_mapping">
<div class="pds-form-row">
    <label>상품코드*</label>
    <input type="text" name="it_id" id="manItId" placeholder="상품코드 입력" required style="width:150px;">
    <label>브랜드*</label>
    <select name="brand_id" id="manSelBrand" required onchange="manLoadSeries(this.value)">
        <option value="">선택</option>
        <?php foreach ($brands as $b): ?>
        <option value="<?php echo $b['brand_id']; ?>"><?php echo htmlspecialchars($b['brand_name']); ?></option>
        <?php endforeach; ?>
    </select>
    <label>시리즈</label>
    <select name="series_id" id="manSelSeries" disabled onchange="manLoadModels(this.value)">
        <option value="0">선택</option>
    </select>
    <label>모델</label>
    <select name="model_id" id="manSelModel" disabled>
        <option value="0">선택</option>
    </select>
    <button type="submit" class="btn-pds-add">+ 매핑 추가</button>
</div>
</form>

<div class="adm_content_title" style="margin-top:20px;">
    매핑 목록
    <form method="get" style="display:inline-flex; gap:6px; margin-left:10px;">
        <input type="text" name="filter_it" value="<?php echo htmlspecialchars($filter_it); ?>" placeholder="상품코드/명 검색" style="padding:4px 8px; border:1px solid #ddd; border-radius:4px; font-size:13px;">
        <button type="submit" style="padding:4px 10px; background:#555; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:13px;">검색</button>
    </form>
</div>
<table class="pds-table">
    <tr><th>ID</th><th>상품코드</th><th>상품명</th><th>브랜드</th><th>시리즈</th><th>모델</th><th>삭제</th></tr>
    <?php foreach ($mappings as $m): ?>
    <tr>
        <td><?php echo $m['id']; ?></td>
        <td><?php echo htmlspecialchars($m['it_id']); ?></td>
        <td><?php echo htmlspecialchars($m['it_name']); ?></td>
        <td><?php echo htmlspecialchars($m['brand_name']); ?></td>
        <td><?php echo htmlspecialchars($m['series_name']); ?></td>
        <td><?php echo htmlspecialchars($m['model_name']); ?></td>
        <td>
            <form method="post" style="display:inline">
            <input type="hidden" name="action" value="del_mapping">
            <input type="hidden" name="map_id" value="<?php echo $m['id']; ?>">
            <button type="submit" class="btn-pds-del" onclick="return confirm('삭제할까요?')">삭제</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$mappings): ?><tr><td colspan="7" style="text-align:center">매핑 데이터가 없습니다.</td></tr><?php endif; ?>
</table>
</div>

<script>
var ADMIN_URL = '<?php echo G5_URL; ?>/partsds/admin/item_car_manage.php';
function manLoadSeries(bid) {
    var sel = document.getElementById('manSelSeries');
    var selM = document.getElementById('manSelModel');
    sel.innerHTML = '<option value="0">선택</option>';
    sel.disabled  = true;
    selM.innerHTML = '<option value="0">선택</option>';
    selM.disabled  = true;
    if (!bid) return;
    fetch(ADMIN_URL + '?ajax=series&brand_id=' + bid)
        .then(function(r){ return r.json(); })
        .then(function(data){
            data.forEach(function(s){
                var o = document.createElement('option');
                o.value = s.series_id; o.textContent = s.series_name;
                sel.appendChild(o);
            });
            sel.disabled = false;
        });
}
function manLoadModels(sid) {
    var selM = document.getElementById('manSelModel');
    selM.innerHTML = '<option value="0">선택</option>';
    selM.disabled  = true;
    if (!sid) return;
    fetch(ADMIN_URL + '?ajax=models&series_id=' + sid)
        .then(function(r){ return r.json(); })
        .then(function(data){
            data.forEach(function(m){
                var o = document.createElement('option');
                o.value = m.model_id;
                o.textContent = m.model_name + (m.model_year ? ' (' + m.model_year + ')' : '');
                selM.appendChild(o);
            });
            selM.disabled = false;
        });
}
</script>
<?php include_once(G5_ADMIN_PATH.'/admin.tail.php'); ?>
