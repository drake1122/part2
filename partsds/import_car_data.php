<?php
/**
 * PartsDS 차종 데이터 가져오기 스크립트
 * 이전 저장소에서 수집한 브랜드/시리즈/모델 데이터를 현재 DB에 INSERT
 * 
 * 사용법: 브라우저에서 https://[사이트]/partsds/import_car_data.php 접속
 * 
 * ⚠️  주의: 실행 후 이 파일은 반드시 삭제하세요 (보안)
 */

// 보안: 직접 접근 허용 (gnuboard common 없이도 동작)
if (file_exists(dirname(__DIR__) . '/common.php')) {
    @include_once(dirname(__DIR__) . '/common.php');
}

// DB 연결 (gnuboard common 없을 경우 직접 연결)
if (!isset($db_host) && file_exists(dirname(__DIR__) . '/config.php')) {
    include_once(dirname(__DIR__) . '/config.php');
}

// DB 직접 연결 처리
if (!isset($conn) && !isset($g5)) {
    // gnuboard config.php에서 DB 정보 읽기
    $config_file = dirname(__DIR__) . '/config.php';
    if (file_exists($config_file)) {
        $config_content = file_get_contents($config_file);
        preg_match("/define\s*\(\s*'DB_HOST'\s*,\s*'([^']+)'\s*\)/", $config_content, $m);
        $db_host = $m[1] ?? 'localhost';
        preg_match("/define\s*\(\s*'DB_USER'\s*,\s*'([^']+)'\s*\)/", $config_content, $m);
        $db_user = $m[1] ?? '';
        preg_match("/define\s*\(\s*'DB_PASSWORD'\s*,\s*'([^']+)'\s*\)/", $config_content, $m);
        $db_pass = $m[1] ?? '';
        preg_match("/define\s*\(\s*'DB_NAME'\s*,\s*'([^']+)'\s*\)/", $config_content, $m);
        $db_name = $m[1] ?? '';
    }
}

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>PartsDS 차종 데이터 가져오기</title>
<style>
body { font-family: 'Noto Sans KR', sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; background: #f5f5f5; }
.card { background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
.info { background: #e8f4fd; border-left: 4px solid #007bff; padding: 15px; margin: 15px 0; border-radius: 4px; }
.warning { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 15px 0; border-radius: 4px; }
.success { background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 15px 0; border-radius: 4px; }
.error { background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 15px 0; border-radius: 4px; }
table { width: 100%; border-collapse: collapse; margin: 10px 0; }
th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
th { background: #f8f9fa; font-weight: 600; }
.btn { display: inline-block; padding: 12px 24px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; }
.btn:hover { background: #0056b3; }
.btn-danger { background: #dc3545; }
.btn-danger:hover { background: #c82333; }
pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 13px; max-height: 300px; overflow-y: auto; }
</style>
</head>
<body>

<div class="card">
<h1>🚗 PartsDS 차종 데이터 가져오기</h1>

<?php

$action = $_GET['action'] ?? '';

// ── DB 연결 ──
function get_db_connection() {
    // gnuboard의 $g5 전역변수 활용
    global $g5;
    if (isset($g5['db_host'])) {
        $conn = new mysqli($g5['db_host'], $g5['db_user'], $g5['db_pass'], $g5['db_name']);
        $conn->set_charset('utf8mb4');
        return $conn;
    }
    
    // config.php 직접 파싱
    $config_file = dirname(__DIR__) . '/config.php';
    if (file_exists($config_file)) {
        $cfg = file_get_contents($config_file);
        
        preg_match("/define\s*\(\s*['\"]DB_HOST['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $cfg, $m); $host = $m[1] ?? 'localhost';
        preg_match("/define\s*\(\s*['\"]DB_USER['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $cfg, $m); $user = $m[1] ?? '';
        preg_match("/define\s*\(\s*['\"]DB_PASSWORD['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $cfg, $m); $pass = $m[1] ?? '';
        preg_match("/define\s*\(\s*['\"]DB_NAME['\"]\s*,\s*['\"]([^'\"]+)['\"]\s*\)/", $cfg, $m); $name = $m[1] ?? '';
        
        $conn = new mysqli($host, $user, $pass, $name);
        if (!$conn->connect_error) {
            $conn->set_charset('utf8mb4');
            return $conn;
        }
    }
    return null;
}

if ($action === 'run') {
    // ── 실제 SQL 실행 ──
    $conn = get_db_connection();
    if (!$conn || $conn->connect_error) {
        echo '<div class="error">❌ DB 연결 실패: ' . htmlspecialchars($conn ? $conn->connect_error : 'config.php를 찾을 수 없습니다.') . '</div>';
    } else {
        $sql_file = __DIR__ . '/partsds_data.sql';
        if (!file_exists($sql_file)) {
            echo '<div class="error">❌ partsds_data.sql 파일을 찾을 수 없습니다.</div>';
        } else {
            $sql_content = file_get_contents($sql_file);
            
            // SQL 문장 분리
            $statements = [];
            $current = '';
            $lines = explode("\n", $sql_content);
            
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (empty($trimmed) || strpos($trimmed, '--') === 0) continue;
                $current .= $line . "\n";
                if (substr(rtrim($trimmed), -1) === ';') {
                    $statements[] = trim($current);
                    $current = '';
                }
            }
            
            $success = 0;
            $errors = [];
            $start_time = microtime(true);
            
            foreach ($statements as $stmt) {
                if (empty(trim($stmt))) continue;
                if (!$conn->query($stmt)) {
                    $errors[] = $conn->error . ' | SQL: ' . substr($stmt, 0, 100);
                } else {
                    $success++;
                }
            }
            
            $elapsed = round(microtime(true) - $start_time, 2);
            
            // 결과 카운트
            $brand_count = $conn->query("SELECT COUNT(*) as cnt FROM car_brand")->fetch_assoc()['cnt'] ?? 0;
            $series_count = $conn->query("SELECT COUNT(*) as cnt FROM car_series")->fetch_assoc()['cnt'] ?? 0;
            $model_count = $conn->query("SELECT COUNT(*) as cnt FROM car_model")->fetch_assoc()['cnt'] ?? 0;
            
            echo '<div class="success">';
            echo '<h3>✅ 데이터 가져오기 완료!</h3>';
            echo "<p>실행 시간: {$elapsed}초 | 성공: {$success}개 구문</p>";
            echo '<table>';
            echo '<tr><th>테이블</th><th>데이터 수</th></tr>';
            echo "<tr><td>car_brand (브랜드)</td><td><strong>{$brand_count}개</strong></td></tr>";
            echo "<tr><td>car_series (시리즈)</td><td><strong>{$series_count}개</strong></td></tr>";
            echo "<tr><td>car_model (모델)</td><td><strong>{$model_count}개</strong></td></tr>";
            echo '</table>';
            echo '</div>';
            
            if (!empty($errors)) {
                echo '<div class="warning"><strong>⚠️ 일부 오류 (' . count($errors) . '개):</strong><pre>' . htmlspecialchars(implode("\n", array_slice($errors, 0, 10))) . '</pre></div>';
            }
            
            echo '<div class="warning">⚠️ 보안을 위해 이 파일(import_car_data.php)을 서버에서 삭제해주세요!</div>';
        }
        $conn->close();
    }
    
} elseif ($action === 'check') {
    // ── 현재 DB 상태 확인 ──
    $conn = get_db_connection();
    if (!$conn || $conn->connect_error) {
        echo '<div class="error">❌ DB 연결 실패</div>';
    } else {
        echo '<div class="info"><h3>현재 DB 상태</h3><table>';
        $tables = ['car_brand', 'car_series', 'car_model', 'item_car'];
        foreach ($tables as $tbl) {
            $res = $conn->query("SELECT COUNT(*) as cnt FROM `{$tbl}`");
            if ($res) {
                $cnt = $res->fetch_assoc()['cnt'];
                echo "<tr><td>{$tbl}</td><td>{$cnt}개</td></tr>";
            } else {
                echo "<tr><td>{$tbl}</td><td>테이블 없음</td></tr>";
            }
        }
        echo '</table></div>';
        
        // 샘플 데이터
        $res = $conn->query("SELECT brand_id, brand_name, brand_name_en FROM car_brand ORDER BY brand_order LIMIT 20");
        if ($res && $res->num_rows > 0) {
            echo '<div class="success"><h3>✅ 브랜드 목록</h3><table><tr><th>ID</th><th>브랜드명</th><th>영문명</th></tr>';
            while ($row = $res->fetch_assoc()) {
                echo "<tr><td>{$row['brand_id']}</td><td>{$row['brand_name']}</td><td>{$row['brand_name_en']}</td></tr>";
            }
            echo '</table></div>';
        }
        $conn->close();
    }
} else {
    // ── 기본 화면 ──
    $sql_file = __DIR__ . '/partsds_data.sql';
    $file_size = file_exists($sql_file) ? round(filesize($sql_file)/1024, 1) . ' KB' : '파일 없음';
    
    echo '<div class="info">';
    echo '<h3>📋 가져올 데이터 정보</h3>';
    echo '<table>';
    echo '<tr><th>항목</th><th>수량</th></tr>';
    echo '<tr><td>브랜드</td><td><strong>14개</strong> (벤츠, BMW, 아우디, 포르쉐, 미니, 랜드로버, 폭스바겐, 볼보, 지프, 테슬라, 재규어, 렉서스, 도요타, 혼다)</td></tr>';
    echo '<tr><td>시리즈</td><td><strong>539개</strong></td></tr>';
    echo '<tr><td>모델</td><td><strong>3,536개</strong></td></tr>';
    echo "<tr><td>SQL 파일</td><td>partsds_data.sql ({$file_size})</td></tr>";
    echo '</table></div>';
    
    echo '<div class="warning">⚠️ 실행하면 기존 car_brand, car_series, car_model 데이터가 <strong>모두 삭제</strong>되고 새 데이터로 교체됩니다.</div>';
    
    echo '<p>';
    echo '<a href="?action=check" class="btn" style="margin-right:10px;">현재 DB 상태 확인</a> ';
    echo '<a href="?action=run" class="btn btn-danger" onclick="return confirm(\'기존 데이터가 삭제되고 새 데이터로 교체됩니다. 계속하시겠습니까?\')">데이터 가져오기 실행</a>';
    echo '</p>';
}

?>
</div>

<div class="card">
<h3>📌 사용 방법</h3>
<ol>
<li>먼저 <strong>[현재 DB 상태 확인]</strong> 버튼을 눌러 현재 상태를 확인하세요.</li>
<li><strong>[데이터 가져오기 실행]</strong> 버튼을 눌러 데이터를 가져옵니다.</li>
<li>완료 후 반드시 이 파일(<code>import_car_data.php</code>)을 서버에서 삭제하세요.</li>
</ol>
<p><strong>파일 경로:</strong> <code>/partsds/import_car_data.php</code></p>
</div>

</body>
</html>
