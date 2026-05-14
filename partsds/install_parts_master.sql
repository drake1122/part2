-- =====================================================
-- 파츠디에스 - 파츠 마스터 테이블
-- 역할: 부품번호(자체코드) 기준 마스터 가격/이미지 관리
--       → 마스터 변경 시 연결된 모든 상품 자동 동기화
-- 실행: phpMyAdmin > SQL 탭에 붙여넣기
-- =====================================================

SET NAMES utf8mb4;

-- ── 파츠 마스터 테이블 ──────────────────────────────────────
-- pm_part_code: 부품번호 (자체상품코드, 예: 11428593186)
-- pm_parts_ca : 파츠 종류 ca_id (5001~5041)
-- pm_name     : 파츠명 (오일필터, 에어필터 등)
-- pm_price    : 마스터 판매가 → 연결 상품에 동기화
-- pm_supply   : 마스터 공급가
-- pm_img_url  : 마스터 이미지 URL (외부링크 또는 로컬 경로)
-- pm_detail_html: 공통 상세 HTML
-- pm_sync_yn  : 동기화 여부 (Y=연결상품 자동반영)
CREATE TABLE IF NOT EXISTS `pds_parts_master` (
  `pm_id`          int(11) NOT NULL AUTO_INCREMENT,
  `pm_part_code`   varchar(50)  NOT NULL DEFAULT '' COMMENT '부품번호(자체상품코드)',
  `pm_parts_ca`    varchar(10)  NOT NULL DEFAULT '' COMMENT '파츠 종류 ca_id (5001~5041)',
  `pm_name`        varchar(200) NOT NULL DEFAULT '' COMMENT '파츠명',
  `pm_brand`       varchar(50)  NOT NULL DEFAULT '' COMMENT '제조사/브랜드 (벤츠=M00000BN 등)',
  `pm_price`       int(11)      NOT NULL DEFAULT 0  COMMENT '판매가 (원)',
  `pm_supply`      int(11)      NOT NULL DEFAULT 0  COMMENT '공급가 (원)',
  `pm_img_url`     varchar(500) NOT NULL DEFAULT '' COMMENT '목록/대표 이미지 URL',
  `pm_img_add`     varchar(500) NOT NULL DEFAULT '' COMMENT '추가(상세상단) 이미지 URL',
  `pm_detail_html` mediumtext              COMMENT '공통 상세 HTML',
  `pm_sync_yn`     char(1)      NOT NULL DEFAULT 'Y' COMMENT '동기화 여부 Y/N',
  `pm_qty_unit`    varchar(20)  NOT NULL DEFAULT '1EA' COMMENT '포장단위',
  `pm_memo`        varchar(500) NOT NULL DEFAULT '' COMMENT '관리 메모',
  `pm_reg_dt`      datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pm_upd_dt`      datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pm_id`),
  UNIQUE KEY `uq_part_brand` (`pm_part_code`, `pm_brand`),
  KEY `idx_parts_ca`   (`pm_parts_ca`),
  KEY `idx_part_code`  (`pm_part_code`),
  KEY `idx_sync`       (`pm_sync_yn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='파츠 마스터 - 가격/이미지 동기화 기준';

-- ── 동기화 로그 테이블 ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `pds_parts_sync_log` (
  `log_id`       int(11) NOT NULL AUTO_INCREMENT,
  `pm_id`        int(11) NOT NULL DEFAULT 0,
  `pm_part_code` varchar(50)  NOT NULL DEFAULT '',
  `changed_col`  varchar(100) NOT NULL DEFAULT '' COMMENT '변경된 컬럼명',
  `old_val`      varchar(1000) NOT NULL DEFAULT '' COMMENT '이전 값',
  `new_val`      varchar(1000) NOT NULL DEFAULT '' COMMENT '새 값',
  `sync_count`   int(11) NOT NULL DEFAULT 0 COMMENT '동기화된 상품 수',
  `sync_dt`      datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_id`     varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `idx_pm_id` (`pm_id`),
  KEY `idx_sync_dt` (`sync_dt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='파츠 마스터 동기화 로그';

-- ── 재고 알림 설정 테이블 ──────────────────────────────────
CREATE TABLE IF NOT EXISTS `pds_stock_alert` (
  `sa_id`         int(11) NOT NULL AUTO_INCREMENT,
  `sa_it_id`      varchar(20)  NOT NULL DEFAULT '' COMMENT '상품코드',
  `sa_it_name`    varchar(200) NOT NULL DEFAULT '' COMMENT '상품명',
  `sa_qty`        int(11) NOT NULL DEFAULT 0  COMMENT '현재 재고',
  `sa_threshold`  int(11) NOT NULL DEFAULT 5  COMMENT '알림 기준 재고 (기본 5개)',
  `sa_sent_yn`    char(1) NOT NULL DEFAULT 'N' COMMENT '알림 발송 여부',
  `sa_sent_dt`    datetime     DEFAULT NULL   COMMENT '발송 일시',
  `sa_reg_dt`     datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sa_id`),
  UNIQUE KEY `uq_it_id` (`sa_it_id`),
  KEY `idx_qty` (`sa_qty`),
  KEY `idx_sent` (`sa_sent_yn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='재고 부족 알림 설정';

-- ── 배송사 마스터 테이블 ────────────────────────────────────
CREATE TABLE IF NOT EXISTS `pds_delivery_master` (
  `dm_id`        int(11) NOT NULL AUTO_INCREMENT,
  `dm_company`   varchar(100) NOT NULL DEFAULT '' COMMENT '택배사명',
  `dm_code`      varchar(20)  NOT NULL DEFAULT '' COMMENT '택배사 코드',
  `dm_fee`       int(11) NOT NULL DEFAULT 3000 COMMENT '기본 배송비',
  `dm_free_over` int(11) NOT NULL DEFAULT 0   COMMENT '무료배송 기준금액 (0=항상유료)',
  `dm_use`       char(1) NOT NULL DEFAULT 'Y',
  `dm_order`     int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`dm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='택배사 마스터';

-- 기본 택배사 데이터
INSERT IGNORE INTO `pds_delivery_master`
  (`dm_company`, `dm_code`, `dm_fee`, `dm_free_over`, `dm_use`, `dm_order`)
VALUES
  ('CJ대한통운', 'CJ', 3000, 50000, 'Y', 1),
  ('한진택배',   'HANJIN', 3000, 50000, 'Y', 2),
  ('로젠택배',   'LOGEN', 3000, 50000, 'Y', 3),
  ('롯데택배',   'LOTTE', 3000, 50000, 'Y', 4),
  ('우체국택배', 'EPOST', 3000, 50000, 'Y', 5);

-- ── 확인 쿼리 ──────────────────────────────────────────────
-- SHOW TABLES LIKE 'pds_%';
-- SELECT * FROM pds_delivery_master;
