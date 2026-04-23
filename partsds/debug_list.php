<?php
/**
 * PartSDS List Debug Tool
 * shop/list.php 500 에러 원인 진단용
 *
 * 사용법: https://도메인/partsds/debug_list.php?key=partsds2024!&ca_id=10
 * ⚠️ 진단 완료 후 이 파일을 삭제하세요!
 */

define('SQL_RUNNER_KEY', 'partsds2024!');
$input_key = isset($_GET['key']) ? $_GET['key'] : '';
if ($input_key !== SQL_RUNNER_KEY) {
    http_response_code(403);
    die('403 Forbidden');
}

// gnuboard common.php 로드
$common_path = dirname(__DIR__) . '/_common.php';
$shop_common = dirname(__DIR__) . '/shop/_common.php';

ob_start();
$error_occurred = false;
set_error_handler(function($errno, $errstr, $errfile, $errline) use (&$error_occurred) {
    $error_occurred = true;
    echo "\n[PHP ERROR] #$errno: $errstr in $errfile on line $errline\n";
    return true;
});

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>PartSDS Debug</title>
<style>
body { font-family: monospace; max-width: 1000px; margin: 20px auto; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
h1 { color: #4ec9b0; }
h2 { color: #9cdcfe; border-bottom: 1px solid #444; }
.ok  { color: #4ec9b0; }
.err { color: #f44747; }
.warn { color: #dcdcaa; }
.info { color: #9cdcfe; }
pre { background: #252526; padding: 15px; border-radius: 5px; overflow-x: auto; white-space: pre-wrap; }
</style>
</head>
<body>
<h1>⚡ PartSDS Debug: shop/list.php 에러 진단</h1>

<h2>1. 환경 정보</h2>
<pre>
PHP Version: <?php echo PHP_VERSION; ?>

Server: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?>

Script: <?php echo __FILE__; ?>

</pre>

<h2>2. 상수/경로 확인</h2>
<pre>
<?php
// shop/_common.php 로드
if (file_exists($shop_common)) {
    include_once($shop_common);
    echo "<span class='ok'>✅ shop/_common.php 로드 성공</span>\n";
} else {
    echo "<span class='err'>❌ shop/_common.php 없음: $shop_common</span>\n";
}

$constants = [
    'G5_PATH', 'G5_URL', 'G5_SHOP_PATH', 'G5_SHOP_URL', 'G5_SHOP_SKIN_PATH',
    'G5_THEME_PATH', 'G5_THEME_SHOP_PATH', 'EYOOM_PATH', 'EYOOM_SHOP_PATH',
    'EYOOM_CORE_PATH', 'EYOOM_THEME_SHOP_PATH', 'EYOOM_THEME_SHOP_SKIN_PATH',
    '_EYOOM_', '_SHOP_'
];

foreach ($constants as $c) {
    if (defined($c)) {
        $val = constant($c);
        if (is_bool($val)) $val = $val ? 'true' : 'false';
        echo "<span class='ok'>✅ $c = " . htmlspecialchars($val) . "</span>\n";
    } else {
        echo "<span class='err'>❌ $c = [미정의]</span>\n";
    }
}
?>
</pre>

<h2>3. 파일 존재 확인</h2>
<pre>
<?php
$ca_id = isset($_GET['ca_id']) ? (int)$_GET['ca_id'] : 10;

$files_to_check = [];

if (defined('G5_THEME_SHOP_PATH')) {
    $files_to_check['G5_THEME_SHOP_PATH/list.php'] = G5_THEME_SHOP_PATH . '/list.php';
}
if (defined('EYOOM_SHOP_PATH')) {
    $files_to_check['EYOOM_SHOP_PATH/shop.head.php'] = EYOOM_SHOP_PATH . '/shop.head.php';
    $files_to_check['EYOOM_SHOP_PATH/shop.tail.php'] = EYOOM_SHOP_PATH . '/shop.tail.php';
}
if (defined('EYOOM_CORE_PATH')) {
    $skin_dir_core = EYOOM_CORE_PATH . '/shop';
    $files_to_check['EYOOM_CORE_PATH/shop/list.10.skin.php'] = $skin_dir_core . '/list.10.skin.php';
    $files_to_check['EYOOM_CORE_PATH/shop/navigation.skin.php'] = $skin_dir_core . '/navigation.skin.php';
    $files_to_check['EYOOM_CORE_PATH/shop/listcategory.skin.php'] = $skin_dir_core . '/listcategory.skin.php';
    $files_to_check['EYOOM_CORE_PATH/shop/list.sort.skin.php'] = $skin_dir_core . '/list.sort.skin.php';
    $files_to_check['EYOOM_CORE_PATH/shop/list.sub.skin.php'] = $skin_dir_core . '/list.sub.skin.php';
}
if (defined('EYOOM_THEME_SHOP_SKIN_PATH')) {
    $files_to_check['EYOOM_THEME_SHOP_SKIN_PATH/list.skin.html.php'] = EYOOM_THEME_SHOP_SKIN_PATH . '/list.skin.html.php';
    $files_to_check['EYOOM_THEME_SHOP_SKIN_PATH/list.sort.skin.html.php'] = EYOOM_THEME_SHOP_SKIN_PATH . '/list.sort.skin.html.php';
    $files_to_check['EYOOM_THEME_SHOP_SKIN_PATH/list.sub.skin.html.php'] = EYOOM_THEME_SHOP_SKIN_PATH . '/list.sub.skin.html.php';
}
if (defined('G5_SHOP_SKIN_PATH')) {
    $files_to_check['G5_SHOP_SKIN_PATH/navigation.skin.php'] = G5_SHOP_SKIN_PATH . '/navigation.skin.php';
    $files_to_check['G5_SHOP_SKIN_PATH/listcategory.skin.php'] = G5_SHOP_SKIN_PATH . '/listcategory.skin.php';
}

foreach ($files_to_check as $label => $path) {
    if (file_exists($path)) {
        echo "<span class='ok'>✅ $label</span>\n";
    } else {
        echo "<span class='err'>❌ $label → $path</span>\n";
    }
}
?>
</pre>

<h2>4. DB 카테고리 확인 (ca_id=<?php echo $ca_id; ?>)</h2>
<pre>
<?php
if (defined('G5_DB_HOST') || function_exists('sql_fetch')) {
    if (function_exists('sql_fetch') && isset($g5)) {
        $sql = "SELECT ca_id, ca_name, ca_skin, ca_skin_dir, ca_use, ca_list_mod, ca_list_row 
                FROM {$g5['g5_shop_category_table']} 
                WHERE ca_id = '$ca_id'";
        $ca = sql_fetch($sql);
        if ($ca) {
            echo "ca_id: {$ca['ca_id']}\n";
            echo "ca_name: {$ca['ca_name']}\n";
            echo "ca_skin: " . (empty($ca['ca_skin']) ? "[빈값 → list.10.skin.php 폴백]" : $ca['ca_skin']) . "\n";
            echo "ca_skin_dir: " . (empty($ca['ca_skin_dir']) ? "[빈값]" : $ca['ca_skin_dir']) . "\n";
            echo "ca_use: {$ca['ca_use']}\n";
            echo "ca_list_mod: {$ca['ca_list_mod']}\n";
            echo "ca_list_row: {$ca['ca_list_row']}\n";

            // skin_file 결정 로직 재현
            $skin_dir = defined('G5_SHOP_SKIN_PATH') ? G5_SHOP_SKIN_PATH : '[미정의]';
            $skin_file = $skin_dir . '/' . ($ca['ca_skin'] ?: 'list.10.skin.php');
            echo "\n결정된 skin_file:\n  $skin_file\n";
            echo "파일 존재: " . (file_exists($skin_file) ? '<span class="ok">✅ 존재</span>' : '<span class="err">❌ 없음</span>') . "\n";

            // eyoom core skin 확인
            if (defined('EYOOM_CORE_PATH')) {
                $ec_skin_dir = EYOOM_CORE_PATH . '/shop';
                $ec_skin_file = $ec_skin_dir . '/' . ($ca['ca_skin'] ?: 'list.10.skin.php');
                echo "\nEyoom Core skin_file:\n  $ec_skin_file\n";
                echo "파일 존재: " . (file_exists($ec_skin_file) ? '<span class="ok">✅ 존재</span>' : '<span class="err">❌ 없음</span>') . "\n";
            }
        } else {
            echo "<span class='err'>❌ ca_id=$ca_id 카테고리 없음 (ca_use='1' 조건 확인 필요)</span>\n";
            // ca_use 상관없이 검색
            $sql2 = "SELECT ca_id, ca_name, ca_use FROM {$g5['g5_shop_category_table']} WHERE ca_id = '$ca_id'";
            $ca2 = sql_fetch($sql2);
            if ($ca2) {
                echo "  → 카테고리 존재: ca_use={$ca2['ca_use']} (ca_use='1'이 아님!)\n";
            } else {
                echo "  → 해당 ca_id 카테고리 자체가 없음\n";
            }
        }
    } else {
        echo "<span class='warn'>⚠️ sql_fetch 함수 없음 또는 \$g5 변수 없음</span>\n";
    }
} else {
    echo "<span class='err'>❌ DB 연결 상수 미정의</span>\n";
}
?>
</pre>

<h2>5. $shop 객체 확인</h2>
<pre>
<?php
echo 'isset($shop): ' . (isset($shop) ? '<span class="ok">✅ 있음</span>' : '<span class="err">❌ 없음</span>') . "\n";
echo 'isset($eb): '   . (isset($eb)   ? '<span class="ok">✅ 있음</span>' : '<span class="err">❌ 없음</span>') . "\n";
echo 'isset($thema): '.(isset($thema) ? '<span class="ok">✅ 있음</span>' : '<span class="err">❌ 없음</span>') . "\n";
echo 'isset($eyoom): '.(isset($eyoom) ? '<span class="ok">✅ 있음</span>' : '<span class="err">❌ 없음</span>') . "\n";
echo 'is_admin: '     . (isset($is_admin) ? htmlspecialchars($is_admin) : '[미정의]') . "\n";
?>
</pre>

<h2>6. 결론</h2>
<pre>
<?php
$has_theme_list = defined('G5_THEME_SHOP_PATH') && file_exists(G5_THEME_SHOP_PATH . '/list.php');
$has_eyoom_shop_head = defined('EYOOM_SHOP_PATH') && file_exists(EYOOM_SHOP_PATH . '/shop.head.php');
$has_eyoom_const = defined('_EYOOM_');

echo "이윰빌더 활성화: " . ($has_eyoom_const ? '<span class="ok">✅ 예</span>' : '<span class="err">❌ 아니오</span>') . "\n";
echo "G5_THEME_SHOP_PATH/list.php 존재: " . ($has_theme_list ? '<span class="ok">✅ 존재 (이 파일로 라우팅됨)</span>' : '<span class="warn">⚠️ 없음 (shop/list.php 직접 실행)</span>') . "\n";
echo "EYOOM_SHOP_PATH/shop.head.php: " . ($has_eyoom_shop_head ? '<span class="ok">✅ 있음</span>' : '<span class="err">❌ 없음</span>') . "\n";

if ($has_theme_list) {
    echo "\n<span class='ok'>→ eyoom/shop/list.php가 서버에 있어 이 파일로 라우팅됩니다.</span>\n";
    echo "   eyoom/shop/list.php의 EYOOM_THEME_SHOP_SKIN_PATH 경로 확인 필요\n";
} else {
    echo "\n<span class='warn'>→ eyoom/shop/list.php가 서버에 없습니다.</span>\n";
    echo "   shop/list.php의 자체 코드가 실행되며, 이 코드가 500 에러를 발생시킵니다.\n";
    echo "   해결: FTP로 eyoom/shop/list.php를 서버에 업로드하세요.\n";
}
?>
</pre>

<div style="background:#1a1a2e;border:1px solid #f44747;padding:15px;margin-top:20px;border-radius:5px;">
    <strong style="color:#f44747;">⚠️ 보안 주의:</strong> 
    <span style="color:#dcdcaa;">진단 완료 후 이 파일을 삭제하세요!</span><br>
    <code style="color:#9cdcfe;">파일 경로: <?php echo __FILE__; ?></code>
</div>

</body>
</html>
<?php
restore_error_handler();
ob_end_flush();
?>
