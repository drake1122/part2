<?php
/**
 * 그누보드 관리자 - PartsDS 관리 메뉴 (menu600)
 * 파일명 규칙: admin.menu600.*.php → $amenu[600] 으로 자동 등록됨
 */

$menu['menu600'] = array(
    array('600000', 'PartsDS 관리',    G5_URL . '/partsds/admin/parts_master.php',    ''),
    array('600100', '파츠 마스터 관리', G5_URL . '/partsds/admin/parts_master.php',    ''),
    array('600200', '상품 복사 등록',   G5_URL . '/partsds/admin/bulk_copy.php',       ''),
    array('600300', '엑셀 일괄등록',    G5_URL . '/partsds/admin/parts_excel.php',     ''),
    array('600400', '재고 알림 관리',   G5_URL . '/partsds/admin/stock_alert.php',     ''),
    array('600500', '차종 매핑 관리',   G5_URL . '/partsds/admin/item_car_manage.php', ''),
    array('600600', '메인홈 위젯 설정', G5_URL . '/partsds/admin/pds_home_config.php', ''),
    array('600700', '차량 DB 관리',    G5_URL . '/partsds/admin/pds_car_db.php',        ''),
);
