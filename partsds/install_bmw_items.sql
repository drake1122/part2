-- ============================================================
-- PartsDS - BMW 상품 일괄등록 SQL (다중분류 적용)
-- 생성일시: 2026-05-14 09:15:46
-- 원본파일: BMW-cafe24.xlsx (206개 상품)
--
-- ■ 다중분류 구조
--   ca_id  = 차종분류  (예: 2010 = BMW 3시리즈 F30)
--   ca_id2 = 파츠종류  (예: 5001 = 오일필터)
--   ca_id3 = 부품브랜드(예: B01  = BMW OEM정품)
--
-- ■ 실행 전 확인사항 (순서대로)
--   1. install_shop_categories.sql   ← 차종분류 (이미 실행했으면 skip)
--   2. install_parts_categories.sql  ← 파츠종류 5001~5041
--   3. install_brand_categories.sql  ← 부품브랜드 B0~B99  ← 신규!
--   4. install_parts_master.sql      ← pds_parts_master 테이블
--   5. 이 파일 실행
--
-- ■ Cafe24 분류번호 → ca_id 매핑
--   35670~35672 → 2010 (BMW 3시리즈 F30)
--   35673       → 2011 (BMW 3시리즈 E90)
--   35677~35680 → 2017 (BMW 5시리즈 F10)
--   37032       → 2016 (BMW 5시리즈 G30)
--   37033       → 2017 (BMW 5시리즈 F10)
--
-- ■ 이미지 경로: 서버 /partsds/images/BMW/ 디렉토리에 업로드 필요
-- ============================================================

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- ──────────────────────────────────────────────────────────────
-- 1. g5_shop_item - BMW 상품 등록 (206개)
--    OEM 없는 상품: 18개 / OEM 중복처리: 117개
-- ──────────────────────────────────────────────────────────────

-- 행2: 오일필터 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11428593186_2010', '2010', '5001', 'B01',
   '오일필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   7500, 0, 9000,
   '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png',
   1, 1, '11428593186',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행3: 에어필터 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13718513944_2010', '2010', '5002', 'B01',
   '에어필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   9500, 0, 11400,
   '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg',
   1, 1, '13718513944',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행4: 에어컨필터 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64316835405_2010', '2010', '5003', 'B01',
   '에어컨필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   11300, 0, 13600,
   '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png',
   1, 1, '64316835405',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행5: 부동액 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2010', '2010', '5009', 'B99',
   '부동액', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN1.JPG" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   23000, 0, 27600,
   '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000DK',
   'B0000000',
   NOW(), NOW());

-- 행6: 브레이크오일 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2010_6', '2010', '5010', 'B99',
   '브레이크오일', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BO.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13700, 0, 16500,
   '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행7: 브레이크디스크(앞) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34116865713_2010', '2010', '5011', 'B99',
   '브레이크디스크(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   136200, 0, 163500,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34116865713',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행8: 브레이크디스크(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34216799369_2010', '2010', '5011', 'B99',
   '브레이크디스크(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   80600, 0, 96800,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34216799369',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행9: 브레이크패드(앞) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34106860019_2010', '2010', '5012', 'B99',
   '브레이크패드(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   39600, 0, 47600,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34106860019',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행10: 브레이크패드(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34201543683_2010', '2010', '5012', 'B99',
   '브레이크패드(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   28600, 0, 34400,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34201543683',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행11: 브레이크패드센서(앞) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356888167_2010', '2010', '5012', 'B99',
   '브레이크패드센서(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356888167',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행12: 브레이크패드센서(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356865612_2010', '2010', '5012', 'B99',
   '브레이크패드센서(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356865612',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행13: V벨트 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11287952902_2010', '2010', '5017', 'B99',
   'V벨트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13200, 0, 15900,
   '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg',
   1, 1, '11287952902',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행14: 와이퍼(운전석.조수석세트) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61615A25860_2010', '2010', '5029', 'B99',
   '와이퍼(운전석.조수석세트)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61615A25860',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행15: 와이퍼(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61627442093_2010', '2010', '5029', 'B99',
   '와이퍼(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   19800, 0, 23800,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61627442093',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행16: 스테빌라이져링크 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31306862864_2010', '2010', '5041', 'B99',
   '스테빌라이져링크', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   26400, 0, 31700,
   '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png',
   1, 1, '31306862864',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행17: 컨트롤암(앞.운전석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879843_2010', '2010', '5041', 'B99',
   '컨트롤암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행18: 컨트롤암(앞.조수석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879844_2010', '2010', '5041', 'B99',
   '컨트롤암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행19: 홀더위시본 어퍼암(앞.운전석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31128831645_2010', '2010', '5041', 'B99',
   '홀더위시본 어퍼암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31128831645',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행20: 홀더위시본 어퍼암(앞.조수석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31128831646_2010', '2010', '5041', 'B99',
   '홀더위시본 어퍼암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31128831646',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행21: 휠볼트 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('36136890324_2010', '2010', '5034', 'B99',
   '휠볼트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   3200, 0, 3900,
   '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png',
   1, 1, '36136890324',
   0, 0, 0, '%', 1, 0,
   'M00000BV',
   'B0000000',
   NOW(), NOW());

-- 행22: 플러그 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12122455258_2010', '2010', '5041', 'B99',
   '플러그', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   49500, 0, 59400,
   '/partsds/images/BMW/S1.jpg', '/partsds/images/BMW/S1.jpg', '/partsds/images/BMW/S1.jpg', '/partsds/images/BMW/S1.jpg',
   1, 1, '12122455258',
   0, 0, 0, '%', 1, 0,
   'M00000BX',
   'B0000000',
   NOW(), NOW());

-- 행23: 점화코일(플러그배선) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12138643360_2010', '2010', '5041', 'B99',
   '점화코일(플러그배선)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg',
   1, 1, '12138643360',
   0, 0, 0, '%', 1, 0,
   'M00000CR',
   'B0000000',
   NOW(), NOW());

-- 행24: 오일필터 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11428593186_2010_24', '2010', '5001', 'B01',
   '오일필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   7500, 0, 9000,
   '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png',
   1, 1, '11428593186',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행25: 에어필터 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13718513944_2010_25', '2010', '5002', 'B01',
   '에어필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   9500, 0, 11400,
   '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg',
   1, 1, '13718513944',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행26: 에어컨필터 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64316835405_2010_26', '2010', '5003', 'B01',
   '에어컨필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   11300, 0, 13600,
   '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png',
   1, 1, '64316835405',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행27: 부동액 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2010_27', '2010', '5009', 'B99',
   '부동액', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN1.JPG" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   23000, 0, 27600,
   '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000DK',
   'B0000000',
   NOW(), NOW());

-- 행28: 브레이크오일 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2010_28', '2010', '5010', 'B99',
   '브레이크오일', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BO.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13700, 0, 16500,
   '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행29: 브레이크디스크(앞) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34116865713_2010_29', '2010', '5011', 'B99',
   '브레이크디스크(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   136200, 0, 163500,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34116865713',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행30: 브레이크디스크(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34216799369_2010_30', '2010', '5011', 'B99',
   '브레이크디스크(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   80600, 0, 96800,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34216799369',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행31: 브레이크패드(앞) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34106860019_2010_31', '2010', '5012', 'B99',
   '브레이크패드(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   39600, 0, 47600,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34106860019',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행32: 브레이크패드(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34201543683_2010_32', '2010', '5012', 'B99',
   '브레이크패드(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   28600, 0, 34400,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34201543683',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행33: 브레이크패드센서(앞) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356888167_2010_33', '2010', '5012', 'B99',
   '브레이크패드센서(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356888167',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행34: 브레이크패드센서(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356865612_2010_34', '2010', '5012', 'B99',
   '브레이크패드센서(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356865612',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행35: 벨트텐셔너 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11288580360_2010', '2010', '5019', 'B99',
   '벨트텐셔너', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   65600, 0, 78800,
   '/partsds/images/BMW/E2.jpg', '/partsds/images/BMW/E2.jpg', '/partsds/images/BMW/E2.jpg', '/partsds/images/BMW/E2.jpg',
   1, 1, '11288580360',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행36: 와이퍼(운전석.조수석세트) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61615A25860_2010_36', '2010', '5029', 'B99',
   '와이퍼(운전석.조수석세트)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61615A25860',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행37: 와이퍼(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61627442093_2010_37', '2010', '5029', 'B99',
   '와이퍼(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   19800, 0, 23800,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61627442093',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행38: 스테빌라이져링크 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31306862864_2010_38', '2010', '5041', 'B99',
   '스테빌라이져링크', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   26400, 0, 31700,
   '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png',
   1, 1, '31306862864',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행39: 컨트롤암(앞.운전석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879843_2010_39', '2010', '5041', 'B99',
   '컨트롤암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행40: 컨트롤암(앞.조수석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879844_2010_40', '2010', '5041', 'B99',
   '컨트롤암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행41: 홀더위시본 어퍼암(앞.운전석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31128831645_2010_41', '2010', '5041', 'B99',
   '홀더위시본 어퍼암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31128831645',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행42: 홀더위시본 어퍼암(앞.조수석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31128831646_2010_42', '2010', '5041', 'B99',
   '홀더위시본 어퍼암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31128831646',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행43: 휠볼트 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('36136890324_2010_43', '2010', '5034', 'B99',
   '휠볼트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   3200, 0, 3900,
   '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png',
   1, 1, '36136890324',
   0, 0, 0, '%', 1, 0,
   'M00000BV',
   'B0000000',
   NOW(), NOW());

-- 행44: 플러그 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12122455258_2010_44', '2010', '5041', 'B99',
   '플러그', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   49500, 0, 59400,
   '/partsds/images/BMW/S1.jpg', '/partsds/images/BMW/S1.jpg', '/partsds/images/BMW/S1.jpg', '/partsds/images/BMW/S1.jpg',
   1, 1, '12122455258',
   0, 0, 0, '%', 1, 0,
   'M00000BX',
   'B0000000',
   NOW(), NOW());

-- 행45: 점화코일(플러그배선) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12138643360_2010_45', '2010', '5041', 'B99',
   '점화코일(플러그배선)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg',
   1, 1, '12138643360',
   0, 0, 0, '%', 1, 0,
   'M00000CR',
   'B0000000',
   NOW(), NOW());

-- 행46: 오일필터 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11428593186_2010_46', '2010', '5001', 'B01',
   '오일필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   7500, 0, 9000,
   '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png',
   1, 1, '11428593186',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행47: 에어필터 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13718513944_2010_47', '2010', '5002', 'B01',
   '에어필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   9500, 0, 11400,
   '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg',
   1, 1, '13718513944',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행48: 에어컨필터 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64316835405_2010_48', '2010', '5003', 'B01',
   '에어컨필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   11300, 0, 13600,
   '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png',
   1, 1, '64316835405',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행49: 부동액 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2010_49', '2010', '5009', 'B99',
   '부동액', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN1.JPG" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   23000, 0, 27600,
   '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000DK',
   'B0000000',
   NOW(), NOW());

-- 행50: 브레이크오일 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2010_50', '2010', '5010', 'B99',
   '브레이크오일', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BO.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13700, 0, 16500,
   '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행51: 브레이크디스크(앞) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34116865713_2010_51', '2010', '5011', 'B99',
   '브레이크디스크(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   136200, 0, 163500,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34116865713',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행52: 브레이크디스크(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34216799369_2010_52', '2010', '5011', 'B99',
   '브레이크디스크(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   80600, 0, 96800,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34216799369',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행53: 브레이크패드(앞) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34106860019_2010_53', '2010', '5012', 'B99',
   '브레이크패드(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   39600, 0, 47600,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34106860019',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행54: 브레이크패드(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34201543683_2010_54', '2010', '5012', 'B99',
   '브레이크패드(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   28600, 0, 34400,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34201543683',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행55: 브레이크패드센서(앞) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356888167_2010_55', '2010', '5012', 'B99',
   '브레이크패드센서(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356888167',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행56: 브레이크패드센서(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356865612_2010_56', '2010', '5012', 'B99',
   '브레이크패드센서(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356865612',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행57: 벨트텐셔너 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11288580360_2010_57', '2010', '5019', 'B99',
   '벨트텐셔너', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   65600, 0, 78800,
   '/partsds/images/BMW/E2.jpg', '/partsds/images/BMW/E2.jpg', '/partsds/images/BMW/E2.jpg', '/partsds/images/BMW/E2.jpg',
   1, 1, '11288580360',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행58: 와이퍼(운전석.조수석세트) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61615A25860_2010_58', '2010', '5029', 'B99',
   '와이퍼(운전석.조수석세트)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61615A25860',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행59: 와이퍼(뒤) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61627442093_2010_59', '2010', '5029', 'B99',
   '와이퍼(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   19800, 0, 23800,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61627442093',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행60: 스테빌라이져링크 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31306862864_2010_60', '2010', '5041', 'B99',
   '스테빌라이져링크', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   26400, 0, 31700,
   '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png',
   1, 1, '31306862864',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행61: 컨트롤암(앞.운전석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879843_2010_61', '2010', '5041', 'B99',
   '컨트롤암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행62: 컨트롤암(앞.조수석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879844_2010_62', '2010', '5041', 'B99',
   '컨트롤암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행63: 홀더위시본 어퍼암(앞.운전석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31128831645_2010_63', '2010', '5041', 'B99',
   '홀더위시본 어퍼암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31128831645',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행64: 홀더위시본 어퍼암(앞.조수석) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31128831646_2010_64', '2010', '5041', 'B99',
   '홀더위시본 어퍼암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31128831646',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행65: 휠볼트 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('36136890324_2010_65', '2010', '5034', 'B99',
   '휠볼트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   3200, 0, 3900,
   '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png',
   1, 1, '36136890324',
   0, 0, 0, '%', 1, 0,
   'M00000BV',
   'B0000000',
   NOW(), NOW());

-- 행66: 플러그 [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12122455258_2010_66', '2010', '5041', 'B99',
   '플러그', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   49500, 0, 59400,
   '/partsds/images/BMW/S1.jpg', '/partsds/images/BMW/S1.jpg', '/partsds/images/BMW/S1.jpg', '/partsds/images/BMW/S1.jpg',
   1, 1, '12122455258',
   0, 0, 0, '%', 1, 0,
   'M00000BX',
   'B0000000',
   NOW(), NOW());

-- 행67: 점화코일(플러그배선) [BMW 3시리즈 F30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12138643360_2010_67', '2010', '5041', 'B99',
   '점화코일(플러그배선)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg',
   1, 1, '12138643360',
   0, 0, 0, '%', 1, 0,
   'M00000CR',
   'B0000000',
   NOW(), NOW());

-- 행68: 오일필터 [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11428593186_2011', '2011', '5001', 'B01',
   '오일필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   7500, 0, 9000,
   '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png',
   1, 1, '11428593186',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행69: 에어필터 [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13718480400_2011', '2011', '5002', 'B99',
   '에어필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   45900, 0, 55100,
   '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg',
   1, 1, '13718480400',
   0, 0, 0, '%', 1, 0,
   'M00000CK',
   'B0000000',
   NOW(), NOW());

-- 행70: 에어컨필터 [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64316835405_2011', '2011', '5003', 'B01',
   '에어컨필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   11300, 0, 13600,
   '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png',
   1, 1, '64316835405',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행71: 부동액 [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2011', '2011', '5009', 'B99',
   '부동액', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN1.JPG" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   23000, 0, 27600,
   '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000DK',
   'B0000000',
   NOW(), NOW());

-- 행72: 브레이크오일 [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2011_72', '2011', '5010', 'B99',
   '브레이크오일', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BO.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13700, 0, 16500,
   '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행73: 브레이크디스크(앞) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34106884001_2011', '2011', '5011', 'B99',
   '브레이크디스크(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   108900, 0, 130700,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34106884001',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행74: 브레이크패드(앞) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34106889266_2011', '2011', '5012', 'B99',
   '브레이크패드(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   54500, 0, 65400,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34106889266',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행75: 브레이크패드(뒤) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34206885600_2011', '2011', '5012', 'B99',
   '브레이크패드(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   86700, 0, 104100,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34206885600',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행76: 브레이크패드센서(앞) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356888167_2011', '2011', '5012', 'B99',
   '브레이크패드센서(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356888167',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행77: 브레이크패드센서(뒤) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356887154_2011', '2011', '5012', 'B99',
   '브레이크패드센서(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356887154',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행78: 벨트텐셔너 [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11288580360_2011', '2011', '5019', 'B99',
   '벨트텐셔너', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   65600, 0, 78800,
   '/partsds/images/BMW/E2.jpg', '/partsds/images/BMW/E2.jpg', '/partsds/images/BMW/E2.jpg', '/partsds/images/BMW/E2.jpg',
   1, 1, '11288580360',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행79: 스타트모터 [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12418676405_2011', '2011', '5025', 'B99',
   '스타트모터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   313500, 0, 376200,
   '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png',
   1, 1, '12418676405',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행80: 와이퍼(운전석.조수석세트) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61615A25860_2011', '2011', '5029', 'B99',
   '와이퍼(운전석.조수석세트)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61615A25860',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행81: 와이퍼(뒤) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61627442093_2011', '2011', '5029', 'B99',
   '와이퍼(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   19800, 0, 23800,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61627442093',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행82: 스테빌라이져링크 [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31306862864_2011', '2011', '5041', 'B99',
   '스테빌라이져링크', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   26400, 0, 31700,
   '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png',
   1, 1, '31306862864',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행83: 컨트롤암(앞.운전석) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879843_2011', '2011', '5041', 'B99',
   '컨트롤암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행84: 컨트롤암(앞.조수석) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879844_2011', '2011', '5041', 'B99',
   '컨트롤암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행85: 홀더위시본 어퍼암(앞.운전석) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31128831645_2011', '2011', '5041', 'B99',
   '홀더위시본 어퍼암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31128831645',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행86: 홀더위시본 어퍼암(앞.조수석) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31128831646_2011', '2011', '5041', 'B99',
   '홀더위시본 어퍼암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31128831646',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행87: 휠볼트 [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('36136890324_2011', '2011', '5034', 'B99',
   '휠볼트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   3200, 0, 3900,
   '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png',
   1, 1, '36136890324',
   0, 0, 0, '%', 1, 0,
   'M00000BV',
   'B0000000',
   NOW(), NOW());

-- 행88: 점화코일(플러그배선) [BMW 3시리즈 E90]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12138643360_2011', '2011', '5041', 'B99',
   '점화코일(플러그배선)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg', '/partsds/images/BMW/S2.jpg',
   1, 1, '12138643360',
   0, 0, 0, '%', 1, 0,
   'M00000CR',
   'B0000000',
   NOW(), NOW());

-- 행89: 오일필터 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11428593186_2016', '2016', '5001', 'B01',
   '오일필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   7500, 0, 9000,
   '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png',
   1, 1, '11428593186',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행90: 에어필터 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13718513944_2016', '2016', '5002', 'B01',
   '에어필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   9500, 0, 11400,
   '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg',
   1, 1, '13718513944',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행91: 에어컨필터 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64316835405_2016', '2016', '5003', 'B01',
   '에어컨필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   11300, 0, 13600,
   '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png',
   1, 1, '64316835405',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행92: 연료필터 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13328584868_2016', '2016', '5004', 'B99',
   '연료필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   58300, 0, 70000,
   '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png',
   1, 1, '13328584868',
   0, 0, 0, '%', 1, 0,
   'M00000CI',
   'B0000000',
   NOW(), NOW());

-- 행93: 부동액 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2016', '2016', '5009', 'B99',
   '부동액', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN1.JPG" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   23000, 0, 27600,
   '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000DK',
   'B0000000',
   NOW(), NOW());

-- 행94: 브레이크오일 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2016_94', '2016', '5010', 'B99',
   '브레이크오일', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BO.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13700, 0, 16500,
   '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행95: 브레이크디스크(앞) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34116799351_2016', '2016', '5011', 'B99',
   '브레이크디스크(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   96600, 0, 116000,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34116799351',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행96: 브레이크디스크(뒤) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34216861013_2016', '2016', '5011', 'B99',
   '브레이크디스크(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   38500, 0, 46200,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34216861013',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행97: 브레이크패드(앞) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34106860019_2016', '2016', '5012', 'B99',
   '브레이크패드(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   39600, 0, 47600,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34106860019',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행98: 브레이크패드(뒤) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34206885547_2016', '2016', '5012', 'B99',
   '브레이크패드(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   28600, 0, 34400,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34206885547',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행99: 브레이크패드센서(앞) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34351543830_2016', '2016', '5012', 'B99',
   '브레이크패드센서(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34351543830',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행100: 브레이크패드센서(뒤) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356865612_2016', '2016', '5012', 'B99',
   '브레이크패드센서(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356865612',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행101: V벨트 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11289888807_2016', '2016', '5017', 'B99',
   'V벨트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13200, 0, 15900,
   '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg',
   1, 1, '11289888807',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행102: 콘덴서 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64539271207_2016', '2016', '5041', 'B99',
   '콘덴서', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   162200, 0, 194700,
   '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png',
   1, 1, '64539271207',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행103: 스타트모터 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12418580389_2016', '2016', '5025', 'B99',
   '스타트모터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   313500, 0, 376200,
   '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png',
   1, 1, '12418580389',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행104: 와이퍼(운전석.조수석세트) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61615A25860_2016', '2016', '5029', 'B99',
   '와이퍼(운전석.조수석세트)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61615A25860',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행105: 와이퍼(뒤) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61627442093_2016', '2016', '5029', 'B99',
   '와이퍼(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   19800, 0, 23800,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61627442093',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행106: 컨트롤암(앞.운전석) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879843_2016', '2016', '5041', 'B99',
   '컨트롤암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행107: 컨트롤암(앞.조수석) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879844_2016', '2016', '5041', 'B99',
   '컨트롤암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행108: 홀더위시본 어퍼암(앞.운전석) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126882843_2016', '2016', '5041', 'B99',
   '홀더위시본 어퍼암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31126882843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행109: 홀더위시본 어퍼암(앞.조수석) [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126882844_2016', '2016', '5041', 'B99',
   '홀더위시본 어퍼암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31126882844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행110: 휠볼트 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('36136890324_2016', '2016', '5034', 'B99',
   '휠볼트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   3200, 0, 3900,
   '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png',
   1, 1, '36136890324',
   0, 0, 0, '%', 1, 0,
   'M00000BV',
   'B0000000',
   NOW(), NOW());

-- 행111: 예열플러그 [BMW 5시리즈 G30]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12230053522_2016', '2016', '5038', 'B99',
   '예열플러그', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   54500, 0, 65400,
   '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png',
   1, 1, '12230053522',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행112: 오일필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11428593186_2017', '2017', '5001', 'B01',
   '오일필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   7500, 0, 9000,
   '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png',
   1, 1, '11428593186',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행113: 에어필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13718513944_2017', '2017', '5002', 'B01',
   '에어필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   9500, 0, 11400,
   '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg',
   1, 1, '13718513944',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행114: 에어컨필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64316835405_2017', '2017', '5003', 'B01',
   '에어컨필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   11300, 0, 13600,
   '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png',
   1, 1, '64316835405',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행115: 연료필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13328584868_2017', '2017', '5004', 'B99',
   '연료필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   58300, 0, 70000,
   '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png',
   1, 1, '13328584868',
   0, 0, 0, '%', 1, 0,
   'M00000CI',
   'B0000000',
   NOW(), NOW());

-- 행116: 부동액 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2017', '2017', '5009', 'B99',
   '부동액', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN1.JPG" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   23000, 0, 27600,
   '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000DK',
   'B0000000',
   NOW(), NOW());

-- 행117: 브레이크오일 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2017_117', '2017', '5010', 'B99',
   '브레이크오일', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BO.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13700, 0, 16500,
   '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행118: 브레이크디스크(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34116799351_2017', '2017', '5011', 'B99',
   '브레이크디스크(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   96600, 0, 116000,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34116799351',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행119: 브레이크디스크(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34216799367_2017', '2017', '5011', 'B99',
   '브레이크디스크(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   38500, 0, 46200,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34216799367',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행120: 브레이크패드(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34106860019_2017', '2017', '5012', 'B99',
   '브레이크패드(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   39600, 0, 47600,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34106860019',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행121: 브레이크패드(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34206885547_2017', '2017', '5012', 'B99',
   '브레이크패드(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   28600, 0, 34400,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34206885547',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행122: 브레이크패드센서(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34351543830_2017', '2017', '5012', 'B99',
   '브레이크패드센서(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34351543830',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행123: 브레이크패드센서(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356865612_2017', '2017', '5012', 'B99',
   '브레이크패드센서(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356865612',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행124: V벨트 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11289888807_2017', '2017', '5017', 'B99',
   'V벨트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13200, 0, 15900,
   '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg',
   1, 1, '11289888807',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행125: 콘덴서 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64539271207_2017', '2017', '5041', 'B99',
   '콘덴서', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   162200, 0, 194700,
   '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png',
   1, 1, '64539271207',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행126: 스타트모터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12418580389_2017', '2017', '5025', 'B99',
   '스타트모터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   313500, 0, 376200,
   '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png',
   1, 1, '12418580389',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행127: 와이퍼(운전석.조수석세트) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61615A25860_2017', '2017', '5029', 'B99',
   '와이퍼(운전석.조수석세트)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61615A25860',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행128: 와이퍼(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61627442093_2017', '2017', '5029', 'B99',
   '와이퍼(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   19800, 0, 23800,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61627442093',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행129: 스테빌라이져링크 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31306862864_2017', '2017', '5041', 'B99',
   '스테빌라이져링크', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   26400, 0, 31700,
   '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png',
   1, 1, '31306862864',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행130: 컨트롤암(앞.운전석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879843_2017', '2017', '5041', 'B99',
   '컨트롤암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행131: 컨트롤암(앞.조수석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879844_2017', '2017', '5041', 'B99',
   '컨트롤암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행132: 홀더위시본 어퍼암(앞.운전석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126882843_2017', '2017', '5041', 'B99',
   '홀더위시본 어퍼암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31126882843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행133: 홀더위시본 어퍼암(앞.조수석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126882844_2017', '2017', '5041', 'B99',
   '홀더위시본 어퍼암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31126882844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행134: 휠볼트 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('36136890324_2017', '2017', '5034', 'B99',
   '휠볼트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   3200, 0, 3900,
   '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png',
   1, 1, '36136890324',
   0, 0, 0, '%', 1, 0,
   'M00000BV',
   'B0000000',
   NOW(), NOW());

-- 행135: 예열플러그 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12230053522_2017', '2017', '5038', 'B99',
   '예열플러그', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   54500, 0, 65400,
   '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png',
   1, 1, '12230053522',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행136: 오일필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11428593186_2017_136', '2017', '5001', 'B01',
   '오일필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   7500, 0, 9000,
   '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png',
   1, 1, '11428593186',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행137: 에어필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13718513944_2017_137', '2017', '5002', 'B01',
   '에어필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   9500, 0, 11400,
   '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg',
   1, 1, '13718513944',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행138: 에어컨필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64316835405_2017_138', '2017', '5003', 'B01',
   '에어컨필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   11300, 0, 13600,
   '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png',
   1, 1, '64316835405',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행139: 연료필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13328584868_2017_139', '2017', '5004', 'B99',
   '연료필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   58300, 0, 70000,
   '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png',
   1, 1, '13328584868',
   0, 0, 0, '%', 1, 0,
   'M00000CI',
   'B0000000',
   NOW(), NOW());

-- 행140: 부동액 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2017_140', '2017', '5009', 'B99',
   '부동액', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN1.JPG" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   23000, 0, 27600,
   '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000DK',
   'B0000000',
   NOW(), NOW());

-- 행141: 브레이크오일 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2017_141', '2017', '5010', 'B99',
   '브레이크오일', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BO.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13700, 0, 16500,
   '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행142: 브레이크디스크(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34116799351_2017_142', '2017', '5011', 'B99',
   '브레이크디스크(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   96600, 0, 116000,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34116799351',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행143: 브레이크디스크(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34216799367_2017_143', '2017', '5011', 'B99',
   '브레이크디스크(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   38500, 0, 46200,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34216799367',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행144: 브레이크패드(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34106860019_2017_144', '2017', '5012', 'B99',
   '브레이크패드(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   39600, 0, 47600,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34106860019',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행145: 브레이크패드(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34201543683_2017', '2017', '5012', 'B99',
   '브레이크패드(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   28600, 0, 34400,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34201543683',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행146: 브레이크패드센서(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34351543830_2017_146', '2017', '5012', 'B99',
   '브레이크패드센서(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34351543830',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행147: 브레이크패드센서(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356865612_2017_147', '2017', '5012', 'B99',
   '브레이크패드센서(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356865612',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행148: V벨트 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11289888807_2017_148', '2017', '5017', 'B99',
   'V벨트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13200, 0, 15900,
   '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg',
   1, 1, '11289888807',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행149: 콘덴서 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64539271207_2017_149', '2017', '5041', 'B99',
   '콘덴서', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   162200, 0, 194700,
   '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png',
   1, 1, '64539271207',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행150: 스타트모터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12418580389_2017_150', '2017', '5025', 'B99',
   '스타트모터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   313500, 0, 376200,
   '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png',
   1, 1, '12418580389',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행151: 와이퍼(운전석.조수석세트) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61615A25860_2017_151', '2017', '5029', 'B99',
   '와이퍼(운전석.조수석세트)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61615A25860',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행152: 와이퍼(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61627442093_2017_152', '2017', '5029', 'B99',
   '와이퍼(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   19800, 0, 23800,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61627442093',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행153: 스테빌라이져링크 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31306862864_2017_153', '2017', '5041', 'B99',
   '스테빌라이져링크', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   26400, 0, 31700,
   '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png',
   1, 1, '31306862864',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행154: 컨트롤암(앞.운전석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879843_2017_154', '2017', '5041', 'B99',
   '컨트롤암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행155: 컨트롤암(앞.조수석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879844_2017_155', '2017', '5041', 'B99',
   '컨트롤암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행156: 홀더위시본 어퍼암(앞.운전석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126882843_2017_156', '2017', '5041', 'B99',
   '홀더위시본 어퍼암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31126882843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행157: 홀더위시본 어퍼암(앞.조수석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126882844_2017_157', '2017', '5041', 'B99',
   '홀더위시본 어퍼암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31126882844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행158: 휠볼트 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('36136890324_2017_158', '2017', '5034', 'B99',
   '휠볼트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   3200, 0, 3900,
   '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png',
   1, 1, '36136890324',
   0, 0, 0, '%', 1, 0,
   'M00000BV',
   'B0000000',
   NOW(), NOW());

-- 행159: 예열플러그 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12230053522_2017_159', '2017', '5038', 'B99',
   '예열플러그', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   54500, 0, 65400,
   '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png',
   1, 1, '12230053522',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행160: 오일필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11428593186_2017_160', '2017', '5001', 'B01',
   '오일필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   7500, 0, 9000,
   '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png',
   1, 1, '11428593186',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행161: 에어필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13718513944_2017_161', '2017', '5002', 'B01',
   '에어필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   9500, 0, 11400,
   '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg',
   1, 1, '13718513944',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행162: 에어컨필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64316835405_2017_162', '2017', '5003', 'B01',
   '에어컨필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   11300, 0, 13600,
   '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png',
   1, 1, '64316835405',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행163: 연료필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13328584868_2017_163', '2017', '5004', 'B99',
   '연료필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   58300, 0, 70000,
   '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png',
   1, 1, '13328584868',
   0, 0, 0, '%', 1, 0,
   'M00000CI',
   'B0000000',
   NOW(), NOW());

-- 행164: 부동액 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2017_164', '2017', '5009', 'B99',
   '부동액', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN1.JPG" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   23000, 0, 27600,
   '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000DK',
   'B0000000',
   NOW(), NOW());

-- 행165: 브레이크오일 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2017_165', '2017', '5010', 'B99',
   '브레이크오일', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BO.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13700, 0, 16500,
   '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행166: 브레이크디스크(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34116799351_2017_166', '2017', '5011', 'B99',
   '브레이크디스크(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   96600, 0, 116000,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34116799351',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행167: 브레이크디스크(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34216861013_2017', '2017', '5011', 'B99',
   '브레이크디스크(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   38500, 0, 46200,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34216861013',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행168: 브레이크패드(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34106860019_2017_168', '2017', '5012', 'B99',
   '브레이크패드(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   39600, 0, 47600,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34106860019',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행169: 브레이크패드(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34206885547_2017_169', '2017', '5012', 'B99',
   '브레이크패드(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   28600, 0, 34400,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34206885547',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행170: 브레이크패드센서(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34351543830_2017_170', '2017', '5012', 'B99',
   '브레이크패드센서(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34351543830',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행171: 브레이크패드센서(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356865612_2017_171', '2017', '5012', 'B99',
   '브레이크패드센서(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356865612',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행172: V벨트 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11289888807_2017_172', '2017', '5017', 'B99',
   'V벨트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13200, 0, 15900,
   '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg',
   1, 1, '11289888807',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행173: 콘덴서 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64539271207_2017_173', '2017', '5041', 'B99',
   '콘덴서', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   162200, 0, 194700,
   '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png',
   1, 1, '64539271207',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행174: 스타트모터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12418580389_2017_174', '2017', '5025', 'B99',
   '스타트모터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   313500, 0, 376200,
   '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png',
   1, 1, '12418580389',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행175: 와이퍼(운전석.조수석세트) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61615A25860_2017_175', '2017', '5029', 'B99',
   '와이퍼(운전석.조수석세트)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61615A25860',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행176: 와이퍼(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61627442093_2017_176', '2017', '5029', 'B99',
   '와이퍼(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   19800, 0, 23800,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61627442093',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행177: 스테빌라이져링크 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31306862864_2017_177', '2017', '5041', 'B99',
   '스테빌라이져링크', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   26400, 0, 31700,
   '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png',
   1, 1, '31306862864',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행178: 컨트롤암(앞.운전석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879843_2017_178', '2017', '5041', 'B99',
   '컨트롤암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행179: 컨트롤암(앞.조수석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879844_2017_179', '2017', '5041', 'B99',
   '컨트롤암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행180: 홀더위시본 어퍼암(앞.운전석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126882843_2017_180', '2017', '5041', 'B99',
   '홀더위시본 어퍼암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31126882843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행181: 홀더위시본 어퍼암(앞.조수석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126882844_2017_181', '2017', '5041', 'B99',
   '홀더위시본 어퍼암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31126882844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행182: 휠볼트 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('36136890324_2017_182', '2017', '5034', 'B99',
   '휠볼트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   3200, 0, 3900,
   '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png',
   1, 1, '36136890324',
   0, 0, 0, '%', 1, 0,
   'M00000BV',
   'B0000000',
   NOW(), NOW());

-- 행183: 예열플러그 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12230053522_2017_183', '2017', '5038', 'B99',
   '예열플러그', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   54500, 0, 65400,
   '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png',
   1, 1, '12230053522',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행184: 오일필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11428593186_2017_184', '2017', '5001', 'B01',
   '오일필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   7500, 0, 9000,
   '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png', '/partsds/images/BMW/A.png',
   1, 1, '11428593186',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행185: 에어필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13718513944_2017_185', '2017', '5002', 'B01',
   '에어필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   9500, 0, 11400,
   '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg', '/partsds/images/BMW/A1.jpg',
   1, 1, '13718513944',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행186: 에어컨필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64316835405_2017_186', '2017', '5003', 'B01',
   '에어컨필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/F.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   11300, 0, 13600,
   '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png', '/partsds/images/BMW/A2.png',
   1, 1, '64316835405',
   0, 0, 0, '%', 1, 0,
   'M00000BM',
   'B0000000',
   NOW(), NOW());

-- 행187: 연료필터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('13328584868_2017_187', '2017', '5004', 'B99',
   '연료필터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   58300, 0, 70000,
   '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png', '/partsds/images/BMW/A3.png',
   1, 1, '13328584868',
   0, 0, 0, '%', 1, 0,
   'M00000CI',
   'B0000000',
   NOW(), NOW());

-- 행188: 부동액 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2017_188', '2017', '5009', 'B99',
   '부동액', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/AN1.JPG" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   23000, 0, 27600,
   '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png', '/partsds/images/BMW/B2.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000DK',
   'B0000000',
   NOW(), NOW());

-- 행189: 브레이크오일 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('X_2017_189', '2017', '5010', 'B99',
   '브레이크오일', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BO.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13700, 0, 16500,
   '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png', '/partsds/images/BMW/B3.png',
   1, 1, '',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행190: 브레이크디스크(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34116799351_2017_190', '2017', '5011', 'B99',
   '브레이크디스크(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   96600, 0, 116000,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34116799351',
   0, 0, 0, '%', 1, 0,
   'M00000BP',
   'B0000000',
   NOW(), NOW());

-- 행191: 브레이크디스크(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34216861013_2017_191', '2017', '5011', 'B99',
   '브레이크디스크(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   38500, 0, 46200,
   '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png', '/partsds/images/BMW/C.png',
   1, 1, '34216861013',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행192: 브레이크패드(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34106860019_2017_192', '2017', '5012', 'B99',
   '브레이크패드(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   39600, 0, 47600,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34106860019',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행193: 브레이크패드(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34206885547_2017_193', '2017', '5012', 'B99',
   '브레이크패드(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   28600, 0, 34400,
   '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png', '/partsds/images/BMW/C1.png',
   1, 1, '34206885547',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행194: 브레이크패드센서(앞) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34351543830_2017_194', '2017', '5012', 'B99',
   '브레이크패드센서(앞)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34351543830',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행195: 브레이크패드센서(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('34356865612_2017_195', '2017', '5012', 'B99',
   '브레이크패드센서(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/BP.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   6300, 0, 7600,
   '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png', '/partsds/images/BMW/C2.png',
   1, 1, '34356865612',
   0, 0, 0, '%', 1, 0,
   'M00000BO',
   'B0000000',
   NOW(), NOW());

-- 행196: V벨트 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('11289888807_2017_196', '2017', '5017', 'B99',
   'V벨트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   13200, 0, 15900,
   '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg', '/partsds/images/BMW/E.jpg',
   1, 1, '11289888807',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행197: 콘덴서 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('64539271207_2017_197', '2017', '5041', 'B99',
   '콘덴서', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   162200, 0, 194700,
   '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png', '/partsds/images/BMW/F3.png',
   1, 1, '64539271207',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행198: 스타트모터 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12418580389_2017_198', '2017', '5025', 'B99',
   '스타트모터', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   313500, 0, 376200,
   '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png', '/partsds/images/BMW/I.png',
   1, 1, '12418580389',
   0, 0, 0, '%', 1, 0,
   'M00000BW',
   'B0000000',
   NOW(), NOW());

-- 행199: 와이퍼(운전석.조수석세트) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61615A25860_2017_199', '2017', '5029', 'B99',
   '와이퍼(운전석.조수석세트)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   55800, 0, 67000,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61615A25860',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행200: 와이퍼(뒤) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('61627442093_2017_200', '2017', '5029', 'B99',
   '와이퍼(뒤)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/W.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   19800, 0, 23800,
   '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png', '/partsds/images/BMW/L.png',
   1, 1, '61627442093',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());

-- 행201: 스테빌라이져링크 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31306862864_2017_201', '2017', '5041', 'B99',
   '스테빌라이져링크', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   26400, 0, 31700,
   '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png', '/partsds/images/BMW/Q.png',
   1, 1, '31306862864',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행202: 컨트롤암(앞.운전석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879843_2017_202', '2017', '5041', 'B99',
   '컨트롤암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행203: 컨트롤암(앞.조수석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126879844_2017_203', '2017', '5041', 'B99',
   '컨트롤암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   101400, 0, 121700,
   '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png', '/partsds/images/BMW/Q1.png',
   1, 1, '31126879844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행204: 홀더위시본 어퍼암(앞.운전석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126882843_2017_204', '2017', '5041', 'B99',
   '홀더위시본 어퍼암(앞.운전석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31126882843',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행205: 홀더위시본 어퍼암(앞.조수석) [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('31126882844_2017_205', '2017', '5041', 'B99',
   '홀더위시본 어퍼암(앞.조수석)', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   85800, 0, 103000,
   '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png', '/partsds/images/BMW/Q2.png',
   1, 1, '31126882844',
   0, 0, 0, '%', 1, 0,
   'M00000CA',
   'B0000000',
   NOW(), NOW());

-- 행206: 휠볼트 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('36136890324_2017_206', '2017', '5034', 'B99',
   '휠볼트', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   3200, 0, 3900,
   '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png', '/partsds/images/BMW/p2.png',
   1, 1, '36136890324',
   0, 0, 0, '%', 1, 0,
   'M00000BV',
   'B0000000',
   NOW(), NOW());

-- 행207: 예열플러그 [BMW 5시리즈 F10]
INSERT INTO `g5_shop_item`
  (`it_id`, `ca_id`, `ca_id2`, `ca_id3`,
   `it_name`, `it_info`, `it_content`,
   `it_price`, `it_cust_price`, `it_supply_price`,
   `it_img1`, `it_img2`, `it_img3`, `it_img4`,
   `it_sell_display`, `it_sell_use`, `it_id_code`,
   `it_hit`, `it_order`, `it_point`, `it_point_type`,
   `it_minimum`, `it_maximum`,
   `it_maker`, `it_brand`,
   `it_regdate`, `it_update`)
VALUES
  ('12230053522_2017_207', '2017', '5038', 'B99',
   '예열플러그', '', '<img style="display: block;vertical-align: top;margin: 0px auto;text-align: center;width: 860px;margin-bottom: 2rem;"
src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/A.png">
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/B.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/P.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/C.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >
<img src="//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/detail/DT/D.png" style="display: block; vertical-align: top; margin: 0px auto; margin-bottom:2rem; text-align: center;" >',
   54500, 0, 65400,
   '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png', '/partsds/images/BMW/S.png',
   1, 1, '12230053522',
   0, 0, 0, '%', 1, 0,
   'M00000BU',
   'B0000000',
   NOW(), NOW());


-- ──────────────────────────────────────────────────────────────
-- 2. pds_parts_master - 파츠 마스터 등록 (0개)
-- ──────────────────────────────────────────────────────────────


SET foreign_key_checks = 1;
