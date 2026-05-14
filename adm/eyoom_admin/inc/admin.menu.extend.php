<?php
if (!defined('_EYOOM_IS_ADMIN_')) exit;

/**
 * 추가메뉴 디렉토리
 * PartsDS 관리 메뉴 (메뉴번호 600 활용)
 */
$cate_num = '600';
$_dirname[$cate_num] = 'partsds';

/**
 * 추가메뉴 폰트어썸 아이콘
 */
$dir_icon['partsds'] = 'fa-tools';

/**
 * menu600 : PartsDS 관리
 * - OEM 부품번호 기준 마스터 가격/이미지 동기화
 * - 타제조사 상품 복사 등록
 * - BMW-cafe24.xlsx 형식 엑셀 일괄등록
 * - 재고 알림 (카카오 알림톡)
 * - 차종 매핑 관리
 */
if ($member['mb_id'] == $config['cf_admin'] || in_array('partsds', (array)$mg_auth)) {
    $menu['menu600'] = array(
        array('600000', 'PartsDS 관리',    G5_URL . '/partsds/admin/parts_master.php',    'pds_master'),
        array('600100', '파츠 마스터 관리', G5_URL . '/partsds/admin/parts_master.php',    'pds_master'),
        array('600200', '상품 복사 등록',   G5_URL . '/partsds/admin/bulk_copy.php',       'pds_bulk'),
        array('600300', '엑셀 일괄등록',    G5_URL . '/partsds/admin/parts_excel.php',     'pds_excel'),
        array('600400', '재고 알림 관리',   G5_URL . '/partsds/admin/stock_alert.php',     'pds_stock'),
        array('600500', '차종 매핑 관리',   G5_URL . '/partsds/admin/item_car_manage.php', 'pds_carmap'),
        array('600600', '메인홈 위젯 설정', G5_URL . '/partsds/admin/pds_home_config.php',  'pds_home'),
        array('600700', '차량 DB 관리',    G5_URL . '/partsds/admin/pds_car_db.php',        'pds_car_db'),
    );
}
