<?php
/**
 * PartSDS SQL Runner v2
 * URL 접속으로 SQL 파일을 실행하는 도구 (대용량 청크 처리 지원)
 *
 * 사용법:
 *   ① fix_ca_skin         → ca_skin 초기화
 *   ② install_member_type → 회원 유형 기본값 설정
 *   ③ install_shop_categories → 브랜드+시리즈+모델 분류 4,089개 INSERT
 *   ④ update_ca_id        → car_brand/series/model 테이블에 ca_id 업데이트
 *
 * ⚠️  실행 후 이 파일을 반드시 삭제하거나 key를 변경하세요!
 */

// ── 보안 키 설정 ────────────────────────────────────────────────────────────
define('SQL_RUNNER_KEY', 'partsds2024!');
// ────────────────────────────────────────────────────────────────────────────

$input_key = isset($_GET['key']) ? $_GET['key'] : '';
if ($input_key !== SQL_RUNNER_KEY) {
    http_response_code(403);
    die('<h2>403 Forbidden</h2><p>올바른 키를 입력하세요.</p>');
}

// 허용된 SQL 파일 목록
$allowed_files = [
    'fix_ca_skin'             => __DIR__ . '/fix_ca_skin.sql',
    'install_member_type'     => __DIR__ . '/install_member_type.sql',
    'install_shop_categories' => __DIR__ . '/install_shop_categories.sql',
    'update_ca_id'            => __DIR__ . '/update_ca_id.sql',
    'fix_ca_order'            => __DIR__ . '/fix_ca_order.sql',  // 3차분류 ca_order 1부터 시작 정규화
];

$file_key = isset($_GET['file']) ? preg_replace('/[^a-z0-9_]/', '', $_GET['file']) : '';

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PartSDS SQL Runner v2</title>
<style>
* { box-sizing: border-box; }
body { font-family: 'Malgun Gothic', sans-serif; max-width: 960px; margin: 30px auto; padding: 20px; background: #f5f5f5; }
h1 { color: #333; border-bottom: 3px solid #c0392b; padding-bottom: 10px; }
.card { background: #fff; border-radius: 8px; padding: 20px; margin: 15px 0; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
.btn { display: inline-block; padding: 10px 22px; background: #c0392b; color: #fff; text-decoration: none;
       border-radius: 5px; font-size: 14px; margin: 4px; border: none; cursor: pointer; }
.btn:hover { background: #a93226; }
.btn-blue { background: #2980b9; }
.btn-blue:hover { background: #1f6a94; }
.btn-green { background: #27ae60; }
.btn-green:hover { background: #1e8449; }
.btn-gray  { background: #7f8c8d; }
.btn-gray:hover { background: #626e6f; }
.log { background: #1e1e1e; color: #d4d4d4; padding: 16px; border-radius: 6px; font-family: monospace;
       font-size: 12px; max-height: 600px; overflow-y: auto; white-space: pre-wrap; margin-top: 12px; }
.ok   { color: #4ec9b0; }
.err  { color: #f44747; }
.warn { color: #dcdcaa; }
.info { color: #9cdcfe; }
.hdr  { color: #c586c0; font-weight: bold; }
table { width: 100%; border-collapse: collapse; }
td, th { padding: 10px 12px; border: 1px solid #e0e0e0; text-align: left; font-size: 13px; }
th { background: #f0f0f0; font-weight: 600; }
tr:nth-child(even) td { background: #fafafa; }
.step-no { display: inline-block; background: #c0392b; color: #fff; width: 24px; height: 24px;
           border-radius: 50%; text-align: center; line-height: 24px; font-size: 13px; margin-right: 6px; }
.progress-wrap { background: #eee; border-radius: 4px; margin: 8px 0; }
.progress-bar  { background: #27ae60; height: 8px; border-radius: 4px; transition: width .3s; }
.badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 11px; font-weight: 600; }
.badge-ok  { background: #d5f5e3; color: #1e8449; }
.badge-err { background: #fde8e8; color: #a93226; }
</style>
</head>
<body>

<h1>⚡ PartSDS SQL Runner <small style="font-size:14px; color:#888;">v2</small></h1>

<?php if (!$file_key || !isset($allowed_files[$file_key])): ?>

<!-- ══════════════════ 파일 선택 화면 ══════════════════ -->
<div class="card">
    <h2>실행할 SQL 파일을 선택하세요</h2>
    <p style="color:#888; font-size:13px;">순서대로 실행하세요. 이미 실행한 스텝은 건너뛰어도 됩니다(INSERT IGNORE 사용).</p>
    <table>
        <tr>
            <th width="30">순서</th>
            <th>파일</th>
            <th>설명</th>
            <th width="120">실행</th>
        </tr>
        <tr>
            <td><span class="step-no">1</span></td>
            <td><code>fix_ca_skin.sql</code></td>
            <td>
                기존 <code>g5_shop_category</code>의 <code>ca_skin</code>을 <code>''</code>(선택)으로 일괄 초기화<br>
                <small style="color:#666;">→ 이윰빌더가 list.10.skin.php 자동 사용</small>
            </td>
            <td><a href="?key=<?=htmlspecialchars($input_key)?>&file=fix_ca_skin" class="btn">실행</a></td>
        </tr>
        <tr>
            <td><span class="step-no">2</span></td>
            <td><code>install_member_type.sql</code></td>
            <td>
                기존 회원 <code>mb_7</code> 기본값 <code>'normal'</code> 설정<br>
                <small style="color:#666;">→ 일반/사업자 회원 구분 초기화 (최초 1회)</small>
            </td>
            <td><a href="?key=<?=htmlspecialchars($input_key)?>&file=install_member_type" class="btn btn-blue">실행</a></td>
        </tr>
        <tr>
            <td><span class="step-no">3</span></td>
            <td><code>install_shop_categories.sql</code></td>
            <td>
                브랜드(14) + 시리즈(539) + 모델(3,536) = <strong>총 4,089개</strong> 분류 INSERT<br>
                <small style="color:#c0392b;"><strong>⚠️ 대용량!</strong> 청크 처리로 실행됩니다 (3~5분 소요)</small>
            </td>
            <td><a href="?key=<?=htmlspecialchars($input_key)?>&file=install_shop_categories" class="btn" style="background:#e67e00;">실행</a></td>
        </tr>
        <tr>
            <td><span class="step-no">4</span></td>
            <td><code>update_ca_id.sql</code></td>
            <td>
                <code>car_brand</code>/<code>car_series</code>/<code>car_model</code> 테이블에 <code>ca_id</code> 컬럼 추가 및 매핑<br>
                <small style="color:#666;">→ 브랜드 선택기와 상품 목록 필터 연동에 필요</small>
            </td>
            <td><a href="?key=<?=htmlspecialchars($input_key)?>&file=update_ca_id" class="btn btn-gray">실행</a></td>
        </tr>
        <tr>
            <td><span class="step-no">5</span></td>
            <td><code>fix_ca_order.sql</code></td>
            <td>
                3차분류 <code>ca_order</code>를 각 2차분류(시리즈) 내에서 <strong>1부터 시작</strong>하도록 정규화<br>
                <small style="color:#c0392b;">현재: 전체 연속 번호(1,2,...,2876) → 수정: 각 시리즈 내 1,2,3...</small>
            </td>
            <td><a href="?key=<?=htmlspecialchars($input_key)?>&file=fix_ca_order" class="btn btn-green">실행</a></td>
        </tr>
    </table>
</div>

<div class="card" style="background:#fff8f0; border:1px solid #f0c080;">
    <h3 style="margin-top:0;">📋 실행 후 확인</h3>
    <ul style="line-height:1.8;">
        <li><a href="db_check.php?key=<?=htmlspecialchars($input_key)?>&action=overview" target="_blank">🔍 DB 현황 진단</a></li>
        <li><a href="db_check.php?key=<?=htmlspecialchars($input_key)?>&action=abc_models" target="_blank">🚗 A/B/C 클래스 모델 확인</a></li>
        <li><a href="db_check.php?key=<?=htmlspecialchars($input_key)?>&action=members" target="_blank">👤 회원 유형 현황</a></li>
    </ul>
</div>

<?php else:

    $sql_file = $allowed_files[$file_key];

    if (!file_exists($sql_file)) {
        echo '<div class="card"><p class="err">❌ SQL 파일을 찾을 수 없습니다: ' . htmlspecialchars($sql_file) . '</p></div>';
    } else {

        echo '<div class="card">';
        echo '<h2>실행: <code>' . htmlspecialchars($file_key) . '.sql</code></h2>';
        echo '<p>파일 크기: ' . number_format(filesize($sql_file)) . ' bytes | ';
        echo '라인 수: ' . number_format(count(file($sql_file))) . '</p>';

        // ── DB 연결 ──
        $base = dirname(__DIR__);
        if (file_exists($base . '/config.php')) require_once($base . '/config.php');
        if (!defined('G5_DB_HOST') && file_exists($base . '/extend/config.php')) require_once($base . '/extend/config.php');

        $db_host = defined('G5_DB_HOST') ? G5_DB_HOST : 'localhost';
        $db_user = defined('G5_DB_USER') ? G5_DB_USER : '';
        $db_pass = defined('G5_DB_PASS') ? G5_DB_PASS : '';
        $db_name = defined('G5_DB_NAME') ? G5_DB_NAME : '';
        $db_prefix = defined('G5_TABLE_PREFIX') ? G5_TABLE_PREFIX : 'g5_';

        // config.php 직접 파싱 (fallback)
        if (!$db_user && file_exists($base . '/config.php')) {
            $cfg = file_get_contents($base . '/config.php');
            preg_match("/define\('G5_DB_HOST',\s*'([^']+)'\)/", $cfg, $m); $db_host = $m[1] ?? 'localhost';
            preg_match("/define\('G5_DB_USER',\s*'([^']+)'\)/", $cfg, $m); $db_user = $m[1] ?? '';
            preg_match("/define\('G5_DB_PASS',\s*'([^']+)'\)/", $cfg, $m); $db_pass = $m[1] ?? '';
            preg_match("/define\('G5_DB_NAME',\s*'([^']+)'\)/", $cfg, $m); $db_name = $m[1] ?? '';
            preg_match("/define\('G5_TABLE_PREFIX',\s*'([^']+)'\)/", $cfg, $m); $db_prefix = $m[1] ?? 'g5_';
        }

        // 출력 버퍼 해제 (실시간 출력)
        if (ob_get_level()) { ob_end_flush(); }
        ob_implicit_flush(true);

        echo '<div class="log">';
        echo "<span class='info'>DB: {$db_host} / {$db_name} (prefix: {$db_prefix})</span>\n";

        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            echo "<span class='err'>❌ DB 연결 실패: " . htmlspecialchars($conn->connect_error) . "</span>\n";
            echo '</div></div>';
            goto footer;
        }
        $conn->set_charset('utf8mb4');
        echo "<span class='ok'>✅ DB 연결 성공</span>\n\n";

        set_time_limit(600);
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '512M');

        // ── SQL 파일 읽기 ──
        $sql_content = file_get_contents($sql_file);

        // 테이블 접두어 치환 (g5_ → 실제 접두어)
        if ($db_prefix !== 'g5_') {
            $sql_content = str_replace('`g5_', '`' . $db_prefix, $sql_content);
            echo "<span class='warn'>테이블 접두어 치환: g5_ → {$db_prefix}</span>\n";
        }

        // ── SQL 구문 분리 ──
        $statements = [];
        $current    = '';
        $in_insert  = false;

        foreach (explode("\n", $sql_content) as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || substr($trimmed, 0, 2) === '--') continue;
            $current .= $line . "\n";
            if (substr(rtrim($trimmed), -1) === ';') {
                $stmt = trim($current);
                if ($stmt && $stmt !== ';') $statements[] = $stmt;
                $current = '';
            }
        }
        if (trim($current)) $statements[] = trim($current);

        $total_stmts = count($statements);
        echo "<span class='info'>총 SQL 구문: {$total_stmts}개</span>\n\n";

        $ok_count  = 0;
        $err_count = 0;
        $chunk_size = ($file_key === 'install_shop_categories') ? 50 : 1; // 대용량은 50개씩 청크

        // 청크 INSERT 처리
        if ($file_key === 'install_shop_categories') {
            echo "<span class='hdr'>════ 분류 INSERT 처리 (청크 모드) ════</span>\n";

            $insert_groups = [];
            $other_stmts   = [];
            foreach ($statements as $stmt) {
                if (stripos($stmt, 'INSERT') === 0) {
                    $insert_groups[] = $stmt;
                } else {
                    $other_stmts[] = $stmt;
                }
            }

            // 비-INSERT 먼저 실행 (SET 등)
            foreach ($other_stmts as $stmt) {
                $conn->query($stmt);
            }

            // INSERT별로 VALUES를 파싱해서 청크로 쪼개기
            $grand_ok = 0;
            $grand_err = 0;

            foreach ($insert_groups as $gi => $big_insert) {
                // INSERT IGNORE INTO `table` (cols) VALUES ... 파싱
                if (!preg_match('/^(INSERT\s+IGNORE\s+INTO\s+`[^`]+`\s*\([^)]+\)\s*VALUES\s*)/si', $big_insert, $hdr_match)) {
                    // 일반 실행
                    if ($conn->query($big_insert)) { $grand_ok++; } else { $grand_err++; }
                    continue;
                }

                $insert_header = rtrim($hdr_match[1]);
                $values_str    = substr($big_insert, strlen($hdr_match[0]));
                // 마지막 ; 제거
                $values_str    = rtrim($values_str, " \t\n\r;");

                // VALUES 행 파싱 (괄호 단위)
                $rows = [];
                $depth = 0;
                $start = 0;
                $in_str = false;
                $str_ch = '';

                for ($ci = 0; $ci < strlen($values_str); $ci++) {
                    $ch = $values_str[$ci];
                    if ($in_str) {
                        if ($ch === '\\') { $ci++; continue; }
                        if ($ch === $str_ch) $in_str = false;
                        continue;
                    }
                    if ($ch === "'" || $ch === '"') { $in_str = true; $str_ch = $ch; continue; }
                    if ($ch === '(') $depth++;
                    elseif ($ch === ')') {
                        $depth--;
                        if ($depth === 0) {
                            $rows[] = substr($values_str, $start, $ci - $start + 1);
                            // 다음 ,(공백) 건너뜀
                            $ni = $ci + 1;
                            while ($ni < strlen($values_str) && ($values_str[$ni] === ',' || $values_str[$ni] === ' ' || $values_str[$ni] === "\n")) $ni++;
                            $start = $ni;
                            $ci = $ni - 1;
                        }
                    }
                }

                $total_rows = count($rows);
                $chunks = array_chunk($rows, 100); // 100행씩 청크
                $chunk_ok = 0;

                echo "<span class='info'>INSERT 그룹 " . ($gi+1) . ": {$total_rows}개 행 → " . count($chunks) . "개 청크로 분할</span>\n";
                flush();

                foreach ($chunks as $ci => $chunk_rows) {
                    $chunk_sql = $insert_header . "\nVALUES\n" . implode(",\n", $chunk_rows) . ";";
                    if ($conn->query($chunk_sql)) {
                        $chunk_ok += $conn->affected_rows;
                        $grand_ok++;
                        $ok_count++;
                    } else {
                        $grand_err++;
                        $err_count++;
                        echo "<span class='err'>✗ 청크 " . ($ci+1) . " 오류: " . htmlspecialchars($conn->error) . "</span>\n";
                    }

                    // 진행 상황 (10청크마다)
                    if (($ci + 1) % 10 === 0 || ($ci + 1) === count($chunks)) {
                        $pct = round(($ci + 1) / count($chunks) * 100);
                        echo "<span class='ok'>  진행: {$pct}% (" . ($ci+1) . "/" . count($chunks) . " 청크)</span>\n";
                        flush();
                    }
                }
                echo "<span class='ok'>✅ INSERT 그룹 " . ($gi+1) . " 완료: {$chunk_ok}개 행 처리</span>\n\n";
                flush();
            }

            echo "<span class='ok'>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</span>\n";
            echo "<span class='ok'>✅ 전체 완료! 성공 청크: {$ok_count} / 오류: {$err_count}</span>\n";
            echo "<span class='warn'>→ 다음 단계: update_ca_id.sql 실행</span>\n";

        } else {
            // ── 일반 실행 ──
            foreach ($statements as $i => $stmt) {
                $is_meta    = (stripos($stmt, 'SET NAMES') === 0 || stripos($stmt, 'SET FOREIGN_KEY_CHECKS') === 0);
                $is_alter   = stripos($stmt, 'ALTER TABLE') === 0;

                if ($conn->query($stmt)) {
                    $ok_count++;
                    if (!$is_meta) {
                        echo "<span class='ok'>✓</span> [" . ($i+1) . "] " . htmlspecialchars(substr($stmt, 0, 70)) . "...\n";
                        echo "   → affected: " . $conn->affected_rows . "\n";
                    }
                } else {
                    $err_no  = $conn->errno;
                    $err_msg = $conn->error;

                    // ALTER TABLE 오류 처리:
                    // 1060 = Duplicate column name (이미 컬럼 존재 → 정상)
                    // 1064 = IF NOT EXISTS 미지원 MySQL → 개별 시도
                    if ($is_alter && ($err_no == 1060 || $err_no == 1054)) {
                        $ok_count++;
                        echo "<span class='warn'>⚠ [" . ($i+1) . "] 컬럼 이미 존재 (무시): " . htmlspecialchars(substr($stmt, 0, 70)) . "\n";
                    } elseif ($is_alter && $err_no == 1064 && stripos($stmt, 'IF NOT EXISTS') !== false) {
                        // IF NOT EXISTS 미지원 → IF NOT EXISTS 제거 후 재시도
                        $stmt2 = preg_replace('/\bIF\s+NOT\s+EXISTS\s+/i', '', $stmt);
                        if ($conn->query($stmt2)) {
                            $ok_count++;
                            echo "<span class='ok'>✓ (IF NOT EXISTS 제거 후 성공)</span> [" . ($i+1) . "] " . htmlspecialchars(substr($stmt2, 0, 70)) . "\n";
                        } else {
                            if ($conn->errno == 1060) {
                                $ok_count++;
                                echo "<span class='warn'>⚠ [" . ($i+1) . "] 컬럼 이미 존재 (무시)\n";
                            } else {
                                $err_count++;
                                echo "<span class='err'>✗ [" . ($i+1) . "] ALTER 오류: " . htmlspecialchars($conn->error) . "</span>\n";
                            }
                        }
                    } else {
                        $err_count++;
                        echo "<span class='err'>✗ [" . ($i+1) . "] 오류(#" . $err_no . "): " . htmlspecialchars($err_msg) . "</span>\n";
                        echo "   SQL: " . htmlspecialchars(substr($stmt, 0, 120)) . "\n";
                    }
                }
                flush();
            }

            echo "\n<span class='ok'>━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</span>\n";
            echo "<span class='ok'>✅ 완료! 성공: {$ok_count}개 / 오류: {$err_count}개</span>\n";

            if ($file_key === 'fix_ca_skin') {
                echo "\n<span class='warn'>→ 다음 단계: install_member_type.sql 실행</span>\n";
                echo "<span class='warn'>→ 그 다음:   install_shop_categories.sql 실행</span>\n";
            } elseif ($file_key === 'install_member_type') {
                echo "\n<span class='warn'>→ 다음 단계: install_shop_categories.sql 실행</span>\n";
            } elseif ($file_key === 'update_ca_id') {
                echo "\n<span class='ok'>→ update_ca_id 완료!</span>\n";
                echo "<span class='warn'>→ 다음 단계: fix_ca_order.sql 실행 (3차분류 순서 정규화)</span>\n";
            } elseif ($file_key === 'fix_ca_order') {
                echo "\n<span class='ok'>→ 모든 단계 완료!</span>\n";
                echo "<span class='ok'>→ 3차분류 ca_order가 각 2차분류 내에서 1부터 시작하도록 정규화되었습니다.</span>\n";
                echo "<span class='warn'>→ 확인: 쇼핑몰 A클래스 페이지에서 모델 목록 확인</span>\n";
            }
        }

        $conn->close();
        echo '</div>'; // .log

        echo '<p style="margin-top:15px;">';
        echo '<a href="?key=' . htmlspecialchars($input_key) . '" class="btn btn-green">← 목록으로</a> ';
        echo '<a href="db_check.php?key=' . htmlspecialchars($input_key) . '&action=overview" class="btn btn-blue" target="_blank">🔍 DB 진단</a>';
        echo '</p>';
        echo '</div>'; // .card
    }
    footer:
endif; ?>

<div class="card" style="background:#fff3cd; border:1px solid #ffc107; margin-top:20px;">
    <strong>⚠️ 보안 주의:</strong> 작업 완료 후 이 파일을 삭제하거나 파일명을 변경하세요.<br>
    <small style="color:#888;">파일 경로: <?= htmlspecialchars(__FILE__) ?></small>
</div>

</body>
</html>
