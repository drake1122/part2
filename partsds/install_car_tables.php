<?php
/**
 * 파츠디에스 - 자동차 브랜드/시리즈/모델 테이블 설치 스크립트
 * 실행 후 삭제 권장
 */
include_once('../_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    exit;
}

$errors = [];
$success = [];

// 1. car_brand 테이블 생성
$sql = "CREATE TABLE IF NOT EXISTS `" . G5_TABLE_PREFIX . "car_brand` (
    `brand_id`     INT(11) NOT NULL AUTO_INCREMENT,
    `brand_name`   VARCHAR(100) NOT NULL COMMENT '브랜드명 (예: 벤츠, BMW)',
    `brand_name_en` VARCHAR(100) NOT NULL DEFAULT '' COMMENT '영문 브랜드명',
    `brand_logo`   VARCHAR(255) NOT NULL DEFAULT '' COMMENT '로고 이미지 경로',
    `brand_order`  INT(11) NOT NULL DEFAULT 0 COMMENT '정렬순서',
    `brand_use`    TINYINT(1) NOT NULL DEFAULT 1 COMMENT '사용여부',
    `brand_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`brand_id`),
    KEY `brand_order` (`brand_order`, `brand_use`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='자동차 브랜드';";

if (sql_query($sql)) {
    $success[] = 'car_brand 테이블 생성 완료';
} else {
    $errors[] = 'car_brand 테이블 생성 실패';
}

// 2. car_series 테이블 생성
$sql = "CREATE TABLE IF NOT EXISTS `" . G5_TABLE_PREFIX . "car_series` (
    `series_id`    INT(11) NOT NULL AUTO_INCREMENT,
    `brand_id`     INT(11) NOT NULL COMMENT '브랜드 ID (FK)',
    `series_name`  VARCHAR(100) NOT NULL COMMENT '시리즈/연식명 (예: E-Class, 3시리즈)',
    `series_order` INT(11) NOT NULL DEFAULT 0 COMMENT '정렬순서',
    `series_use`   TINYINT(1) NOT NULL DEFAULT 1 COMMENT '사용여부',
    `series_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`series_id`),
    KEY `brand_id` (`brand_id`),
    KEY `series_order` (`series_order`, `series_use`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='자동차 시리즈/연식';";

if (sql_query($sql)) {
    $success[] = 'car_series 테이블 생성 완료';
} else {
    $errors[] = 'car_series 테이블 생성 실패';
}

// 3. car_model 테이블 생성
$sql = "CREATE TABLE IF NOT EXISTS `" . G5_TABLE_PREFIX . "car_model` (
    `model_id`     INT(11) NOT NULL AUTO_INCREMENT,
    `series_id`    INT(11) NOT NULL COMMENT '시리즈 ID (FK)',
    `brand_id`     INT(11) NOT NULL COMMENT '브랜드 ID (중복저장, 검색 최적화)',
    `model_name`   VARCHAR(150) NOT NULL COMMENT '모델명 (예: E220d, 320i)',
    `model_year`   VARCHAR(20) NOT NULL DEFAULT '' COMMENT '연식 범위 (예: 2019-2023)',
    `model_order`  INT(11) NOT NULL DEFAULT 0 COMMENT '정렬순서',
    `model_use`    TINYINT(1) NOT NULL DEFAULT 1 COMMENT '사용여부',
    `model_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`model_id`),
    KEY `series_id` (`series_id`),
    KEY `brand_id` (`brand_id`),
    KEY `model_order` (`model_order`, `model_use`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='자동차 모델';";

if (sql_query($sql)) {
    $success[] = 'car_model 테이블 생성 완료';
} else {
    $errors[] = 'car_model 테이블 생성 실패';
}

// 4. 상품-차종 연결 테이블 생성 (상품과 차종 매핑)
$sql = "CREATE TABLE IF NOT EXISTS `" . G5_TABLE_PREFIX . "item_car` (
    `id`           INT(11) NOT NULL AUTO_INCREMENT,
    `it_id`        VARCHAR(20) NOT NULL COMMENT '상품 ID',
    `brand_id`     INT(11) NOT NULL DEFAULT 0 COMMENT '브랜드 ID',
    `series_id`    INT(11) NOT NULL DEFAULT 0 COMMENT '시리즈 ID',
    `model_id`     INT(11) NOT NULL DEFAULT 0 COMMENT '모델 ID',
    PRIMARY KEY (`id`),
    KEY `it_id` (`it_id`),
    KEY `brand_id` (`brand_id`),
    KEY `series_id` (`series_id`),
    KEY `model_id` (`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='상품-차종 연결';";

if (sql_query($sql)) {
    $success[] = 'item_car 테이블 생성 완료';
} else {
    $errors[] = 'item_car 테이블 생성 실패';
}

// 5. 기본 브랜드 데이터 삽입
$brands = [
    ['벤츠', 'Mercedes-Benz', 1],
    ['BMW', 'BMW', 2],
    ['아우디', 'Audi', 3],
    ['포르쉐', 'Porsche', 4],
    ['미니', 'MINI', 5],
    ['랜드로버', 'Land Rover', 6],
    ['폭스바겐', 'Volkswagen', 7],
    ['볼보', 'Volvo', 8],
    ['지프', 'Jeep', 9],
    ['테슬라', 'Tesla', 10],
    ['재규어', 'Jaguar', 11],
    ['렉서스', 'Lexus', 12],
    ['도요타', 'Toyota', 13],
    ['혼다', 'Honda', 14],
];

$inserted_brands = 0;
foreach ($brands as $brand) {
    $check = sql_fetch("SELECT brand_id FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_name = '" . sql_escape_string($brand[0]) . "'");
    if (!$check['brand_id']) {
        sql_query("INSERT INTO `" . G5_TABLE_PREFIX . "car_brand` (brand_name, brand_name_en, brand_order) VALUES ('" . sql_escape_string($brand[0]) . "', '" . sql_escape_string($brand[1]) . "', " . (int)$brand[2] . ")");
        $inserted_brands++;
    }
}
if ($inserted_brands > 0) {
    $success[] = "기본 브랜드 {$inserted_brands}개 삽입 완료";
}

// 6. 그누보드 회원 mb_1~mb_3 컬럼 용도 안내 (이미 존재하는 컬럼)
// mb_1 = 차량 브랜드명, mb_2 = 시리즈명, mb_3 = 모델명
// mb_4 = brand_id, mb_5 = series_id, mb_6 = model_id (숫자 ID 저장)
$success[] = '회원 차종 필드: mb_1(브랜드), mb_2(시리즈), mb_3(모델), mb_4(brand_id), mb_5(series_id), mb_6(model_id) 활용';

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>파츠디에스 - DB 설치</title>
<style>
body { font-family: 'Noto Sans KR', sans-serif; padding: 30px; background: #f5f5f5; }
.box { background: #fff; padding: 20px; border-radius: 8px; max-width: 700px; margin: 0 auto; }
h2 { color: #c0392b; }
.ok { color: #27ae60; }
.err { color: #e74c3c; }
ul { list-style: none; padding: 0; }
li { padding: 8px 0; border-bottom: 1px solid #eee; }
</style>
</head>
<body>
<div class="box">
    <h2>파츠디에스 DB 설치</h2>
    <?php if ($success): ?>
    <h3 class="ok">✅ 성공</h3>
    <ul><?php foreach ($success as $s): ?><li class="ok">✔ <?php echo htmlspecialchars($s); ?></li><?php endforeach; ?></ul>
    <?php endif; ?>
    <?php if ($errors): ?>
    <h3 class="err">❌ 오류</h3>
    <ul><?php foreach ($errors as $e): ?><li class="err">✖ <?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
    <?php endif; ?>
    <p style="margin-top:20px; color:#888;">설치 완료 후 이 파일을 삭제하세요: <code>partsds/install_car_tables.php</code></p>
</div>
</body>
</html>
