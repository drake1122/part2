<?php
/**
 * 파츠디에스 - 메인 브랜드 선택 위젯 (No-AJAX 버전)
 * 경로: /partsds/brand_selector.php
 *
 * 메인 페이지, 쇼핑몰 헤더 등에서 include 해서 사용
 * 시리즈/모델 데이터를 PHP에서 미리 로드 → JavaScript 변수로 삽입 (AJAX 불필요)
 */
if (!defined('_GNUBOARD_') && !defined('_EYOOM_')) exit;

// car_brand 테이블 존재 여부 확인
$_pds_check = @sql_fetch("SELECT COUNT(*) AS cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . G5_TABLE_PREFIX . "car_brand'");
if (empty($_pds_check['cnt'])) return;

// ── 내 차종 저장 처리 (POST) ──────────────────────────────────────────────
if ($is_member && isset($_POST['pds_save_car'])) {
    $sv_brand  = (int)$_POST['pds_brand_id'];
    $sv_series = (int)$_POST['pds_series_id'];
    $sv_model  = (int)$_POST['pds_model_id'];

    $sv_brand_name = $sv_series_name = $sv_model_name = '';
    if ($sv_brand) {
        $r = sql_fetch("SELECT brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_id={$sv_brand}");
        $sv_brand_name = $r['brand_name'];
    }
    if ($sv_series) {
        $r = sql_fetch("SELECT series_name FROM `" . G5_TABLE_PREFIX . "car_series` WHERE series_id={$sv_series}");
        $sv_series_name = $r['series_name'];
    }
    if ($sv_model) {
        $r = sql_fetch("SELECT model_name FROM `" . G5_TABLE_PREFIX . "car_model` WHERE model_id={$sv_model}");
        $sv_model_name = $r['model_name'];
    }
    $mid = sql_escape_string($member['mb_id']);
    sql_query("UPDATE `" . G5_TABLE_PREFIX . "member` SET
        mb_1='" . sql_escape_string($sv_brand_name)  . "',
        mb_2='" . sql_escape_string($sv_series_name) . "',
        mb_3='" . sql_escape_string($sv_model_name)  . "',
        mb_4='{$sv_brand}', mb_5='{$sv_series}', mb_6='{$sv_model}'
        WHERE mb_id='{$mid}'");
    // 저장 후 리다이렉트 방지: 변수 업데이트
    $member['mb_1'] = $sv_brand_name;
    $member['mb_2'] = $sv_series_name;
    $member['mb_3'] = $sv_model_name;
    $member['mb_4'] = $sv_brand;
    $member['mb_5'] = $sv_series;
    $member['mb_6'] = $sv_model;
}

// ── 브랜드 목록 ──────────────────────────────────────────────────────────
$brands = [];
$res = sql_query("SELECT brand_id, brand_name, brand_name_en, brand_logo, ca_id
                  FROM `" . G5_TABLE_PREFIX . "car_brand`
                  WHERE brand_use = 1
                  ORDER BY brand_order, brand_id
                  LIMIT 30");
while ($row = sql_fetch_array($res)) {
    $brands[] = $row;
}

// ── 전체 시리즈 데이터 (브랜드별) ────────────────────────────────────────
$all_series = [];   // brand_id => [ {id, name, ca_id}, ... ]
$res = sql_query("SELECT series_id, brand_id, series_name, ca_id
                  FROM `" . G5_TABLE_PREFIX . "car_series`
                  WHERE series_use = 1
                  ORDER BY series_order, series_id");
while ($row = sql_fetch_array($res)) {
    $bid = (int)$row['brand_id'];
    $all_series[$bid][] = [
        'id'    => (int)$row['series_id'],
        'name'  => $row['series_name'],
        'ca_id' => $row['ca_id'],
    ];
}

// ── 전체 모델 데이터 (시리즈별) ──────────────────────────────────────────
$all_models = [];   // series_id => [ {id, name, ca_id}, ... ]
$res = sql_query("SELECT model_id, series_id, model_name, model_year, ca_id
                  FROM `" . G5_TABLE_PREFIX . "car_model`
                  WHERE model_use = 1
                  ORDER BY model_order, model_id");
while ($row = sql_fetch_array($res)) {
    $sid = (int)$row['series_id'];
    $display_name = $row['model_name'];
    if ($row['model_year']) $display_name .= ' (' . $row['model_year'] . ')';
    $all_models[$sid][] = [
        'id'    => (int)$row['model_id'],
        'name'  => $display_name,
        'ca_id' => $row['ca_id'],
    ];
}

// ── 로그인 회원 저장 차종 ─────────────────────────────────────────────────
$member_car = ['brand_id' => 0, 'series_id' => 0, 'model_id' => 0,
               'brand_name' => '', 'series_name' => '', 'model_name' => ''];
if ($is_member) {
    $member_car = [
        'brand_id'    => (int)$member['mb_4'],
        'series_id'   => (int)$member['mb_5'],
        'model_id'    => (int)$member['mb_6'],
        'brand_name'  => $member['mb_1'],
        'series_name' => $member['mb_2'],
        'model_name'  => $member['mb_3'],
    ];
}

// ── 브랜드 로고 이미지 URL 맵 (CDN 실제 로고 이미지) ─────────────────────
$brand_logo_url_map = [
    '벤츠'    => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/benz_logo.png',
    'BMW'     => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/bmw_logo.png',
    '아우디'  => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/audi_logo.png',
    '포르쉐'  => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/porsche_logo.png',
    '미니'    => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/mini_logo.png',
    '랜드로버'=> 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/landrover_logo.png',
    '폭스바겐'=> 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/vw_logo.png',
    '볼보'    => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/volvo_logo.png',
    '지프'    => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/jeep_logo.png',
    '테슬라'  => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/tesla_logo.png',
    '재규어'  => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/jaguar_logo.png',
    '렉서스'  => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/lexus_logo.png',
    '도요타'  => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/toyota_logo.png',
    '혼다'    => 'https://ecimg.cafe24img.com/pg742b88867550043/min48jjj/web/upload/category/editor/2024/09/06/honda_logo.png',
];

// ── 브랜드 로고 대체 SVG 맵 (CDN 이미지 없을 때 사용) ─────────────────────
$brand_svg_map = [
    '벤츠'    => '<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="45" fill="none" stroke="#999" stroke-width="2"/><line x1="50" y1="5" x2="50" y2="50" stroke="#999" stroke-width="2"/><line x1="50" y1="50" x2="10" y2="78" stroke="#999" stroke-width="2"/><line x1="50" y1="50" x2="90" y2="78" stroke="#999" stroke-width="2"/></svg>',
    'BMW'     => '<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="45" fill="none" stroke="#999" stroke-width="2"/><circle cx="50" cy="50" r="30" fill="none" stroke="#999" stroke-width="1"/><path d="M50 20 L50 50 L20 50" fill="#0066B1"/><path d="M50 50 L50 80 L80 50" fill="#0066B1"/><path d="M50 20 L80 50" fill="white"/><path d="M20 50 L50 80" fill="white"/></svg>',
    '아우디'  => '<svg viewBox="0 0 140 60" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="30" r="18" fill="none" stroke="#999" stroke-width="3"/><circle cx="50" cy="30" r="18" fill="none" stroke="#999" stroke-width="3"/><circle cx="80" cy="30" r="18" fill="none" stroke="#999" stroke-width="3"/><circle cx="110" cy="30" r="18" fill="none" stroke="#999" stroke-width="3"/></svg>',
];
?>

<section class="partsds-brand-section">
    <div class="container">
        <div class="pds-brand-header">
            <h2>BRAND</h2>
            <p>구매하고자 하는 부품의 차량 브랜드를 선택해주세요</p>
        </div>

        <!-- 브랜드 캐러셀 (로고 이미지 슬라이더) -->
        <div class="pds-brand-carousel-wrap">
            <button class="pds-carousel-btn pds-prev" type="button" aria-label="이전">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="pds-brand-carousel-viewport">
                <div class="pds-brand-carousel" id="pdsBrandCarousel">
                    <?php foreach ($brands as $brand): ?>
                    <?php
                        $is_selected = ($member_car['brand_id'] && $member_car['brand_id'] == $brand['brand_id']);
                        $brand_name  = $brand['brand_name'];

                        // 로고 URL 결정: DB brand_logo > 이름 맵 > 텍스트 폴백
                        if (!empty($brand['brand_logo'])) {
                            $logo_url = G5_URL . '/' . ltrim($brand['brand_logo'], '/');
                            $logo_type = 'img';
                        } elseif (isset($brand_logo_url_map[$brand_name])) {
                            $logo_url  = $brand_logo_url_map[$brand_name];
                            $logo_type = 'img';
                        } else {
                            $logo_type = 'text';
                        }
                    ?>
                    <div class="pds-brand-item <?php echo $is_selected ? 'selected' : ''; ?>"
                         data-brand-id="<?php echo (int)$brand['brand_id']; ?>"
                         data-brand-name="<?php echo htmlspecialchars($brand_name); ?>"
                         title="<?php echo htmlspecialchars($brand_name); ?>">
                        <?php if ($logo_type === 'img'): ?>
                            <img src="<?php echo htmlspecialchars($logo_url); ?>"
                                 alt="<?php echo htmlspecialchars($brand_name); ?>"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                            <span class="pds-brand-logo-text" style="display:none;"><?php echo htmlspecialchars($brand_name); ?></span>
                        <?php else: ?>
                            <span class="pds-brand-logo-text"><?php echo htmlspecialchars($brand_name); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="pds-carousel-btn pds-next" type="button" aria-label="다음">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <!-- 3단계 차종 검색 -->
        <div class="pds-car-search-box">
            <div class="pds-car-icon"><i class="fas fa-car"></i></div>
            <p class="pds-car-hint">차량을 선택하면 맞는 부품만 골라드립니다</p>
            <div class="pds-car-selectors">
                <div class="pds-select-wrap" id="pdsStep1Wrap">
                    <select id="pdsSelectBrand" class="pds-select" data-step="1">
                        <option value="">① 브랜드 선택</option>
                        <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo (int)$brand['brand_id']; ?>"
                                data-ca-id="<?php echo htmlspecialchars($brand['ca_id'] ?? ''); ?>"
                                <?php if ($member_car['brand_id'] == $brand['brand_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($brand['brand_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pds-select-wrap" id="pdsStep2Wrap">
                    <select id="pdsSelectSeries" class="pds-select" data-step="2" disabled>
                        <option value="">② 시리즈/연식 선택</option>
                    </select>
                </div>
                <div class="pds-select-wrap" id="pdsStep3Wrap">
                    <select id="pdsSelectModel" class="pds-select" data-step="3" disabled>
                        <option value="">③ 모델 선택</option>
                    </select>
                </div>
                <button type="button" class="pds-search-btn" id="pdsBtnSearch" onclick="pdsDoSearch()">
                    <i class="fas fa-search"></i> 부품 검색
                </button>
            </div>

            <!-- 내 차종 저장 (회원 전용) -->
            <?php if ($is_member): ?>
            <div class="pds-save-car-wrap" id="pdsSaveCarWrap" style="display:none;">
                <label class="pds-save-check">
                    <input type="checkbox" id="pdsSaveMyCar"> 내 차종으로 저장하기
                </label>
            </div>
            <!-- 숨겨진 폼으로 저장 처리 (AJAX 불필요) -->
            <form id="pdsSaveCarForm" method="post" action="" style="display:none;">
                <input type="hidden" name="pds_save_car" value="1">
                <input type="hidden" name="pds_brand_id"  id="pdsFormBrandId"  value="0">
                <input type="hidden" name="pds_series_id" id="pdsFormSeriesId" value="0">
                <input type="hidden" name="pds_model_id"  id="pdsFormModelId"  value="0">
            </form>
            <?php endif; ?>

            <?php if ($is_member && $member_car['brand_name']): ?>
            <div class="pds-my-car-info">
                <i class="fas fa-car-side text-crimson"></i>
                내 차량: <strong><?php echo htmlspecialchars($member_car['brand_name']); ?></strong>
                <?php if ($member_car['series_name']): ?> > <strong><?php echo htmlspecialchars($member_car['series_name']); ?></strong><?php endif; ?>
                <?php if ($member_car['model_name']): ?> > <strong><?php echo htmlspecialchars($member_car['model_name']); ?></strong><?php endif; ?>
                <a href="#" class="pds-reset-car" onclick="pdsResetMyCar(event)">변경</a>
            </div>
            <?php endif; ?>
        </div>

        <!-- 부품번호 직접 검색 -->
        <div class="pds-part-number-search">
            <form name="pds_part_search" action="<?php echo G5_SHOP_URL; ?>/search.php" method="get" onsubmit="return pdsPartSearchSubmit(this);">
                <div class="pds-part-search-inner">
                    <input type="text" name="stx" id="pdsPartInput" placeholder="예) 부품번호" class="pds-part-input">
                    <button type="submit" class="pds-part-search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
(function() {
    'use strict';

    // ── PHP에서 미리 로드한 데이터 (AJAX 불필요) ──────────────────────────
    var PDS_SERIES = <?php echo json_encode($all_series, JSON_UNESCAPED_UNICODE); ?>;
    var PDS_MODELS = <?php echo json_encode($all_models, JSON_UNESCAPED_UNICODE); ?>;
    var memberCar  = <?php echo json_encode($member_car, JSON_UNESCAPED_UNICODE); ?>;

    // ca_id 캐시 (select 선택 추적용)
    var seriesCaMap = {};
    var modelCaMap  = {};

    // ── 캐러셀 ────────────────────────────────────────────────────────────
    var carousel    = document.getElementById('pdsBrandCarousel');
    var itemWidth   = 130; // 아이템 폭 + gap
    var carouselPos = 0;

    function updateCarouselTransform() {
        if (carousel) carousel.style.transform = 'translateX(' + carouselPos + 'px)';
    }

    var prevBtn = document.querySelector('.pds-prev');
    var nextBtn = document.querySelector('.pds-next');
    if (prevBtn) prevBtn.addEventListener('click', function() {
        carouselPos = Math.min(0, carouselPos + itemWidth * 3);
        updateCarouselTransform();
    });
    if (nextBtn) nextBtn.addEventListener('click', function() {
        var visibleCount = Math.floor(carousel.parentElement.offsetWidth / itemWidth);
        var maxScroll = -(Math.max(0, carousel.children.length - visibleCount) * itemWidth);
        carouselPos = Math.max(maxScroll, carouselPos - itemWidth * 3);
        updateCarouselTransform();
    });

    // ── 브랜드 아이템 클릭 ────────────────────────────────────────────────
    var brandItems = document.querySelectorAll('.pds-brand-item');
    brandItems.forEach(function(item) {
        item.addEventListener('click', function() {
            brandItems.forEach(function(i) { i.classList.remove('selected'); });
            this.classList.add('selected');
            var brandId = this.getAttribute('data-brand-id');
            document.getElementById('pdsSelectBrand').value = brandId;
            loadSeries(brandId);
        });
    });

    // ── 브랜드 select 변경 ────────────────────────────────────────────────
    var selBrand = document.getElementById('pdsSelectBrand');
    if (selBrand) selBrand.addEventListener('change', function() {
        var brandId = this.value;
        brandItems.forEach(function(i) {
            i.classList.toggle('selected', i.getAttribute('data-brand-id') == brandId);
        });
        loadSeries(brandId);
    });

    // ── 시리즈 select 변경 ────────────────────────────────────────────────
    var selSeriesEl = document.getElementById('pdsSelectSeries');
    if (selSeriesEl) selSeriesEl.addEventListener('change', function() {
        loadModels(this.value);
    });

    // ── 모델 select 변경 ─────────────────────────────────────────────────
    var selModelEl = document.getElementById('pdsSelectModel');
    if (selModelEl) selModelEl.addEventListener('change', function() {
        var saveWrap = document.getElementById('pdsSaveCarWrap');
        if (saveWrap && this.value) saveWrap.style.display = '';
    });

    // ── 시리즈 로드 (PHP 데이터 사용) ────────────────────────────────────
    function loadSeries(brandId) {
        var selSeries = document.getElementById('pdsSelectSeries');
        var selModel  = document.getElementById('pdsSelectModel');
        selSeries.innerHTML = '<option value="">② 시리즈/연식 선택</option>';
        selSeries.disabled  = true;
        selModel.innerHTML  = '<option value="">③ 모델 선택</option>';
        selModel.disabled   = true;
        seriesCaMap = {};
        modelCaMap  = {};
        if (!brandId) return;

        var list = PDS_SERIES[brandId] || [];
        if (list.length === 0) {
            selSeries.innerHTML = '<option value="">시리즈 데이터 없음</option>';
            return;
        }
        list.forEach(function(s) {
            var opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.name;
            opt.setAttribute('data-ca-id', s.ca_id || '');
            selSeries.appendChild(opt);
            if (s.ca_id) seriesCaMap[s.id] = s.ca_id;
        });
        selSeries.disabled = false;

        // 회원 차종 자동선택
        if (memberCar.brand_id == brandId && memberCar.series_id) {
            selSeries.value = memberCar.series_id;
            loadModels(memberCar.series_id, true);
        }
    }

    // ── 모델 로드 (PHP 데이터 사용) ──────────────────────────────────────
    function loadModels(seriesId, autoSelect) {
        var selModel = document.getElementById('pdsSelectModel');
        selModel.innerHTML = '<option value="">③ 모델 선택</option>';
        selModel.disabled  = true;
        modelCaMap = {};
        if (!seriesId) return;

        var list = PDS_MODELS[seriesId] || [];
        if (list.length === 0) {
            selModel.innerHTML = '<option value="">모델 데이터 없음</option>';
            return;
        }
        list.forEach(function(m) {
            var opt = document.createElement('option');
            opt.value = m.id;
            opt.textContent = m.name;
            opt.setAttribute('data-ca-id', m.ca_id || '');
            selModel.appendChild(opt);
            if (m.ca_id) modelCaMap[m.id] = m.ca_id;
        });
        selModel.disabled = false;

        if (autoSelect && memberCar.model_id) {
            selModel.value = memberCar.model_id;
        }
    }

    // ── 페이지 로드시 회원 차종 자동선택 ─────────────────────────────────
    if (memberCar.brand_id) {
        var selB = document.getElementById('pdsSelectBrand');
        if (selB) {
            selB.value = memberCar.brand_id;
            brandItems.forEach(function(i) {
                i.classList.toggle('selected', i.getAttribute('data-brand-id') == memberCar.brand_id);
            });
            loadSeries(memberCar.brand_id);
        }
    }

    // ── 부품 검색 (ca_id 기반 URL 이동) ──────────────────────────────────
    window.pdsDoSearch = function() {
        var brandId  = document.getElementById('pdsSelectBrand').value;
        var seriesId = document.getElementById('pdsSelectSeries').value;
        var modelId  = document.getElementById('pdsSelectModel').value;

        if (!brandId) { alert('브랜드를 선택해주세요.'); return; }

        // 내 차종 저장 처리 (폼 POST)
        var saveCheck = document.getElementById('pdsSaveMyCar');
        if (saveCheck && saveCheck.checked) {
            document.getElementById('pdsFormBrandId').value  = brandId  || 0;
            document.getElementById('pdsFormSeriesId').value = seriesId || 0;
            document.getElementById('pdsFormModelId').value  = modelId  || 0;
            document.getElementById('pdsSaveCarForm').submit();
            return; // 저장 후 페이지 리로드됨
        }

        // ca_id 결정: 모델 > 시리즈 > 브랜드
        var targetCaId = '';
        if (modelId && modelCaMap[modelId]) {
            targetCaId = modelCaMap[modelId];
        } else if (seriesId && seriesCaMap[seriesId]) {
            targetCaId = seriesCaMap[seriesId];
        } else {
            var brandOpt = document.querySelector('#pdsSelectBrand option[value="' + brandId + '"]');
            targetCaId = brandOpt ? (brandOpt.getAttribute('data-ca-id') || '') : '';
        }

        // 차종 파라미터도 URL에 포함 (상품 목록에서 차종 표시용)
        var carParams = '&pds_brand_id=' + brandId;
        if (seriesId) carParams += '&pds_series_id=' + seriesId;
        if (modelId)  carParams += '&pds_model_id='  + modelId;

        var url;
        if (targetCaId) {
            url = '<?php echo G5_SHOP_URL; ?>/list.php?ca_id=' + encodeURIComponent(targetCaId) + carParams;
        } else {
            var brandOpt2 = document.querySelector('#pdsSelectBrand option[value="' + brandId + '"]');
            var brandCaId = brandOpt2 ? (brandOpt2.getAttribute('data-ca-id') || '') : '';
            if (brandCaId) {
                url = '<?php echo G5_SHOP_URL; ?>/list.php?ca_id=' + encodeURIComponent(brandCaId) + carParams;
            } else {
                alert('해당 차종에 연결된 분류가 없습니다.');
                return;
            }
        }

        window.location.href = url;
    };

    // ── 내 차종 초기화 ─────────────────────────────────────────────────
    window.pdsResetMyCar = function(e) {
        e.preventDefault();
        document.getElementById('pdsSelectBrand').value = '';
        var ss = document.getElementById('pdsSelectSeries');
        var sm = document.getElementById('pdsSelectModel');
        ss.innerHTML = '<option value="">② 시리즈/연식 선택</option>'; ss.disabled = true;
        sm.innerHTML = '<option value="">③ 모델 선택</option>';        sm.disabled = true;
        brandItems.forEach(function(i) { i.classList.remove('selected'); });
        memberCar = {brand_id:0, series_id:0, model_id:0};
        var infoEl = document.querySelector('.pds-my-car-info');
        if (infoEl) infoEl.style.display = 'none';
    };

    // ── 부품번호 검색 폼 제출 처리 ───────────────────────────────────────
    window.pdsPartSearchSubmit = function(frm) {
        var val = frm.stx ? frm.stx.value.trim() : '';
        if (!val) { alert('검색어를 입력해주세요.'); return false; }
        return true;
    };

})();
</script>
