<?php
/**
 * theme file : /theme/THEME_NAME/index.html.php
 * 파츠디에스 커스텀: 브랜드 선택기 추가
 */
if (!defined('_EYOOM_')) exit;

// 파츠디에스 CSS 로드
add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/partsds/css/brand_selector.css?ver='.G5_CSS_VER.'">', 5);
?>

<div class="main-contents">
    <?php /* EB배너 - basic */ ?>
    <?php echo eb_banner('1669280887'); ?>

    <?php /* 파츠디에스 - 브랜드 선택기 */ ?>
    <?php
    if (function_exists('sql_query')) {
        $tbl_check = @sql_fetch("SELECT 1 FROM `" . G5_TABLE_PREFIX . "car_brand` LIMIT 1");
        if ($tbl_check !== false) {
            include_once(G5_PATH . '/partsds/brand_selector.php');
        }
    }
    ?>

    <?php /* EB최신글 - bestset */ ?>
    <?php echo eb_latest('1517122147'); ?>

    <?php /* EB최신글 - basic */ ?>
    <?php echo eb_latest('1720392562'); ?>

    <?php /* EB최신글 - basic */ ?>
    <?php echo eb_latest('1518393947'); ?>

    <?php /* EB최신글 - gallery */ ?>
    <?php echo eb_latest('1720392389'); ?>

    <?php /* EB최신글 - gallery */ ?>
    <?php echo eb_latest('1518503581'); ?>

    <?php /* EB최신글 - webzine */ ?>
    <?php echo eb_latest('1720392143'); ?>

    <?php /* EB최신글 - webzine */ ?>
    <?php echo eb_latest('1519114252'); ?>
</div>
