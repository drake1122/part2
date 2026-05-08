<?php
/**
 * skin file : /theme/THEME_NAME/skin/shop/basic/list.skin.html.php
 */
if (!defined('_EYOOM_')) exit;

$plType = 'gallery';
if (!empty($_COOKIE[$ca_id])) {
    if ($_COOKIE[$ca_id] == 'list') {
        $plType = 'list';
    } else if ($_COOKIE[$ca_id] == 'gallery') {
        $plType = 'gallery';
    }
}
?>

<style>
.shop-list-navcate-wrap {position:relative;margin-bottom:30px}
.shop-list-sort-wrap {position:relative}
@media (max-width:991px) {
    .shop-list-sort-wrap .tab-scroll-category {margin-bottom:20px}
}
</style>

<div class="shop-list">
    <div class="shop-list-navcate-wrap">
        <div class="shop-list-nav-box">
            <?php /* 네이게이션 정보 */ ?>
            <?php include $nav_skin; ?>
        </div>
        <div class="shop-list-cate-box">
            <?php /* 상품분류 정보 */ ?>
            <?php include $cate_skin; ?>
        </div>
    </div>

    <?php /* 상단 HTML */ ?>
    <div id="sct_hhtml" class="m-b-30"><?php echo conv_content($ca['ca_head_html'], 1); ?></div>

    <?php /* ── 파츠디에스 - 파츠 카테고리 그리드 ── */
    // 파츠 카테고리 그리드: 차종 선택 상태 URL 파라미터 수집 및 전달
    $pds_parts_file = G5_PATH . '/partsds/parts_category.php';
    if (file_exists($pds_parts_file)) {
        // URL 파라미터에서 차종 ID 수집 (brand_selector.php pdsDoSearch가 전달한 값)
        $pds_brand_id  = isset($_GET['pds_brand_id'])  ? (int)$_GET['pds_brand_id']  : 0;
        $pds_series_id = isset($_GET['pds_series_id']) ? (int)$_GET['pds_series_id'] : 0;
        $pds_model_id  = isset($_GET['pds_model_id'])  ? (int)$_GET['pds_model_id']  : 0;

        // 로그인 회원 내 차종 자동 적용 (pds_no_filter 파라미터가 없을 때)
        $pds_auto_filter = false;
        if (!$pds_brand_id && $is_member && empty($_GET['pds_no_filter'])) {
            $pds_brand_id  = (int)$member['mb_4'];
            $pds_series_id = (int)$member['mb_5'];
            $pds_model_id  = (int)$member['mb_6'];
            if ($pds_brand_id) $pds_auto_filter = true;
        }

        include($pds_parts_file);
    }
    ?>

    <?php /* ── 파츠디에스 차종 필터 바 (상품목록 상단 표시) ── */ ?>
    <?php
    if (!empty($pds_brand_id)) {
        $pds_filter_file = G5_PATH . '/partsds/car_list_filter.php';
        if (file_exists($pds_filter_file)) {
            if (!function_exists('pds_render_filter_bar')) include_once($pds_filter_file);
            echo pds_render_filter_bar($pds_brand_id, $pds_series_id, $pds_model_id, !empty($pds_auto_filter));
        }
    }
    ?>

    <div class="shop-list-sort-wrap">
        <?php /* 상품 정렬 선택 시작 */ ?>
        <?php include $sort_skin; ?>
        <?php include $sub_skin; ?>
    </div>

    <div id="product_list" class="product-type-<?php echo $plType; ?>">
        <?php echo $item_list; ?>
    </div>

    <?php /* 페이지 */ ?>
    <?php echo eb_paging($eyoom['paging_skin']);?>

    <?php /* 하단 HTML */ ?>
    <div id="sct_thtml"><?php echo conv_content($ca['ca_tail_html'], 1); ?></div>
</div>

<script>
var listCaId = "<?php echo $ca_id; ?>";
var currentPlType = localStorage.getItem(listCaId);

$.fn.listType = function(pltype) {
    var itemList = this.find(".item-list");
    var count = itemList.size();
    if(count < 1)
        return;

    var cls = this.attr("class");
    if(cls && !this.data("class")) {
        this.data("class", cls);
    }

    $("button.product-type-btn span").removeClass("product-type-on").html("");

    if(pltype == "gallery") {
        if(this.data("class")) {
            this.removeAttr("class");
        }
        this.addClass("product-type-gallery");
        $("button.product-type-gallery-btn span").addClass("product-type-on").html("<b class=\"sound_only\"> 선택됨</b>");
    } else if(pltype == "list") {
        if(this.data("class")) {
            this.removeAttr("class");
        }
        this.addClass("product-type-list");
        $("button.product-type-list-btn span").addClass("product-type-on").html("<b class=\"sound_only\"> 선택됨</b>");
    }

    localStorage.setItem(listCaId, pltype);
    set_cookie(listCaId, pltype, 100);
}

$(function() {
    $("#product_list").listType(currentPlType);

    $("button.product-type-btn").on("click", function() {
        if($(this).hasClass("product-type-gallery-btn")) {
            $("#product_list").listType("gallery");
        } else if($(this).hasClass("product-type-list-btn")) {
            $("#product_list").listType("list");
        }
    });
});
</script>