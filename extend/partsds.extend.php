<?php
/**
 * 파츠디에스 - 그누보드 이벤트 훅 확장 파일
 * 경로: /extend/partsds.extend.php
 *
 * 그누보드 extend 폴더에 위치하여 자동 로드됨
 * 실제 존재하는 그누보드 이벤트만 사용합니다.
 */
if (!defined('_GNUBOARD_')) exit;

// ─────────────────────────────────────────────────────────────────────────────
// 1) CSS / JS 에셋 등록  (tail_sub 는 그누보드 실제 이벤트가 아님 → head 쪽 로드)
//    common_header 이벤트로 stylesheet 등록
// ─────────────────────────────────────────────────────────────────────────────
add_event('common_header', 'partsds_add_assets', 1);
function partsds_add_assets() {
    if (defined('G5_USE_SHOP') && G5_USE_SHOP) {
        add_stylesheet('<link rel="stylesheet" href="' . G5_URL . '/partsds/css/brand_selector.css?ver=' . G5_CSS_VER . '">', 5);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// 2) 회원가입 폼: 차종 필드 HTML 을 전역 변수에 준비
//    register_form_before 는 실제 존재하는 그누보드 이벤트
//    이 변수를 이윰빌더 register_form.skin.html.php 에서 출력
// ─────────────────────────────────────────────────────────────────────────────
add_event('register_form_before', 'partsds_prepare_car_field_global', 1);
function partsds_prepare_car_field_global() {
    global $partsds_car_field_html, $member;

    if (!defined('G5_TABLE_PREFIX')) return;

    // car_brand 테이블 존재 여부 체크 (DB 설치 전 오류 방지)
    $table_check = @sql_fetch("SELECT COUNT(*) AS cnt FROM information_schema.TABLES 
        WHERE TABLE_SCHEMA = DATABASE() 
          AND TABLE_NAME = '" . G5_TABLE_PREFIX . "car_brand'");
    if (empty($table_check['cnt'])) return;

    // 이윰빌더 테마가 활성화되어 있으면 eyoom 스타일 필드 사용
    if (defined('_EYOOM_')) {
        include_once(G5_PATH . '/partsds/register_car_field.php');
        $partsds_car_field_html = partsds_car_field_html_eyoom(is_array($member) ? $member : []);
    } else {
        include_once(G5_PATH . '/partsds/register_car_field.php');
        $partsds_car_field_html = partsds_car_field_html(is_array($member) ? $member : []);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// 3) 회원정보 저장 시 차종 저장
//    register_form_update_after 는 실제 존재하는 그누보드 이벤트
// ─────────────────────────────────────────────────────────────────────────────
add_event('register_form_update_after', 'partsds_save_car_on_register', 10);
function partsds_save_car_on_register($mb_id, $w = '') {
    $brand_id  = isset($_POST['pds_brand_id'])  ? (int)$_POST['pds_brand_id']  : 0;
    $series_id = isset($_POST['pds_series_id']) ? (int)$_POST['pds_series_id'] : 0;
    $model_id  = isset($_POST['pds_model_id'])  ? (int)$_POST['pds_model_id']  : 0;
    $brand_name  = isset($_POST['pds_brand_name'])  ? strip_tags(trim($_POST['pds_brand_name']))  : '';
    $series_name = isset($_POST['pds_series_name']) ? strip_tags(trim($_POST['pds_series_name'])) : '';
    $model_name  = isset($_POST['pds_model_name'])  ? strip_tags(trim($_POST['pds_model_name']))  : '';

    // 차종 선택이 없으면 처리 안 함
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
