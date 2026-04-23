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
 *
 * 수정 이력:
 *   v2.1 - g5['g5_member_table'] → g5['member_table'] 오타 수정 (500 오류 원인)
 *        - register_form_before 이벤트에서 오류 발생 시 조용히 실패하도록 개선
 *        - partsds_save_member_extra: mb_1~mb_10 필드가 이미 register_form_update.php에서
 *          저장되므로 차종 ID만 별도 업데이트 (중복 방지)
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
//    - register_form_before 이벤트 훅 (register_form.php 에서만 발생)
// ─────────────────────────────────────────────────────────────────────────────
add_event('register_form_before', 'partsds_prepare_car_field_global', 1);
function partsds_prepare_car_field_global() {
    global $partsds_car_field_html, $member;

    // G5_TABLE_PREFIX 미정의 환경 방어
    if (!defined('G5_TABLE_PREFIX')) return;

    // car_brand 테이블 존재 여부 체크 (파츠DS 미설치 환경 호환)
    // SHOW TABLES 사용 - information_schema 권한 불필요, 더 빠름
    $car_brand_table = G5_TABLE_PREFIX . 'car_brand';
    $table_res = @sql_query("SHOW TABLES LIKE '{$car_brand_table}'", false);
    if (!@sql_fetch_array($table_res)) return;

    $field_file = G5_PATH . '/partsds/register_car_field.php';
    if (!file_exists($field_file)) return;

    // 함수 중복 선언 방지
    if (!function_exists('partsds_car_field_html_eyoom')) {
        include_once($field_file);
    }
    if (!function_exists('partsds_car_field_html_eyoom')) return;

    $cur_member = (is_array($member) && !empty($member)) ? $member : [];
    $partsds_car_field_html = partsds_car_field_html_eyoom($cur_member);
}

// ─────────────────────────────────────────────────────────────────────────────
// 2-B. 회원가입 폼 제출 전 세션 재설정 (중복체크 세션 누락 방지)
//    - register_form_update_before 이벤트 훅
//
//    증상: "새로고침하면 회원가입 완료" → 폼 제출 시 ss_check_mb_id/nick/email
//          세션이 맞지 않아 alert() → HTTP 500
//    원인: 클라이언트 측 AJAX 중복체크 세션이 서버에 미도달 또는 세션 잠금
//    해결: POST로 넘어온 값으로 세션을 재확인·재설정 (신규 가입 시에만)
// ─────────────────────────────────────────────────────────────────────────────
add_event('register_form_update_before', 'partsds_fix_session_check', 1);
function partsds_fix_session_check($mb_id, $w) {
    // 신규 가입($w == '')일 때만 처리
    if ($w !== '') return;
    if (empty($mb_id)) return;

    $mb_nick  = isset($_POST['mb_nick'])  ? trim($_POST['mb_nick'])  : '';
    $mb_email = isset($_POST['mb_email']) ? trim($_POST['mb_email']) : '';

    // 세션 값이 POST 값과 다를 때만 재설정
    // (정상적으로 중복체크한 경우엔 이미 맞아 있으므로 변경 없음)
    if (get_session('ss_check_mb_id') !== $mb_id) {
        set_session('ss_check_mb_id', $mb_id);
    }
    if ($mb_nick && get_session('ss_check_mb_nick') !== $mb_nick) {
        set_session('ss_check_mb_nick', $mb_nick);
    }
    if ($mb_email && get_session('ss_check_mb_email') !== $mb_email) {
        set_session('ss_check_mb_email', $mb_email);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// 3. 회원정보 저장 (회원가입 + 정보수정 공통)
//    - register_form_update_after 이벤트 훅
//
//    중요: register_form_update.php 에서 mb_1~mb_10 을 이미 POST 값으로 저장함.
//    여기서는 pds_brand_id / pds_series_id / pds_model_id (숨김 필드) 값을
//    mb_4 / mb_5 / mb_6 으로 한 번 더 저장해 ID가 정확히 들어가도록 보장함.
//    (mb_1~3, mb_7~10 은 이미 register_form_update.php 에서 처리되었으므로 생략)
// ─────────────────────────────────────────────────────────────────────────────
add_event('register_form_update_after', 'partsds_save_member_extra', 10);
function partsds_save_member_extra($mb_id, $w = '') {
    global $g5;

    // mb_id 안전성 확인
    if (empty($mb_id)) return;

    // ── 차종 ID 정보 (POST 숨김 필드 pds_brand_id / pds_series_id / pds_model_id) ──
    $brand_id    = isset($_POST['pds_brand_id'])    ? (int)$_POST['pds_brand_id']    : 0;
    $series_id   = isset($_POST['pds_series_id'])   ? (int)$_POST['pds_series_id']   : 0;
    $model_id    = isset($_POST['pds_model_id'])    ? (int)$_POST['pds_model_id']    : 0;
    $brand_name  = isset($_POST['pds_brand_name'])  ? strip_tags(trim($_POST['pds_brand_name']))  : '';
    $series_name = isset($_POST['pds_series_name']) ? strip_tags(trim($_POST['pds_series_name'])) : '';
    $model_name  = isset($_POST['pds_model_name'])  ? strip_tags(trim($_POST['pds_model_name']))  : '';

    // ── 회원 유형 / 사업자 정보 ──
    $mb_type  = isset($_POST['mb_7'])  ? preg_replace('/[^a-z]/', '', strtolower(trim($_POST['mb_7'])))  : 'normal';
    $biz_no   = isset($_POST['mb_8'])  ? preg_replace('/[^0-9\-]/', '', $_POST['mb_8'])                  : '';
    $biz_name = isset($_POST['mb_9'])  ? strip_tags(trim($_POST['mb_9']))                                 : '';
    $biz_ceo  = isset($_POST['mb_10']) ? strip_tags(trim($_POST['mb_10']))                                : '';

    // 유형이 일반이면 사업자 정보 비움
    if ($mb_type !== 'business') {
        $mb_type  = 'normal';
        $biz_no   = '';
        $biz_name = '';
        $biz_ceo  = '';
    }

    // ── 테이블명 확인 ──
    // 그누보드5 표준: $g5['member_table'] (g5_member_table 키는 존재하지 않음)
    if (empty($g5['member_table'])) return;

    // ── UPDATE: 차종 ID(mb_4~6) + 차종명(mb_1~3) + 회원유형(mb_7~10) 저장 ──
    $mb_id_safe = sql_escape_string($mb_id);
    $result = sql_query("UPDATE `{$g5['member_table']}` SET
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
        WHERE mb_id = '{$mb_id_safe}'", false);
    // false = 쿼리 실패 시 die() 하지 않음 (회원가입 프로세스 중단 방지)
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
    if (!$is_member)            return;
    if (empty($member['mb_4'])) return;

    // 이미 필터 파라미터가 있거나, 필터 해제(pds_no_filter=1)면 스킵
    if (isset($_GET['pds_brand']))     return;
    if (isset($_GET['pds_no_filter'])) return;

    // 차종 파라미터 서버사이드 주입
    $_GET['pds_brand'] = (int)$member['mb_4'];
    if (!empty($member['mb_5'])) $_GET['pds_series'] = (int)$member['mb_5'];
    if (!empty($member['mb_6'])) $_GET['pds_model']  = (int)$member['mb_6'];
}
