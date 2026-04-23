-- =====================================================
-- 기존 g5_shop_category 출력스킨 일괄 수정 SQL
-- ca_skin = '' → 이윰빌더 관리자 '선택' 상태
-- (이윰빌더는 ca_skin이 빈 값이면 list.10.skin.php를 자동 사용)
-- 실행: 카페24 phpMyAdmin > SQL 탭에 붙여넣기 실행
-- =====================================================

SET NAMES utf8mb4;

-- ── 수정 전 현황 확인 (옵션) ──
-- SELECT COUNT(*) AS total,
--        SUM(ca_skin != '' AND ca_skin IS NOT NULL) AS has_skin,
--        SUM(ca_skin = '' OR ca_skin IS NULL) AS empty_skin
-- FROM `g5_shop_category`;

-- ── 전체 분류 스킨을 '' (선택) 으로 일괄 업데이트 ──
UPDATE `g5_shop_category`
SET
  `ca_skin`        = '',
  `ca_mobile_skin` = '',
  `ca_use`         = '1';

-- ── 수정 후 확인 ──
-- SELECT ca_id, ca_name, ca_skin FROM `g5_shop_category` ORDER BY ca_id LIMIT 20;

-- =====================================================
-- 완료!
-- 모든 분류의 출력스킨이 '' (선택) 상태로 변경됩니다.
-- 이윰빌더(eyoom/core/shop/list.php)는 ca_skin이 비어있으면
-- 기본값 list.10.skin.php 를 자동으로 사용합니다.
-- =====================================================
