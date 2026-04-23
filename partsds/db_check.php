<?php
/**
 * PartSDS DB 진단 도구
 * car_series, car_model 테이블의 실제 데이터를 확인합니다.
 * 특히 A/B/C 클래스 시리즈와 모델 매핑 상태를 점검합니다.
 */

define('SQL_RUNNER_KEY', 'partsds2024!');

$input_key = isset($_GET['key']) ? $_GET['key'] : '';
if ($input_key !== SQL_RUNNER_KEY) {
    http_response_code(403);
    die('<h2>403 Forbidden</h2>');
}

// DB 연결
if (file_exists(dirname(__DIR__) . '/config.php')) {
    require_once(dirname(__DIR__) . '/config.php');
}
if (!defined('G5_DB_HOST') && file_exists(dirname(__DIR__) . '/extend/config.php')) {
    require_once(dirname(__DIR__) . '/extend/config.php');
}

$db_host = defined('G5_DB_HOST') ? G5_DB_HOST : 'localhost';
$db_user = defined('G5_DB_USER') ? G5_DB_USER : '';
$db_pass = defined('G5_DB_PASS') ? G5_DB_PASS : '';
$db_name = defined('G5_DB_NAME') ? G5_DB_NAME : '';

if (!$db_user) {
    $common_content = file_get_contents(dirname(__DIR__) . '/config.php');
    preg_match("/define\('G5_DB_HOST',\s*'([^']+)'\)/", $common_content, $m); $db_host = $m[1] ?? 'localhost';
    preg_match("/define\('G5_DB_USER',\s*'([^']+)'\)/", $common_content, $m); $db_user = $m[1] ?? '';
    preg_match("/define\('G5_DB_PASS',\s*'([^']+)'\)/", $common_content, $m); $db_pass = $m[1] ?? '';
    preg_match("/define\('G5_DB_NAME',\s*'([^']+)'\)/", $common_content, $m); $db_name = $m[1] ?? '';
}

$prefix = defined('G5_TABLE_PREFIX') ? G5_TABLE_PREFIX : 'g5_';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$conn->set_charset('utf8mb4');

$action = isset($_GET['action']) ? $_GET['action'] : 'check';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>PartSDS DB 진단</title>
<style>
body { font-family: 'Malgun Gothic', sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
h1,h2,h3 { color: #333; }
h1 { border-bottom: 3px solid #e67e00; padding-bottom: 8px; }
.card { background: #fff; border-radius: 8px; padding: 20px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.ok  { color: #27ae60; font-weight: bold; }
.err { color: #c0392b; font-weight: bold; }
.warn { color: #e67e22; font-weight: bold; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
td, th { padding: 6px 10px; border: 1px solid #ddd; text-align: left; }
th { background: #f0f0f0; font-weight: bold; }
tr:nth-child(even) { background: #f9f9f9; }
.log { background: #1e1e1e; color: #d4d4d4; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px; max-height: 400px; overflow-y: auto; white-space: pre-wrap; }
.btn { display: inline-block; padding: 8px 18px; background: #e67e00; color: #fff; text-decoration: none; border-radius: 4px; font-size: 14px; margin: 4px; border: none; cursor: pointer; }
.btn-blue { background: #2980b9; }
.btn-red { background: #c0392b; }
.btn-green { background: #27ae60; }
.badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:bold; }
.badge-ok { background:#d5f5e3; color:#1e8449; }
.badge-err { background:#fadbd8; color:#922b21; }
.badge-warn { background:#fef9e7; color:#7d6608; }
</style>
</head>
<body>
<h1>🔍 PartSDS DB 진단 도구</h1>

<?php if ($conn->connect_error): ?>
<div class="card"><p class="err">❌ DB 연결 실패: <?= htmlspecialchars($conn->connect_error) ?></p></div>
<?php else: ?>

<div class="card">
<p class="ok">✅ DB 연결 성공 (<?= htmlspecialchars($db_name) ?>)</p>
<p>
  <a href="?key=<?=urlencode($input_key)?>&action=check" class="btn">🔍 전체 진단</a>
  <a href="?key=<?=urlencode($input_key)?>&action=benz_series" class="btn btn-blue">🚗 벤츠 시리즈 목록</a>
  <a href="?key=<?=urlencode($input_key)?>&action=abc_models" class="btn btn-blue">📋 A/B/C 클래스 모델</a>
  <a href="?key=<?=urlencode($input_key)?>&action=ca_check" class="btn btn-blue">🗂️ 쇼핑몰 분류 확인</a>
  <a href="?key=<?=urlencode($input_key)?>&action=fix_abc" class="btn btn-red">🔧 A/B/C 모델 ca_id 재매핑</a>
</p>
</div>

<?php

// ═══════════════════════════════════════════════════════════
// 1. 전체 진단
// ═══════════════════════════════════════════════════════════
if ($action === 'check') {
    echo '<div class="card"><h2>📊 테이블 현황</h2>';
    
    $tables = [
        $prefix.'car_brand'      => ['brand_id', 'brand_name', 'ca_id'],
        $prefix.'car_series'     => ['series_id', 'brand_id', 'series_name', 'ca_id'],
        $prefix.'car_model'      => ['model_id', 'series_id', 'model_name', 'ca_id'],
        $prefix.'shop_category'  => ['ca_id', 'ca_name'],
    ];
    
    echo '<table>';
    echo '<tr><th>테이블</th><th>레코드 수</th><th>ca_id 있는 것</th><th>ca_id 없는 것</th><th>상태</th></tr>';
    
    foreach ($tables as $tbl => $cols) {
        $cnt = $conn->query("SELECT COUNT(*) c FROM `$tbl`");
        if (!$cnt) { 
            echo "<tr><td>$tbl</td><td colspan='4' class='err'>테이블 없음</td></tr>";
            continue;
        }
        $total = $cnt->fetch_assoc()['c'];
        
        $with_ca = 0; $without_ca = 0;
        if (in_array('ca_id', $cols)) {
            $r = $conn->query("SELECT COUNT(*) c FROM `$tbl` WHERE ca_id != '' AND ca_id IS NOT NULL");
            $with_ca = $r->fetch_assoc()['c'];
            $r = $conn->query("SELECT COUNT(*) c FROM `$tbl` WHERE ca_id = '' OR ca_id IS NULL");
            $without_ca = $r->fetch_assoc()['c'];
        }
        
        $status = '';
        if (in_array('ca_id', $cols)) {
            if ($without_ca == 0) $status = '<span class="badge badge-ok">정상</span>';
            elseif ($with_ca == 0) $status = '<span class="badge badge-err">미매핑</span>';
            else $status = '<span class="badge badge-warn">일부누락</span>';
        } else {
            $status = '<span class="badge badge-ok">-</span>';
        }
        
        echo "<tr><td><code>$tbl</code></td><td>$total</td><td>$with_ca</td><td>$without_ca</td><td>$status</td></tr>";
    }
    echo '</table></div>';
    
    // 벤츠 브랜드의 A/B/C 클래스 시리즈 확인
    echo '<div class="card"><h2>🚗 벤츠(brand_id=1) A/B/C 클래스 시리즈 상태</h2>';
    $res = $conn->query("SELECT series_id, series_name, ca_id FROM `{$prefix}car_series` WHERE brand_id=1 AND (series_name LIKE 'A-%' OR series_name LIKE 'B-%' OR series_name LIKE 'C-%') ORDER BY series_id");
    if ($res && $res->num_rows > 0) {
        echo '<table><tr><th>series_id</th><th>series_name</th><th>ca_id</th><th>상태</th></tr>';
        while ($row = $res->fetch_assoc()) {
            $status = $row['ca_id'] ? '<span class="badge badge-ok">매핑됨</span>' : '<span class="badge badge-err">미매핑</span>';
            echo "<tr><td>{$row['series_id']}</td><td>{$row['series_name']}</td><td>{$row['ca_id']}</td><td>$status</td></tr>";
        }
        echo '</table>';
    } else {
        echo '<p class="err">A/B/C 클래스 시리즈를 찾을 수 없습니다.</p>';
    }
    echo '</div>';
    
    // A클래스 모델 확인
    echo '<div class="card"><h2>📋 A클래스 W177 모델 상태</h2>';
    $res = $conn->query("SELECT cs.series_id, cs.series_name, cs.ca_id as series_ca_id, COUNT(cm.model_id) as model_cnt, SUM(CASE WHEN cm.ca_id != '' THEN 1 ELSE 0 END) as mapped_cnt 
        FROM `{$prefix}car_series` cs 
        LEFT JOIN `{$prefix}car_model` cm ON cm.series_id = cs.series_id
        WHERE cs.brand_id=1 AND cs.series_name LIKE 'A-%'
        GROUP BY cs.series_id ORDER BY cs.series_id");
    if ($res && $res->num_rows > 0) {
        echo '<table><tr><th>series_id</th><th>series_name</th><th>시리즈 ca_id</th><th>모델 수</th><th>ca_id 매핑된 모델</th><th>상태</th></tr>';
        while ($row = $res->fetch_assoc()) {
            $status = ($row['model_cnt'] > 0 && $row['mapped_cnt'] == $row['model_cnt']) 
                ? '<span class="badge badge-ok">완료</span>'
                : ($row['model_cnt'] == 0 ? '<span class="badge badge-err">모델없음</span>' : '<span class="badge badge-warn">일부누락</span>');
            echo "<tr><td>{$row['series_id']}</td><td>{$row['series_name']}</td><td>{$row['series_ca_id']}</td><td>{$row['model_cnt']}</td><td>{$row['mapped_cnt']}</td><td>$status</td></tr>";
        }
        echo '</table>';
    }
    echo '</div>';
}

// ═══════════════════════════════════════════════════════════
// 2. 벤츠 시리즈 목록
// ═══════════════════════════════════════════════════════════
elseif ($action === 'benz_series') {
    echo '<div class="card"><h2>🚗 벤츠(brand_id=1) 전체 시리즈 목록</h2>';
    $res = $conn->query("SELECT series_id, series_name, ca_id FROM `{$prefix}car_series` WHERE brand_id=1 ORDER BY series_id");
    if ($res && $res->num_rows > 0) {
        echo '<p>총 <strong>' . $res->num_rows . '</strong>개 시리즈</p>';
        echo '<table><tr><th>series_id</th><th>series_name</th><th>ca_id</th><th>상태</th></tr>';
        while ($row = $res->fetch_assoc()) {
            $status = $row['ca_id'] ? '<span class="badge badge-ok">매핑됨</span>' : '<span class="badge badge-err">미매핑</span>';
            echo "<tr><td>{$row['series_id']}</td><td>{$row['series_name']}</td><td>{$row['ca_id']}</td><td>$status</td></tr>";
        }
        echo '</table>';
    } else {
        echo '<p class="err">벤츠 시리즈 없음</p>';
    }
    echo '</div>';
}

// ═══════════════════════════════════════════════════════════
// 3. A/B/C 클래스 모델 목록
// ═══════════════════════════════════════════════════════════
elseif ($action === 'abc_models') {
    echo '<div class="card"><h2>📋 A/B/C 클래스 시리즈 및 모델</h2>';
    
    $res = $conn->query("SELECT series_id, series_name, ca_id FROM `{$prefix}car_series` WHERE brand_id=1 AND (series_name LIKE 'A-%' OR series_name LIKE 'B-%' OR series_name LIKE 'C-%') ORDER BY series_id");
    
    while ($row = $res->fetch_assoc()) {
        $sid = $row['series_id'];
        $sname = htmlspecialchars($row['series_name']);
        $sca = htmlspecialchars($row['ca_id']);
        
        echo "<h3>시리즈: {$sname} (series_id={$sid}, ca_id={$sca})</h3>";
        
        $mres = $conn->query("SELECT model_id, model_name, model_year, ca_id FROM `{$prefix}car_model` WHERE series_id={$sid} ORDER BY model_id");
        if ($mres && $mres->num_rows > 0) {
            echo '<table><tr><th>model_id</th><th>model_name</th><th>model_year</th><th>ca_id</th><th>상태</th></tr>';
            while ($mrow = $mres->fetch_assoc()) {
                $status = $mrow['ca_id'] ? '<span class="badge badge-ok">매핑됨</span>' : '<span class="badge badge-err">미매핑</span>';
                echo "<tr><td>{$mrow['model_id']}</td><td>" . htmlspecialchars($mrow['model_name']) . "</td><td>" . htmlspecialchars($mrow['model_year']) . "</td><td>{$mrow['ca_id']}</td><td>$status</td></tr>";
            }
            echo '</table>';
        } else {
            echo '<p class="err">모델 없음</p>';
        }
    }
    echo '</div>';
}

// ═══════════════════════════════════════════════════════════
// 4. 쇼핑몰 분류 확인 (벤츠 ca_id=10 관련)
// ═══════════════════════════════════════════════════════════
elseif ($action === 'ca_check') {
    echo '<div class="card"><h2>🗂️ 쇼핑몰 분류 현황 (벤츠=10, 시리즈=1001~, 모델=100101~)</h2>';
    
    // 브랜드 분류 (2자리)
    echo '<h3>브랜드 분류 (ca_id 10~140)</h3>';
    $res = $conn->query("SELECT ca_id, ca_name FROM `{$prefix}shop_category` WHERE LENGTH(ca_id) <= 3 ORDER BY ca_id+0");
    if ($res && $res->num_rows > 0) {
        echo '<table><tr><th>ca_id</th><th>ca_name</th></tr>';
        while ($row = $res->fetch_assoc()) {
            echo "<tr><td>{$row['ca_id']}</td><td>" . htmlspecialchars($row['ca_name']) . "</td></tr>";
        }
        echo '</table>';
    } else {
        echo '<p class="err">브랜드 분류 없음 → install_shop_categories.sql 실행 필요</p>';
    }
    
    // A/B/C 클래스 시리즈 분류 (4자리)
    echo '<h3>벤츠 A/B/C 시리즈 분류 (ca_id 1001~1009)</h3>';
    $res = $conn->query("SELECT ca_id, ca_name FROM `{$prefix}shop_category` WHERE ca_id BETWEEN '1001' AND '1009' ORDER BY ca_id+0");
    if ($res && $res->num_rows > 0) {
        echo '<table><tr><th>ca_id</th><th>ca_name</th></tr>';
        while ($row = $res->fetch_assoc()) {
            echo "<tr><td>{$row['ca_id']}</td><td>" . htmlspecialchars($row['ca_name']) . "</td></tr>";
        }
        echo '</table>';
    } else {
        echo '<p class="err">A/B/C 시리즈 분류 없음 → install_shop_categories.sql 실행 필요</p>';
    }
    
    // A클래스 W177 모델 분류 (6자리)
    echo '<h3>A클래스 W177 모델 분류 (ca_id 100101~)</h3>';
    $res = $conn->query("SELECT ca_id, ca_name FROM `{$prefix}shop_category` WHERE ca_id LIKE '1001%' AND LENGTH(ca_id)=6 ORDER BY ca_id+0 LIMIT 20");
    if ($res && $res->num_rows > 0) {
        echo '<table><tr><th>ca_id</th><th>ca_name</th></tr>';
        while ($row = $res->fetch_assoc()) {
            echo "<tr><td>{$row['ca_id']}</td><td>" . htmlspecialchars($row['ca_name']) . "</td></tr>";
        }
        echo '</table>';
    } else {
        echo '<p class="err">A클래스 W177 모델 분류 없음 → install_shop_categories.sql 실행 필요</p>';
    }
    
    // 전체 분류 수
    $cnt = $conn->query("SELECT COUNT(*) c FROM `{$prefix}shop_category`")->fetch_assoc()['c'];
    echo "<p>전체 쇼핑몰 분류 수: <strong>{$cnt}</strong>개</p>";
    echo '</div>';
}

// ═══════════════════════════════════════════════════════════
// 5. A/B/C 모델 ca_id 재매핑 (실제 series_id 기반으로)
// ═══════════════════════════════════════════════════════════
elseif ($action === 'fix_abc') {
    echo '<div class="card"><h2>🔧 A/B/C 클래스 모델 ca_id 재매핑</h2>';
    echo '<p class="warn">⚠️ 이 작업은 실제 series_id를 조회하여 car_model.ca_id를 올바르게 설정합니다.</p>';
    
    // 벤츠 시리즈들의 실제 series_id와 ca_id 매핑 확인
    $res = $conn->query("SELECT series_id, series_name, ca_id FROM `{$prefix}car_series` WHERE brand_id=1 ORDER BY series_id");
    
    $series_ca_map = []; // series_id => ca_id
    echo '<div class="log">';
    echo "벤츠 시리즈 실제 매핑 상태:\n";
    
    while ($row = $res->fetch_assoc()) {
        $series_ca_map[(int)$row['series_id']] = $row['ca_id'];
        if (empty($row['ca_id'])) {
            echo "⚠ series_id={$row['series_id']} {$row['series_name']} → ca_id 없음\n";
        } else {
            echo "✓ series_id={$row['series_id']} {$row['series_name']} → ca_id={$row['ca_id']}\n";
        }
    }
    
    // 모델들의 ca_id 상태 체크
    echo "\n모델 ca_id 매핑 작업 시작...\n";
    
    $fixed = 0; $skipped = 0; $errors = 0;
    
    // series_id 기반으로 각 시리즈에 속한 모델들 처리
    $sres = $conn->query("SELECT series_id, ca_id FROM `{$prefix}car_series` WHERE brand_id=1 AND ca_id != ''");
    while ($srow = $sres->fetch_assoc()) {
        $sid = (int)$srow['series_id'];
        $series_ca = $srow['ca_id'];
        
        // 해당 시리즈의 모델들 중 ca_id가 없는 것 확인
        $mres = $conn->query("SELECT model_id, model_name FROM `{$prefix}car_model` WHERE series_id={$sid} AND (ca_id = '' OR ca_id IS NULL) ORDER BY model_id");
        
        if (!$mres || $mres->num_rows == 0) continue;
        
        // ca_id가 이미 있는 모델 중 최대 순번 확인 (같은 시리즈 내)
        $exist = $conn->query("SELECT ca_id FROM `{$prefix}car_model` WHERE series_id={$sid} AND ca_id != '' ORDER BY ca_id DESC LIMIT 1");
        
        // 기존 ca_id가 있으면 다음 번호부터, 없으면 {series_ca}01부터
        $next_seq = 1;
        if ($exist && $erow = $exist->fetch_assoc()) {
            $existing_ca = $erow['ca_id'];
            // 마지막 2자리가 순번
            $last_seq = (int)substr($existing_ca, -2);
            $next_seq = $last_seq + 1;
        }
        
        while ($mrow = $mres->fetch_assoc()) {
            $model_id = (int)$mrow['model_id'];
            $new_ca_id = $series_ca . str_pad($next_seq, 2, '0', STR_PAD_LEFT);
            
            $upd = $conn->query("UPDATE `{$prefix}car_model` SET ca_id='{$new_ca_id}' WHERE model_id={$model_id}");
            if ($upd) {
                echo "✓ model_id={$model_id} {$mrow['model_name']} → ca_id={$new_ca_id}\n";
                $fixed++;
                $next_seq++;
            } else {
                echo "✗ model_id={$model_id} 실패: " . $conn->error . "\n";
                $errors++;
            }
        }
    }
    
    echo "\n완료! 수정: {$fixed}개 / 오류: {$errors}개\n";
    echo '</div>';
    
    if ($fixed > 0) {
        echo '<p class="ok">✅ ca_id 매핑 완료. 이제 메인 페이지에서 A/B/C 클래스 선택 시 모델이 표시됩니다.</p>';
    }
    echo '</div>';
}

$conn->close();
?>

<?php endif; ?>

<div class="card" style="background:#fff3cd;">
  <strong>⚠️</strong> 작업 완료 후 이 파일을 삭제하세요: <code><?= __FILE__ ?></code>
</div>

</body>
</html>
