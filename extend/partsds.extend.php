<?php
/**
 * 파츠디에스 - 그누보드 이벤트 훅 확장 파일
 * 경로: /extend/partsds.extend.php
 * 
 * 그누보드 extend 폴더에 위치하여 자동 로드됨
 * - 쇼핑몰 상품 목록에 차종 필터 기능 추가
 * - 메인 페이지에 브랜드 선택기 CSS/JS 추가
 * - 회원가입/수정 페이지에 차종 선택 필드 추가
 */
if (!defined('_GNUBOARD_')) exit;

// 파츠디에스 CSS 등록 (쇼핑몰 및 메인 공통)
add_event('tail_sub', 'partsds_add_assets', 1);
function partsds_add_assets() {
    if (defined('G5_USE_SHOP') && G5_USE_SHOP) {
        add_stylesheet('<link rel="stylesheet" href="' . G5_URL . '/partsds/css/brand_selector.css?ver=' . G5_CSS_VER . '">', 5);
    }
}

/**
 * 차종 필터가 있는 경우 shop/list.php SQL WHERE 조건 추가
 * shop.lib.php의 shop_item_list 후처리
 */
add_event('shop_item_list_query_before', 'partsds_filter_car_list', 10);
function partsds_filter_car_list(&$sql_where, &$sql_params) {
    $brand_id  = isset($_GET['pds_brand'])  ? (int)$_GET['pds_brand']  : 0;
    $series_id = isset($_GET['pds_series']) ? (int)$_GET['pds_series'] : 0;
    $model_id  = isset($_GET['pds_model'])  ? (int)$_GET['pds_model']  : 0;

    if (!$brand_id) return;

    include_once(G5_PATH . '/partsds/car_list_filter.php');
    $item_ids = pds_get_car_items($brand_id, $series_id, $model_id);

    if (empty($item_ids)) {
        // 해당 차종 상품 없음 -> 결과 없음
        $sql_where[] = "it.it_id = '__NO_RESULT__'";
    } else {
        $sql_where[] = "it.it_id IN (" . implode(',', $item_ids) . ")";
    }
}

/**
 * 회원가입 폼에 차종 선택 필드 추가
 */
add_event('register_form_html', 'partsds_register_car_field', 10);
function partsds_register_car_field() {
    global $member, $is_member;
    if (!defined('G5_USE_SHOP') || !G5_USE_SHOP) return;
    include_once(G5_PATH . '/partsds/register_car_field.php');
    echo partsds_car_field_html($member);
}

/**
 * 회원정보 저장 시 차종 저장
 */
add_event('register_form_update_after', 'partsds_save_car_on_register', 10);
function partsds_save_car_on_register($mb_id) {
    $brand_id  = isset($_POST['pds_brand_id'])  ? (int)$_POST['pds_brand_id']  : 0;
    $series_id = isset($_POST['pds_series_id']) ? (int)$_POST['pds_series_id'] : 0;
    $model_id  = isset($_POST['pds_model_id'])  ? (int)$_POST['pds_model_id']  : 0;
    $brand_name  = isset($_POST['pds_brand_name'])  ? strip_tags($_POST['pds_brand_name'])  : '';
    $series_name = isset($_POST['pds_series_name']) ? strip_tags($_POST['pds_series_name']) : '';
    $model_name  = isset($_POST['pds_model_name'])  ? strip_tags($_POST['pds_model_name'])  : '';

    if (!$brand_id) return;

    global $g5;
    $mb_id_safe = sql_escape_string($mb_id);
    sql_query("UPDATE `{$g5['g5_member_table']}` SET 
                mb_1 = '" . sql_escape_string($brand_name)  . "',
                mb_2 = '" . sql_escape_string($series_name) . "',
                mb_3 = '" . sql_escape_string($model_name)  . "',
                mb_4 = '{$brand_id}',
                mb_5 = '{$series_id}',
                mb_6 = '{$model_id}'
               WHERE mb_id = '{$mb_id_safe}'");
}
