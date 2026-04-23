-- =====================================================
-- 파츠디에스 쇼핑몰 분류 출력스킨 일괄 설정 SQL
-- g5_shop_category 테이블의 출력스킨이 비어있는
-- 모든 분류에 list.10.skin.php 를 일괄 적용
-- =====================================================
-- 
-- ✅ 실행 방법:
--    카페24 phpMyAdmin > drake0913 DB 선택 > SQL 탭 > 붙여넣기 후 실행
--
-- ✅ 적용 대상:
--    ca_skin이 비어있는 모든 분류 (브랜드/시리즈/모델 전체)
--
-- ✅ 선택 사항:
--    list.10.skin.php = 4열 그리드 (기본 추천)
--    list.20.skin.php = 다른 그리드 스타일
--
-- =====================================================

SET NAMES utf8mb4;

-- ── STEP 1: ca_skin 비어있는 분류 확인 (실행 전 확인용) ──
-- SELECT COUNT(*) as 미설정건수 FROM `g5_shop_category` WHERE ca_skin = '' OR ca_skin IS NULL;

-- ── STEP 2: 출력스킨 일괄 업데이트 ──────────────────────
UPDATE `g5_shop_category`
SET
    `ca_skin`        = 'list.10.skin.php',
    `ca_mobile_skin` = 'list.10.skin.php',
    `ca_use`         = '1'
WHERE
    `ca_skin` = ''
    OR `ca_skin` IS NULL;

-- ── STEP 3: 확인 쿼리 ───────────────────────────────────
-- SELECT COUNT(*) as 설정완료건수 FROM `g5_shop_category` WHERE ca_skin = 'list.10.skin.php';
-- SELECT ca_id, ca_name, ca_skin FROM `g5_shop_category` LIMIT 10;

-- =====================================================
-- 완료! 위 UPDATE 실행 후 아래 URL 접속 테스트:
-- http://drake0913.mycafe24.com/shop/list.php?ca_id=10
-- http://drake0913.mycafe24.com/shop/list.php?ca_id=1001
-- http://drake0913.mycafe24.com/shop/list.php?ca_id=101001
-- =====================================================
