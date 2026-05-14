-- ============================================================
-- 파츠디에스 - 통합 설정 테이블 (pds_config)
-- 실행: phpMyAdmin 또는 wrangler d1 execute
-- ============================================================

CREATE TABLE IF NOT EXISTS `g5_pds_config` (
  `cfg_key`   VARCHAR(100) NOT NULL COMMENT '설정 키',
  `cfg_val`   TEXT         NOT NULL DEFAULT '' COMMENT '설정 값',
  `cfg_memo`  VARCHAR(255) NOT NULL DEFAULT '' COMMENT '설명',
  `cfg_dt`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cfg_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='파츠디에스 통합 설정';

-- ── 기본 설정값 삽입 ──────────────────────────────────────────
INSERT IGNORE INTO `g5_pds_config` (`cfg_key`, `cfg_val`, `cfg_memo`) VALUES

-- 메인 홈 위젯 노출 여부
('home_widget_use',        'y',  '메인 홈에 차종선택+파츠그리드 위젯 표시 여부 (y/n)'),

-- 차종 선택 섹션
('home_car_selector_use',  'y',  '차종 선택 UI 표시 여부 (y/n)'),
('home_car_selector_title','차량을 선택하면 해당 차종에 맞는 부품을 검색할 수 있습니다.', '차종 선택 안내 문구'),
('home_oem_search_use',    'y',  'OEM번호 직접 검색창 표시 여부 (y/n)'),
('home_oem_placeholder',   '예) 부품번호 (OEM No.)', 'OEM 검색 입력 필드 placeholder'),

-- 파츠 카테고리 그리드 섹션
('home_parts_grid_use',    'y',  '파츠 카테고리 그리드 표시 여부 (y/n)'),
('home_parts_grid_title',  'PARTS', '파츠 그리드 섹션 제목'),
('home_parts_grid_cols',   '6',  '파츠 그리드 한 줄 열 수 (4/5/6)'),
('home_parts_notice',      '그 밖의 부품은 문의 주세요 | 이미지는 실제 제품과 다를 수 있으니 제품명을 기준으로 구매 부탁드립니다', '파츠 그리드 하단 안내문'),

-- 위젯 위치/순서 (shop 메인에서 상품목록 위/아래)
('home_widget_position',   'top', '위젯 위치: top=상품목록 위, bottom=아래'),

-- 브랜드 로고 배너 섹션
('home_brand_bar_use',     'y',  '차종 브랜드 로고 바 표시 여부 (y/n)'),
('home_brand_bar_title',   '수입자동차 전문 부품', '브랜드 바 상단 제목'),

-- 검색 결과 URL 방식
('search_url_mode',        'ca_id', '차종 검색 URL 방식: ca_id=분류기반, filter=파라미터기반'),

-- 기타
('home_widget_css_extra',  '', '추가 인라인 CSS (관리자 직접 입력)');
