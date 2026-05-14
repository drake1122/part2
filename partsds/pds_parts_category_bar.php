<?php
/**
 * file: /partsds/pds_parts_category_bar.php
 * 파츠 카테고리 이미지 그리드 바 — 상품 리스트 / 검색 결과 상단에 삽입
 *
 * include 방법:
 *   $pds_bar_file = G5_PATH . '/partsds/pds_parts_category_bar.php';
 *   if (file_exists($pds_bar_file)) include($pds_bar_file);
 *
 * 현재 URL 파라미터(ca_id, stx 등)를 유지한 채 ca_id(5001~5041) 링크를 생성합니다.
 */
if (!defined('_GNUBOARD_') && !defined('_EYOOM_')) exit;

/* ── 카테고리 이미지 BASE URL ─────────────────────────────────────────── */
define('PDS_CAT_IMG_BASE', '//ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor');

/* ── 파츠종류 목록 (ca_id2 코드 → 이미지 + 표시명) ─────────────────── */
$pds_cat_items = array(
    array('ca_id'=>'5001', 'name'=>'오일필터',         'img'=>'2024/09/06/3d7bf681d7979f8c353deb988eca1cb1.png'),
    array('ca_id'=>'5002', 'name'=>'에어필터',         'img'=>'2024/09/06/f8bb08e3ef6b2cabea71c85ced3c278c.png'),
    array('ca_id'=>'5003', 'name'=>'에어컨필터',       'img'=>'2024/09/06/4d15b07280f8c733b4b2e367e3a2bf68.png'),
    array('ca_id'=>'5004', 'name'=>'연료필터',         'img'=>'2024/09/11/80d524d2ead5ecf3ba1d30f89a7cfad7.png'),
    array('ca_id'=>'5005', 'name'=>'미션오일필터',     'img'=>'2024/09/06/df38071e2a6a7f0f9a96aaf54a016a42.png'),
    array('ca_id'=>'5006', 'name'=>'오일필터하우징',   'img'=>'2024/09/06/66580b342b4b2eb5f27e294e35004a14.png'),
    array('ca_id'=>'5007', 'name'=>'미션오일',         'img'=>'2024/09/06/2e2f7417b690b5507f4ba3069d6f36fb.png'),
    array('ca_id'=>'5008', 'name'=>'엔진오일',         'img'=>'2024/09/06/91af54e1b49990ff928a9ff5540e10eb.png'),
    array('ca_id'=>'5009', 'name'=>'부동액',           'img'=>'2024/09/06/7801fe711fe6da296403b50a552542f6.png'),
    array('ca_id'=>'5010', 'name'=>'브레이크오일',     'img'=>'2024/09/11/3e840e76a0433dcf04111685b2c16ac9.png'),
    array('ca_id'=>'5011', 'name'=>'브레이크디스크',   'img'=>'2024/09/11/ea7c7b751ed5cb4ff5eb187630d65d76.png'),
    array('ca_id'=>'5012', 'name'=>'브레이크패드',     'img'=>'2024/09/06/f6e679d16cd532a616b67f015fbd6926.png'),
    array('ca_id'=>'5013', 'name'=>'브레이크센서',     'img'=>'2024/09/06/a661ac58f6968301d1a5cc1c2533cf9f.png'),
    array('ca_id'=>'5014', 'name'=>'브레이크캘리퍼',   'img'=>'2024/09/07/47200c715596bcce41c283de7654170e.png'),
    array('ca_id'=>'5015', 'name'=>'엔진마운트',       'img'=>'2024/09/06/ef35247c9c232dcdd744c819b1165c3d.png'),
    array('ca_id'=>'5016', 'name'=>'미션마운트',       'img'=>'2024/09/06/62ff3f6bd11547f8920466ab5857e484.png'),
    array('ca_id'=>'5017', 'name'=>'V벨트',            'img'=>'2024/09/06/039ae4c1fd2fd7a3476cad013c44ec7c.png'),
    array('ca_id'=>'5018', 'name'=>'댐퍼풀리',         'img'=>'2024/09/06/a479b106c44a907fed73f874219ffcc8.png'),
    array('ca_id'=>'5019', 'name'=>'벨트텐셔너',       'img'=>'2024/09/06/0464e3ccf781610aef5992e3e52584f9.png'),
    array('ca_id'=>'5020', 'name'=>'워터펌프',         'img'=>'2024/09/11/a72fa4022daea7de867f60d94f2871eb.png'),
    array('ca_id'=>'5021', 'name'=>'써머스탯',         'img'=>'2024/09/11/6780f6f25c9e810f2f6c5f3daacc9171.png'),
    array('ca_id'=>'5022', 'name'=>'라디에이터 관련',  'img'=>'2024/09/06/b5a032d855d3d5e67a209f11e064bfe9.png'),
    array('ca_id'=>'5023', 'name'=>'알터네이터',       'img'=>'2024/09/06/cb5b2fd547a858c29c7acb2cbe33f375.png'),
    array('ca_id'=>'5024', 'name'=>'에어컨콤프레셔',   'img'=>'2024/09/06/e5193eefb12b0209b9781da558bb94b2.png'),
    array('ca_id'=>'5025', 'name'=>'스타트모터',       'img'=>'2024/09/06/58f35144e075f7c6d9ff770b3591a8a2.png'),
    array('ca_id'=>'5026', 'name'=>'흡기 매니폴드 관련','img'=>'2025/09/30/5ac767b494f4788e17ebff2180c7f09b.png'),
    array('ca_id'=>'5027', 'name'=>'고압펌프',         'img'=>'2024/09/06/515bf4268bb194484cf8660aed32ff72.png'),
    array('ca_id'=>'5028', 'name'=>'인젝터',           'img'=>'2024/09/06/d7ff28bbfe981cd2b196bc3679a13116.png'),
    array('ca_id'=>'5029', 'name'=>'와이퍼',           'img'=>'2024/09/09/40895e20cda09def43843192b044104c.png'),
    array('ca_id'=>'5030', 'name'=>'드라이브샤프트',   'img'=>'2024/09/06/5c62bf8211c75f49256cdce4a4a2dcee.png'),
    array('ca_id'=>'5031', 'name'=>'쇼바',             'img'=>'2024/09/06/36c066aa4771d6995505af6f6cb4a07a.png'),
    array('ca_id'=>'5032', 'name'=>'유니버셜조인트',   'img'=>'2024/09/06/7f8ec4a75e1892165b357ec00853f44e.png'),
    array('ca_id'=>'5033', 'name'=>'허브베어링',       'img'=>'2024/09/11/9a2fa9b4176fddaae08c4b1819150262.png'),
    array('ca_id'=>'5034', 'name'=>'휠볼트',           'img'=>'2024/09/11/%ED%9C%A0%EB%B3%B4%ED%8A%B8.png'),
    array('ca_id'=>'5035', 'name'=>'프로펠러샤프트',   'img'=>'2024/09/06/25dc65b7d9ec5ffb3e681ca699c96079.png'),
    array('ca_id'=>'5036', 'name'=>'하체부품',         'img'=>'2024/09/06/017304d00624d995d62034d436845ac2.png'),
    array('ca_id'=>'5037', 'name'=>'산소센서',         'img'=>'2024/09/06/9f736b0c6eb0f4b001a03b866227671a.png'),
    array('ca_id'=>'5038', 'name'=>'점화플러그(예열) 배선 관련','img'=>'2024/09/09/a47437577d11c9b6510446320a155451.png'),
    array('ca_id'=>'5039', 'name'=>'라이트모듈 관련',  'img'=>'2024/09/06/0c3e28e579009aa84b201c38653672b4.png'),
    array('ca_id'=>'5040', 'name'=>'자동차용품 관련',  'img'=>'2024/09/06/kar.png'),
    array('ca_id'=>'5041', 'name'=>'기타 관련',        'img'=>'logg2.png'),
);

/* ── 현재 선택된 ca_id (상품 리스트 페이지의 경우) ─────────────────── */
$pds_bar_active_ca = isset($ca_id) ? $ca_id : (isset($_GET['ca_id']) ? $_GET['ca_id'] : '');

/* ── 검색 결과 페이지인지 여부 ──────────────────────────────────────── */
$pds_bar_is_search = (isset($_GET['stx']) && $_GET['stx'] !== '');

/* ── 링크 생성 함수 ─────────────────────────────────────────────────── */
function pds_cat_link($ca_id_val) {
    // 상품 리스트 페이지로 이동: /shop/?ca_id=5001
    return G5_SHOP_URL . '/?ca_id=' . $ca_id_val;
}
?>

<?php /* ── 파츠 카테고리 그리드 바 출력 ──────────────────────────────── */ ?>
<div class="pds-cat-bar-wrap">
    <p class="pds-cat-bar-title">PARTS</p>
    <div class="pds-cat-bar-grid">
        <?php foreach ($pds_cat_items as $pds_cat): ?>
        <?php $pds_cat_active = ($pds_bar_active_ca === $pds_cat['ca_id']) ? ' active' : ''; ?>
        <a href="<?php echo pds_cat_link($pds_cat['ca_id']); ?>" class="pds-cat-item<?php echo $pds_cat_active; ?>">
            <img src="<?php echo PDS_CAT_IMG_BASE . '/' . $pds_cat['img']; ?>"
                 alt="<?php echo htmlspecialchars($pds_cat['name']); ?>"
                 loading="lazy">
            <span><?php echo htmlspecialchars($pds_cat['name']); ?></span>
        </a>
        <?php endforeach; ?>
    </div>
    <p class="pds-cat-bar-note">
        그 외의 부품은 문의 주세요&nbsp;&nbsp;|&nbsp;&nbsp;이미지는 실제 제품과 다를 수 있으나, 제품명을 기준으로 구매 부탁드립니다
    </p>
</div>

<style>
/* ── PDS 파츠 카테고리 바 ────────────────────────────────────────── */
.pds-cat-bar-wrap {
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 6px;
    padding: 20px 20px 14px;
    margin-bottom: 30px;
}
.pds-cat-bar-title {
    font-size: 1rem;
    font-weight: 700;
    color: #222;
    letter-spacing: .08em;
    margin: 0 0 14px;
    padding-bottom: 10px;
    border-bottom: 2px solid #222;
}
.pds-cat-bar-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 6px 4px;
}
@media (max-width: 1199px) {
    .pds-cat-bar-grid { grid-template-columns: repeat(5, 1fr); }
}
@media (max-width: 767px) {
    .pds-cat-bar-grid { grid-template-columns: repeat(4, 1fr); }
}
@media (max-width: 479px) {
    .pds-cat-bar-grid { grid-template-columns: repeat(3, 1fr); }
}
.pds-cat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 8px 4px 7px;
    border: 1px solid #eee;
    border-radius: 5px;
    text-decoration: none;
    color: #444;
    font-size: 0.72rem;
    text-align: center;
    transition: border-color .18s, background .18s, color .18s;
    background: #fafafa;
    line-height: 1.35;
    word-break: keep-all;
}
.pds-cat-item:hover,
.pds-cat-item.active {
    border-color: #b8860b;
    background: #fffbf0;
    color: #b8860b;
    text-decoration: none;
}
.pds-cat-item img {
    width: 56px;
    height: 56px;
    object-fit: contain;
    margin-bottom: 5px;
    display: block;
}
@media (max-width: 767px) {
    .pds-cat-item img { width: 44px; height: 44px; }
    .pds-cat-item { font-size: 0.67rem; }
}
.pds-cat-bar-note {
    font-size: 0.75rem;
    color: #888;
    text-align: center;
    margin: 14px 0 0;
    padding-top: 10px;
    border-top: 1px solid #f0f0f0;
}
</style>
