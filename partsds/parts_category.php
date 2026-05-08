<?php
/**
 * 파츠디에스 - 파츠 카테고리 그리드 컴포넌트
 * 경로: /partsds/parts_category.php
 *
 * 상품 목록 페이지(shop/list.php) 상단 또는 shop.head.html.php 에서 include
 *
 * 기능:
 *  - 파츠 카테고리 이미지 그리드 표시 (카페24와 동일 디자인)
 *  - 카테고리 클릭 → 해당 ca_id 상품 목록으로 이동
 *  - 차종 선택 상태가 있으면 ca_id + 차종 필터 파라미터 함께 전달
 *  - 현재 선택된 ca_id에 해당하는 카테고리 아이템 활성화 표시
 *  - 해당 차종에 등록된 상품이 있는 카테고리만 활성 / 없으면 흐리게 표시
 */
if (!defined('_GNUBOARD_') && !defined('_EYOOM_')) exit;

// ── 파츠 카테고리 정의 (이미지 URL + 쇼핑몰 ca_id 매핑) ──────────────────
// ca_id: g5_shop_category 테이블의 ca_id (install_shop_categories.sql 기준)
// 파츠 관련 ca_id는 별도 상품분류로 구성됨
// 아래 ca_id_parts는 파츠 종류별 분류 ID (DB에 맞게 수정 필요)
$pds_parts_categories = [
    ['name' => '오일필터',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/3d7bf681d7979f8c353deb988eca1cb1.png', 'ca_id' => 'P001'],
    ['name' => '에어필터',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/f8bb08e3ef6b2cabea71c85ced3c278c.png', 'ca_id' => 'P002'],
    ['name' => '에어컨필터',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/4d15b07280f8c733b4b2e367e3a2bf68.png', 'ca_id' => 'P003'],
    ['name' => '연료필터',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/80d524d2ead5ecf3ba1d30f89a7cfad7.png', 'ca_id' => 'P004'],
    ['name' => '미션오일필터',          'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/df38071e2a6a7f0f9a96aaf54a016a42.png', 'ca_id' => 'P005'],
    ['name' => '오일필터하우징',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/66580b342b4b2eb5f27e294e35004a14.png', 'ca_id' => 'P006'],
    ['name' => '미션오일',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/2e2f7417b690b5507f4ba3069d6f36fb.png', 'ca_id' => 'P007'],
    ['name' => '엔진오일',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/91af54e1b49990ff928a9ff5540e10eb.png', 'ca_id' => 'P008'],
    ['name' => '부동액',                'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/7801fe711fe6da296403b50a552542f6.png', 'ca_id' => 'P009'],
    ['name' => '브레이크오일',          'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/3e840e76a0433dcf04111685b2c16ac9.png', 'ca_id' => 'P010'],
    ['name' => '브레이크디스크',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/ea7c7b751ed5cb4ff5eb187630d65d76.png', 'ca_id' => 'P011'],
    ['name' => '브레이크패드',          'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/f6e679d16cd532a616b67f015fbd6926.png', 'ca_id' => 'P012'],
    ['name' => '브레이크센서',          'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/a661ac58f6968301d1a5cc1c2533cf9f.png', 'ca_id' => 'P013'],
    ['name' => '브레이크캘리퍼',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/07/47200c715596bcce41c283de7654170e.png', 'ca_id' => 'P014'],
    ['name' => '엔진마운트',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/ef35247c9c232dcdd744c819b1165c3d.png', 'ca_id' => 'P015'],
    ['name' => '미션마운트',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/62ff3f6bd11547f8920466ab5857e484.png', 'ca_id' => 'P016'],
    ['name' => 'V벨트',                 'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/039ae4c1fd2fd7a3476cad013c44ec7c.png', 'ca_id' => 'P017'],
    ['name' => '댐퍼풀리',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/a479b106c44a907fed73f874219ffcc8.png', 'ca_id' => 'P018'],
    ['name' => '벨트텐셔너',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/0464e3ccf781610aef5992e3e52584f9.png', 'ca_id' => 'P019'],
    ['name' => '워터펌프',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/a72fa4022daea7de867f60d94f2871eb.png', 'ca_id' => 'P020'],
    ['name' => '써머스탯',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/6780f6f25c9e810f2f6c5f3daacc9171.png', 'ca_id' => 'P021'],
    ['name' => '라디에이터 관련',       'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/b5a032d855d3d5e67a209f11e064bfe9.png', 'ca_id' => 'P022'],
    ['name' => '알터네이터',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/cb5b2fd547a858c29c7acb2cbe33f375.png', 'ca_id' => 'P023'],
    ['name' => '에어컨콤프레셔',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/e5193eefb12b0209b9781da558bb94b2.png', 'ca_id' => 'P024'],
    ['name' => '스타트모터',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/58f35144e075f7c6d9ff770b3591a8a2.png', 'ca_id' => 'P025'],
    ['name' => '흡기 매니폴드 관련',    'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2025/09/30/5ac767b494f4788e17ebff2180c7f09b.png', 'ca_id' => 'P026'],
    ['name' => '고압펌프',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/515bf4268bb194484cf8660aed32ff72.png', 'ca_id' => 'P027'],
    ['name' => '인젝터',                'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/d7ff28bbfe981cd2b196bc3679a13116.png', 'ca_id' => 'P028'],
    ['name' => '와이퍼',                'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/09/40895e20cda09def43843192b044104c.png', 'ca_id' => 'P029'],
    ['name' => '드라이브샤프트',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/5c62bf8211c75f49256cdce4a4a2dcee.png', 'ca_id' => 'P030'],
    ['name' => '쇼바',                  'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/36c066aa4771d6995505af6f6cb4a07a.png', 'ca_id' => 'P031'],
    ['name' => '유니버셜조인트',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/7f8ec4a75e1892165b357ec00853f44e.png', 'ca_id' => 'P032'],
    ['name' => '허브베어링',            'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/9a2fa9b4176fddaae08c4b1819150262.png', 'ca_id' => 'P033'],
    ['name' => '휠볼트',                'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/11/%ED%9C%A0%EB%B3%B4%ED%8A%B8.png',      'ca_id' => 'P034'],
    ['name' => '프로펠러샤프트',        'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/25dc65b7d9ec5ffb3e681ca699c96079.png', 'ca_id' => 'P035'],
    ['name' => '하체부품',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/017304d00624d995d62034d436845ac2.png', 'ca_id' => 'P036'],
    ['name' => '산소센서',              'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/9f736b0c6eb0f4b001a03b866227671a.png', 'ca_id' => 'P037'],
    ['name' => '점화플러그(예열) 배선 관련', 'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/09/a47437577d11c9b6510446320a155451.png', 'ca_id' => 'P038'],
    ['name' => '라이트모듈 관련',       'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/0c3e28e579009aa84b201c38653672b4.png', 'ca_id' => 'P039'],
    ['name' => '자동차용품 관련',       'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/kar.png',                               'ca_id' => 'P040'],
    ['name' => '기타 관련',             'img' => '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/logg2.png',                                       'ca_id' => 'P041'],
];

// ── g5_shop_category 에서 실제 파츠 ca_id 목록 조회 (ca_id가 'P'로 시작하는 것) ──
// 실제 DB ca_id와 매핑이 완료되면 위 배열의 ca_id 값을 DB 실제값으로 교체
// 현재는 g5_shop_category에서 ca_name 기준으로 매핑 시도
$_pds_parts_ca_map = [];
$_res = @sql_query("SELECT ca_id, ca_name FROM `" . G5_TABLE_PREFIX . "shop_category` WHERE ca_id LIKE 'P%' AND ca_use = '1' ORDER BY ca_order");
if ($_res) {
    while ($_row = sql_fetch_array($_res)) {
        $_pds_parts_ca_map[$_row['ca_id']] = $_row['ca_name'];
    }
}

// ── 현재 선택된 차종 파라미터 수집 ──────────────────────────────────────
$_pds_cur_brand_id  = isset($pds_brand_id)  ? (int)$pds_brand_id  : 0;
$_pds_cur_series_id = isset($pds_series_id) ? (int)$pds_series_id : 0;
$_pds_cur_model_id  = isset($pds_model_id)  ? (int)$pds_model_id  : 0;

// 차종 선택 상태가 있으면 URL 파라미터 구성 (카테고리 이동 시 차종 유지)
$_pds_car_params = '';
if ($_pds_cur_brand_id) {
    $_pds_car_params .= '&pds_brand_id='  . $_pds_cur_brand_id;
    if ($_pds_cur_series_id) $_pds_car_params .= '&pds_series_id=' . $_pds_cur_series_id;
    if ($_pds_cur_model_id)  $_pds_car_params .= '&pds_model_id='  . $_pds_cur_model_id;
}

// ── 차종 선택 시: 해당 차종에 상품이 있는 파츠 카테고리 ca_id 목록 조회 ──
// item_car 테이블 연동: 해당 차종에 상품이 있는 it_id → 상품의 ca_id 추출
$_pds_available_ca_ids = null; // null = 차종 미선택(전체 활성), array = 활성 ca_id 목록

if ($_pds_cur_brand_id) {
    $_pds_available_ca_ids = [];

    // item_car 테이블 존재 확인
    $_tcheck = @sql_fetch("SELECT COUNT(*) AS cnt FROM information_schema.TABLES 
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . G5_TABLE_PREFIX . "item_car'");

    if (!empty($_tcheck['cnt'])) {
        // 차종 조건에 맞는 it_id 목록 조회
        $_where = [];
        if ($_pds_cur_model_id)       $_where[] = "ic.model_id = " . $_pds_cur_model_id;
        elseif ($_pds_cur_series_id)  $_where[] = "ic.series_id = " . $_pds_cur_series_id;
        elseif ($_pds_cur_brand_id)   $_where[] = "ic.brand_id = " . $_pds_cur_brand_id;

        if ($_where) {
            // 해당 차종 상품들의 카테고리 ID 조회
            $_sql = "SELECT DISTINCT si.ca_id 
                     FROM `" . G5_TABLE_PREFIX . "item_car` ic
                     JOIN `" . G5_TABLE_PREFIX . "shop_item` si ON ic.it_id = si.it_id
                     WHERE " . implode(' AND ', $_where) . "
                     AND si.ca_id LIKE 'P%'";
            $_res2 = @sql_query($_sql);
            if ($_res2) {
                while ($_r = sql_fetch_array($_res2)) {
                    if ($_r['ca_id']) $_pds_available_ca_ids[] = $_r['ca_id'];
                }
            }
        }
    }
}

// 현재 페이지의 ca_id (상품목록 페이지에서 전달)
$_pds_current_ca_id = isset($ca_id) ? $ca_id : (isset($_GET['ca_id']) ? $_GET['ca_id'] : '');
?>

<link rel="stylesheet" href="<?php echo G5_URL; ?>/partsds/css/parts_category.css">

<section class="pds-parts-section">
    <div class="pds-parts-inner">
        <h2 class="pds-parts-title">PARTS</h2>

        <?php if ($_pds_cur_brand_id && $_pds_available_ca_ids !== null): ?>
        <div class="pds-parts-vehicle-notice">
            <?php
            // 차종명 표시
            $_vname = '';
            if ($_pds_cur_brand_id) {
                $_br = @sql_fetch("SELECT brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_id=" . $_pds_cur_brand_id);
                $_vname = $_br['brand_name'] ?? '';
            }
            if ($_pds_cur_series_id) {
                $_sr = @sql_fetch("SELECT series_name FROM `" . G5_TABLE_PREFIX . "car_series` WHERE series_id=" . $_pds_cur_series_id);
                if (!empty($_sr['series_name'])) $_vname .= ' ' . $_sr['series_name'];
            }
            if ($_pds_cur_model_id) {
                $_mr = @sql_fetch("SELECT model_name FROM `" . G5_TABLE_PREFIX . "car_model` WHERE model_id=" . $_pds_cur_model_id);
                if (!empty($_mr['model_name'])) $_vname .= ' ' . $_mr['model_name'];
            }
            ?>
            <i class="fas fa-car"></i>
            <strong><?php echo htmlspecialchars(trim($_vname)); ?></strong> 차량에 해당하는 파츠 카테고리입니다.
            <span class="pds-active-count">(<?php echo count($_pds_available_ca_ids); ?>개 카테고리 상품 있음)</span>
            <a href="<?php echo G5_SHOP_URL; ?>/list.php?ca_id=<?php echo urlencode($_pds_current_ca_id); ?>&pds_no_filter=1" class="pds-notice-clear">
                <i class="fas fa-times-circle"></i> 차종 필터 해제
            </a>
        </div>
        <?php endif; ?>

        <div class="pds-parts-grid" id="pdsPartsGrid">
            <?php foreach ($pds_parts_categories as $_pcat):
                $_ca = $_pcat['ca_id'];
                $_name = $_pcat['name'];
                $_img  = $_pcat['img'];

                // 현재 선택된 카테고리 여부
                $_is_active = ($_pds_current_ca_id && $_pds_current_ca_id === $_ca);

                // 차종 선택 시: 해당 카테고리에 상품 있는지 확인
                $_has_items = true;
                if ($_pds_available_ca_ids !== null) {
                    $_has_items = in_array($_ca, $_pds_available_ca_ids);
                }

                // URL 구성: ca_id + 차종 파라미터
                $_url = G5_SHOP_URL . '/list.php?ca_id=' . urlencode($_ca) . $_pds_car_params;

                // CSS 클래스 구성
                $_cls = 'pds-parts-item';
                if ($_is_active)  $_cls .= ' is-active';
                if (!$_has_items) $_cls .= ' no-items';
            ?>
            <a href="<?php echo $_url; ?>"
               class="<?php echo $_cls; ?>"
               title="<?php echo htmlspecialchars($_name); ?>"
               <?php if (!$_has_items): ?>
               onclick="return confirmNoItems(event, '<?php echo htmlspecialchars($_name, ENT_QUOTES); ?>')"
               <?php endif; ?>>
                <div class="pds-parts-img-wrap">
                    <img src="<?php echo $_img; ?>"
                         alt="<?php echo htmlspecialchars($_name); ?>"
                         loading="lazy"
                         onerror="this.parentNode.innerHTML='<span class=\'pds-no-img\'><?php echo htmlspecialchars($_name); ?></span>'">
                    <?php if ($_is_active): ?>
                    <span class="pds-active-badge"><i class="fas fa-check"></i></span>
                    <?php endif; ?>
                    <?php if (!$_has_items && $_pds_available_ca_ids !== null): ?>
                    <span class="pds-no-item-overlay"><i class="fas fa-minus-circle"></i></span>
                    <?php endif; ?>
                </div>
                <span class="pds-parts-name"><?php echo htmlspecialchars($_name); ?></span>
            </a>
            <?php endforeach; ?>
        </div><!-- //pds-parts-grid -->

        <p class="pds-parts-notice">그 밖의 부품은 문의 주세요 &nbsp;|&nbsp; 이미지는 실제 제품과 다를 수 있으니 제품명을 기준으로 구매 부탁드립니다</p>
    </div>
</section>

<script>
/**
 * 상품 없는 카테고리 클릭 시 안내
 */
function confirmNoItems(e, catName) {
    // 차종 필터 없으면 그냥 이동
    <?php if ($_pds_available_ca_ids === null): ?>
    return true;
    <?php else: ?>
    var msg = '선택하신 차량에는 [' + catName + '] 카테고리의 상품이 등록되어 있지 않습니다.\n\n전체 상품에서 검색하시겠습니까?';
    if (confirm(msg)) {
        // 차종 필터 없이 해당 카테고리로 이동
        var href = e.currentTarget ? e.currentTarget.href : '';
        // pds_brand_id 등 파라미터 제거
        href = href.replace(/&pds_brand_id=[^&]*/g, '').replace(/&pds_series_id=[^&]*/g, '').replace(/&pds_model_id=[^&]*/g, '');
        window.location.href = href;
        return false;
    }
    return false;
    <?php endif; ?>
}
</script>
