<?php
include_once('./_common.php');

$ca_id = isset($_REQUEST['ca_id']) ? safe_replace_regex($_REQUEST['ca_id'], 'ca_id') : '';
$skin = isset($_REQUEST['skin']) ? safe_replace_regex($_REQUEST['skin'], 'skin') : '';

// 상품 리스트에서 다른 필드로 정렬을 하려면 아래의 배열 코드에서 해당 필드를 추가하세요.
if( isset($sort) && ! in_array($sort, array('it_name', 'it_sum_qty', 'it_price', 'it_use_avg', 'it_use_cnt', 'it_update_time')) ){
    $sort='';
}

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH.'/list.php');
    return;
}

// 테마에 list.php 있으면 include (이윗빌더 eyoom/shop/list.php 자동 라우팅)
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_list_file = G5_THEME_SHOP_PATH.'/list.php';
    if(is_file($theme_list_file)) {
        include_once($theme_list_file);
        return;
    }
    unset($theme_list_file);
}

$sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' and ca_use = '1'  ";
$ca = sql_fetch($sql);
if (! (isset($ca['ca_id']) && $ca['ca_id']))
    alert('등록된 분류가 없습니다.');

// 테마미리보기 스킨 등의 변수 재설정
if(defined('_THEME_PREVIEW_') && _THEME_PREVIEW_ === true) {
    $ca['ca_skin']       = (isset($tconfig['ca_skin']) && $tconfig['ca_skin']) ? $tconfig['ca_skin'] : $ca['ca_skin'];
    $ca['ca_img_width']  = (isset($tconfig['ca_img_width']) && $tconfig['ca_img_width']) ? $tconfig['ca_img_width'] : $ca['ca_img_width'];
    $ca['ca_img_height'] = (isset($tconfig['ca_img_height']) && $tconfig['ca_img_height']) ? $tconfig['ca_img_height'] : $ca['ca_img_height'];
    $ca['ca_list_mod']   = (isset($tconfig['ca_list_mod']) && $tconfig['ca_list_mod']) ? $tconfig['ca_list_mod'] : $ca['ca_list_mod'];
    $ca['ca_list_row']   = (isset($tconfig['ca_list_row']) && $tconfig['ca_list_row']) ? $tconfig['ca_list_row'] : $ca['ca_list_row'];
}

// 본인인증, 성인인증체크
if(!$is_admin && $config['cf_cert_use']) {
    $msg = shop_member_cert_check($ca_id, 'list');
    if($msg)
        alert($msg, G5_SHOP_URL);
}

$g5['title'] = $ca['ca_name'].' 상품리스트';

// 상단 디자인 출력
if ($ca['ca_include_head'] && is_include_path_check($ca['ca_include_head']))
    @include_once($ca['ca_include_head']);
elseif (defined('EYOOM_SHOP_PATH') && file_exists(EYOOM_SHOP_PATH . '/shop.head.php'))
    include_once(EYOOM_SHOP_PATH . '/shop.head.php');
else
    include_once(G5_SHOP_PATH.'/_head.php');

// 스킨경로 결정: �Ը요빌더 core 스킨 우선
if (defined('EYOOM_CORE_PATH') && is_dir(EYOOM_CORE_PATH . '/' . G5_SHOP_DIR)) {
    $skin_dir = EYOOM_CORE_PATH . '/' . G5_SHOP_DIR;
} else {
    $skin_dir = G5_SHOP_SKIN_PATH;
}

// 카테고리 별도 스킨 지정이 있는 경우
if($ca['ca_skin_dir']) {
    $custom_skin_dir = '';
    if(preg_match('#^theme/(.+)$#', $ca['ca_skin_dir'], $match))
        $custom_skin_dir = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/shop/'.$match[1];
    else
        $custom_skin_dir = G5_PATH.'/'.G5_SKIN_DIR.'/shop/'.$ca['ca_skin_dir'];

    if($custom_skin_dir && is_dir($custom_skin_dir)) {
        $skin_file_check = $custom_skin_dir.'/'.$ca['ca_skin'];
        if(is_file($skin_file_check))
            $skin_dir = $custom_skin_dir;
    }
}

if (!defined('G5_SHOP_CSS_URL'))
    define('G5_SHOP_CSS_URL', str_replace(G5_PATH, G5_URL, $skin_dir));

if ($is_admin)
    echo '<div class="sct_admin"><a href="'.G5_ADMIN_URL.'/shop_admin/categoryform.php?w=u&amp;ca_id='.$ca_id.'" class="btn_admin btn"><span class="sound_only">분류 관리</span><i class="fa fa-cog fa-spin fa-fw"></i></a></div>';

// 네비게이션, 카테고리, 정렬, 기본 스킨 경로
$nav_skin = $skin_dir.'/navigation.skin.php';
if(!is_file($nav_skin)) $nav_skin = G5_SHOP_SKIN_PATH.'/navigation.skin.php';

$cate_skin = $skin_dir.'/listcategory.skin.php';
if(!is_file($cate_skin)) $cate_skin = G5_SHOP_SKIN_PATH.'/listcategory.skin.php';

$sort_skin = $skin_dir.'/list.sort.skin.php';
if(!is_file($sort_skin)) $sort_skin = G5_SHOP_SKIN_PATH.'/list.sort.skin.php';

$sub_skin = $skin_dir.'/list.sub.skin.php';
if(!is_file($sub_skin)) $sub_skin = G5_SHOP_SKIN_PATH.'/list.sub.skin.php';

// 상품 출력순서가 있다면
if ($sort != "")
    $order_by = $sort.' '.$sortodr.' , it_order, it_id desc';
else
    $order_by = 'it_order, it_id desc';

// 리스트 스킨 파일 결정 (ca_skin='' �Ը 재�Ը됴 수스 = list.10.skin.php)
$skin_file = is_include_path_check($skin_dir.'/'.$ca['ca_skin']) ? $skin_dir.'/'.$ca['ca_skin'] : $skin_dir.'/list.10.skin.php';

// 파츠디에스 차종 필터 파라미터
// 로그인 회원의 차종이 저장되어 있고 URL에 파라미터가 없으면 자동 적용
if ($is_member && !empty($member['mb_4']) && !isset($_GET['pds_brand']) && !isset($_GET['pds_no_filter'])) {
    $pds_brand_id  = (int)$member['mb_4'];
    $pds_series_id = !empty($member['mb_5']) ? (int)$member['mb_5'] : 0;
    $pds_model_id  = !empty($member['mb_6']) ? (int)$member['mb_6'] : 0;
    $pds_auto_filter = true; // 자동 적용 표시 (스킨에서 안내문 표시용)
} else {
    $pds_brand_id  = isset($_GET['pds_brand'])  ? (int)$_GET['pds_brand']  : 0;
    $pds_series_id = isset($_GET['pds_series']) ? (int)$_GET['pds_series'] : 0;
    $pds_model_id  = isset($_GET['pds_model'])  ? (int)$_GET['pds_model']  : 0;
    $pds_auto_filter = false;
}

if (file_exists($skin_file)) {
    $items = max(1,(int)$ca['ca_list_mod']) * max(1,(int)$ca['ca_list_row']);
    if ($page < 1) $page = 1;
    $from_record = ($page - 1) * $items;

    $list = new item_list($skin_file, $ca['ca_list_mod'], $ca['ca_list_row'], $ca['ca_img_width'], $ca['ca_img_height']);
    $list->set_category($ca['ca_id'], 1);
    $list->set_category($ca['ca_id'], 2);
    $list->set_category($ca['ca_id'], 3);
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

    // 파츠디에스 차종 필터 적용
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

    $item_list   = $list->run();
    $total_count = $list->total_count;
    $total_page  = ceil($total_count / $items);
} else {
    $item_list   = '<p class="sct_noitem">' . str_replace(G5_PATH.'/', '', $skin_file) . ' 파일을 찾을 수 없습니다.</p>';
    $total_count = 0;
    $total_page  = 0;
}

// 페이징
$qstr1 = 'ca_id=' . $ca_id . '&amp;sort=' . $sort . '&amp;sortodr=' . $sortodr;
if (!empty($pds_brand_id)) {
    $qstr1 .= '&amp;pds_brand=' . $pds_brand_id;
    if (!empty($pds_series_id)) $qstr1 .= '&amp;pds_series=' . $pds_series_id;
    if (!empty($pds_model_id))  $qstr1 .= '&amp;pds_model='  . $pds_model_id;
}

// 이윗빌더 테마 스킨으로 출력 (list.skin.html.php 사용)
if (defined('EYOOM_THEME_SHOP_SKIN_PATH') && file_exists(EYOOM_THEME_SHOP_SKIN_PATH . '/list.skin.html.php')) {
    // 페이징 스팅 필요 (eb_paging 함수에서 사용)
    if (isset($eb) && method_exists($eb, 'set_paging')) {
        $paging = $eb->set_paging('itemlist', $ca_id, $qstr1);
    } else {
        // fallback: 기본 페이징
        $paging = ['url' => G5_SHOP_URL.'/list.php?'.$qstr1.'&page=', 'pages' => $config['cf_write_pages']];
    }
    include_once(EYOOM_THEME_SHOP_SKIN_PATH . '/list.skin.html.php');
} else {
    // fallback: 기본 출력 (이윗빌더 경로�없을 때)
?>
<script>
var itemlist_ca_id = "<?php echo $ca_id; ?>";
</script>
<script src="<?php echo G5_JS_URL; ?>/shop.list.js"></script>

<!-- 상품 목록 시작 { -->
<div id="sct">
    <?php include $nav_skin; ?>
    <div id="sct_hhtml"><?php echo conv_content($ca['ca_head_html'], 1); ?></div>
    <?php include $cate_skin; ?>
    <div id="sct_sortlst">
        <?php include $sort_skin; ?>
        <?php include $sub_skin; ?>
    </div>
    <div id="product_list">
        <?php echo $item_list; ?>
    </div>
    <?php echo get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&amp;page='); ?>
    <div id="sct_thtml"><?php echo conv_content($ca['ca_tail_html'], 1); ?></div>
</div>
<!-- } 상품 목록 끝 -->
<?php
}

// 하단 디자인 출력
if ($ca['ca_include_tail'] && is_include_path_check($ca['ca_include_tail']))
    @include_once($ca['ca_include_tail']);
elseif (defined('EYOOM_SHOP_PATH') && file_exists(EYOOM_SHOP_PATH . '/shop.tail.php'))
    include_once(EYOOM_SHOP_PATH . '/shop.tail.php');
else
    include_once(G5_SHOP_PATH.'/_tail.php');

echo "\n<!-- {$ca['ca_skin']} -->\n";
