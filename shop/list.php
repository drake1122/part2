<?php
/**
 * 파츠디에스 커스텀 shop/list.php
 * 차종 필터(pds_brand, pds_series, pds_model) 파라미터 처리 추가
 */
include_once('./_common.php');

$ca_id = isset($_REQUEST['ca_id']) ? safe_replace_regex($_REQUEST['ca_id'], 'ca_id') : '';
$skin = isset($_REQUEST['skin']) ? safe_replace_regex($_REQUEST['skin'], 'skin') : '';

// 파츠디에스 차종 필터 파라미터
$pds_brand  = isset($_GET['pds_brand'])  ? (int)$_GET['pds_brand']  : 0;
$pds_series = isset($_GET['pds_series']) ? (int)$_GET['pds_series'] : 0;
$pds_model  = isset($_GET['pds_model'])  ? (int)$_GET['pds_model']  : 0;

// 차종 필터 있고 ca_id 없으면 전체 상품에서 필터링
// ca_id 없을 때 대표 카테고리 사용 (첫 번째 활성 카테고리)
if ($pds_brand && !$ca_id) {
    $first_ca = sql_fetch("SELECT ca_id FROM {$g5['g5_shop_category_table']} WHERE ca_use = '1' ORDER BY ca_order, ca_id LIMIT 1");
    // ca_id 없어도 계속 진행 (전체 상품 필터링)
}

// 상품 리스트에서 다른 필드로 정렬을 하려면 아래의 배열 코드에서 해당 필드를 추가하세요.
if( isset($sort) && ! in_array($sort, array('it_name', 'it_sum_qty', 'it_price', 'it_use_avg', 'it_use_cnt', 'it_update_time')) ){
    $sort='';
}

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/list.php');
    return;
}

// 테마에 list.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_list_file = G5_THEME_SHOP_PATH.'/list.php';
    if(is_file($theme_list_file)) {
        include_once($theme_list_file);
        return;
    }
    unset($theme_list_file);
}

// 카테고리 정보
if ($ca_id) {
    $sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' and ca_use = '1'  ";
    $ca = sql_fetch($sql);
    if (! (isset($ca['ca_id']) && $ca['ca_id'])) {
        if (!$pds_brand) {
            alert('등록된 분류가 없습니다.');
        }
        // 차종 필터만 있는 경우 가상 카테고리 설정
        $ca = [
            'ca_id'         => '',
            'ca_name'       => '차종별 부품',
            'ca_skin'       => 'list.10.skin.php',
            'ca_skin_dir'   => '',
            'ca_img_width'  => 200,
            'ca_img_height' => 200,
            'ca_list_mod'   => 4,
            'ca_list_row'   => 5,
            'ca_head_html'  => '',
            'ca_tail_html'  => '',
            'ca_include_head' => '',
            'ca_include_tail' => '',
            'ca_cert_use'   => 0,
            'ca_adult_use'  => 0,
        ];
    }
} elseif ($pds_brand) {
    // 차종 필터만 있는 경우 가상 카테고리 설정
    $ca = [
        'ca_id'         => '',
        'ca_name'       => '차종별 부품',
        'ca_skin'       => 'list.10.skin.php',
        'ca_skin_dir'   => '',
        'ca_img_width'  => 200,
        'ca_img_height' => 200,
        'ca_list_mod'   => 4,
        'ca_list_row'   => 5,
        'ca_head_html'  => '',
        'ca_tail_html'  => '',
        'ca_include_head' => '',
        'ca_include_tail' => '',
        'ca_cert_use'   => 0,
        'ca_adult_use'  => 0,
    ];
} else {
    alert('분류를 선택해주세요.');
}

// 테마미리보기 스킨 등의 변수 재설정
if(defined('_THEME_PREVIEW_') && _THEME_PREVIEW_ === true) {
    $ca['ca_skin']       = (isset($tconfig['ca_skin']) && $tconfig['ca_skin']) ? $tconfig['ca_skin'] : $ca['ca_skin'];
    $ca['ca_img_width']  = (isset($tconfig['ca_img_width']) && $tconfig['ca_img_width']) ? $tconfig['ca_img_width'] : $ca['ca_img_width'];
    $ca['ca_img_height'] = (isset($tconfig['ca_img_height']) && $tconfig['ca_img_height']) ? $tconfig['ca_img_height'] : $ca['ca_img_height'];
    $ca['ca_list_mod']   = (isset($tconfig['ca_list_mod']) && $tconfig['ca_list_mod']) ? $tconfig['ca_list_mod'] : $ca['ca_list_mod'];
    $ca['ca_list_row']   = (isset($tconfig['ca_list_row']) && $tconfig['ca_list_row']) ? $tconfig['ca_list_row'] : $ca['ca_list_row'];
}

// 본인인증, 성인인증체크
if(!$is_admin && $config['cf_cert_use'] && $ca_id) {
    $msg = shop_member_cert_check($ca_id, 'list');
    if($msg)
        alert($msg, G5_SHOP_URL);
}

// 차종 필터로 접근시 타이틀 변경
if ($pds_brand) {
    include_once(G5_PATH . '/partsds/car_list_filter.php');
    $filter_info = pds_get_car_filter_info($pds_brand, $pds_series, $pds_model);
    $filter_label = $filter_info['brand'];
    if ($filter_info['series']) $filter_label .= ' ' . $filter_info['series'];
    if ($filter_info['model'])  $filter_label .= ' ' . $filter_info['model'];
    $g5['title'] = $filter_label . ' 호환 부품';
} else {
    $g5['title'] = $ca['ca_name'].' 상품리스트';
}

if ($ca['ca_include_head'] && is_include_path_check($ca['ca_include_head']))
    @include_once($ca['ca_include_head']);
else
    include_once(G5_SHOP_PATH.'/_head.php');

// 스킨경로
$skin_dir = G5_SHOP_SKIN_PATH;

if($ca['ca_skin_dir']) {
    if(preg_match('#^theme/(.+)$#', $ca['ca_skin_dir'], $match))
        $skin_dir = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/shop/'.$match[1];
    else
        $skin_dir = G5_PATH.'/'.G5_SKIN_DIR.'/shop/'.$ca['ca_skin_dir'];

    if(is_dir($skin_dir)) {
        $skin_file = $skin_dir.'/'.$ca['ca_skin'];

        if(!is_file($skin_file))
            $skin_dir = G5_SHOP_SKIN_PATH;
    } else {
        $skin_dir = G5_SHOP_SKIN_PATH;
    }
}

define('G5_SHOP_CSS_URL', str_replace(G5_PATH, G5_URL, $skin_dir));

if ($is_admin && $ca_id)
    echo '<div class="sct_admin"><a href="'.G5_ADMIN_URL.'/shop_admin/categoryform.php?w=u&amp;ca_id='.$ca_id.'" class="btn_admin btn"><span class="sound_only">분류 관리</span><i class="fa fa-cog fa-spin fa-fw"></i></a></div>';
?>

<script>
var itemlist_ca_id = "<?php echo $ca_id; ?>";
</script>
<script src="<?php echo G5_JS_URL; ?>/shop.list.js"></script>

<!-- 상품 목록 시작 { -->
<div id="sct">

    <?php
    // 파츠디에스 CSS
    add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/partsds/css/brand_selector.css?ver='.G5_CSS_VER.'">', 5);

    $nav_skin = $skin_dir.'/navigation.skin.php';
    if(!is_file($nav_skin))
        $nav_skin = G5_SHOP_SKIN_PATH.'/navigation.skin.php';
    include $nav_skin;

    // 상단 HTML
    echo '<div id="sct_hhtml">'.conv_content($ca['ca_head_html'], 1).'</div>';

    // 파츠디에스 - 차종 필터 바 표시
    if ($pds_brand) {
        if (!isset($filter_info)) {
            include_once(G5_PATH . '/partsds/car_list_filter.php');
            $filter_info = pds_get_car_filter_info($pds_brand, $pds_series, $pds_model);
        }
        echo pds_render_filter_bar($pds_brand, $pds_series, $pds_model);
    }

    if ($ca_id) {
        $cate_skin = $skin_dir.'/listcategory.skin.php';
        if(!is_file($cate_skin))
            $cate_skin = G5_SHOP_SKIN_PATH.'/listcategory.skin.php';
        include $cate_skin;
    }

    // 상품 출력순서가 있다면
    if ($sort != "")
        $order_by = $sort.' '.$sortodr.' , it_order, it_id desc';
    else
        $order_by = 'it_order, it_id desc';

    $error = '<p class="sct_noitem">등록된 상품이 없습니다.</p>';

    // 리스트 스킨
    $skin_file = is_include_path_check($skin_dir.'/'.$ca['ca_skin']) ? $skin_dir.'/'.$ca['ca_skin'] : $skin_dir.'/list.10.skin.php';

    if (file_exists($skin_file)) {

        echo '<div id="sct_sortlst">';
        $sort_skin = $skin_dir.'/list.sort.skin.php';
        if(!is_file($sort_skin))
            $sort_skin = G5_SHOP_SKIN_PATH.'/list.sort.skin.php';
        include $sort_skin;

        // 상품 보기 타입 변경 버튼
        $sub_skin = $skin_dir.'/list.sub.skin.php';
        if(!is_file($sub_skin))
            $sub_skin = G5_SHOP_SKIN_PATH.'/list.sub.skin.php';
        include $sub_skin;
        echo '</div>';

        // 총몇개 = 한줄에 몇개 * 몇줄
        $items = $ca['ca_list_mod'] * $ca['ca_list_row'];
        // 페이지가 없으면 첫 페이지 (1 페이지)
        if ($page < 1) $page = 1;
        // 시작 레코드 구함
        $from_record = ($page - 1) * $items;

        $list = new item_list($skin_file, $ca['ca_list_mod'], $ca['ca_list_row'], $ca['ca_img_width'], $ca['ca_img_height']);

        // 카테고리 필터
        if ($ca_id) {
            $list->set_category($ca['ca_id'], 1);
            $list->set_category($ca['ca_id'], 2);
            $list->set_category($ca['ca_id'], 3);
        }

        // 파츠디에스 - 차종 필터 적용
        if ($pds_brand) {
            if (!function_exists('pds_get_car_items')) {
                include_once(G5_PATH . '/partsds/car_list_filter.php');
            }
            $car_item_ids = pds_get_car_items($pds_brand, $pds_series, $pds_model);

            if (empty($car_item_ids)) {
                // 해당 차종 상품 없음 -> 강제 빈 결과
                $list->set_query(" SELECT * FROM {$g5['g5_shop_item_table']} WHERE it_id = '__NO_RESULT__PARTSDS__' LIMIT 1 ");
            } else {
                // item_car 테이블의 it_id 목록으로 추가 where 조건 설정
                $list->set_query(
                    " SELECT it.* FROM {$g5['g5_shop_item_table']} it
                      WHERE it.it_id IN (" . implode(',', $car_item_ids) . ")
                      AND it.it_use = '1' " .
                    ($ca_id ? " AND (it.ca_id LIKE '" . sql_escape_string($ca_id) . "%' OR it.ca_id2 LIKE '" . sql_escape_string($ca_id) . "%' OR it.ca_id3 LIKE '" . sql_escape_string($ca_id) . "%') " : "") .
                    " ORDER BY {$order_by}
                      LIMIT {$from_record}, " . ($ca['ca_list_mod'] * $ca['ca_list_row'])
                );
            }
        }

        $list->set_is_page(true);
        $list->set_order_by($order_by);
        $list->set_from_record($from_record);
        $list->set_view('it_img', true);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_basic', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', true);
        echo $list->run();

        // where 된 전체 상품수
        $total_count = $list->total_count;
        // 전체 페이지 계산
        $total_page  = ceil($total_count / $items);
    }
    else
    {
        echo '<div class="sct_nofile">'.str_replace(G5_PATH.'/', '', $skin_file).' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
    }

    $qstr1 = 'ca_id='.$ca_id;
    // 차종 파라미터 유지
    if ($pds_brand)  $qstr1 .= '&amp;pds_brand='.$pds_brand;
    if ($pds_series) $qstr1 .= '&amp;pds_series='.$pds_series;
    if ($pds_model)  $qstr1 .= '&amp;pds_model='.$pds_model;
    $qstr1 .='&amp;sort='.$sort.'&amp;sortodr='.$sortodr;
    echo get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&amp;page=');

    // 하단 HTML
    echo '<div id="sct_thtml">'.conv_content($ca['ca_tail_html'], 1).'</div>';

?>
</div>
<!-- } 상품 목록 끝 -->

<?php
if ($ca['ca_include_tail'] && is_include_path_check($ca['ca_include_tail']))
    @include_once($ca['ca_include_tail']);
else
    include_once(G5_SHOP_PATH.'/_tail.php');

echo "\n<!-- {$ca['ca_skin']} -->\n";
