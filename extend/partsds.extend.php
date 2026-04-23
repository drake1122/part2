<?php
/**
 * 파츠디에스 - 그누보드 이벤트 훅 확장 파일
 * 경로: /extend/partsds.extend.php
 *
 * 기능:
 * 1. CSS/JS 에셋 등록
 * 2. 회원가입/수정 폼: 차종 + 회원유형(일반/사업자) 필드
 * 3. 회원정보 저장 시 차종 + 사업자 정보 저장
 * 4. 쇼핑몰 상품목록 - 로그인 회원 차종 자동 필터 (서버사이드 GET 주입)
 *
 * 회원 필드 매핑:
 *   mb_1 = 브랜드명    mb_4 = brand_id
 *   mb_2 = 시리즈명    mb_5 = series_id
 *   mb_3 = 모델명      mb_6 = model_id
 *   mb_7 = 회원유형 ('normal' | 'business')
 *   mb_8 = 사업자등록번호
 *   mb_9 = 업체명
 *   mb_10 = 담당자명
 */
if (!defined('_GNUBOARD_')) exit;

// ─────────────────────────────────────────────────────────────────────────────
// 1. CSS / JS 에셋 등록
// ─────────────────────────────────────────────────────────────────────────────
add_event('common_header', 'partsds_add_assets', 1);
function partsds_add_assets() {
    if (defined('G5_USE_SHOP') && G5_USE_SHOP) {
        add_stylesheet('<link rel="stylesheet" href="' . G5_URL . '/partsds/css/brand_selector.css?ver=' . G5_CSS_VER . '">', 5);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// 2. 회원가입/수정 폼: 차종 + 회원유형 필드 HTML 준비
//    - register_form_before 이벤트 훅
// ─────────────────────────────────────────────────────────────────────────────
add_event('register_form_before', 'partsds_prepare_car_field_global', 1);
function partsds_prepare_car_field_global() {
    global $partsds_car_field_html, $member;

    if (!defined('G5_TABLE_PREFIX')) return;

    // car_brand 테이블 존재 여부 체크 (파츠DS 미설치 환경 호환)
    $table_check = @sql_fetch("SELECT COUNT(*) AS cnt FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = '" . G5_TABLE_PREFIX . "car_brand'");
    if (empty($table_check['cnt'])) return;

    $field_file = G5_PATH . '/partsds/register_car_field.php';
    if (!file_exists($field_file)) return;

    include_once($field_file);
    $cur_member = (is_array($member) && !empty($member)) ? $member : [];
    $partsds_car_field_html = partsds_car_field_html_eyoom($cur_member);
}

// ─────────────────────────────────────────────────────────────────────────────
// 3. 회원정보 저장 (회원가입 + 정보수정 공통)
//    - register_form_update_after 이벤트 훅
// ─────────────────────────────────────────────────────────────────────────────
add_event('register_form_update_after', 'partsds_save_member_extra', 10);
function partsds_save_member_extra($mb_id, $w = '') {
    global $g5;

    // ── 차종 정보 ──
    $brand_id    = isset($_POST['pds_brand_id'])    ? (int)$_POST['pds_brand_id']                        : 0;
    $series_id   = isset($_POST['pds_series_id'])   ? (int)$_POST['pds_series_id']                       : 0;
    $model_id    = isset($_POST['pds_model_id'])    ? (int)$_POST['pds_model_id']                        : 0;
    $brand_name  = isset($_POST['pds_brand_name'])  ? strip_tags(trim($_POST['pds_brand_name']))          : '';
    $series_name = isset($_POST['pds_series_name']) ? strip_tags(trim($_POST['pds_series_name']))         : '';
    $model_name  = isset($_POST['pds_model_name'])  ? strip_tags(trim($_POST['pds_model_name']))          : '';

    // ── 회원 유형 / 사업자 정보 ──
    $mb_type  = isset($_POST['mb_7'])  ? preg_replace('/[^a-z]/', '', strtolower($_POST['mb_7']))  : 'normal';
    $biz_no   = isset($_POST['mb_8'])  ? preg_replace('/[^0-9\-]/', '', $_POST['mb_8'])            : '';
    $biz_name = isset($_POST['mb_9'])  ? strip_tags(trim($_POST['mb_9']))                           : '';
    $biz_ceo  = isset($_POST['mb_10']) ? strip_tags(trim($_POST['mb_10']))                          : '';

    // 유형이 일반이면 사업자 정보 비움
    if ($mb_type !== 'business') {
        $mb_type  = 'normal';
        $biz_no   = '';
        $biz_name = '';
        $biz_ceo  = '';
    }

    // ── UPDATE ──
    $mb_id_safe = sql_escape_string($mb_id);
    sql_query("UPDATE `{$g5['g5_member_table']}` SET
        mb_1  = '" . sql_escape_string($brand_name)  . "',
        mb_2  = '" . sql_escape_string($series_name) . "',
        mb_3  = '" . sql_escape_string($model_name)  . "',
        mb_4  = '{$brand_id}',
        mb_5  = '{$series_id}',
        mb_6  = '{$model_id}',
        mb_7  = '" . sql_escape_string($mb_type)  . "',
        mb_8  = '" . sql_escape_string($biz_no)   . "',
        mb_9  = '" . sql_escape_string($biz_name) . "',
        mb_10 = '" . sql_escape_string($biz_ceo)  . "'
        WHERE mb_id = '{$mb_id_safe}'");
}

// ─────────────────────────────────────────────────────────────────────────────
// 4. 쇼핑몰 상품목록 - 로그인 회원의 차종을 GET 파라미터에 자동 주입
//    - common_header 이벤트 훅 (우선순위 99 = 늦게 실행)
//    - shop/list.php에서도 직접 처리하므로 이중 적용 방지 포함
// ─────────────────────────────────────────────────────────────────────────────
add_event('common_header', 'partsds_auto_car_filter', 99);
function partsds_auto_car_filter() {
    global $member, $is_member;

    // 상품목록 페이지가 아니면 스킵
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $is_shop_list = (
        strpos($uri, '/shop/list.php') !== false ||
        strpos($uri, '/shop/list')     !== false
    );
    if (!$is_shop_list) return;

    // 비로그인 or 차종 미등록이면 스킵
    if (!$is_member)              return;
    if (empty($member['mb_4']))   return;

    // 이미 필터 파라미터가 있거나, 필터 해제(pds_no_filter=1)면 스킵
    if (isset($_GET['pds_brand']))     return;
    if (isset($_GET['pds_no_filter'])) return;

    // 차종 파라미터 서버사이드 주입
    $_GET['pds_brand'] = (int)$member['mb_4'];
    if (!empty($member['mb_5'])) $_GET['pds_series'] = (int)$member['mb_5'];
    if (!empty($member['mb_6'])) $_GET['pds_model']  = (int)$member['mb_6'];
}
