<?php
/**
 * file: /partsds/admin/pds_car_db.php
 * 차량 DB 관리 — 브랜드 / 시리즈 / 모델 CRUD
 *
 * 직접 접근: if (!defined('_EYOOM_IS_ADMIN_')) { include_once('../../_common.php'); }
 * 아이윰 어드민: adm/eyoom_admin/core/partsds/pds_car_db.php 브릿지를 통해 include_once
 *
 * 테이블 구조 (자동 생성):
 *   {prefix}car_brand  : id, name, use_yn, sort_order, created_at
 *   {prefix}car_series : id, brand_id, name, use_yn, sort_order, created_at
 *   {prefix}car_model  : id, series_id, name, use_yn, sort_order, created_at
 */

// ── 직접 접근 시 그누보드 공통 로드 ─────────────────────────────────
if (!defined('_EYOOM_IS_ADMIN_')) {
    include_once('../../_common.php');
}

// ── 관리자 권한 체크 ─────────────────────────────────────────────────
if (!$is_admin) {
    alert('관리자 권한이 없습니다.', G5_URL);
    exit;
}

// ── 테이블명 ─────────────────────────────────────────────────────────
$tbl_brand  = G5_TABLE_PREFIX . 'car_brand';
$tbl_series = G5_TABLE_PREFIX . 'car_series';
$tbl_model  = G5_TABLE_PREFIX . 'car_model';

// ── 테이블 자동 생성 ─────────────────────────────────────────────────
$sqls = array();

$sqls[] = "CREATE TABLE IF NOT EXISTS `{$tbl_brand}` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100) NOT NULL DEFAULT '',
  `use_yn`      TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order`  INT(11) NOT NULL DEFAULT 0,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_use_sort` (`use_yn`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sqls[] = "CREATE TABLE IF NOT EXISTS `{$tbl_series}` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `brand_id`    INT(11) NOT NULL DEFAULT 0,
  `name`        VARCHAR(100) NOT NULL DEFAULT '',
  `use_yn`      TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order`  INT(11) NOT NULL DEFAULT 0,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_brand_use` (`brand_id`, `use_yn`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sqls[] = "CREATE TABLE IF NOT EXISTS `{$tbl_model}` (
  `id`          INT(11) NOT NULL AUTO_INCREMENT,
  `series_id`   INT(11) NOT NULL DEFAULT 0,
  `name`        VARCHAR(150) NOT NULL DEFAULT '',
  `use_yn`      TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order`  INT(11) NOT NULL DEFAULT 0,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_series_use` (`series_id`, `use_yn`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

foreach ($sqls as $sql) {
    sql_query($sql);
}

// ── AJAX 처리 ────────────────────────────────────────────────────────
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json; charset=utf-8');
    $ajax_action = $_GET['ajax'];

    // CSRF / 어드민 재확인
    if (!$is_admin) { echo json_encode(array('ok'=>false,'msg'=>'권한 없음')); exit; }

    // ─── 브랜드 ───────────────────────────────────────────────────────
    if ($ajax_action === 'save_brand') {
        $id       = isset($_POST['id'])   ? (int)$_POST['id']   : 0;
        $name     = isset($_POST['name']) ? trim($_POST['name']) : '';
        $use_yn   = isset($_POST['use_yn'])   ? (int)$_POST['use_yn']   : 1;
        $sort_ord = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;

        if ($name === '') { echo json_encode(array('ok'=>false,'msg'=>'브랜드명을 입력하세요')); exit; }

        if ($id > 0) {
            sql_query("UPDATE `{$tbl_brand}` SET `name`='".sql_escape_string($name)."', `use_yn`={$use_yn}, `sort_order`={$sort_ord} WHERE `id`={$id}");
        } else {
            sql_query("INSERT INTO `{$tbl_brand}` (`name`,`use_yn`,`sort_order`) VALUES ('".sql_escape_string($name)."',{$use_yn},{$sort_ord})");
            $id = mysql_insert_id();
        }
        echo json_encode(array('ok'=>true,'id'=>$id,'msg'=>'저장 완료'));
        exit;
    }

    if ($ajax_action === 'delete_brand') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id < 1) { echo json_encode(array('ok'=>false,'msg'=>'잘못된 요청')); exit; }
        // 하위 시리즈/모델 확인
        $cnt = sql_fetch("SELECT COUNT(*) AS cnt FROM `{$tbl_series}` WHERE `brand_id`={$id}");
        if ($cnt['cnt'] > 0) { echo json_encode(array('ok'=>false,'msg'=>'하위 시리즈가 있어 삭제할 수 없습니다.')); exit; }
        sql_query("DELETE FROM `{$tbl_brand}` WHERE `id`={$id}");
        echo json_encode(array('ok'=>true,'msg'=>'삭제 완료'));
        exit;
    }

    // ─── 시리즈 ───────────────────────────────────────────────────────
    if ($ajax_action === 'save_series') {
        $id       = isset($_POST['id'])       ? (int)$_POST['id']       : 0;
        $brand_id = isset($_POST['brand_id']) ? (int)$_POST['brand_id'] : 0;
        $name     = isset($_POST['name'])     ? trim($_POST['name'])     : '';
        $use_yn   = isset($_POST['use_yn'])   ? (int)$_POST['use_yn']   : 1;
        $sort_ord = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;

        if ($name === '' || $brand_id < 1) { echo json_encode(array('ok'=>false,'msg'=>'브랜드와 시리즈명을 입력하세요')); exit; }

        if ($id > 0) {
            sql_query("UPDATE `{$tbl_series}` SET `brand_id`={$brand_id}, `name`='".sql_escape_string($name)."', `use_yn`={$use_yn}, `sort_order`={$sort_ord} WHERE `id`={$id}");
        } else {
            sql_query("INSERT INTO `{$tbl_series}` (`brand_id`,`name`,`use_yn`,`sort_order`) VALUES ({$brand_id},'".sql_escape_string($name)."',{$use_yn},{$sort_ord})");
            $id = mysql_insert_id();
        }
        echo json_encode(array('ok'=>true,'id'=>$id,'msg'=>'저장 완료'));
        exit;
    }

    if ($ajax_action === 'delete_series') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id < 1) { echo json_encode(array('ok'=>false,'msg'=>'잘못된 요청')); exit; }
        $cnt = sql_fetch("SELECT COUNT(*) AS cnt FROM `{$tbl_model}` WHERE `series_id`={$id}");
        if ($cnt['cnt'] > 0) { echo json_encode(array('ok'=>false,'msg'=>'하위 모델이 있어 삭제할 수 없습니다.')); exit; }
        sql_query("DELETE FROM `{$tbl_series}` WHERE `id`={$id}");
        echo json_encode(array('ok'=>true,'msg'=>'삭제 완료'));
        exit;
    }

    // ─── 모델 ────────────────────────────────────────────────────────
    if ($ajax_action === 'save_model') {
        $id        = isset($_POST['id'])        ? (int)$_POST['id']        : 0;
        $series_id = isset($_POST['series_id']) ? (int)$_POST['series_id'] : 0;
        $name      = isset($_POST['name'])      ? trim($_POST['name'])      : '';
        $use_yn    = isset($_POST['use_yn'])    ? (int)$_POST['use_yn']    : 1;
        $sort_ord  = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;

        if ($name === '' || $series_id < 1) { echo json_encode(array('ok'=>false,'msg'=>'시리즈와 모델명을 입력하세요')); exit; }

        if ($id > 0) {
            sql_query("UPDATE `{$tbl_model}` SET `series_id`={$series_id}, `name`='".sql_escape_string($name)."', `use_yn`={$use_yn}, `sort_order`={$sort_ord} WHERE `id`={$id}");
        } else {
            sql_query("INSERT INTO `{$tbl_model}` (`series_id`,`name`,`use_yn`,`sort_order`) VALUES ({$series_id},'".sql_escape_string($name)."',{$use_yn},{$sort_ord})");
            $id = mysql_insert_id();
        }
        echo json_encode(array('ok'=>true,'id'=>$id,'msg'=>'저장 완료'));
        exit;
    }

    if ($ajax_action === 'delete_model') {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id < 1) { echo json_encode(array('ok'=>false,'msg'=>'잘못된 요청')); exit; }
        sql_query("DELETE FROM `{$tbl_model}` WHERE `id`={$id}");
        echo json_encode(array('ok'=>true,'msg'=>'삭제 완료'));
        exit;
    }

    // 시리즈/모델 ajax 목록 로드 (탭 전환 시)
    if ($ajax_action === 'list_series') {
        $bid = isset($_GET['brand_id']) ? (int)$_GET['brand_id'] : 0;
        $rows = array();
        $q = sql_query("SELECT s.*, b.name AS brand_name FROM `{$tbl_series}` s LEFT JOIN `{$tbl_brand}` b ON b.id=s.brand_id" . ($bid ? " WHERE s.brand_id={$bid}" : '') . " ORDER BY s.brand_id ASC, s.sort_order ASC, s.name ASC LIMIT 500");
        while ($r = sql_fetch_array($q)) $rows[] = $r;
        echo json_encode(array('ok'=>true,'rows'=>$rows));
        exit;
    }

    if ($ajax_action === 'list_models') {
        $sid = isset($_GET['series_id']) ? (int)$_GET['series_id'] : 0;
        $rows = array();
        $q = sql_query("SELECT m.*, s.name AS series_name, b.name AS brand_name FROM `{$tbl_model}` m LEFT JOIN `{$tbl_series}` s ON s.id=m.series_id LEFT JOIN `{$tbl_brand}` b ON b.id=s.brand_id" . ($sid ? " WHERE m.series_id={$sid}" : '') . " ORDER BY m.series_id ASC, m.sort_order ASC, m.name ASC LIMIT 1000");
        while ($r = sql_fetch_array($q)) $rows[] = $r;
        echo json_encode(array('ok'=>true,'rows'=>$rows));
        exit;
    }

    echo json_encode(array('ok'=>false,'msg'=>'알 수 없는 요청'));
    exit;
}

// ── 통계 ─────────────────────────────────────────────────────────────
$stat_brand  = sql_fetch("SELECT COUNT(*) AS cnt FROM `{$tbl_brand}`");
$stat_series = sql_fetch("SELECT COUNT(*) AS cnt FROM `{$tbl_series}`");
$stat_model  = sql_fetch("SELECT COUNT(*) AS cnt FROM `{$tbl_model}`");

// ── 브랜드 목록 (전체) ────────────────────────────────────────────────
$brands = array();
$bq = sql_query("SELECT * FROM `{$tbl_brand}` ORDER BY sort_order ASC, name ASC");
while ($r = sql_fetch_array($bq)) $brands[] = $r;

// ── 현재 탭 ──────────────────────────────────────────────────────────
$cur_tab = isset($_GET['tab']) ? $_GET['tab'] : 'brand';
if (!in_array($cur_tab, array('brand','series','model'))) $cur_tab = 'brand';

// ── 시리즈 목록 (탭=series 일 때, 선택된 brand_id 기준) ──────────────
$filter_brand = isset($_GET['fbrand']) ? (int)$_GET['fbrand'] : 0;
$series_list  = array();
if ($cur_tab === 'series' || $cur_tab === 'model') {
    $sw = $filter_brand ? " WHERE s.brand_id={$filter_brand}" : '';
    $sq = sql_query("SELECT s.*, b.name AS brand_name FROM `{$tbl_series}` s LEFT JOIN `{$tbl_brand}` b ON b.id=s.brand_id{$sw} ORDER BY s.brand_id ASC, s.sort_order ASC, s.name ASC LIMIT 500");
    while ($r = sql_fetch_array($sq)) $series_list[] = $r;
}

// ── 모델 목록 (탭=model) ─────────────────────────────────────────────
$filter_series = isset($_GET['fseries']) ? (int)$_GET['fseries'] : 0;
$model_list    = array();
if ($cur_tab === 'model') {
    $mw = $filter_series ? " WHERE m.series_id={$filter_series}" : ($filter_brand ? " WHERE s.brand_id={$filter_brand}" : '');
    $jn = " LEFT JOIN `{$tbl_series}` s ON s.id=m.series_id LEFT JOIN `{$tbl_brand}` b ON b.id=s.brand_id";
    $mq = sql_query("SELECT m.*, s.name AS series_name, b.name AS brand_name FROM `{$tbl_model}` m{$jn}{$mw} ORDER BY m.series_id ASC, m.sort_order ASC, m.name ASC LIMIT 1000");
    while ($r = sql_fetch_array($mq)) $model_list[] = $r;
}

// ── 현재 페이지 URL (탭 전환용) ─────────────────────────────────────
$pds_cardb_url = G5_URL . '/partsds/admin/pds_car_db.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>차량 DB 관리 — PartsDS 관리자</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Noto Sans KR', sans-serif; background: #f4f6fb; color: #333; font-size: 14px; }
.pds-admin-wrap { max-width: 1400px; margin: 0 auto; padding: 24px 20px; }
.pds-admin-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
.pds-admin-header h1 { font-size: 1.4rem; font-weight: 700; color: #1a1a2e; }
.stat-cards { display: flex; gap: 16px; margin-bottom: 24px; flex-wrap: wrap; }
.stat-card { background: #fff; border-radius: 8px; padding: 18px 24px; border: 1px solid #e8e8e8; min-width: 140px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,.04); }
.stat-card .cnt { font-size: 2rem; font-weight: 700; color: #b8860b; }
.stat-card .lbl { font-size: 0.82rem; color: #888; margin-top: 4px; }
.tab-nav { display: flex; gap: 4px; margin-bottom: 0; border-bottom: 2px solid #b8860b; }
.tab-nav a { display: inline-block; padding: 10px 20px; font-size: 0.9rem; font-weight: 600; color: #555; text-decoration: none; border-radius: 6px 6px 0 0; border: 1px solid transparent; border-bottom: none; transition: all .18s; }
.tab-nav a:hover { background: #fffbf0; color: #b8860b; }
.tab-nav a.active { background: #fff; border-color: #e0d4b8; border-bottom-color: #fff; color: #b8860b; }
.tab-content { background: #fff; border: 1px solid #e0d4b8; border-top: none; border-radius: 0 0 8px 8px; }
.pds-main-grid { display: grid; grid-template-columns: 1fr 340px; gap: 0; }
.pds-list-pane { padding: 20px; border-right: 1px solid #f0f0f0; }
.pds-form-pane { padding: 20px; background: #fafafa; }
.pds-filter-bar { display: flex; gap: 8px; margin-bottom: 14px; align-items: center; flex-wrap: wrap; }
.pds-filter-bar select { height: 32px; padding: 0 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.85rem; }
.pds-filter-bar button { height: 32px; padding: 0 14px; background: #555; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 0.83rem; }
table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
th { background: #f5f5f5; border-bottom: 2px solid #e0e0e0; padding: 9px 10px; text-align: left; font-weight: 600; color: #444; white-space: nowrap; }
td { padding: 8px 10px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
tr:hover td { background: #fffbf0; }
.badge-use { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 0.77rem; font-weight: 600; }
.badge-on  { background: #e8f5e9; color: #2e7d32; }
.badge-off { background: #fce4ec; color: #c62828; }
.btn-edit, .btn-del { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 0.78rem; cursor: pointer; border: none; font-weight: 600; }
.btn-edit { background: #e8f0fe; color: #1a73e8; }
.btn-edit:hover { background: #1a73e8; color: #fff; }
.btn-del  { background: #fce4ec; color: #c62828; margin-left: 4px; }
.btn-del:hover  { background: #c62828; color: #fff; }
/* Form */
.form-section { margin-bottom: 20px; }
.form-section h3 { font-size: 0.95rem; font-weight: 700; color: #333; margin-bottom: 14px; padding-bottom: 8px; border-bottom: 1px solid #e8e8e8; }
.form-row { margin-bottom: 12px; }
.form-row label { display: block; font-size: 0.83rem; font-weight: 600; color: #555; margin-bottom: 4px; }
.form-row input[type=text], .form-row input[type=number], .form-row select { width: 100%; height: 34px; padding: 0 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.88rem; }
.form-row-inline { display: flex; gap: 8px; }
.form-row-inline > div { flex: 1; }
.form-check { display: flex; align-items: center; gap: 6px; height: 34px; }
.form-check input { width: auto; }
.btn-submit { width: 100%; height: 38px; background: #b8860b; color: #fff; border: none; border-radius: 4px; font-size: 0.92rem; font-weight: 700; cursor: pointer; transition: background .18s; margin-top: 4px; }
.btn-submit:hover { background: #9a6f00; }
.btn-reset { width: 100%; height: 34px; background: #eee; color: #555; border: 1px solid #ccc; border-radius: 4px; font-size: 0.85rem; cursor: pointer; margin-top: 6px; }
.msg-box { padding: 10px 14px; border-radius: 4px; font-size: 0.88rem; margin-bottom: 12px; display: none; }
.msg-ok  { background: #e8f5e9; border: 1px solid #a5d6a7; color: #2e7d32; }
.msg-err { background: #fce4ec; border: 1px solid #f48fb1; color: #c62828; }
.pds-no-data { text-align: center; color: #aaa; padding: 30px; }
@media (max-width: 900px) {
    .pds-main-grid { grid-template-columns: 1fr; }
    .pds-form-pane { border-top: 1px solid #f0f0f0; }
}
</style>
</head>
<body>
<div class="pds-admin-wrap">

    <!-- 헤더 -->
    <div class="pds-admin-header">
        <i class="fas fa-car-side fa-lg" style="color:#b8860b;"></i>
        <h1>차량 DB 관리</h1>
        <span style="font-size:0.82rem;color:#888;margin-left:auto;">PartsDS › 차량 DB 관리</span>
    </div>

    <!-- 통계 카드 -->
    <div class="stat-cards">
        <div class="stat-card">
            <div class="cnt"><?php echo number_format($stat_brand['cnt']); ?></div>
            <div class="lbl"><i class="fas fa-trademark"></i> 브랜드</div>
        </div>
        <div class="stat-card">
            <div class="cnt"><?php echo number_format($stat_series['cnt']); ?></div>
            <div class="lbl"><i class="fas fa-layer-group"></i> 시리즈</div>
        </div>
        <div class="stat-card">
            <div class="cnt"><?php echo number_format($stat_model['cnt']); ?></div>
            <div class="lbl"><i class="fas fa-car"></i> 모델</div>
        </div>
    </div>

    <!-- 탭 -->
    <div class="tab-nav">
        <a href="?tab=brand"  class="<?php echo $cur_tab==='brand'  ? 'active' : ''; ?>"><i class="fas fa-trademark fa-sm"></i> 브랜드 관리</a>
        <a href="?tab=series&fbrand=<?php echo $filter_brand; ?>" class="<?php echo $cur_tab==='series' ? 'active' : ''; ?>"><i class="fas fa-layer-group fa-sm"></i> 시리즈 관리</a>
        <a href="?tab=model&fbrand=<?php echo $filter_brand; ?>&fseries=<?php echo $filter_series; ?>" class="<?php echo $cur_tab==='model' ? 'active' : ''; ?>"><i class="fas fa-car fa-sm"></i> 모델 관리</a>
    </div>

    <!-- 탭 콘텐츠 -->
    <div class="tab-content">
        <div class="pds-main-grid">

            <!-- 왼쪽: 목록 -->
            <div class="pds-list-pane">

                <?php /* ═══ 브랜드 탭 ═══ */ if ($cur_tab === 'brand'): ?>
                <div class="pds-filter-bar">
                    <strong style="font-size:0.9rem;">브랜드 목록</strong>
                    <span style="color:#888;font-size:0.82rem;">총 <?php echo count($brands); ?>개</span>
                </div>
                <table id="brandTable">
                    <thead>
                        <tr><th>ID</th><th>브랜드명</th><th>정렬</th><th>사용</th><th>관리</th></tr>
                    </thead>
                    <tbody>
                    <?php if (empty($brands)): ?>
                        <tr><td colspan="5" class="pds-no-data">등록된 브랜드가 없습니다.</td></tr>
                    <?php else: ?>
                        <?php foreach ($brands as $b): ?>
                        <tr id="brand-row-<?php echo $b['id']; ?>">
                            <td><?php echo $b['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($b['name']); ?></strong></td>
                            <td><?php echo $b['sort_order']; ?></td>
                            <td><span class="badge-use <?php echo $b['use_yn'] ? 'badge-on' : 'badge-off'; ?>"><?php echo $b['use_yn'] ? '사용' : '미사용'; ?></span></td>
                            <td>
                                <button class="btn-edit" onclick="pdsEditBrand(<?php echo $b['id']; ?>,'<?php echo addslashes(htmlspecialchars($b['name'])); ?>',<?php echo $b['use_yn']; ?>,<?php echo $b['sort_order']; ?>)">수정</button>
                                <button class="btn-del"  onclick="pdsDelBrand(<?php echo $b['id']; ?>)">삭제</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>

                <?php /* ═══ 시리즈 탭 ═══ */ elseif ($cur_tab === 'series'): ?>
                <form method="get" class="pds-filter-bar">
                    <input type="hidden" name="tab" value="series">
                    <label style="margin:0;font-size:0.85rem;">브랜드 필터:</label>
                    <select name="fbrand" onchange="this.form.submit()">
                        <option value="">전체</option>
                        <?php foreach ($brands as $b): ?>
                        <option value="<?php echo $b['id']; ?>"<?php echo $filter_brand==$b['id']?' selected':''; ?>><?php echo htmlspecialchars($b['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span style="color:#888;font-size:0.82rem;">총 <?php echo count($series_list); ?>개</span>
                </form>
                <table>
                    <thead>
                        <tr><th>ID</th><th>브랜드</th><th>시리즈명</th><th>정렬</th><th>사용</th><th>관리</th></tr>
                    </thead>
                    <tbody>
                    <?php if (empty($series_list)): ?>
                        <tr><td colspan="6" class="pds-no-data">등록된 시리즈가 없습니다.</td></tr>
                    <?php else: ?>
                        <?php foreach ($series_list as $s): ?>
                        <tr>
                            <td><?php echo $s['id']; ?></td>
                            <td><?php echo htmlspecialchars($s['brand_name']); ?></td>
                            <td><?php echo htmlspecialchars($s['name']); ?></td>
                            <td><?php echo $s['sort_order']; ?></td>
                            <td><span class="badge-use <?php echo $s['use_yn'] ? 'badge-on' : 'badge-off'; ?>"><?php echo $s['use_yn'] ? '사용' : '미사용'; ?></span></td>
                            <td>
                                <button class="btn-edit" onclick="pdsEditSeries(<?php echo $s['id']; ?>,<?php echo $s['brand_id']; ?>,'<?php echo addslashes(htmlspecialchars($s['name'])); ?>',<?php echo $s['use_yn']; ?>,<?php echo $s['sort_order']; ?>)">수정</button>
                                <button class="btn-del"  onclick="pdsDelSeries(<?php echo $s['id']; ?>)">삭제</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>

                <?php /* ═══ 모델 탭 ═══ */ elseif ($cur_tab === 'model'): ?>
                <form method="get" class="pds-filter-bar">
                    <input type="hidden" name="tab" value="model">
                    <label style="margin:0;font-size:0.85rem;">브랜드:</label>
                    <select name="fbrand" onchange="this.form.submit()">
                        <option value="">전체</option>
                        <?php foreach ($brands as $b): ?>
                        <option value="<?php echo $b['id']; ?>"<?php echo $filter_brand==$b['id']?' selected':''; ?>><?php echo htmlspecialchars($b['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label style="margin:0;font-size:0.85rem;">시리즈:</label>
                    <select name="fseries">
                        <option value="">전체</option>
                        <?php foreach ($series_list as $s): ?>
                        <option value="<?php echo $s['id']; ?>"<?php echo $filter_series==$s['id']?' selected':''; ?>><?php echo htmlspecialchars($s['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">필터</button>
                    <span style="color:#888;font-size:0.82rem;">총 <?php echo count($model_list); ?>개</span>
                </form>
                <table>
                    <thead>
                        <tr><th>ID</th><th>브랜드</th><th>시리즈</th><th>모델명</th><th>정렬</th><th>사용</th><th>관리</th></tr>
                    </thead>
                    <tbody>
                    <?php if (empty($model_list)): ?>
                        <tr><td colspan="7" class="pds-no-data">등록된 모델이 없습니다.</td></tr>
                    <?php else: ?>
                        <?php foreach ($model_list as $m): ?>
                        <tr>
                            <td><?php echo $m['id']; ?></td>
                            <td><?php echo htmlspecialchars($m['brand_name']); ?></td>
                            <td><?php echo htmlspecialchars($m['series_name']); ?></td>
                            <td><?php echo htmlspecialchars($m['name']); ?></td>
                            <td><?php echo $m['sort_order']; ?></td>
                            <td><span class="badge-use <?php echo $m['use_yn'] ? 'badge-on' : 'badge-off'; ?>"><?php echo $m['use_yn'] ? '사용' : '미사용'; ?></span></td>
                            <td>
                                <button class="btn-edit" onclick="pdsEditModel(<?php echo $m['id']; ?>,<?php echo $m['series_id']; ?>,'<?php echo addslashes(htmlspecialchars($m['name'])); ?>',<?php echo $m['use_yn']; ?>,<?php echo $m['sort_order']; ?>)">수정</button>
                                <button class="btn-del"  onclick="pdsDelModel(<?php echo $m['id']; ?>)">삭제</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
                <?php endif; ?>

            </div><!-- /.pds-list-pane -->

            <!-- 오른쪽: 입력 폼 -->
            <div class="pds-form-pane">

                <?php /* ─── 브랜드 폼 ─── */ if ($cur_tab === 'brand'): ?>
                <div class="form-section">
                    <h3><i class="fas fa-plus-circle fa-sm"></i> 브랜드 추가 / 수정</h3>
                    <div id="msg_brand" class="msg-box"></div>
                    <form id="formBrand" onsubmit="return pdsSubmitBrand()">
                        <input type="hidden" id="brand_id" value="0">
                        <div class="form-row">
                            <label>브랜드명 <span style="color:#c00;">*</span></label>
                            <input type="text" id="brand_name" placeholder="예: Mercedes-Benz">
                        </div>
                        <div class="form-row-inline">
                            <div class="form-row" style="margin:0;">
                                <label>정렬 순서</label>
                                <input type="number" id="brand_sort" value="0" min="0">
                            </div>
                            <div class="form-row" style="margin:0;">
                                <label>사용 여부</label>
                                <div class="form-check">
                                    <input type="checkbox" id="brand_use" checked>
                                    <span>사용</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-submit"><i class="fas fa-save fa-sm"></i> 저장</button>
                        <button type="button" class="btn-reset" onclick="pdsResetBrand()">새로 입력</button>
                    </form>
                </div>

                <?php /* ─── 시리즈 폼 ─── */ elseif ($cur_tab === 'series'): ?>
                <div class="form-section">
                    <h3><i class="fas fa-plus-circle fa-sm"></i> 시리즈 추가 / 수정</h3>
                    <div id="msg_series" class="msg-box"></div>
                    <form id="formSeries" onsubmit="return pdsSubmitSeries()">
                        <input type="hidden" id="series_id" value="0">
                        <div class="form-row">
                            <label>브랜드 <span style="color:#c00;">*</span></label>
                            <select id="series_brand_id">
                                <option value="">-- 선택 --</option>
                                <?php foreach ($brands as $b): ?>
                                <option value="<?php echo $b['id']; ?>"<?php echo $filter_brand==$b['id']?' selected':''; ?>><?php echo htmlspecialchars($b['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>시리즈명 <span style="color:#c00;">*</span></label>
                            <input type="text" id="series_name" placeholder="예: A-Class W177">
                        </div>
                        <div class="form-row-inline">
                            <div class="form-row" style="margin:0;">
                                <label>정렬 순서</label>
                                <input type="number" id="series_sort" value="0" min="0">
                            </div>
                            <div class="form-row" style="margin:0;">
                                <label>사용 여부</label>
                                <div class="form-check">
                                    <input type="checkbox" id="series_use" checked>
                                    <span>사용</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-submit"><i class="fas fa-save fa-sm"></i> 저장</button>
                        <button type="button" class="btn-reset" onclick="pdsResetSeries()">새로 입력</button>
                    </form>
                </div>

                <?php /* ─── 모델 폼 ─── */ elseif ($cur_tab === 'model'): ?>
                <div class="form-section">
                    <h3><i class="fas fa-plus-circle fa-sm"></i> 모델 추가 / 수정</h3>
                    <div id="msg_model" class="msg-box"></div>
                    <form id="formModel" onsubmit="return pdsSubmitModel()">
                        <input type="hidden" id="model_id" value="0">
                        <div class="form-row">
                            <label>시리즈 <span style="color:#c00;">*</span></label>
                            <select id="model_series_id">
                                <option value="">-- 선택 --</option>
                                <?php foreach ($series_list as $s): ?>
                                <option value="<?php echo $s['id']; ?>"<?php echo $filter_series==$s['id']?' selected':''; ?>>[<?php echo htmlspecialchars($s['brand_name']); ?>] <?php echo htmlspecialchars($s['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>모델명 <span style="color:#c00;">*</span></label>
                            <input type="text" id="model_name" placeholder="예: A220 가솔린 2WD">
                        </div>
                        <div class="form-row-inline">
                            <div class="form-row" style="margin:0;">
                                <label>정렬 순서</label>
                                <input type="number" id="model_sort" value="0" min="0">
                            </div>
                            <div class="form-row" style="margin:0;">
                                <label>사용 여부</label>
                                <div class="form-check">
                                    <input type="checkbox" id="model_use" checked>
                                    <span>사용</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-submit"><i class="fas fa-save fa-sm"></i> 저장</button>
                        <button type="button" class="btn-reset" onclick="pdsResetModel()">새로 입력</button>
                    </form>
                </div>
                <?php endif; ?>

            </div><!-- /.pds-form-pane -->
        </div><!-- /.pds-main-grid -->
    </div><!-- /.tab-content -->

</div><!-- /.pds-admin-wrap -->

<script>
var PDS_CARDB_URL = '<?php echo $pds_cardb_url; ?>';

/* ═══ 공통 AJAX 요청 ═══════════════════════════════════════════════ */
function pdsAjax(action, data, cb) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', PDS_CARDB_URL + '?ajax=' + action, true);
    var fd = new FormData();
    for (var k in data) fd.append(k, data[k]);
    xhr.onload = function() {
        try { cb(JSON.parse(xhr.responseText)); }
        catch(e) { cb({ok:false, msg:'응답 파싱 오류'}); }
    };
    xhr.onerror = function() { cb({ok:false, msg:'네트워크 오류'}); };
    xhr.send(fd);
}

function showMsg(id, ok, msg) {
    var el = document.getElementById(id);
    if (!el) return;
    el.className = 'msg-box ' + (ok ? 'msg-ok' : 'msg-err');
    el.textContent = msg;
    el.style.display = 'block';
    setTimeout(function(){ el.style.display='none'; }, 3500);
}

/* ═══ 브랜드 ════════════════════════════════════════════════════════ */
function pdsEditBrand(id, name, use_yn, sort) {
    document.getElementById('brand_id').value   = id;
    document.getElementById('brand_name').value = name;
    document.getElementById('brand_sort').value = sort;
    document.getElementById('brand_use').checked = (use_yn == 1);
    document.getElementById('brand_name').focus();
}
function pdsResetBrand() {
    document.getElementById('brand_id').value   = '0';
    document.getElementById('brand_name').value = '';
    document.getElementById('brand_sort').value = '0';
    document.getElementById('brand_use').checked = true;
}
function pdsSubmitBrand() {
    var id    = document.getElementById('brand_id').value;
    var name  = document.getElementById('brand_name').value.trim();
    var sort  = document.getElementById('brand_sort').value;
    var use_yn = document.getElementById('brand_use').checked ? 1 : 0;
    if (!name) { showMsg('msg_brand', false, '브랜드명을 입력하세요'); return false; }
    pdsAjax('save_brand', {id:id, name:name, sort_order:sort, use_yn:use_yn}, function(r){
        showMsg('msg_brand', r.ok, r.msg);
        if (r.ok) { setTimeout(function(){ location.reload(); }, 800); }
    });
    return false;
}
function pdsDelBrand(id) {
    if (!confirm('이 브랜드를 삭제하시겠습니까?\n하위 시리즈가 있으면 삭제되지 않습니다.')) return;
    pdsAjax('delete_brand', {id:id}, function(r){
        showMsg('msg_brand', r.ok, r.msg);
        if (r.ok) { setTimeout(function(){ location.reload(); }, 800); }
    });
}

/* ═══ 시리즈 ════════════════════════════════════════════════════════ */
function pdsEditSeries(id, brand_id, name, use_yn, sort) {
    document.getElementById('series_id').value       = id;
    document.getElementById('series_brand_id').value = brand_id;
    document.getElementById('series_name').value     = name;
    document.getElementById('series_sort').value     = sort;
    document.getElementById('series_use').checked    = (use_yn == 1);
    document.getElementById('series_name').focus();
}
function pdsResetSeries() {
    document.getElementById('series_id').value    = '0';
    document.getElementById('series_name').value  = '';
    document.getElementById('series_sort').value  = '0';
    document.getElementById('series_use').checked = true;
}
function pdsSubmitSeries() {
    var id       = document.getElementById('series_id').value;
    var brand_id = document.getElementById('series_brand_id').value;
    var name     = document.getElementById('series_name').value.trim();
    var sort     = document.getElementById('series_sort').value;
    var use_yn   = document.getElementById('series_use').checked ? 1 : 0;
    if (!brand_id || !name) { showMsg('msg_series', false, '브랜드와 시리즈명을 입력하세요'); return false; }
    pdsAjax('save_series', {id:id, brand_id:brand_id, name:name, sort_order:sort, use_yn:use_yn}, function(r){
        showMsg('msg_series', r.ok, r.msg);
        if (r.ok) { setTimeout(function(){ location.reload(); }, 800); }
    });
    return false;
}
function pdsDelSeries(id) {
    if (!confirm('이 시리즈를 삭제하시겠습니까?\n하위 모델이 있으면 삭제되지 않습니다.')) return;
    pdsAjax('delete_series', {id:id}, function(r){
        showMsg('msg_series', r.ok, r.msg);
        if (r.ok) { setTimeout(function(){ location.reload(); }, 800); }
    });
}

/* ═══ 모델 ══════════════════════════════════════════════════════════ */
function pdsEditModel(id, series_id, name, use_yn, sort) {
    document.getElementById('model_id').value        = id;
    document.getElementById('model_series_id').value = series_id;
    document.getElementById('model_name').value      = name;
    document.getElementById('model_sort').value      = sort;
    document.getElementById('model_use').checked     = (use_yn == 1);
    document.getElementById('model_name').focus();
}
function pdsResetModel() {
    document.getElementById('model_id').value    = '0';
    document.getElementById('model_name').value  = '';
    document.getElementById('model_sort').value  = '0';
    document.getElementById('model_use').checked = true;
}
function pdsSubmitModel() {
    var id        = document.getElementById('model_id').value;
    var series_id = document.getElementById('model_series_id').value;
    var name      = document.getElementById('model_name').value.trim();
    var sort      = document.getElementById('model_sort').value;
    var use_yn    = document.getElementById('model_use').checked ? 1 : 0;
    if (!series_id || !name) { showMsg('msg_model', false, '시리즈와 모델명을 입력하세요'); return false; }
    pdsAjax('save_model', {id:id, series_id:series_id, name:name, sort_order:sort, use_yn:use_yn}, function(r){
        showMsg('msg_model', r.ok, r.msg);
        if (r.ok) { setTimeout(function(){ location.reload(); }, 800); }
    });
    return false;
}
function pdsDelModel(id) {
    if (!confirm('이 모델을 삭제하시겠습니까?')) return;
    pdsAjax('delete_model', {id:id}, function(r){
        showMsg('msg_model', r.ok, r.msg);
        if (r.ok) { setTimeout(function(){ location.reload(); }, 800); }
    });
}
</script>
</body>
</html>
