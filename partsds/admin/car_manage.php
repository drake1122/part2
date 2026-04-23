<?php
/**
 * 파츠디에스 관리자 - 차종 관리 메인
 * 경로: /partsds/admin/car_manage.php
 * 
 * 브랜드 / 시리즈 / 모델 관리 통합 페이지
 */
include_once('../../_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    exit;
}

$tab    = isset($_GET['tab'])      ? preg_replace('/[^a-z]/', '', $_GET['tab'])      : 'brand';
$action = isset($_POST['action'])  ? preg_replace('/[^a-z_]/', '', $_POST['action']) : '';
$msg    = '';

// ── POST 처리 ──
if ($action) {
    switch ($action) {

        // 브랜드 추가/수정
        case 'save_brand':
            $brand_id   = isset($_POST['brand_id'])   ? (int)$_POST['brand_id']             : 0;
            $brand_name = isset($_POST['brand_name'])  ? trim(strip_tags($_POST['brand_name']))  : '';
            $brand_name_en = isset($_POST['brand_name_en']) ? trim(strip_tags($_POST['brand_name_en'])) : '';
            $brand_order = isset($_POST['brand_order']) ? (int)$_POST['brand_order'] : 0;
            $brand_use   = isset($_POST['brand_use'])   ? 1 : 0;

            if (!$brand_name) { $msg = '브랜드명을 입력해주세요.'; break; }

            if ($brand_id) {
                sql_query("UPDATE `" . G5_TABLE_PREFIX . "car_brand` SET 
                    brand_name='" . sql_escape_string($brand_name) . "',
                    brand_name_en='" . sql_escape_string($brand_name_en) . "',
                    brand_order={$brand_order}, brand_use={$brand_use}
                    WHERE brand_id={$brand_id}");
                $msg = '브랜드가 수정되었습니다.';
            } else {
                sql_query("INSERT INTO `" . G5_TABLE_PREFIX . "car_brand` 
                    (brand_name, brand_name_en, brand_order, brand_use) VALUES
                    ('" . sql_escape_string($brand_name) . "', '" . sql_escape_string($brand_name_en) . "', {$brand_order}, {$brand_use})");
                $msg = '브랜드가 추가되었습니다.';
            }
            break;

        // 브랜드 삭제
        case 'delete_brand':
            $brand_id = isset($_POST['brand_id']) ? (int)$_POST['brand_id'] : 0;
            if ($brand_id) {
                sql_query("DELETE FROM `" . G5_TABLE_PREFIX . "car_brand`  WHERE brand_id  = {$brand_id}");
                sql_query("DELETE FROM `" . G5_TABLE_PREFIX . "car_series` WHERE brand_id  = {$brand_id}");
                sql_query("DELETE FROM `" . G5_TABLE_PREFIX . "car_model`  WHERE brand_id  = {$brand_id}");
                $msg = '브랜드 및 하위 데이터가 삭제되었습니다.';
            }
            break;

        // 시리즈 추가/수정
        case 'save_series':
            $series_id   = isset($_POST['series_id'])   ? (int)$_POST['series_id']   : 0;
            $brand_id    = isset($_POST['brand_id'])    ? (int)$_POST['brand_id']    : 0;
            $series_name = isset($_POST['series_name']) ? trim(strip_tags($_POST['series_name'])) : '';
            $series_order = isset($_POST['series_order']) ? (int)$_POST['series_order'] : 0;
            $series_use   = isset($_POST['series_use'])   ? 1 : 0;

            if (!$brand_id || !$series_name) { $msg = '브랜드와 시리즈명을 입력해주세요.'; break; }

            if ($series_id) {
                sql_query("UPDATE `" . G5_TABLE_PREFIX . "car_series` SET 
                    brand_id={$brand_id}, series_name='" . sql_escape_string($series_name) . "',
                    series_order={$series_order}, series_use={$series_use}
                    WHERE series_id={$series_id}");
                $msg = '시리즈가 수정되었습니다.';
            } else {
                sql_query("INSERT INTO `" . G5_TABLE_PREFIX . "car_series`
                    (brand_id, series_name, series_order, series_use) VALUES
                    ({$brand_id}, '" . sql_escape_string($series_name) . "', {$series_order}, {$series_use})");
                $msg = '시리즈가 추가되었습니다.';
            }
            $tab = 'series';
            break;

        // 시리즈 삭제
        case 'delete_series':
            $series_id = isset($_POST['series_id']) ? (int)$_POST['series_id'] : 0;
            if ($series_id) {
                sql_query("DELETE FROM `" . G5_TABLE_PREFIX . "car_series` WHERE series_id = {$series_id}");
                sql_query("DELETE FROM `" . G5_TABLE_PREFIX . "car_model`  WHERE series_id = {$series_id}");
                $msg = '시리즈 및 하위 모델이 삭제되었습니다.';
            }
            $tab = 'series';
            break;

        // 모델 추가/수정
        case 'save_model':
            $model_id   = isset($_POST['model_id'])   ? (int)$_POST['model_id']   : 0;
            $series_id  = isset($_POST['series_id'])  ? (int)$_POST['series_id']  : 0;
            $model_name = isset($_POST['model_name']) ? trim(strip_tags($_POST['model_name'])) : '';
            $model_year = isset($_POST['model_year']) ? trim(strip_tags($_POST['model_year'])) : '';
            $model_order = isset($_POST['model_order']) ? (int)$_POST['model_order'] : 0;
            $model_use   = isset($_POST['model_use'])   ? 1 : 0;

            if (!$series_id || !$model_name) { $msg = '시리즈와 모델명을 입력해주세요.'; break; }

            // brand_id 조회
            $s_row = sql_fetch("SELECT brand_id FROM `" . G5_TABLE_PREFIX . "car_series` WHERE series_id = {$series_id}");
            $b_id  = (int)$s_row['brand_id'];

            if ($model_id) {
                sql_query("UPDATE `" . G5_TABLE_PREFIX . "car_model` SET 
                    series_id={$series_id}, brand_id={$b_id},
                    model_name='" . sql_escape_string($model_name) . "',
                    model_year='" . sql_escape_string($model_year) . "',
                    model_order={$model_order}, model_use={$model_use}
                    WHERE model_id={$model_id}");
                $msg = '모델이 수정되었습니다.';
            } else {
                sql_query("INSERT INTO `" . G5_TABLE_PREFIX . "car_model`
                    (series_id, brand_id, model_name, model_year, model_order, model_use) VALUES
                    ({$series_id}, {$b_id}, '" . sql_escape_string($model_name) . "',
                    '" . sql_escape_string($model_year) . "', {$model_order}, {$model_use})");
                $msg = '모델이 추가되었습니다.';
            }
            $tab = 'model';
            break;

        // 모델 삭제
        case 'delete_model':
            $model_id = isset($_POST['model_id']) ? (int)$_POST['model_id'] : 0;
            if ($model_id) {
                sql_query("DELETE FROM `" . G5_TABLE_PREFIX . "car_model` WHERE model_id = {$model_id}");
                $msg = '모델이 삭제되었습니다.';
            }
            $tab = 'model';
            break;
    }
}

// 데이터 조회
$brands_list  = [];
$res = sql_query("SELECT * FROM `" . G5_TABLE_PREFIX . "car_brand` ORDER BY brand_order, brand_id");
while ($r = sql_fetch_array($res)) { $brands_list[] = $r; }

$series_list = [];
$filter_brand = isset($_GET['filter_brand']) ? (int)$_GET['filter_brand'] : 0;
$sql_s = "SELECT s.*, b.brand_name FROM `" . G5_TABLE_PREFIX . "car_series` s
          JOIN `" . G5_TABLE_PREFIX . "car_brand` b ON s.brand_id = b.brand_id";
if ($filter_brand) $sql_s .= " WHERE s.brand_id = {$filter_brand}";
$sql_s .= " ORDER BY b.brand_order, b.brand_id, s.series_order, s.series_id";
$res = sql_query($sql_s);
while ($r = sql_fetch_array($res)) { $series_list[] = $r; }

$model_list = [];
$filter_series = isset($_GET['filter_series']) ? (int)$_GET['filter_series'] : 0;
$sql_m = "SELECT m.*, s.series_name, b.brand_name 
          FROM `" . G5_TABLE_PREFIX . "car_model` m
          JOIN `" . G5_TABLE_PREFIX . "car_series` s ON m.series_id = s.series_id
          JOIN `" . G5_TABLE_PREFIX . "car_brand`  b ON m.brand_id  = b.brand_id";
if ($filter_series) $sql_m .= " WHERE m.series_id = {$filter_series}";
$sql_m .= " ORDER BY b.brand_order, b.brand_id, s.series_order, s.series_id, m.model_order, m.model_id";
$res = sql_query($sql_m);
while ($r = sql_fetch_array($res)) { $model_list[] = $r; }

$g5['title'] = '파츠디에스 - 차종 관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>
<link rel="stylesheet" href="<?php echo G5_URL; ?>/partsds/css/brand_selector.css?ver=<?php echo G5_CSS_VER; ?>">
<style>
.pds-admin-tabs { display:flex; gap:4px; margin-bottom:20px; }
.pds-admin-tabs a { padding:8px 20px; border-radius:6px 6px 0 0; background:#eee; color:#555; text-decoration:none; font-weight:600; }
.pds-admin-tabs a.active { background:#c0392b; color:#fff; }
.pds-table { width:100%; border-collapse:collapse; margin-bottom:20px; }
.pds-table th,.pds-table td { border:1px solid #ddd; padding:8px 10px; font-size:13px; }
.pds-table th { background:#f5f5f5; font-weight:700; }
.pds-form-row { display:flex; gap:10px; flex-wrap:wrap; align-items:center; margin-bottom:14px; }
.pds-form-row label { font-size:13px; font-weight:600; min-width:80px; }
.pds-form-row input[type=text] { border:1px solid #ddd; border-radius:4px; padding:6px 10px; font-size:13px; }
.pds-msg { padding:10px 14px; background:#d4edda; border-radius:6px; color:#155724; margin-bottom:14px; }
.btn-pds-add { background:#c0392b; color:#fff; border:none; border-radius:6px; padding:8px 18px; font-size:13px; font-weight:700; cursor:pointer; }
.btn-pds-del { background:#e74c3c; color:#fff; border:none; border-radius:4px; padding:3px 10px; font-size:12px; cursor:pointer; }
.badge-use { display:inline-block; padding:2px 8px; border-radius:10px; font-size:11px; }
.badge-1 { background:#d4edda; color:#155724; }
.badge-0 { background:#f8d7da; color:#721c24; }
</style>

<div id="container">
<h1 class="adm_title">파츠디에스 - 차종 관리</h1>

<?php if ($msg): ?>
<div class="pds-msg"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<div class="pds-admin-tabs">
    <a href="?tab=brand"  class="<?php if($tab=='brand')  echo 'active'; ?>">브랜드 관리</a>
    <a href="?tab=series" class="<?php if($tab=='series') echo 'active'; ?>">시리즈 관리</a>
    <a href="?tab=model"  class="<?php if($tab=='model')  echo 'active'; ?>">모델 관리</a>
</div>

<?php /* ── 브랜드 탭 ── */ if ($tab === 'brand'): ?>
<div class="adm_content_title">브랜드 목록</div>
<table class="pds-table">
    <tr><th>ID</th><th>브랜드명</th><th>영문명</th><th>순서</th><th>사용</th><th>관리</th></tr>
    <?php foreach ($brands_list as $b): ?>
    <tr>
        <td><?php echo $b['brand_id']; ?></td>
        <td><?php echo htmlspecialchars($b['brand_name']); ?></td>
        <td><?php echo htmlspecialchars($b['brand_name_en']); ?></td>
        <td><?php echo $b['brand_order']; ?></td>
        <td><span class="badge-use badge-<?php echo $b['brand_use']; ?>"><?php echo $b['brand_use'] ? '사용' : '미사용'; ?></span></td>
        <td>
            <button type="button" class="btn-pds-del"
                onclick="if(confirm('삭제시 하위 시리즈/모델도 삭제됩니다. 계속할까요?')) {
                    var f=document.getElementById('brandDelForm');
                    f.brand_id.value=<?php echo $b['brand_id']; ?>;
                    f.submit();
                }">삭제</button>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$brands_list): ?><tr><td colspan="6" style="text-align:center">등록된 브랜드가 없습니다.</td></tr><?php endif; ?>
</table>
<form method="post" action="?tab=brand">
<input type="hidden" name="action" value="delete_brand">
<input type="hidden" name="brand_id" id="brandDelFormId" value="">
</form>
<script>document.getElementById('brandDelFormId')&&(document.getElementById('brandDelFormId').form.id='brandDelForm');</script>
<form id="brandDelForm" method="post" action="?tab=brand" style="display:none"><input type="hidden" name="action" value="delete_brand"><input type="hidden" name="brand_id" value=""></form>

<div class="adm_content_title">브랜드 추가/수정</div>
<form method="post" action="?tab=brand">
<input type="hidden" name="action" value="save_brand">
<div class="pds-form-row">
    <label>브랜드명*</label>
    <input type="text" name="brand_name" placeholder="예: 벤츠" required>
    <label>영문명</label>
    <input type="text" name="brand_name_en" placeholder="예: Mercedes-Benz">
    <label>순서</label>
    <input type="text" name="brand_order" value="0" style="width:60px">
    <label><input type="checkbox" name="brand_use" value="1" checked> 사용</label>
    <button type="submit" class="btn-pds-add">+ 추가</button>
</div>
</form>

<?php /* ── 시리즈 탭 ── */ elseif ($tab === 'series'): ?>
<div class="pds-form-row">
    <label>브랜드 필터:</label>
    <select onchange="location.href='?tab=series&filter_brand='+this.value" style="padding:6px; border:1px solid #ddd; border-radius:4px;">
        <option value="">전체</option>
        <?php foreach ($brands_list as $b): ?>
        <option value="<?php echo $b['brand_id']; ?>" <?php if ($filter_brand == $b['brand_id']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($b['brand_name']); ?>
        </option>
        <?php endforeach; ?>
    </select>
</div>
<table class="pds-table">
    <tr><th>ID</th><th>브랜드</th><th>시리즈명</th><th>순서</th><th>사용</th><th>관리</th></tr>
    <?php foreach ($series_list as $s): ?>
    <tr>
        <td><?php echo $s['series_id']; ?></td>
        <td><?php echo htmlspecialchars($s['brand_name']); ?></td>
        <td><?php echo htmlspecialchars($s['series_name']); ?></td>
        <td><?php echo $s['series_order']; ?></td>
        <td><span class="badge-use badge-<?php echo $s['series_use']; ?>"><?php echo $s['series_use'] ? '사용' : '미사용'; ?></span></td>
        <td>
            <button type="button" class="btn-pds-del"
                onclick="if(confirm('삭제시 하위 모델도 삭제됩니다.')) {
                    var f=document.getElementById('seriesDelForm');
                    f.querySelector('[name=series_id]').value=<?php echo $s['series_id']; ?>;
                    f.submit();
                }">삭제</button>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$series_list): ?><tr><td colspan="6" style="text-align:center">등록된 시리즈가 없습니다.</td></tr><?php endif; ?>
</table>
<form id="seriesDelForm" method="post" action="?tab=series" style="display:none"><input type="hidden" name="action" value="delete_series"><input type="hidden" name="series_id" value=""></form>

<div class="adm_content_title">시리즈 추가</div>
<form method="post" action="?tab=series">
<input type="hidden" name="action" value="save_series">
<div class="pds-form-row">
    <label>브랜드*</label>
    <select name="brand_id" required style="padding:6px; border:1px solid #ddd; border-radius:4px;">
        <option value="">선택</option>
        <?php foreach ($brands_list as $b): ?>
        <option value="<?php echo $b['brand_id']; ?>" <?php if ($filter_brand == $b['brand_id']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($b['brand_name']); ?>
        </option>
        <?php endforeach; ?>
    </select>
    <label>시리즈명*</label>
    <input type="text" name="series_name" placeholder="예: E-Class, 3시리즈" required>
    <label>순서</label>
    <input type="text" name="series_order" value="0" style="width:60px">
    <label><input type="checkbox" name="series_use" value="1" checked> 사용</label>
    <button type="submit" class="btn-pds-add">+ 추가</button>
</div>
</form>

<?php /* ── 모델 탭 ── */ elseif ($tab === 'model'): ?>
<div class="pds-form-row">
    <label>시리즈 필터:</label>
    <select onchange="location.href='?tab=model&filter_series='+this.value" style="padding:6px; border:1px solid #ddd; border-radius:4px;">
        <option value="">전체</option>
        <?php foreach ($series_list as $s): ?>
        <option value="<?php echo $s['series_id']; ?>" <?php if ($filter_series == $s['series_id']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($s['brand_name'] . ' > ' . $s['series_name']); ?>
        </option>
        <?php endforeach; ?>
    </select>
</div>
<table class="pds-table">
    <tr><th>ID</th><th>브랜드</th><th>시리즈</th><th>모델명</th><th>연식</th><th>순서</th><th>사용</th><th>관리</th></tr>
    <?php foreach ($model_list as $m): ?>
    <tr>
        <td><?php echo $m['model_id']; ?></td>
        <td><?php echo htmlspecialchars($m['brand_name']); ?></td>
        <td><?php echo htmlspecialchars($m['series_name']); ?></td>
        <td><?php echo htmlspecialchars($m['model_name']); ?></td>
        <td><?php echo htmlspecialchars($m['model_year']); ?></td>
        <td><?php echo $m['model_order']; ?></td>
        <td><span class="badge-use badge-<?php echo $m['model_use']; ?>"><?php echo $m['model_use'] ? '사용' : '미사용'; ?></span></td>
        <td>
            <button type="button" class="btn-pds-del"
                onclick="if(confirm('모델을 삭제할까요?')) {
                    var f=document.getElementById('modelDelForm');
                    f.querySelector('[name=model_id]').value=<?php echo $m['model_id']; ?>;
                    f.submit();
                }">삭제</button>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if (!$model_list): ?><tr><td colspan="8" style="text-align:center">등록된 모델이 없습니다.</td></tr><?php endif; ?>
</table>
<form id="modelDelForm" method="post" action="?tab=model" style="display:none"><input type="hidden" name="action" value="delete_model"><input type="hidden" name="model_id" value=""></form>

<div class="adm_content_title">모델 추가</div>
<form method="post" action="?tab=model">
<input type="hidden" name="action" value="save_model">
<div class="pds-form-row">
    <label>시리즈*</label>
    <select name="series_id" required id="modelSeriesSelect" style="padding:6px; border:1px solid #ddd; border-radius:4px;">
        <option value="">선택</option>
        <?php foreach ($series_list as $s): ?>
        <option value="<?php echo $s['series_id']; ?>" <?php if ($filter_series == $s['series_id']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($s['brand_name'] . ' > ' . $s['series_name']); ?>
        </option>
        <?php endforeach; ?>
    </select>
    <label>모델명*</label>
    <input type="text" name="model_name" placeholder="예: E220d, 320i" required>
    <label>연식</label>
    <input type="text" name="model_year" placeholder="예: 2019-2023" style="width:110px">
    <label>순서</label>
    <input type="text" name="model_order" value="0" style="width:60px">
    <label><input type="checkbox" name="model_use" value="1" checked> 사용</label>
    <button type="submit" class="btn-pds-add">+ 추가</button>
</div>
</form>
<?php endif; ?>

</div><!-- #container -->
<?php include_once(G5_ADMIN_PATH.'/admin.tail.php'); ?>
