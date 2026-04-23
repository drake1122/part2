<?php
/**
 * PartSDS SQL Runner
 * URL 접속으로 SQL 파일을 실행하는 도구
 *
 * 사용법:
 *   1. fix_ca_skin.sql 실행:
 *      https://도메인/partsds/sql_runner.php?key=비밀키&file=fix_ca_skin
 *   2. install_shop_categories.sql 실행:
 *      https://도메인/partsds/sql_runner.php?key=비밀키&file=install_shop_categories
 *   3. update_ca_id.sql 실행:
 *      https://도메인/partsds/sql_runner.php?key=비밀키&file=update_ca_id
 *
 * ⚠️  실행 후 이 파일을 반드시 삭제하거나 key를 변경하세요!
 */

// ── 보안 키 설정 (반드시 변경하세요!) ────────────────────────────────────
define('SQL_RUNNER_KEY', 'partsds2024!');
// ────────────────────────────────────────────────────────────────────────

// 접근 인증
$input_key = isset($_GET['key']) ? $_GET['key'] : '';
if ($input_key !== SQL_RUNNER_KEY) {
    http_response_code(403);
    die('<h2>403 Forbidden</h2><p>올바른 키를 입력하세요.</p>');
}

// 허용된 SQL 파일 목록
$allowed_files = [
    'fix_ca_skin'             => __DIR__ . '/fix_ca_skin.sql',
    'install_shop_categories' => __DIR__ . '/install_shop_categories.sql',
    'update_ca_id'            => __DIR__ . '/update_ca_id.sql',
];

$file_key = isset($_GET['file']) ? preg_replace('/[^a-z0-9_]/', '', $_GET['file']) : '';

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PartSDS SQL Runner</title>
<style>
body { font-family: 'Malgun Gothic', sans-serif; max-width: 900px; margin: 30px auto; padding: 20px; background: #f5f5f5; }
h1 { color: #333; border-bottom: 3px solid #e67e00; padding-bottom: 10px; }
.card { background: #fff; border-radius: 8px; padding: 20px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.btn { display: inline-block; padding: 12px 24px; background: #e67e00; color: #fff; text-decoration: none; border-radius: 5px; font-size: 15px; margin: 5px; border: none; cursor: pointer; }
.btn:hover { background: #c96a00; }
.btn-danger { background: #c0392b; }
.btn-danger:hover { background: #a93226; }
.btn-success { background: #27ae60; }
.log { background: #1e1e1e; color: #d4d4d4; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 13px; max-height: 500px; overflow-y: auto; white-space: pre-wrap; margin-top: 15px; }
.ok  { color: #4ec9b0; }
.err { color: #f44747; }
.warn { color: #dcdcaa; }
.info { color: #9cdcfe; }
table { width: 100%; border-collapse: collapse; }
td, th { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
th { background: #f0f0f0; }
</style>
</head>
<body>

<h1>⚡ PartSDS SQL Runner</h1>

<?php if (!$file_key || !isset($allowed_files[$file_key])): ?>

<!-- 파일 선택 화면 -->
<div class="card">
    <h2>실행할 SQL 파일을 선택하세요</h2>
    <table>
        <tr><th>파일</th><th>설명</th><th>실행</th></tr>
        <tr>
            <td><code>fix_ca_skin.sql</code></td>
            <td>기존 분류 출력스킨 일괄 수정 (ca_skin='' 빈값, 이윰빌더 '선택' 상태)<br>
                <small>⚠️ 전체 UPDATE — 먼저 실행 권장</small></td>
            <td><a href="?key=<?=htmlspecialchars($input_key)?>&file=fix_ca_skin" class="btn">실행</a></td>
        </tr>
        <tr>
            <td><code>install_shop_categories.sql</code></td>
            <td>브랜드14+시리즈539+모델3,536 = 4,089개 분류 INSERT IGNORE<br>
                <small>⚠️ 대용량 SQL — 타임아웃 발생 시 phpMyAdmin 사용</small></td>
            <td><a href="?key=<?=htmlspecialchars($input_key)?>&file=install_shop_categories&confirm=1" class="btn btn-danger">실행</a></td>
        </tr>
        <tr>
            <td><code>update_ca_id.sql</code></td>
            <td>car_brand/car_series/car_model 테이블에 ca_id 컬럼 추가 및 값 업데이트</td>
            <td><a href="?key=<?=htmlspecialchars($input_key)?>&file=update_ca_id" class="btn">실행</a></td>
        </tr>
    </table>
</div>

<div class="card">
    <h3>⚠️ 주의사항</h3>
    <ul>
        <li>실행 순서: <strong>① fix_ca_skin → ② install_shop_categories → ③ update_ca_id</strong></li>
        <li>실행 완료 후 이 파일을 삭제하거나 파일명을 변경하세요</li>
        <li>대용량 SQL(install_shop_categories)은 phpMyAdmin이 더 안정적입니다</li>
    </ul>
</div>

<?php else:

    $sql_file = $allowed_files[$file_key];

    // 파일 존재 확인
    if (!file_exists($sql_file)) {
        echo '<div class="card"><p class="err">❌ SQL 파일을 찾을 수 없습니다: ' . htmlspecialchars($sql_file) . '</p></div>';
    } else {
        echo '<div class="card">';
        echo '<h2>실행: <code>' . htmlspecialchars($file_key) . '.sql</code></h2>';
        echo '<p>파일 크기: ' . number_format(filesize($sql_file)) . ' bytes | ';
        echo '라인 수: ' . number_format(count(file($sql_file))) . '</p>';

        // gnuboard DB 연결
        $gnuboard_config = dirname(__DIR__) . '/config.php';
        $gnuboard_common = dirname(__DIR__) . '/common.php';

        // DB 설정 읽기
        if (file_exists(dirname(__DIR__) . '/config.php')) {
            require_once(dirname(__DIR__) . '/config.php');
        }

        // DB 직접 연결 (gnuboard의 $g5 설정 사용)
        // config.php에서 G5_DB_* 상수를 읽어서 연결
        if (!defined('G5_DB_HOST') && file_exists(dirname(__DIR__) . '/extend/config.php')) {
            require_once(dirname(__DIR__) . '/extend/config.php');
        }

        echo '<div class="log">';

        // MySQLi로 DB 연결
        $db_host = defined('G5_DB_HOST') ? G5_DB_HOST : 'localhost';
        $db_user = defined('G5_DB_USER') ? G5_DB_USER : '';
        $db_pass = defined('G5_DB_PASS') ? G5_DB_PASS : '';
        $db_name = defined('G5_DB_NAME') ? G5_DB_NAME : '';

        if (!$db_user) {
            // gnuboard _common 방식으로 읽기
            $common_content = file_get_contents(dirname(__DIR__) . '/config.php');
            preg_match("/define\('G5_DB_HOST',\s*'([^']+)'\)/", $common_content, $m); $db_host = $m[1] ?? 'localhost';
            preg_match("/define\('G5_DB_USER',\s*'([^']+)'\)/", $common_content, $m); $db_user = $m[1] ?? '';
            preg_match("/define\('G5_DB_PASS',\s*'([^']+)'\)/", $common_content, $m); $db_pass = $m[1] ?? '';
            preg_match("/define\('G5_DB_NAME',\s*'([^']+)'\)/", $common_content, $m); $db_name = $m[1] ?? '';
        }

        echo "<span class='info'>DB 연결: {$db_host} / {$db_name}</span>\n";

        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $conn->set_charset('utf8mb4');

        if ($conn->connect_error) {
            echo "<span class='err'>❌ DB 연결 실패: " . htmlspecialchars($conn->connect_error) . "</span>\n";
        } else {
            echo "<span class='ok'>✅ DB 연결 성공</span>\n\n";

            // SQL 파일 읽기 및 분할 실행
            $sql_content = file_get_contents($sql_file);

            // 큰 파일의 경우 타임아웃 조정
            set_time_limit(300);
            ini_set('max_execution_time', 300);

            // SQL 구문 분리 (delimiter 기준)
            $statements = [];
            $current    = '';
            $lines      = explode("\n", $sql_content);

            foreach ($lines as $line) {
                $trimmed = trim($line);
                // 주석 skip
                if (substr($trimmed, 0, 2) === '--' || $trimmed === '') {
                    continue;
                }
                $current .= $line . "\n";
                if (substr(rtrim($trimmed), -1) === ';') {
                    $stmt = trim($current);
                    if ($stmt && $stmt !== ';') {
                        $statements[] = $stmt;
                    }
                    $current = '';
                }
            }
            if (trim($current)) {
                $statements[] = trim($current);
            }

            echo "<span class='info'>총 SQL 구문: " . count($statements) . "개</span>\n\n";

            $ok_count  = 0;
            $err_count = 0;
            $skip_meta = ['SET NAMES', 'SET FOREIGN_KEY_CHECKS'];

            foreach ($statements as $i => $stmt) {
                // SET 구문 등은 조용히 실행
                $is_meta = false;
                foreach ($skip_meta as $m) {
                    if (stripos($stmt, $m) === 0) { $is_meta = true; break; }
                }

                if ($conn->multi_query($stmt)) {
                    // multi_query 결과 flush
                    do { $conn->store_result(); } while ($conn->more_results() && $conn->next_result());
                    $ok_count++;
                    if (!$is_meta) {
                        $affected = $conn->affected_rows;
                        echo "<span class='ok'>✓</span> [" . ($i+1) . "] " . substr($stmt, 0, 60) . "... (affected: {$affected})\n";
                    }
                } else {
                    $err_count++;
                    echo "<span class='err'>✗ [" . ($i+1) . "] 오류: " . htmlspecialchars($conn->error) . "</span>\n";
                    echo "  SQL: " . htmlspecialchars(substr($stmt, 0, 100)) . "\n";
                }
            }

            $conn->close();

            echo "\n";
            echo "<span class='ok'>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</span>\n";
            echo "<span class='ok'>✅ 완료! 성공: {$ok_count}개 / 오류: {$err_count}개</span>\n";

            if ($file_key === 'fix_ca_skin') {
                echo "\n<span class='warn'>→ 다음 단계: install_shop_categories.sql 실행</span>\n";
            } elseif ($file_key === 'install_shop_categories') {
                echo "\n<span class='warn'>→ 다음 단계: update_ca_id.sql 실행 (선택)</span>\n";
                echo "<span class='warn'>→ 확인: http://drake0913.mycafe24.com/shop/list.php?ca_id=10</span>\n";
            }
        }

        echo '</div>'; // .log

        echo '<p style="margin-top:15px;">';
        echo '<a href="?key=' . htmlspecialchars($input_key) . '" class="btn btn-success">← 목록으로</a> ';
        echo '</p>';
        echo '</div>'; // .card
    }
endif; ?>

<div class="card" style="background:#fff3cd; border:1px solid #ffc107;">
    <strong>⚠️ 보안 주의:</strong> 작업 완료 후 이 파일을 삭제하세요.<br>
    <code>파일 경로: <?= __FILE__ ?></code>
</div>

</body>
</html>
