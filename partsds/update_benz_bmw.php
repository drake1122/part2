<?php
/**
 * 벤츠/BMW 모델 ca_id 업데이트 (카페24 서버에서 직접 실행)
 * 실행 후 반드시 삭제하세요!
 */
$db_host = 'localhost';
$db_user = 'drake0913';
$db_pass = 'zxcv1122!@';
$db_name = 'drake0913';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) die("DB 연결 실패: " . mysqli_connect_error());
mysqli_set_charset($conn, 'utf8mb4');

header('Content-Type: text/html; charset=utf-8');
echo "<pre>\n";

// 현재 상태
$r = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM g5_car_model WHERE ca_id = ''");
$pending = mysqli_fetch_assoc($r)['cnt'];
echo "업데이트 필요한 모델: {$pending}개\n\n";

if ($pending == 0) {
    echo "이미 완료됐습니다!\n";
    mysqli_close($conn);
    echo "</pre>";
    exit;
}

// 시리즈 ca_id 캐시
$series_ca = [];
$r = mysqli_query($conn, "SELECT series_id, ca_id FROM g5_car_series WHERE ca_id != '' AND brand_id IN (1,2)");
while ($row = mysqli_fetch_assoc($r)) {
    $series_ca[$row['series_id']] = $row['ca_id'];
}
echo "시리즈 캐시: " . count($series_ca) . "개\n";

// 시리즈별로 나눠서 업데이트
$series_ids = array_keys($series_ca);
$success = 0;
$fail = 0;

foreach ($series_ids as $series_id) {
    $sca = $series_ca[$series_id];
    
    $r = mysqli_query($conn, "SELECT model_id, model_order FROM g5_car_model WHERE series_id={$series_id} AND ca_id=''");
    $models = mysqli_fetch_all($r, MYSQLI_ASSOC);
    if (empty($models)) continue;
    
    // CASE WHEN UPDATE
    $ids = implode(',', array_column($models, 'model_id'));
    $cases = '';
    foreach ($models as $m) {
        $ca = (string)((int)$sca * 100 + (int)$m['model_order']);
        if (strlen($ca) <= 10) {
            $cases .= "WHEN {$m['model_id']} THEN '{$ca}' ";
        }
    }
    
    if ($cases) {
        $sql = "UPDATE g5_car_model SET ca_id = CASE model_id {$cases} END WHERE model_id IN ({$ids})";
        if (mysqli_query($conn, $sql)) {
            $success += count($models);
        } else {
            echo "  시리즈 {$series_id} 오류: " . mysqli_error($conn) . "\n";
            $fail++;
        }
    }
}

echo "성공: {$success}개, 실패: {$fail}개\n\n";

// 최종 확인
$r = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM g5_car_model WHERE ca_id != ''");
echo "완료: " . mysqli_fetch_assoc($r)['cnt'] . "/3536\n\n";

// 검증 샘플
echo "[검증: 벤츠 A-클래스 모델 ↔ shop_category]\n";
$sql = "SELECT cm.model_id, cm.model_name, cm.ca_id, sc.ca_name
        FROM g5_car_model cm
        LEFT JOIN g5_shop_category sc ON cm.ca_id = sc.ca_id
        JOIN g5_car_series cs ON cm.series_id = cs.series_id
        WHERE cs.brand_id=1 AND cs.series_order=1
        ORDER BY cm.model_order LIMIT 7";
$r = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($r)) {
    $match = $row['ca_name'] ? "✅" : "❌";
    echo "  {$match} model_id={$row['model_id']}, ca={$row['ca_id']}, model=" . substr($row['model_name'],0,25) . ", shop={$row['ca_name']}\n";
}

mysqli_close($conn);
echo "\n완료! 이 파일을 삭제하세요.\n";
echo "</pre>\n";
?>
