<?php
/**
 * 파츠디에스 - 파츠 카테고리 그리드 컴포넌트
 * 경로: /partsds/parts_category.php
 *
 * 사용법 (이윰빌더 eb4_basic 테마):
 *   - theme/eb4_basic/skin/shop/basic/list.skin.html.php 상단에 include
 *   - <?php include_once(G5_PATH.'/partsds/parts_category.php'); ?>
 *
 * 기능:
 *   1. 파츠 41개 카테고리 이미지 그리드 표시 (카페24 동일 이미지)
 *   2. 카테고리 클릭 → 해당 ca_id(5001~5041) 상품 목록으로 이동
 *   3. 차종 선택 상태(pds_brand_id/series_id/model_id)가 있으면
 *      ca_id + 차종 필터 파라미터 함께 전달
 *   4. 현재 선택된 ca_id 카테고리 활성화(is-active) 표시
 *   5. 차종 선택 시: item_car 테이블 기반으로 해당 차종 상품이 있는
 *      카테고리만 활성 / 없으면 흐리게 + 클릭 시 안내 다이얼로그
 *
 * 사전 조건:
 *   - install_parts_categories.sql 실행 완료 (ca_id 5000~5041 생성)
 *   - item_car 테이블에 it_id + brand_id/series_id/model_id 연결 데이터 존재
 */
if (!defined('_GNUBOARD_') && !defined('_EYOOM_')) exit;

// ── 파츠 카테고리 정의 ────────────────────────────────────────────────────────
// ca_id: g5_shop_category의 ca_id (install_parts_categories.sql 기준: 5001~5041)
// img  : 카페24 카테고리 이미지 URL 그대로 사용
$pds_parts_categories = [
    ['ca_id' => '5001', 'name' => '오일필터',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/3d7bf681d7979f8c353deb988eca1cb1.png'],
    ['ca_id' => '5002', 'name' => '에어필터',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/f8bb08e3ef6b2cabea71c85ced3c278c.png'],
    ['ca_id' => '5003', 'name' => '에어컨필터',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/4d15b07280f8c733b4b2e367e3a2bf68.png'],
    ['ca_id' => '5004', 'name' => '연료필터',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/80d524d2ead5ecf3ba1d30f89a7cfad7.png'],
    ['ca_id' => '5005', 'name' => '미션오일필터',          'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/df38071e2a6a7f0f9a96aaf54a016a42.png'],
    ['ca_id' => '5006', 'name' => '오일필터하우징',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/66580b342b4b2eb5f27e294e35004a14.png'],
    ['ca_id' => '5007', 'name' => '미션오일',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/2e2f7417b690b5507f4ba3069d6f36fb.png'],
    ['ca_id' => '5008', 'name' => '엔진오일',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/91af54e1b49990ff928a9ff5540e10eb.png'],
    ['ca_id' => '5009', 'name' => '부동액',                'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/7801fe711fe6da296403b50a552542f6.png'],
    ['ca_id' => '5010', 'name' => '브레이크오일',          'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/3e840e76a0433dcf04111685b2c16ac9.png'],
    ['ca_id' => '5011', 'name' => '브레이크디스크',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/ea7c7b751ed5cb4ff5eb187630d65d76.png'],
    ['ca_id' => '5012', 'name' => '브레이크패드',          'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/f6e679d16cd532a616b67f015fbd6926.png'],
    ['ca_id' => '5013', 'name' => '브레이크센서',          'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/a661ac58f6968301d1a5cc1c2533cf9f.png'],
    ['ca_id' => '5014', 'name' => '브레이크캘리퍼',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/07/47200c715596bcce41c283de7654170e.png'],
    ['ca_id' => '5015', 'name' => '엔진마운트',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/ef35247c9c232dcdd744c819b1165c3d.png'],
    ['ca_id' => '5016', 'name' => '미션마운트',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/62ff3f6bd11547f8920466ab5857e484.png'],
    ['ca_id' => '5017', 'name' => 'V벨트',                 'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/039ae4c1fd2fd7a3476cad013c44ec7c.png'],
    ['ca_id' => '5018', 'name' => '댐퍼풀리',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/a479b106c44a907fed73f874219ffcc8.png'],
    ['ca_id' => '5019', 'name' => '벨트텐셔너',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/0464e3ccf781610aef5992e3e52584f9.png'],
    ['ca_id' => '5020', 'name' => '워터펌프',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/a72fa4022daea7de867f60d94f2871eb.png'],
    ['ca_id' => '5021', 'name' => '써머스탯',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/6780f6f25c9e810f2f6c5f3daacc9171.png'],
    ['ca_id' => '5022', 'name' => '라디에이터 관련',       'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/b5a032d855d3d5e67a209f11e064bfe9.png'],
    ['ca_id' => '5023', 'name' => '알터네이터',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/cb5b2fd547a858c29c7acb2cbe33f375.png'],
    ['ca_id' => '5024', 'name' => '에어컨콤프레셔',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/e5193eefb12b0209b9781da558bb94b2.png'],
    ['ca_id' => '5025', 'name' => '스타트모터',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/58f35144e075f7c6d9ff770b3591a8a2.png'],
    ['ca_id' => '5026', 'name' => '흡기 매니폴드 관련',    'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2025/09/30/5ac767b494f4788e17ebff2180c7f09b.png'],
    ['ca_id' => '5027', 'name' => '고압펌프',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/515bf4268bb194484cf8660aed32ff72.png'],
    ['ca_id' => '5028', 'name' => '인젝터',                'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/d7ff28bbfe981cd2b196bc3679a13116.png'],
    ['ca_id' => '5029', 'name' => '와이퍼',                'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/09/40895e20cda09def43843192b044104c.png'],
    ['ca_id' => '5030', 'name' => '드라이브샤프트',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/5c62bf8211c75f49256cdce4a4a2dcee.png'],
    ['ca_id' => '5031', 'name' => '쇼바',                  'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/36c066aa4771d6995505af6f6cb4a07a.png'],
    ['ca_id' => '5032', 'name' => '유니버셜조인트',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/7f8ec4a75e1892165b357ec00853f44e.png'],
    ['ca_id' => '5033', 'name' => '허브베어링',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/9a2fa9b4176fddaae08c4b1819150262.png'],
    ['ca_id' => '5034', 'name' => '휠볼트',                'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/%ED%9C%A0%EB%B3%B4%ED%8A%B8.png'],
    ['ca_id' => '5035', 'name' => '프로펠러샤프트',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/25dc65b7d9ec5ffb3e681ca699c96079.png'],
    ['ca_id' => '5036', 'name' => '하체부품',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/017304d00624d995d62034d436845ac2.png'],
    ['ca_id' => '5037', 'name' => '산소센서',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/9f736b0c6eb0f4b001a03b866227671a.png'],
    ['ca_id' => '5038', 'name' => '점화플러그(예열) 배선 관련', 'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/09/a47437577d11c9b6510446320a155451.png'],
    ['ca_id' => '5039', 'name' => '라이트모듈 관련',       'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/0c3e28e579009aa84b201c38653672b4.png'],
    ['ca_id' => '5040', 'name' => '자동차용품 관련',       'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/kar.png'],
    ['ca_id' => '5041', 'name' => '기타 관련',             'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/logg2.png'],
];

// ── 현재 선택된 차종 파라미터 수집 ──────────────────────────────────────────
// list.skin.html.php 또는 shop/list.php 에서 이미 파싱된 변수가 있으면 우선 사용
$_pcat_brand_id  = isset($pds_brand_id)  ? (int)$pds_brand_id
                 : (isset($_GET['pds_brand_id'])  ? (int)$_GET['pds_brand_id']  : 0);
$_pcat_series_id = isset($pds_series_id) ? (int)$pds_series_id
                 : (isset($_GET['pds_series_id']) ? (int)$_GET['pds_series_id'] : 0);
$_pcat_model_id  = isset($pds_model_id)  ? (int)$pds_model_id
                 : (isset($_GET['pds_model_id'])  ? (int)$_GET['pds_model_id']  : 0);

// 차종 URL 파라미터 문자열 (카테고리 이동 시 차종 유지)
$_pcat_car_qs = '';
if ($_pcat_brand_id) {
    $_pcat_car_qs .= '&pds_brand_id='  . $_pcat_brand_id;
    if ($_pcat_series_id) $_pcat_car_qs .= '&pds_series_id=' . $_pcat_series_id;
    if ($_pcat_model_id)  $_pcat_car_qs .= '&pds_model_id='  . $_pcat_model_id;
}

// ── 현재 열려 있는 파츠 ca_id (상품목록에서 전달) ───────────────────────────
$_pcat_current_ca = isset($ca_id) ? (string)$ca_id
                  : (isset($_GET['ca_id']) ? (string)$_GET['ca_id'] : '');

// ── 차종 선택 시: item_car 기반으로 상품 있는 파츠 ca_id 집합 조회 ──────────
// null = 차종 미선택(모두 활성), array = 해당 차종에 상품 있는 ca_id 목록
$_pcat_active_ca_ids = null;
$_pcat_vehicle_name  = '';

if ($_pcat_brand_id) {
    $_pcat_active_ca_ids = [];

    // ── 차종명 조합 ──
    $_br = @sql_fetch("SELECT brand_name FROM `" . G5_TABLE_PREFIX . "car_brand`
                        WHERE brand_id = " . $_pcat_brand_id);
    if (!empty($_br['brand_name'])) $_pcat_vehicle_name = $_br['brand_name'];

    if ($_pcat_series_id) {
        $_sr = @sql_fetch("SELECT series_name FROM `" . G5_TABLE_PREFIX . "car_series`
                            WHERE series_id = " . $_pcat_series_id);
        if (!empty($_sr['series_name'])) $_pcat_vehicle_name .= ' ' . $_sr['series_name'];
    }
    if ($_pcat_model_id) {
        $_mr = @sql_fetch("SELECT model_name FROM `" . G5_TABLE_PREFIX . "car_model`
                            WHERE model_id = " . $_pcat_model_id);
        if (!empty($_mr['model_name'])) $_pcat_vehicle_name .= ' ' . $_mr['model_name'];
    }

    // ── item_car 테이블 존재 여부 확인 ──
    $_tbl_check = @sql_fetch("SELECT COUNT(*) AS cnt
        FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = '" . G5_TABLE_PREFIX . "item_car'");

    if (!empty($_tbl_check['cnt'])) {
        // 차종 조건 WHERE 구성
        $_pcat_where = [];
        if ($_pcat_model_id)       $_pcat_where[] = "ic.model_id = "  . $_pcat_model_id;
        elseif ($_pcat_series_id)  $_pcat_where[] = "ic.series_id = " . $_pcat_series_id;
        else                       $_pcat_where[] = "ic.brand_id = "  . $_pcat_brand_id;

        // 해당 차종 상품들의 파츠 ca_id(5001~5041) 조회
        $_pcat_sql = "SELECT DISTINCT si.ca_id
                      FROM `" . G5_TABLE_PREFIX . "item_car` ic
                      INNER JOIN `" . G5_TABLE_PREFIX . "shop_item` si ON ic.it_id = si.it_id
                      WHERE " . implode(' AND ', $_pcat_where) . "
                        AND si.ca_id BETWEEN '5001' AND '5041'
                        AND si.it_use = '1'";

        $_pcat_res = @sql_query($_pcat_sql);
        if ($_pcat_res) {
            while ($_pcat_row = sql_fetch_array($_pcat_res)) {
                if ($_pcat_row['ca_id']) {
                    $_pcat_active_ca_ids[] = $_pcat_row['ca_id'];
                }
            }
        }
    }
}

// ── 쇼핑몰 목록 기본 URL ────────────────────────────────────────────────────
$_pcat_list_url = defined('G5_SHOP_URL') ? G5_SHOP_URL . '/list.php' : '/shop/list.php';
?>
<!-- ============================================================
     파츠디에스 - 파츠 카테고리 그리드
     /partsds/parts_category.php
     ============================================================ -->
<link rel="stylesheet" href="<?php echo G5_URL; ?>/partsds/css/parts_category.css">

<section class="pds-parts-section" id="pdsPartsSection">
<div class="pds-parts-inner">

    <!-- 타이틀 -->
    <div class="pds-parts-header">
        <h2 class="pds-parts-title">PARTS</h2>
        <?php if ($_pcat_brand_id && $_pcat_active_ca_ids !== null): ?>
        <div class="pds-vehicle-badge">
            <i class="fas fa-car"></i>
            <strong><?php echo htmlspecialchars(trim($_pcat_vehicle_name)); ?></strong>
            <span class="pds-vehicle-count">
                (<?php echo count($_pcat_active_ca_ids); ?>개 카테고리 해당)
            </span>
            <a href="<?php echo $_pcat_list_url; ?>?ca_id=<?php echo urlencode($_pcat_current_ca); ?>"
               class="pds-vehicle-clear" title="차종 필터 해제">
                <i class="fas fa-times-circle"></i> 해제
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- 파츠 그리드 -->
    <div class="pds-parts-grid" id="pdsPartsGrid">
        <?php foreach ($pds_parts_categories as $_pcat_item):
            $_ca_id   = $_pcat_item['ca_id'];
            $_ca_name = $_pcat_item['name'];
            $_ca_img  = $_pcat_item['img'];

            // 현재 선택 여부
            $_is_selected = ($_pcat_current_ca !== '' && $_pcat_current_ca === $_ca_id);

            // 해당 차종에 상품 존재 여부
            // - $_pcat_active_ca_ids === null : 차종 미선택 → 모두 활성
            // - $_pcat_active_ca_ids === []   : 차종 선택했으나 파츠 매핑 없음 → 모두 흐림
            // - 배열에 포함 여부로 판단
            if ($_pcat_active_ca_ids === null) {
                $_has_items = true;
            } else {
                $_has_items = in_array($_ca_id, $_pcat_active_ca_ids);
            }

            // 이동 URL: ca_id + 차종 파라미터
            $_href = $_pcat_list_url . '?ca_id=' . urlencode($_ca_id) . $_pcat_car_qs;

            // CSS 클래스
            $_item_class = 'pds-parts-item';
            if ($_is_selected) $_item_class .= ' is-active';
            if (!$_has_items)  $_item_class .= ' no-items';
        ?>
        <a href="<?php echo $_href; ?>"
           class="<?php echo $_item_class; ?>"
           data-ca-id="<?php echo $_ca_id; ?>"
           data-name="<?php echo htmlspecialchars($_ca_name, ENT_QUOTES); ?>"
           data-has-items="<?php echo $_has_items ? '1' : '0'; ?>"
           title="<?php echo htmlspecialchars($_ca_name); ?>">
            <div class="pds-parts-img-wrap">
                <img src="<?php echo $_ca_img; ?>"
                     alt="<?php echo htmlspecialchars($_ca_name); ?>"
                     loading="lazy"
                     onerror="this.style.display='none';this.parentNode.querySelector('.pds-img-fallback').style.display='flex'">
                <span class="pds-img-fallback" style="display:none">
                    <?php echo htmlspecialchars($_ca_name); ?>
                </span>
                <?php if ($_is_selected): ?>
                <span class="pds-badge-active"><i class="fas fa-check"></i></span>
                <?php endif; ?>
                <?php if (!$_has_items && $_pcat_active_ca_ids !== null): ?>
                <span class="pds-badge-empty"><i class="fas fa-minus-circle"></i></span>
                <?php endif; ?>
            </div>
            <span class="pds-parts-name"><?php echo htmlspecialchars($_ca_name); ?></span>
        </a>
        <?php endforeach; ?>
    </div><!-- //pds-parts-grid -->

    <p class="pds-parts-notice">
        그 밖의 부품은 문의 주세요 &nbsp;|&nbsp; 이미지는 실제 제품과 다를 수 있으니 제품명을 기준으로 구매 부탁드립니다
    </p>

</div>
</section>

<script>
(function() {
    'use strict';

    var hasCarFilter = <?php echo ($_pcat_active_ca_ids !== null) ? 'true' : 'false'; ?>;

    /**
     * 상품 없는 카테고리 클릭 처리
     * - 차종 필터 없으면 그냥 이동
     * - 차종 필터 있고 상품 없으면 다이얼로그 → 전체 상품으로 이동 or 취소
     */
    function handlePartsItemClick(e) {
        var el = this;
        var hasItems = el.getAttribute('data-has-items') === '1';
        var catName  = el.getAttribute('data-name') || '';

        if (!hasCarFilter || hasItems) {
            return true; // 그냥 이동
        }

        e.preventDefault();

        var msg = '선택하신 차량에는 [' + catName + '] 카테고리의 상품이 등록되어 있지 않습니다.\n\n'
                + '차종 필터를 해제하고 전체 상품에서 해당 카테고리를 검색하시겠습니까?';

        if (confirm(msg)) {
            // 차종 파라미터 제거 후 해당 ca_id 페이지로 이동
            var href = el.href;
            href = href.replace(/[?&]pds_brand_id=[^&]*/g, '')
                       .replace(/[?&]pds_series_id=[^&]*/g, '')
                       .replace(/[?&]pds_model_id=[^&]*/g, '');
            // ?가 제거된 경우 복원
            href = href.replace(/^([^?]*)\&/, '$1?');
            window.location.href = href;
        }
    }

    // DOM 준비 후 이벤트 바인딩
    function init() {
        var items = document.querySelectorAll('#pdsPartsGrid .pds-parts-item');
        for (var i = 0; i < items.length; i++) {
            items[i].addEventListener('click', handlePartsItemClick);
        }

        // 현재 카테고리 아이템이 보이도록 스크롤 (필요 시)
        var activeItem = document.querySelector('#pdsPartsGrid .pds-parts-item.is-active');
        if (activeItem) {
            activeItem.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
