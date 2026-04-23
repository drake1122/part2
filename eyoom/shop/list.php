<?php
/**
 * file : /eyoom/shop/list.php
 * 이윰빌더 테마 상품 목록 페이지
 * G5_THEME_SHOP_PATH/list.php 로 자동 호출됨
 */
if (!defined('_EYOOM_')) exit;

// 정렬 필드 화이트리스트
if (isset($sort) && !in_array($sort, array('it_name', 'it_sum_qty', 'it_price', 'it_use_avg', 'it_use_cnt', 'it_update_time'))) {
    $sort = '';
}

/**
 * 분류 체크
 */
$sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' and ca_use = '1' ";
$ca  = sql_fetch($sql);
if (!$ca['ca_id'])
    alert('등록된 분류가 없습니다.');

/**
 * 본인인증, 성인인증 체크
 */
if (!$is_admin && $config['cf_cert_use']) {
    $msg = shop_member_cert_check($ca_id, 'list');
    if ($msg)
        alert($msg, G5_SHOP_URL);
}

/**
 * 페이지 타이틀
 */
$g5['title'] = $ca['ca_name'] . ' 상품리스트';

/**
 * ── 이윰빌더 헤더 출력 ──────────────────────────────
 * shop.head.php 가 이윰 레이아웃(CSS·JS·header·nav) 전부 출력
 */
include_once(EYOOM_SHOP_PATH . '/shop.head.php');

/**
 * 스킨 경로: eyoom/core/shop/ 우선, 없으면 skin/shop/basic/
 */
$skin_dir = EYOOM_CORE_PATH . '/' . G5_SHOP_DIR;

// 네비게이션 스킨
$nav_skin = $skin_dir . '/navigation.skin.php';
if (!is_file($nav_skin)) $nav_skin = G5_SHOP_SKIN_PATH . '/navigation.skin.php';

// 카테고리 스킨
$cate_skin = $skin_dir . '/listcategory.skin.php';
if (!is_file($cate_skin)) $cate_skin = G5_SHOP_SKIN_PATH . '/listcategory.skin.php';

// 정렬
if ($sort != '')
    $order_by = $sort . ' ' . $sortodr . ' , it_order, it_id desc';
else
    $order_by = 'it_order, it_id desc';

/**
 * 리스트 스킨 파일 결정
 * ca_skin 이 비어있으면 → list.10.skin.php 자동 폴백
 */
$skin_file = is_include_path_check($skin_dir . '/' . $ca['ca_skin'])
    ? $skin_dir . '/' . $ca['ca_skin']
    : $skin_dir . '/list.10.skin.php';

// 정렬 스킨
$sort_skin = $skin_dir . '/list.sort.skin.php';
if (!is_file($sort_skin)) $sort_skin = G5_SHOP_SKIN_PATH . '/list.sort.skin.php';

// 뷰타입(갤러리/리스트) 전환 스킨
$sub_skin = $skin_dir . '/list.sub.skin.php';
if (!is_file($sub_skin)) $sub_skin = G5_SHOP_SKIN_PATH . '/list.sub.skin.php';

/**
 * 상품 리스트 데이터
 */
if (file_exists($skin_file)) {
    $items       = max(1, (int)$ca['ca_list_mod']) * max(1, (int)$ca['ca_list_row']);
    if ($page < 1) $page = 1;
    $from_record = ($page - 1) * $items;

    $list = new item_list($skin_file, $ca['ca_list_mod'], $ca['ca_list_row'], $ca['ca_img_width'], $ca['ca_img_height']);
    $list->set_category($ca['ca_id'], 1);
    $list->set_category($ca['ca_id'], 2);
    $list->set_category($ca['ca_id'], 3);
    $list->set_is_page(true);
    $list->set_order_by($order_by);
    $list->set_from_record($from_record);
    $list->set_view('it_img',        true);
    $list->set_view('it_id',         false);
    $list->set_view('it_name',       true);
    $list->set_view('it_basic',      true);
    $list->set_view('it_cust_price', true);
    $list->set_view('it_price',      true);
    $list->set_view('it_icon',       true);
    $list->set_view('sns',           true);

    // ── 파츠디에스 차종 필터 적용 ──────────────────────
    $pds_brand_id  = isset($_GET['pds_brand'])  ? (int)$_GET['pds_brand']  : 0;
    $pds_series_id = isset($_GET['pds_series']) ? (int)$_GET['pds_series'] : 0;
    $pds_model_id  = isset($_GET['pds_model'])  ? (int)$_GET['pds_model']  : 0;

    if ($pds_brand_id) {
        $pds_filter_file = G5_PATH . '/partsds/car_list_filter.php';
        if (file_exists($pds_filter_file)) {
            include_once($pds_filter_file);
            $pds_item_ids = pds_get_car_items($pds_brand_id, $pds_series_id, $pds_model_id);

            if (empty($pds_item_ids)) {
                $list->set_query("SELECT * FROM `{$g5['g5_shop_item_table']}` WHERE it_id = '__NO_RESULT__'");
            } else {
                $pds_id_list = implode("','", array_map('sql_escape_string', $pds_item_ids));
                $pds_ca_cond = '';
                if ($ca['ca_id']) {
                    $safe_ca = sql_escape_string($ca['ca_id']);
                    $pds_ca_cond = " AND (ca_id LIKE '{$safe_ca}%' OR ca_id2 LIKE '{$safe_ca}%' OR ca_id3 LIKE '{$safe_ca}%')";
                }
                $list->set_query(
                    "SELECT * FROM `{$g5['g5_shop_item_table']}`"
                    . " WHERE it_use = '1'"
                    . $pds_ca_cond
                    . " AND it_id IN ('{$pds_id_list}')"
                    . " ORDER BY " . $order_by
                );
            }
        }
    }
    // ────────────────────────────────────────────────────

    $item_list   = $list->run();
    $total_count = $list->total_count;
    $total_page  = ceil($total_count / $items);
} else {
    $item_list   = '';
    $total_count = 0;
    $total_page  = 0;
}

/**
 * 페이징 문자열
 */
$qstr1 = 'ca_id=' . $ca_id . '&amp;sort=' . $sort . '&amp;sortodr=' . $sortodr;
if (!empty($pds_brand_id)) {
    $qstr1 .= '&amp;pds_brand=' . $pds_brand_id;
    if (!empty($pds_series_id)) $qstr1 .= '&amp;pds_series=' . $pds_series_id;
    if (!empty($pds_model_id))  $qstr1 .= '&amp;pds_model='  . $pds_model_id;
}
$paging = $eb->set_paging('itemlist', $ca_id, $qstr1);

/**
 * ── 이윰빌더 테마 HTML 출력 ─────────────────────────
 * theme/eb4_basic/skin/shop/basic/list.skin.html.php
 * (nav_skin, cate_skin, sort_skin, sub_skin, item_list, paging 변수 사용)
 */
include_once(EYOOM_THEME_SHOP_SKIN_PATH . '/list.skin.html.php');

/**
 * ── 이윰빌더 푸터 출력 ──────────────────────────────
 */
include_once(EYOOM_SHOP_PATH . '/shop.tail.php');

echo "\n<!-- {$ca['ca_skin']} -->\n";
