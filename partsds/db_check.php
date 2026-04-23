<?php
/**
 * PartSDS DB 진단 도구 v2
 * - 3차분류(모델) DB 저장 상태 확인
 * - 회원 유형(일반/사업자) 현황 확인
 * - car_series / car_model ca_id 매핑 상태 확인
 */

define('SQL_RUNNER_KEY', 'partsds2024!');

$input_key = isset($_GET['key']) ? $_GET['key'] : '';
if ($input_key !== SQL_RUNNER_KEY) {
    http_response_code(403);
    die('<h2>403 Forbidden</h2><p>올바른 키를 입력하세요.</p>');
}

$action = isset($_GET['action']) ? preg_replace('/[^a-z_]/', '', $_GET['action']) : 'overview';

// ── DB 연결 ──
$base = dirname(__DIR__);
if (file_exists($base . '/config.php')) @require_once($base . '/config.php');
if (!defined('G5_DB_HOST') && file_exists($base . '/extend/config.php')) @require_once($base . '/extend/config.php');

$db_host   = defined('G5_DB_HOST')      ? G5_DB_HOST      : 'localhost';
$db_user   = defined('G5_DB_USER')      ? G5_DB_USER      : '';
$db_pass   = defined('G5_DB_PASS')      ? G5_DB_PASS      : '';
$db_name   = defined('G5_DB_NAME')      ? G5_DB_NAME      : '';
$db_prefix = defined('G5_TABLE_PREFIX') ? G5_TABLE_PREFIX : 'g5_';

if (!$db_user && file_exists($base . '/config.php')) {
    $cfg = file_get_contents($base . '/config.php');
    preg_match("/define\('G5_DB_HOST',\s*'([^']+)'\)/", $cfg, $m); $db_host   = $m[1] ?? 'localhost';
    preg_match("/define\('G5_DB_USER',\s*'([^']+)'\)/", $cfg, $m); $db_user   = $m[1] ?? '';
    preg_match("/define\('G5_DB_PASS',\s*'([^']+)'\)/", $cfg, $m); $db_pass   = $m[1] ?? '';
    preg_match("/define\('G5_DB_NAME',\s*'([^']+)'\)/", $cfg, $m); $db_name   = $m[1] ?? '';
    preg_match("/define\('G5_TABLE_PREFIX',\s*'([^']+)'\)/", $cfg, $m); $db_prefix = $m[1] ?? 'g5_';
}

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$conn_ok = !$conn->connect_error;
if ($conn_ok) $conn->set_charset('utf8mb4');

function q($conn, $sql) {
    $r = $conn->query($sql);
    if (!$r) return [];
    $rows = [];
    while ($row = $r->fetch_assoc()) $rows[] = $row;
    return $rows;
}
function q1($conn, $sql) {
    $rows = q($conn, $sql);
    return $rows[0] ?? [];
}

$key = htmlspecialchars($input_key);
$p   = $db_prefix;

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PartSDS DB 진단 v2</title>
<style>
* { box-sizing: border-box; }
body { font-family: 'Malgun Gothic', sans-serif; max-width: 1100px; margin: 20px auto; padding: 16px; background: #f4f6f8; }
h1 { color: #1a1a1a; border-bottom: 3px solid #c0392b; padding-bottom: 8px; font-size: 22px; }
h2 { font-size: 17px; color: #333; margin: 0 0 12px; }
.card { background: #fff; border-radius: 8px; padding: 18px; margin: 12px 0; box-shadow: 0 1px 6px rgba(0,0,0,.1); }
.nav { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 16px; }
.nav a { display: inline-block; padding: 8px 16px; border-radius: 4px; text-decoration: none;
         font-size: 13px; background: #ecf0f1; color: #555; }
.nav a:hover, .nav a.active { background: #c0392b; color: #fff; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
th { background: #2c3e50; color: #fff; padding: 8px 10px; text-align: left; }
td { padding: 7px 10px; border-bottom: 1px solid #eee; }
tr:hover td { background: #f9f9f9; }
.ok   { color: #27ae60; font-weight: 600; }
.err  { color: #c0392b; font-weight: 600; }
.warn { color: #e67e00; font-weight: 600; }
.info { color: #2980b9; font-weight: 600; }
.badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: 700; }
.b-ok   { background: #d5f5e3; color: #1e8449; }
.b-err  { background: #fde8e8; color: #a93226; }
.b-warn { background: #fef9e7; color: #9a7d0a; }
.b-blue { background: #d6eaf8; color: #1a5276; }
.b-gray { background: #f2f3f4; color: #555; }
.stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
.stat-box { border: 1px solid #e0e0e0; border-radius: 6px; padding: 14px; text-align: center; }
.stat-num { font-size: 28px; font-weight: 700; color: #c0392b; }
.stat-lbl { font-size: 12px; color: #888; margin-top: 4px; }
.section-divider { border: none; border-top: 1px solid #e0e0e0; margin: 16px 0; }
</style>
</head>
<body>

<h1>🔍 PartSDS DB 진단 <small style="font-size:13px; color:#888;">v2</small></h1>

<div class="nav">
    <a href="?key=<?=$key?>&action=overview"    <?=$action==='overview'    ?'class="active"':'';?>>📊 전체 현황</a>
    <a href="?key=<?=$key?>&action=abc_models"  <?=$action==='abc_models'  ?'class="active"':'';?>>🚗 A~C클래스 모델</a>
    <a href="?key=<?=$key?>&action=categories"  <?=$action==='categories'  ?'class="active"':'';?>>📂 분류 상태</a>
    <a href="?key=<?=$key?>&action=car_mapping" <?=$action==='car_mapping' ?'class="active"':'';?>>🗺 차종 ca_id 매핑</a>
    <a href="?key=<?=$key?>&action=members"     <?=$action==='members'     ?'class="active"':'';?>>👤 회원 유형 현황</a>
    <a href="?key=<?=$key?>&action=ca_order_check" <?=$action==='ca_order_check' ?'class="active"':'';?>>🔢 ca_order 검증</a>
    <a href="?key=<?=$key?>&action=register_diag" <?=$action==='register_diag' ?'class="active"':'';?>>🔧 회원가입 진단</a>
    <a href="sql_runner.php?key=<?=$key?>"      target="_blank">⚡ SQL Runner</a>
</div>

<?php if (!$conn_ok): ?>
<div class="card">
    <p class="err">❌ DB 연결 실패: <?=htmlspecialchars($conn->connect_error)?></p>
    <p>DB 정보: <?=$db_host?> / <?=$db_name?></p>
</div>
<?php else: ?>

<!-- ═══════════════════════════════════════════════════════ -->
<?php if ($action === 'overview'): ?>

<div class="card">
    <h2>📊 전체 현황 (DB: <?=htmlspecialchars($db_name)?>, prefix: <code><?=htmlspecialchars($p)?></code>)</h2>

    <?php
    // 테이블 존재 여부
    $tables = ['shop_category', 'car_brand', 'car_series', 'car_model', 'member', 'shop_item'];
    $table_status = [];
    foreach ($tables as $t) {
        $full = $p . $t;
        $r = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$full}'");
        $table_status[$t] = ['exists' => (bool)($r['cnt'] ?? 0), 'count' => 0];
        if ($table_status[$t]['exists']) {
            $cr = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$full}`");
            $table_status[$t]['count'] = $cr['cnt'] ?? 0;
        }
    }

    // ca_id 컬럼 존재 여부
    $ca_col_series = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$p}car_series' AND COLUMN_NAME='ca_id'");
    $ca_col_model  = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$p}car_model'  AND COLUMN_NAME='ca_id'");
    ?>

    <div class="stat-grid">
        <?php foreach ($table_status as $t => $st): ?>
        <div class="stat-box">
            <div class="stat-num <?=$st['exists'] ? 'ok' : 'err'?>"><?=number_format($st['count'])?></div>
            <div class="stat-lbl">
                <?=$st['exists'] ? '✅' : '❌'?>
                <code><?=$p.$t?></code>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <hr class="section-divider">

    <table>
        <tr><th>항목</th><th>상태</th><th>설명</th></tr>
        <tr>
            <td>g5_shop_category 3단계 모델 분류</td>
            <?php
            $model_ca = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}shop_category` WHERE LENGTH(ca_id) >= 6");
            $mc = (int)($model_ca['cnt'] ?? 0);
            ?>
            <td><?=$mc > 0 ? "<span class='ok'>✅ {$mc}개</span>" : "<span class='err'>❌ 없음</span>"?></td>
            <td>6~7자리 ca_id (모델 분류). <code>install_shop_categories.sql</code> 실행 필요</td>
        </tr>
        <tr>
            <td>car_series.ca_id 컬럼</td>
            <td><?=($ca_col_series['cnt']??0) ? "<span class='ok'>✅ 있음</span>" : "<span class='err'>❌ 없음</span>"?></td>
            <td><code>update_ca_id.sql</code> 실행 필요</td>
        </tr>
        <tr>
            <td>car_model.ca_id 컬럼</td>
            <td><?=($ca_col_model['cnt']??0) ? "<span class='ok'>✅ 있음</span>" : "<span class='err'>❌ 없음</span>"?></td>
            <td><code>update_ca_id.sql</code> 실행 필요</td>
        </tr>
        <?php
        // ca_id가 있는 시리즈 수
        if (($ca_col_series['cnt']??0)) {
            $mapped = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}car_series` WHERE ca_id IS NOT NULL AND ca_id != ''");
            echo "<tr><td>car_series ca_id 매핑</td><td><span class='ok'>✅ {$mapped['cnt']}개 매핑됨</span></td><td>총 " . ($table_status['car_series']['count']) . "개 중</td></tr>";
        }
        // ca_id가 있는 모델 수
        if (($ca_col_model['cnt']??0)) {
            $mapped = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}car_model` WHERE ca_id IS NOT NULL AND ca_id != ''");
            echo "<tr><td>car_model ca_id 매핑</td><td><span class='ok'>✅ {$mapped['cnt']}개 매핑됨</span></td><td>총 " . ($table_status['car_model']['count']) . "개 중</td></tr>";
        }
        ?>
        <tr>
            <td>회원 유형 구분 (mb_7)</td>
            <?php
            $biz_cnt  = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member` WHERE mb_7='business'");
            $norm_cnt = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member` WHERE mb_7='normal'");
            ?>
            <td><span class='info'>일반: <?=$norm_cnt['cnt']?> / 사업자: <?=$biz_cnt['cnt']?></span></td>
            <td>mb_7 컬럼으로 구분</td>
        </tr>
    </table>
</div>

<!-- ════════════════════════════════════════════════ -->
<?php elseif ($action === 'abc_models'): ?>

<div class="card">
    <h2>🚗 A~C 클래스 3차분류(모델) 확인</h2>

    <?php
    // car_series에서 A,B,C 클래스 확인
    $abc_series = q($conn, "SELECT series_id, brand_id, series_name FROM `{$p}car_series`
        WHERE series_name LIKE 'A-%클래스%' OR series_name LIKE 'B-%클래스%' OR series_name LIKE 'C-%클래스%'
        OR series_name LIKE 'A-클래스%' OR series_name LIKE 'B-클래스%' OR series_name LIKE 'C-클래스%'
        ORDER BY series_id LIMIT 20");
    ?>

    <h3 style="font-size:15px;">① car_series 테이블의 A~C 클래스</h3>
    <table>
        <tr><th>series_id</th><th>brand_id</th><th>series_name</th><th>ca_id</th><th>모델수</th><th>shop_category 6자리 분류</th></tr>
        <?php foreach ($abc_series as $s):
            $has_ca = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$p}car_series' AND COLUMN_NAME='ca_id'");
            $ca_val = '';
            if ($has_ca['cnt']) {
                $ca_r = q1($conn, "SELECT ca_id FROM `{$p}car_series` WHERE series_id={$s['series_id']}");
                $ca_val = $ca_r['ca_id'] ?? '';
            }
            $model_cnt = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}car_model` WHERE series_id={$s['series_id']}");
            // shop_category에서 6자리 분류 확인
            $shop_cat_cnt = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}shop_category` WHERE LENGTH(ca_id)=6 AND LEFT(ca_id,4)='{$ca_val}'");
        ?>
        <tr>
            <td><?=$s['series_id']?></td>
            <td><?=$s['brand_id']?></td>
            <td><?=htmlspecialchars($s['series_name'])?></td>
            <td>
                <?php if ($ca_val): ?>
                    <span class="badge b-blue"><?=$ca_val?></span>
                <?php else: ?>
                    <span class="badge b-err">미매핑</span>
                <?php endif; ?>
            </td>
            <td><?=$model_cnt['cnt']?></td>
            <td>
                <?php
                if ($ca_val) {
                    $cnt = (int)($shop_cat_cnt['cnt']??0);
                    echo $cnt > 0
                        ? "<span class='badge b-ok'>✅ {$cnt}개</span>"
                        : "<span class='badge b-err'>❌ 없음</span>";
                } else {
                    echo '<span class="badge b-gray">ca_id 없음</span>';
                }
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <hr class="section-divider">

    <h3 style="font-size:15px;">② g5_shop_category의 A클래스 모델 분류 (ca_id 1001xx)</h3>
    <?php
    $a_models = q($conn, "SELECT ca_id, ca_name, ca_skin FROM `{$p}shop_category`
        WHERE ca_id LIKE '1001%' AND LENGTH(ca_id)=6 ORDER BY ca_id LIMIT 20");
    ?>
    <?php if ($a_models): ?>
    <table>
        <tr><th>ca_id</th><th>ca_name</th><th>ca_skin</th></tr>
        <?php foreach ($a_models as $r): ?>
        <tr>
            <td><code><?=$r['ca_id']?></code></td>
            <td><?=htmlspecialchars($r['ca_name'])?></td>
            <td>
                <?php if ($r['ca_skin'] === ''): ?>
                    <span class="badge b-ok">✅ '' (이윰빌더 기본)</span>
                <?php elseif ($r['ca_skin']): ?>
                    <span class="badge b-warn"><?=htmlspecialchars($r['ca_skin'])?></span>
                <?php else: ?>
                    <span class="badge b-gray">NULL</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
    <div style="background:#fde8e8; padding:12px; border-radius:4px; color:#a93226;">
        ❌ <strong>A클래스 모델 분류가 DB에 없습니다.</strong><br>
        <code>install_shop_categories.sql</code>을 실행하여 4,089개 분류를 INSERT 해주세요.<br>
        <a href="sql_runner.php?key=<?=$key?>" target="_blank">→ SQL Runner 바로가기</a>
    </div>
    <?php endif; ?>

    <hr class="section-divider">

    <h3 style="font-size:15px;">③ car_model 테이블의 A클래스 W177 모델 목록</h3>
    <?php
    // series_id=1 (partsds_data.sql 기준)이지만, 실제 DB series_id 확인
    $w177_series = q1($conn, "SELECT series_id FROM `{$p}car_series` WHERE series_name LIKE '%W177%' LIMIT 1");
    $sid = $w177_series['series_id'] ?? 1;
    $a_car_models = q($conn, "SELECT model_id, series_id, model_name FROM `{$p}car_model` WHERE series_id={$sid} LIMIT 15");
    ?>
    <?php if ($a_car_models): ?>
    <p style="color:#888; font-size:12px;">series_id=<?=$sid?> (A-클래스 W177)</p>
    <table>
        <tr><th>model_id</th><th>model_name</th><th>ca_id (있으면)</th></tr>
        <?php
        $has_ca_model = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$p}car_model' AND COLUMN_NAME='ca_id'");
        foreach ($a_car_models as $r): ?>
        <tr>
            <td><?=$r['model_id']?></td>
            <td><?=htmlspecialchars($r['model_name'])?></td>
            <td>
                <?php if ($has_ca_model['cnt']): ?>
                    <?php $cr = q1($conn, "SELECT ca_id FROM `{$p}car_model` WHERE model_id={$r['model_id']}"); ?>
                    <?php if (!empty($cr['ca_id'])): ?>
                        <span class="badge b-blue"><?=$cr['ca_id']?></span>
                    <?php else: ?>
                        <span class="badge b-err">미매핑</span>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="badge b-gray">컬럼 없음</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
    <div style="background:#fff3cd; padding:10px; border-radius:4px;">⚠️ car_model 테이블에 A클래스 W177 모델이 없습니다.</div>
    <?php endif; ?>
</div>

<!-- ════════════════════════════════════════════════ -->
<?php elseif ($action === 'categories'): ?>

<div class="card">
    <h2>📂 g5_shop_category 분류 상태</h2>
    <?php
    $cat_stats = q($conn, "SELECT
        CASE WHEN LENGTH(ca_id)=2 THEN '2자리(브랜드-소)'
             WHEN LENGTH(ca_id)=3 THEN '3자리(브랜드-대)'
             WHEN LENGTH(ca_id)=4 THEN '4자리(시리즈)'
             WHEN LENGTH(ca_id)=5 THEN '5자리(시리즈-대)'
             WHEN LENGTH(ca_id)=6 THEN '6자리(모델)'
             WHEN LENGTH(ca_id)=7 THEN '7자리(모델-대)'
             ELSE CONCAT(LENGTH(ca_id), '자리(기타)')
        END AS ca_type,
        COUNT(*) AS cnt,
        SUM(ca_skin='' OR ca_skin IS NULL) AS empty_skin,
        SUM(ca_skin != '' AND ca_skin IS NOT NULL) AS has_skin
        FROM `{$p}shop_category`
        GROUP BY LENGTH(ca_id)
        ORDER BY LENGTH(ca_id)");
    $total = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}shop_category`");
    ?>
    <p>전체 분류 수: <strong><?=number_format($total['cnt']??0)?></strong>개
        (브랜드14 + 시리즈539 + 모델3,536 = 4,089개 정상)
    </p>
    <table>
        <tr><th>분류 유형</th><th>개수</th><th>ca_skin='' (이윰빌더 기본)</th><th>ca_skin 지정</th><th>상태</th></tr>
        <?php foreach ($cat_stats as $r): ?>
        <tr>
            <td><?=$r['ca_type']?></td>
            <td><strong><?=number_format($r['cnt'])?></strong></td>
            <td><?=number_format($r['empty_skin'])?></td>
            <td><?=$r['has_skin'] > 0 ? "<span class='warn'>" . number_format($r['has_skin']) . "</span>" : "0"?></td>
            <td>
                <?php if ($r['has_skin'] > 0): ?>
                    <span class="badge b-warn">⚠️ fix_ca_skin 실행 필요</span>
                <?php else: ?>
                    <span class="badge b-ok">✅ 정상</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php if (($total['cnt']??0) < 4000): ?>
    <div style="background:#fde8e8; padding:12px; border-radius:4px; margin-top:12px; color:#a93226;">
        ⚠️ 분류 수가 4,089개보다 적습니다. <code>install_shop_categories.sql</code>을 실행하세요.
    </div>
    <?php endif; ?>

    <hr class="section-divider">
    <h3 style="font-size:14px;">최근 추가된 분류 (상위 10개)</h3>
    <?php $recent = q($conn, "SELECT ca_id, ca_name, ca_skin FROM `{$p}shop_category` ORDER BY ca_order DESC LIMIT 10"); ?>
    <table>
        <tr><th>ca_id</th><th>ca_name</th><th>ca_skin</th></tr>
        <?php foreach ($recent as $r): ?>
        <tr>
            <td><code><?=htmlspecialchars($r['ca_id'])?></code></td>
            <td><?=htmlspecialchars($r['ca_name'])?></td>
            <td><?=htmlspecialchars($r['ca_skin'] !== '' ? $r['ca_skin'] : '(빈값=기본)')?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<!-- ════════════════════════════════════════════════ -->
<?php elseif ($action === 'car_mapping'): ?>

<div class="card">
    <h2>🗺 차종 ca_id 매핑 상태 (car_series / car_model)</h2>

    <?php
    // car_series ca_id 컬럼 확인
    $has_series_ca = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$p}car_series' AND COLUMN_NAME='ca_id'");
    $has_model_ca  = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$p}car_model'  AND COLUMN_NAME='ca_id'");
    ?>

    <table style="margin-bottom:16px;">
        <tr><th>테이블</th><th>ca_id 컬럼</th><th>총 레코드</th><th>매핑된 수</th><th>미매핑</th><th>상태</th></tr>
        <?php
        foreach (['car_series'=>$has_series_ca, 'car_model'=>$has_model_ca] as $tbl=>$has):
            $full = $p . $tbl;
            $total_r = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$full}`");
            $tot = (int)($total_r['cnt']??0);
            if ($has['cnt']) {
                $mapped = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$full}` WHERE ca_id IS NOT NULL AND ca_id != ''");
                $mp = (int)($mapped['cnt']??0);
                $unmapped = $tot - $mp;
                $ok = $unmapped === 0;
                echo "<tr>
                    <td><code>{$full}</code></td>
                    <td><span class='badge b-ok'>✅ 있음</span></td>
                    <td>{$tot}</td>
                    <td>{$mp}</td>
                    <td>" . ($unmapped > 0 ? "<span class='err'>{$unmapped}</span>" : "0") . "</td>
                    <td>" . ($ok ? "<span class='badge b-ok'>✅ 완료</span>" : "<span class='badge b-warn'>⚠️ 부분매핑</span>") . "</td>
                </tr>";
            } else {
                echo "<tr>
                    <td><code>{$full}</code></td>
                    <td><span class='badge b-err'>❌ 없음</span></td>
                    <td>{$tot}</td><td>-</td><td>-</td>
                    <td><span class='badge b-err'>update_ca_id.sql 실행 필요</span></td>
                </tr>";
            }
        endforeach;
        ?>
    </table>

    <?php if ($has_series_ca['cnt']): ?>
    <h3 style="font-size:14px;">벤츠 시리즈별 ca_id 매핑 (brand_id=1, 처음 20개)</h3>
    <?php $benz = q($conn, "SELECT series_id, series_name, ca_id FROM `{$p}car_series` WHERE brand_id=1 ORDER BY series_id LIMIT 20"); ?>
    <table>
        <tr><th>series_id</th><th>series_name</th><th>ca_id</th></tr>
        <?php foreach ($benz as $r): ?>
        <tr>
            <td><?=$r['series_id']?></td>
            <td><?=htmlspecialchars($r['series_name'])?></td>
            <td>
                <?php if ($r['ca_id']): ?>
                    <span class="badge b-blue"><?=$r['ca_id']?></span>
                <?php else: ?>
                    <span class="badge b-err">미매핑</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>

<!-- ════════════════════════════════════════════════ -->
<?php elseif ($action === 'members'): ?>

<div class="card">
    <h2>👤 회원 유형 현황</h2>

    <?php
    $total_mb = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member`");
    $normal   = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member` WHERE mb_7='normal'");
    $business = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member` WHERE mb_7='business'");
    $no_type  = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member` WHERE mb_7='' OR mb_7 IS NULL");
    $has_car  = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member` WHERE mb_4 IS NOT NULL AND mb_4 != '' AND mb_4 != '0'");
    ?>

    <div class="stat-grid">
        <div class="stat-box">
            <div class="stat-num"><?=$total_mb['cnt']?></div>
            <div class="stat-lbl">전체 회원</div>
        </div>
        <div class="stat-box">
            <div class="stat-num ok"><?=$normal['cnt']?></div>
            <div class="stat-lbl">일반 회원 (mb_7='normal')</div>
        </div>
        <div class="stat-box">
            <div class="stat-num info"><?=$business['cnt']?></div>
            <div class="stat-lbl">사업자 회원 (mb_7='business')</div>
        </div>
        <div class="stat-box">
            <div class="stat-num warn"><?=$no_type['cnt']?></div>
            <div class="stat-lbl">미분류 (mb_7 미설정)</div>
        </div>
        <div class="stat-box">
            <div class="stat-num"><?=$has_car['cnt']?></div>
            <div class="stat-lbl">차종 등록 회원 (mb_4 설정)</div>
        </div>
    </div>

    <?php if ($no_type['cnt'] > 0): ?>
    <div style="background:#fff3cd; padding:10px; border-radius:4px; margin-top:12px;">
        ⚠️ 미분류 회원 <?=$no_type['cnt']?>명이 있습니다.
        <a href="sql_runner.php?key=<?=$key?>&file=install_member_type">install_member_type.sql 실행</a>으로 기본값 설정
    </div>
    <?php endif; ?>

    <hr class="section-divider">
    <h3 style="font-size:14px;">사업자 회원 목록</h3>
    <?php $biz_members = q($conn, "SELECT mb_id, mb_name, mb_email, mb_7, mb_8, mb_9, mb_10, mb_1, mb_2, mb_3 FROM `{$p}member` WHERE mb_7='business' ORDER BY mb_datetime DESC LIMIT 20"); ?>
    <?php if ($biz_members): ?>
    <table>
        <tr><th>아이디</th><th>이름</th><th>사업자번호</th><th>업체명</th><th>담당자</th><th>등록차량</th></tr>
        <?php foreach ($biz_members as $m): ?>
        <tr>
            <td><?=htmlspecialchars($m['mb_id'])?></td>
            <td><?=htmlspecialchars($m['mb_name'])?></td>
            <td><?=htmlspecialchars($m['mb_8'])?></td>
            <td><?=htmlspecialchars($m['mb_9'])?></td>
            <td><?=htmlspecialchars($m['mb_10'])?></td>
            <td>
                <?php
                $car = array_filter([$m['mb_1'], $m['mb_2'], $m['mb_3']]);
                echo htmlspecialchars(implode(' > ', $car)) ?: '<span style="color:#aaa;">없음</span>';
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
    <p style="color:#888;">사업자 회원이 없습니다.</p>
    <?php endif; ?>

    <hr class="section-divider">
    <h3 style="font-size:14px;">차종 등록 회원 (최근 20명)</h3>
    <?php $car_members = q($conn, "SELECT mb_id, mb_name, mb_7, mb_1, mb_2, mb_3, mb_4, mb_5, mb_6 FROM `{$p}member` WHERE mb_4 IS NOT NULL AND mb_4 != '' AND mb_4 != '0' ORDER BY mb_datetime DESC LIMIT 20"); ?>
    <?php if ($car_members): ?>
    <table>
        <tr><th>아이디</th><th>이름</th><th>유형</th><th>브랜드</th><th>시리즈</th><th>모델</th><th>ID (brand/series/model)</th></tr>
        <?php foreach ($car_members as $m): ?>
        <tr>
            <td><?=htmlspecialchars($m['mb_id'])?></td>
            <td><?=htmlspecialchars($m['mb_name'])?></td>
            <td>
                <?php if ($m['mb_7']==='business'): ?>
                    <span class="badge b-blue">사업자</span>
                <?php else: ?>
                    <span class="badge b-ok">일반</span>
                <?php endif; ?>
            </td>
            <td><?=htmlspecialchars($m['mb_1'])?></td>
            <td><?=htmlspecialchars($m['mb_2'])?></td>
            <td><?=htmlspecialchars($m['mb_3'])?></td>
            <td><code><?=$m['mb_4']?>/<?=$m['mb_5']?>/<?=$m['mb_6']?></code></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
    <p style="color:#888;">차종 등록 회원이 없습니다.</p>
    <?php endif; ?>
</div>

<?php elseif ($action === 'ca_order_check'): ?>

<!-- ════════════════════ ca_order 검증 탭 ════════════════════ -->
<div class="card">
    <h2>🔢 3차분류 ca_order 정규화 검증</h2>
    <p style="color:#666;">각 2차분류(시리즈) 내에서 3차분류의 <code>ca_order</code>가 1부터 시작하는지 확인합니다.</p>

    <?php
    // g5_shop_category에서 6자리 ca_id 그룹 분석
    $ca_res = $conn->query("SELECT ca_id, ca_name, ca_order FROM `{$p}shop_category` WHERE LENGTH(ca_id)=6 AND ca_use='1' ORDER BY ca_id");
    $groups = [];
    if ($ca_res) {
        while ($row = $ca_res->fetch_assoc()) {
            $parent = substr($row['ca_id'], 0, 4);
            $groups[$parent][] = $row;
        }
    }

    $total_groups   = count($groups);
    $bad_groups     = 0;
    $fixed_groups   = 0;
    $bad_list       = [];

    foreach ($groups as $parent => $items) {
        usort($items, function($a,$b){ return $a['ca_order'] <=> $b['ca_order']; });
        $min_order = $items[0]['ca_order'];
        if ($min_order != 1) {
            $bad_groups++;
            $bad_list[] = ['parent'=>$parent, 'items'=>$items, 'min'=>$min_order];
        } else {
            // 확인: 순서가 연속인지
            $ok = true;
            foreach ($items as $i => $item) {
                if ($item['ca_order'] != ($i+1)) { $ok=false; break; }
            }
            if (!$ok) { $bad_groups++; $bad_list[] = ['parent'=>$parent,'items'=>$items,'min'=>$min_order,'gap'=>true]; }
            else $fixed_groups++;
        }
    }
    ?>

    <div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:20px;">
        <div class="stat-box">
            <div class="stat-num"><?=$total_groups?></div>
            <div class="stat-label">전체 2차분류 그룹</div>
        </div>
        <div class="stat-box" style="background:<?=$bad_groups>0?'#fdf5f5':'#f0fff0';?>">
            <div class="stat-num" style="color:<?=$bad_groups>0?'#c0392b':'#27ae60';?>"><?=$bad_groups?></div>
            <div class="stat-label">ca_order 오류 그룹</div>
        </div>
        <div class="stat-box" style="background:#f0fff0;">
            <div class="stat-num" style="color:#27ae60;"><?=$fixed_groups?></div>
            <div class="stat-label">정상 그룹</div>
        </div>
    </div>

    <?php if ($bad_groups === 0): ?>
    <p style="color:#27ae60;font-weight:bold;">✅ 모든 3차분류의 ca_order가 각 2차분류 내에서 1부터 시작합니다.</p>
    <?php else: ?>
    <p style="color:#c0392b;font-weight:bold;">⚠️ <?=$bad_groups?>개 그룹에서 ca_order가 1부터 시작하지 않습니다.</p>
    <p><a href="sql_runner.php?key=<?=$key?>&file=fix_ca_order" style="color:#c0392b;font-weight:bold;" target="_blank">
        ⚡ fix_ca_order.sql 실행하여 수정하기 →
    </a></p>

    <table style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:6px 10px;text-align:left;">2차분류 ca_id</th>
                <th style="padding:6px 10px;text-align:center;">항목 수</th>
                <th style="padding:6px 10px;text-align:center;">최솟값</th>
                <th style="padding:6px 10px;text-align:center;">상태</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach (array_slice($bad_list, 0, 20) as $g): ?>
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:6px 10px;"><?=htmlspecialchars($g['parent'])?></td>
                <td style="padding:6px 10px;text-align:center;"><?=count($g['items'])?></td>
                <td style="padding:6px 10px;text-align:center;color:#c0392b;"><?=$g['min']?></td>
                <td style="padding:6px 10px;text-align:center;">
                    <?php if (!empty($g['gap'])): ?>
                        <span style="background:#fff3cd;padding:2px 6px;border-radius:3px;font-size:12px;">순서 불연속</span>
                    <?php else: ?>
                        <span style="background:#fdf5f5;color:#c0392b;padding:2px 6px;border-radius:3px;font-size:12px;">1부터 시작 ✗</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (count($bad_list) > 20): ?>
            <tr><td colspan="4" style="padding:6px 10px;color:#888;">... 외 <?=count($bad_list)-20?>개</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<!-- A~C클래스 ca_order 현황 -->
<div class="card">
    <h2>📊 A/B/C 클래스 ca_order 현황</h2>
    <?php
    // 2차분류 1001~1010 (벤츠 A~CL 클래스)
    $cls_res = $conn->query("SELECT ca_id, ca_name, ca_order FROM `{$p}shop_category` WHERE ca_id BETWEEN '1001' AND '1010' ORDER BY ca_id");
    if ($cls_res && $cls_res->num_rows > 0):
    ?>
    <table style="width:100%;border-collapse:collapse;font-size:13px;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:6px 10px;text-align:left;">ca_id (2차)</th>
                <th style="padding:6px 10px;text-align:left;">클래스명</th>
                <th style="padding:6px 10px;text-align:center;">ca_order</th>
                <th style="padding:6px 10px;text-align:center;">3차분류 수</th>
                <th style="padding:6px 10px;text-align:center;">3차 ca_order 시작값</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($cls = $cls_res->fetch_assoc()): ?>
            <?php
            $sub_cnt_row = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}shop_category` WHERE ca_id LIKE '{$cls['ca_id']}%' AND LENGTH(ca_id)=6");
            $sub_min_row = q1($conn, "SELECT MIN(ca_order) AS m FROM `{$p}shop_category` WHERE ca_id LIKE '{$cls['ca_id']}%' AND LENGTH(ca_id)=6");
            $sub_cnt = (int)($sub_cnt_row['cnt'] ?? 0);
            $sub_min = (int)($sub_min_row['m'] ?? 0);
            $ok_cls = ($sub_cnt == 0 || $sub_min == 1);
            ?>
            <tr style="border-bottom:1px solid #eee;background:<?=$ok_cls?'inherit':'#fdf5f5';?>;">
                <td style="padding:6px 10px;font-weight:bold;"><?=htmlspecialchars($cls['ca_id'])?></td>
                <td style="padding:6px 10px;"><?=htmlspecialchars($cls['ca_name'])?></td>
                <td style="padding:6px 10px;text-align:center;"><?=$cls['ca_order']?></td>
                <td style="padding:6px 10px;text-align:center;"><?=$sub_cnt?></td>
                <td style="padding:6px 10px;text-align:center;">
                    <?php if ($sub_cnt == 0): ?>
                        <span style="color:#aaa;">-</span>
                    <?php elseif ($ok_cls): ?>
                        <span style="color:#27ae60;">✅ <?=$sub_min?></span>
                    <?php else: ?>
                        <span style="color:#c0392b;">❌ <?=$sub_min?> (수정 필요)</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p style="color:#888;">A~C클래스 데이터가 없습니다. install_shop_categories.sql을 먼저 실행하세요.</p>
    <?php endif; ?>
</div>

<?php elseif ($action === 'register_diag'): ?>

<!-- ════════════════════ 회원가입 진단 탭 ════════════════════ -->
<div class="card">
    <h2>🔧 회원가입 관련 진단</h2>

    <?php
    // 1) car_brand 테이블 존재 여부
    $car_brand_exists = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$p}car_brand'");
    $car_series_exists = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$p}car_series'");
    $car_model_exists  = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$p}car_model'");

    // 2) 각 테이블 레코드 수
    $brand_cnt  = ($car_brand_exists['cnt']  ?? 0) ? q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}car_brand`  WHERE brand_use=1")  : ['cnt'=>0];
    $series_cnt = ($car_series_exists['cnt'] ?? 0) ? q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}car_series` WHERE series_use=1") : ['cnt'=>0];
    $model_cnt  = ($car_model_exists['cnt']  ?? 0) ? q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}car_model`  WHERE model_use=1")  : ['cnt'=>0];

    // 3) g5_shop_category 분류 수
    $shop_2 = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}shop_category` WHERE LENGTH(ca_id)=4");
    $shop_3 = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}shop_category` WHERE LENGTH(ca_id)>=6");

    // 4) mb_7 컬럼 존재 여부
    $mb7_exists = q1($conn, "SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$p}member' AND COLUMN_NAME='mb_7'");
    ?>

    <h3 style="font-size:15px; margin-bottom:10px;">① 차종 선택 테이블 상태</h3>
    <table>
        <tr><th>테이블</th><th>존재</th><th>레코드 수 (use=1)</th><th>비고</th></tr>
        <tr>
            <td><code><?=$p?>car_brand</code></td>
            <td><?=($car_brand_exists['cnt']??0) ? "<span class='ok'>✅ 있음</span>" : "<span class='err'>❌ 없음</span>"?></td>
            <td><?=number_format($brand_cnt['cnt'])?></td>
            <td>브랜드 선택 셀렉트용</td>
        </tr>
        <tr>
            <td><code><?=$p?>car_series</code></td>
            <td><?=($car_series_exists['cnt']??0) ? "<span class='ok'>✅ 있음</span>" : "<span class='err'>❌ 없음</span>"?></td>
            <td><?=number_format($series_cnt['cnt'])?></td>
            <td>시리즈/연식 선택 셀렉트용</td>
        </tr>
        <tr>
            <td><code><?=$p?>car_model</code></td>
            <td><?=($car_model_exists['cnt']??0) ? "<span class='ok'>✅ 있음</span>" : "<span class='err'>❌ 없음</span>"?></td>
            <td><?=number_format($model_cnt['cnt'])?></td>
            <td>모델 선택 셀렉트용</td>
        </tr>
        <tr>
            <td><code><?=$p?>shop_category</code> 2차 분류 (4자리)</td>
            <td>-</td>
            <td><?=number_format($shop_2['cnt'])?></td>
            <td>시리즈 분류 (예: 1001, 1002...)</td>
        </tr>
        <tr>
            <td><code><?=$p?>shop_category</code> 3차 분류 (6자리+)</td>
            <td>-</td>
            <td>
                <?php $mc = (int)($shop_3['cnt']??0); ?>
                <?=$mc > 0 ? "<span class='ok'>✅ ".number_format($mc)."개</span>" : "<span class='err'>❌ 없음 → install_shop_categories.sql 실행 필요</span>"?>
            </td>
            <td>모델 분류 (예: 100101, 100102...)</td>
        </tr>
    </table>

    <hr class="section-divider">

    <h3 style="font-size:15px; margin-bottom:10px;">② 회원 유형 구분 필드 상태</h3>
    <table>
        <tr><th>컬럼</th><th>존재</th><th>비고</th></tr>
        <tr>
            <td><code>mb_7</code> (회원유형)</td>
            <td><?=($mb7_exists['cnt']??0) ? "<span class='ok'>✅ 있음</span>" : "<span class='err'>❌ 없음</span>"?></td>
            <td>'normal' | 'business' 값 저장</td>
        </tr>
    </table>
    <?php if ($mb7_exists['cnt']??0): ?>
    <?php
    $mb7_empty = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member` WHERE mb_7='' OR mb_7 IS NULL");
    $mb7_normal = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member` WHERE mb_7='normal'");
    $mb7_biz    = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member` WHERE mb_7='business'");
    ?>
    <table style="margin-top:8px;">
        <tr><th>mb_7 값</th><th>회원 수</th></tr>
        <tr><td>일반 (normal)</td><td><?=number_format($mb7_normal['cnt'])?></td></tr>
        <tr><td>사업자 (business)</td><td><?=number_format($mb7_biz['cnt'])?></td></tr>
        <tr>
            <td>미설정 (비어있음)</td>
            <td>
                <?php $ec = (int)($mb7_empty['cnt']??0); ?>
                <?=$ec > 0 ? "<span class='warn'>⚠️ {$ec}명 미설정 → install_member_type.sql 실행 권장</span>" : "<span class='ok'>✅ 없음 (정상)"?>
            </td>
        </tr>
    </table>
    <?php endif; ?>

    <hr class="section-divider">

    <h3 style="font-size:15px; margin-bottom:10px;">③ 차종 등록 회원 현황</h3>
    <?php
    $car_reg_members = q($conn, "SELECT mb_id, mb_name, mb_nick, mb_7, mb_1, mb_2, mb_3, mb_4, mb_5, mb_6
        FROM `{$p}member` WHERE mb_4 != '' AND mb_4 != '0' AND mb_4 IS NOT NULL
        ORDER BY mb_datetime DESC LIMIT 10");
    $car_unreg_count = q1($conn, "SELECT COUNT(*) AS cnt FROM `{$p}member` WHERE mb_4='' OR mb_4='0' OR mb_4 IS NULL");
    ?>
    <p style="font-size:13px; color:#666;">
        차종 등록 회원: <strong class="ok"><?=count($car_reg_members)?>명 (최근 10명 표시)</strong> |
        차종 미등록 회원: <strong><?=number_format($car_unreg_count['cnt'])?></strong>명
    </p>
    <?php if ($car_reg_members): ?>
    <table>
        <tr><th>ID</th><th>이름</th><th>회원유형</th><th>브랜드</th><th>시리즈</th><th>모델</th><th>ID (mb_4/5/6)</th></tr>
        <?php foreach ($car_reg_members as $m): ?>
        <tr>
            <td><?=htmlspecialchars($m['mb_id'])?></td>
            <td><?=htmlspecialchars($m['mb_name'])?></td>
            <td>
                <?php if ($m['mb_7']==='business'): ?>
                    <span class="badge b-blue">사업자</span>
                <?php else: ?>
                    <span class="badge b-ok">일반</span>
                <?php endif; ?>
            </td>
            <td><?=htmlspecialchars($m['mb_1'])?></td>
            <td><?=htmlspecialchars($m['mb_2'])?></td>
            <td><?=htmlspecialchars($m['mb_3'])?></td>
            <td><code><?=$m['mb_4']?>/<?=$m['mb_5']?>/<?=$m['mb_6']?></code></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
    <p style="color:#888;">아직 차종 등록 회원이 없습니다.</p>
    <?php endif; ?>

    <hr class="section-divider">

    <h3 style="font-size:15px; margin-bottom:10px;">④ 주요 파일 존재 여부</h3>
    <?php
    $base_path = dirname(__DIR__);
    $files_to_check = [
        'extend/partsds.extend.php'                          => '회원가입 이벤트 훅 (핵심)',
        'partsds/register_car_field.php'                     => '차종선택 폼 HTML',
        'partsds/css/brand_selector.css'                     => '차종선택 CSS',
        'partsds/install_shop_categories.sql'                => '3차분류 SQL (실행 필요)',
        'partsds/install_member_type.sql'                    => '회원유형 초기화 SQL',
        'theme/eb4_basic/skin/member/basic/register_form.skin.html.php' => '회원가입 폼 스킨',
    ];
    ?>
    <table>
        <tr><th>파일</th><th>상태</th><th>설명</th></tr>
        <?php foreach ($files_to_check as $fp => $desc): ?>
        <tr>
            <td><code><?=$fp?></code></td>
            <td><?=file_exists($base_path.'/'.$fp) ? "<span class='ok'>✅ 있음</span>" : "<span class='err'>❌ 없음</span>"?></td>
            <td><?=$desc?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <hr class="section-divider">

    <h3 style="font-size:15px; margin-bottom:10px;">⑤ partsds.extend.php 오류 점검</h3>
    <?php
    $extend_file = $base_path . '/extend/partsds.extend.php';
    if (file_exists($extend_file)) {
        $extend_content = file_get_contents($extend_file);
        $has_wrong_key   = strpos($extend_content, "g5['g5_member_table']") !== false;
        $has_correct_key = strpos($extend_content, "g5['member_table']") !== false;
    ?>
    <table>
        <tr><th>점검 항목</th><th>결과</th></tr>
        <tr>
            <td>잘못된 테이블 키 사용 <code>$g5['g5_member_table']</code></td>
            <td><?=$has_wrong_key
                ? "<span class='err'>❌ 발견됨! → HTTP 500 오류 원인. extend/partsds.extend.php 교체 필요</span>"
                : "<span class='ok'>✅ 없음 (정상)</span>"?></td>
        </tr>
        <tr>
            <td>올바른 테이블 키 사용 <code>$g5['member_table']</code></td>
            <td><?=$has_correct_key
                ? "<span class='ok'>✅ 있음 (정상)</span>"
                : "<span class='warn'>⚠️ 없음</span>"?></td>
        </tr>
    </table>
    <?php } else { echo "<p class='err'>❌ extend/partsds.extend.php 파일 없음</p>"; } ?>

    <div style="margin-top:16px; padding:12px; background:#e8f4fd; border-radius:6px; font-size:13px;">
        <strong>📋 회원가입 500 오류 해결 순서:</strong>
        <ol style="margin:8px 0 0; line-height:2;">
            <li>서버에 <code>extend/partsds.extend.php</code> 최신 버전 업로드 (FTP/Git)</li>
            <li><a href="sql_runner.php?key=<?=$key?>&file=install_member_type" target="_blank">install_member_type.sql 실행</a> → 기존 회원 mb_7 기본값 'normal' 설정</li>
            <li><a href="sql_runner.php?key=<?=$key?>&file=install_shop_categories" target="_blank">install_shop_categories.sql 실행</a> → 3차분류 4,089개 INSERT</li>
            <li><a href="sql_runner.php?key=<?=$key?>&file=update_ca_id" target="_blank">update_ca_id.sql 실행</a> → car_series/model ca_id 매핑</li>
            <li>회원가입 테스트</li>
        </ol>
    </div>
</div>

<?php endif; // action ?>

<?php endif; // conn_ok ?>

<div style="margin-top:16px; padding:10px; background:#f9f9f9; border-radius:4px; font-size:12px; color:#888;">
    <strong>⚠️ 보안 주의:</strong> 작업 완료 후 이 파일을 삭제하거나 파일명을 변경하세요.
    | <?=date('Y-m-d H:i:s')?>
</div>

</body>
</html>
<?php $conn->close(); ?>
