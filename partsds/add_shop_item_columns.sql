-- ============================================================
-- g5_shop_item 다중분류 컬럼 추가
-- PartsDS ca_id2(파츠종류) / ca_id3(부품브랜드) / it_info(요약정보)
-- HeidiSQL 쿼리탭에서 실행 (F9)
-- ============================================================

SET NAMES utf8mb4;

-- ca_id2 컬럼 추가 (없을 경우에만)
SET @exist_ca_id2 = (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'g5_shop_item'
    AND COLUMN_NAME  = 'ca_id2'
);
SET @sql_ca_id2 = IF(@exist_ca_id2 = 0,
  "ALTER TABLE `g5_shop_item` ADD COLUMN `ca_id2` varchar(20) NOT NULL DEFAULT '' COMMENT '2차분류(파츠종류)' AFTER `ca_id`",
  "SELECT '⚠ ca_id2 이미 존재' AS msg"
);
PREPARE stmt FROM @sql_ca_id2; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ca_id3 컬럼 추가
SET @exist_ca_id3 = (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'g5_shop_item'
    AND COLUMN_NAME  = 'ca_id3'
);
SET @sql_ca_id3 = IF(@exist_ca_id3 = 0,
  "ALTER TABLE `g5_shop_item` ADD COLUMN `ca_id3` varchar(20) NOT NULL DEFAULT '' COMMENT '3차분류(부품브랜드)' AFTER `ca_id2`",
  "SELECT '⚠ ca_id3 이미 존재' AS msg"
);
PREPARE stmt FROM @sql_ca_id3; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- it_info 컬럼 추가
SET @exist_it_info = (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'g5_shop_item'
    AND COLUMN_NAME  = 'it_info'
);
SET @sql_it_info = IF(@exist_it_info = 0,
  "ALTER TABLE `g5_shop_item` ADD COLUMN `it_info` text COMMENT '상품요약정보' AFTER `it_name`",
  "SELECT '⚠ it_info 이미 존재' AS msg"
);
PREPARE stmt FROM @sql_it_info; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 인덱스 추가 (중복 방지)
SET @exist_idx2 = (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'g5_shop_item'
    AND INDEX_NAME   = 'idx_ca_id2'
);
SET @sql_idx2 = IF(@exist_idx2 = 0,
  "ALTER TABLE `g5_shop_item` ADD INDEX `idx_ca_id2` (`ca_id2`)",
  "SELECT '⚠ idx_ca_id2 이미 존재' AS msg"
);
PREPARE stmt FROM @sql_idx2; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @exist_idx3 = (
  SELECT COUNT(*) FROM information_schema.STATISTICS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME   = 'g5_shop_item'
    AND INDEX_NAME   = 'idx_ca_id3'
);
SET @sql_idx3 = IF(@exist_idx3 = 0,
  "ALTER TABLE `g5_shop_item` ADD INDEX `idx_ca_id3` (`ca_id3`)",
  "SELECT '⚠ idx_ca_id3 이미 존재' AS msg"
);
PREPARE stmt FROM @sql_idx3; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 결과 확인
SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_COMMENT
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME   = 'g5_shop_item'
  AND COLUMN_NAME  IN ('ca_id', 'ca_id2', 'ca_id3', 'it_info')
ORDER BY ORDINAL_POSITION;

SELECT '✅ 완료: g5_shop_item 컬럼 추가 성공' AS result;
