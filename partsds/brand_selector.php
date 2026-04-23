<?php
/**
 * 파츠디에스 - 메인 브랜드 선택 위젯
 * 경로: /partsds/brand_selector.php
 * 
 * 메인 페이지, 쇼핑몰 헤더 등에서 include 해서 사용
 * 이미지 캐러셀 + 3단계(브랜드>시리즈>모델) 연동 검색
 */
if (!defined('_GNUBOARD_') && !defined('_EYOOM_')) exit;

// car_brand 테이블 존재 여부 확인 (DB 미설치 시 오류 방지)
$_pds_check = @sql_fetch("SELECT COUNT(*) AS cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . G5_TABLE_PREFIX . "car_brand'");
if (empty($_pds_check['cnt'])) return;

// 브랜드 목록 로드
$brands = [];
$res = sql_query("SELECT brand_id, brand_name, brand_name_en, brand_logo 
                  FROM `" . G5_TABLE_PREFIX . "car_brand` 
                  WHERE brand_use = 1 
                  ORDER BY brand_order, brand_id 
                  LIMIT 30");
while ($row = sql_fetch_array($res)) {
    $brands[] = $row;
}

// 로그인 회원 저장 차종
$member_car = ['brand_id' => 0, 'series_id' => 0, 'model_id' => 0,
               'brand_name' => '', 'series_name' => '', 'model_name' => ''];
if ($is_member) {
    $mb_row = sql_fetch("SELECT mb_1, mb_2, mb_3, mb_4, mb_5, mb_6 
                         FROM `" . G5_TABLE_PREFIX . "member` 
                         WHERE mb_id = '" . sql_escape_string($member['mb_id']) . "'");
    $member_car = [
        'brand_id'    => (int)$mb_row['mb_4'],
        'series_id'   => (int)$mb_row['mb_5'],
        'model_id'    => (int)$mb_row['mb_6'],
        'brand_name'  => $mb_row['mb_1'],
        'series_name' => $mb_row['mb_2'],
        'model_name'  => $mb_row['mb_3'],
    ];
}

// 브랜드 로고 이미지 맵핑 (이미지 없을 때 기본 텍스트)
$brand_logo_map = [
    '벤츠'    => 'mercedes',
    'BMW'     => 'bmw',
    '아우디'  => 'audi',
    '포르쉐'  => 'porsche',
    '미니'    => 'mini',
    '랜드로버' => 'landrover',
    '폭스바겐' => 'vw',
    '볼보'    => 'volvo',
    '지프'    => 'jeep',
    '테슬라'  => 'tesla',
    '재규어'  => 'jaguar',
    '렉서스'  => 'lexus',
    '도요타'  => 'toyota',
    '혼다'    => 'honda',
];
?>

<section class="partsds-brand-section">
    <div class="container">
        <div class="pds-brand-header">
            <h2>BRAND</h2>
            <p>구매하고자 하는 부품의 차량 브랜드를 선택해주세요</p>
        </div>

        <?php /* 브랜드 캐러셀 */ ?>
        <div class="pds-brand-carousel-wrap">
            <button class="pds-carousel-btn pds-prev" type="button" aria-label="이전">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="pds-brand-carousel-viewport">
                <div class="pds-brand-carousel" id="pdsBrandCarousel">
                    <?php foreach ($brands as $brand): ?>
                    <div class="pds-brand-item <?php if ($member_car['brand_id'] && $member_car['brand_id'] == $brand['brand_id']) echo 'selected'; ?>"
                         data-brand-id="<?php echo (int)$brand['brand_id']; ?>"
                         data-brand-name="<?php echo htmlspecialchars($brand['brand_name']); ?>"
                         title="<?php echo htmlspecialchars($brand['brand_name']); ?>">
                        <?php if ($brand['brand_logo']): ?>
                            <img src="<?php echo G5_URL . '/' . ltrim($brand['brand_logo'], '/'); ?>"
                                 alt="<?php echo htmlspecialchars($brand['brand_name']); ?>">
                        <?php else: ?>
                            <?php
                            $slug = isset($brand_logo_map[$brand['brand_name']]) ? $brand_logo_map[$brand['brand_name']] : '';
                            ?>
                            <div class="pds-brand-logo-text"
                                 data-brand-slug="<?php echo $slug; ?>">
                                <span><?php echo htmlspecialchars($brand['brand_name']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="pds-carousel-btn pds-next" type="button" aria-label="다음">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <?php /* 3단계 차종 검색 박스 */ ?>
        <div class="pds-car-search-box">
            <div class="pds-car-icon"><i class="fas fa-car"></i></div>
            <p class="pds-car-hint">차량을 선택하면 맞는 부품만 골라드립니다</p>
            <div class="pds-car-selectors">
                <div class="pds-select-wrap" id="pdsStep1Wrap">
                    <select id="pdsSelectBrand" class="pds-select" data-step="1">
                        <option value="">① 브랜드 선택</option>
                        <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo (int)$brand['brand_id']; ?>"
                                data-name="<?php echo htmlspecialchars($brand['brand_name']); ?>"
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

            <?php if ($is_member): ?>
            <div class="pds-save-car-wrap" id="pdsSaveCarWrap" style="display:none;">
                <label class="pds-save-check">
                    <input type="checkbox" id="pdsSaveMyCar">
                    내 차종으로 저장하기
                </label>
            </div>
            <?php endif; ?>

            <?php if ($is_member && $member_car['brand_name']): ?>
            <div class="pds-my-car-info">
                <i class="fas fa-car-side text-crimson"></i>
                내 차량: <strong><?php echo htmlspecialchars($member_car['brand_name']); ?></strong>
                <?php if ($member_car['series_name']): ?>
                    > <strong><?php echo htmlspecialchars($member_car['series_name']); ?></strong>
                <?php endif; ?>
                <?php if ($member_car['model_name']): ?>
                    > <strong><?php echo htmlspecialchars($member_car['model_name']); ?></strong>
                <?php endif; ?>
                <a href="#" class="pds-reset-car" onclick="pdsResetMyCar(event)">변경</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
// 파츠디에스 브랜드 선택기 JS
(function() {
    'use strict';

    var PDS_API = '<?php echo G5_URL; ?>/partsds/car_api.php';
    var memberCar = <?php echo json_encode($member_car); ?>;

    // 캐러셀
    var carousel     = document.getElementById('pdsBrandCarousel');
    var itemWidth    = 110; // 아이템 너비+gap
    var carouselPos  = 0;

    function updateCarouselTransform() {
        if (carousel) carousel.style.transform = 'translateX(' + carouselPos + 'px)';
    }

    var prevBtn = document.querySelector('.pds-prev');
    var nextBtn = document.querySelector('.pds-next');
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            carouselPos = Math.min(0, carouselPos + itemWidth * 3);
            updateCarouselTransform();
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            var maxScroll = -(Math.max(0, carousel.children.length - 7) * itemWidth);
            carouselPos = Math.max(maxScroll, carouselPos - itemWidth * 3);
            updateCarouselTransform();
        });
    }

    // 브랜드 아이템 클릭
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

    // 브랜드 select 변경
    var selBrand = document.getElementById('pdsSelectBrand');
    if (selBrand) {
        selBrand.addEventListener('change', function() {
            var brandId = this.value;
            // 캐러셀 동기화
            brandItems.forEach(function(i) {
                i.classList.toggle('selected', i.getAttribute('data-brand-id') == brandId);
            });
            loadSeries(brandId);
        });
    }

    // 시리즈 select 변경
    var selSeries = document.getElementById('pdsSelectSeries');
    if (selSeries) {
        selSeries.addEventListener('change', function() {
            loadModels(this.value);
        });
    }

    // 모델 select 변경
    var selModel = document.getElementById('pdsSelectModel');
    if (selModel) {
        selModel.addEventListener('change', function() {
            var saveWrap = document.getElementById('pdsSaveCarWrap');
            if (saveWrap && this.value) saveWrap.style.display = '';
        });
    }

    function loadSeries(brandId) {
        var selSeries = document.getElementById('pdsSelectSeries');
        var selModel  = document.getElementById('pdsSelectModel');
        selSeries.innerHTML = '<option value="">② 시리즈/연식 선택</option>';
        selSeries.disabled  = true;
        selModel.innerHTML  = '<option value="">③ 모델 선택</option>';
        selModel.disabled   = true;
        if (!brandId) return;

        fetch(PDS_API + '?action=series&brand_id=' + brandId)
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success && res.data.length > 0) {
                    res.data.forEach(function(s) {
                        var opt = document.createElement('option');
                        opt.value = s.id;
                        opt.textContent = s.name;
                        selSeries.appendChild(opt);
                    });
                    selSeries.disabled = false;

                    // 로그인 회원 차종 자동선택
                    if (memberCar.brand_id == brandId && memberCar.series_id) {
                        selSeries.value = memberCar.series_id;
                        loadModels(memberCar.series_id, true);
                    }
                } else {
                    selSeries.innerHTML = '<option value="">시리즈 데이터 없음</option>';
                }
            });
    }

    function loadModels(seriesId, autoSelect) {
        var selModel = document.getElementById('pdsSelectModel');
        selModel.innerHTML = '<option value="">③ 모델 선택</option>';
        selModel.disabled  = true;
        if (!seriesId) return;

        fetch(PDS_API + '?action=models&series_id=' + seriesId)
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success && res.data.length > 0) {
                    res.data.forEach(function(m) {
                        var opt = document.createElement('option');
                        opt.value = m.id;
                        opt.textContent = m.name;
                        selModel.appendChild(opt);
                    });
                    selModel.disabled = false;

                    // 로그인 회원 모델 자동선택
                    if (autoSelect && memberCar.model_id) {
                        selModel.value = memberCar.model_id;
                    }
                } else {
                    selModel.innerHTML = '<option value="">모델 데이터 없음</option>';
                }
            });
    }

    // 페이지 로드시 회원 차종 자동선택
    if (memberCar.brand_id) {
        var selBrand = document.getElementById('pdsSelectBrand');
        if (selBrand) {
            selBrand.value = memberCar.brand_id;
            brandItems.forEach(function(i) {
                i.classList.toggle('selected', i.getAttribute('data-brand-id') == memberCar.brand_id);
            });
            loadSeries(memberCar.brand_id);
        }
    }

    // 전역 함수 등록
    window.pdsDoSearch = function() {
        var brandId  = document.getElementById('pdsSelectBrand').value;
        var seriesId = document.getElementById('pdsSelectSeries').value;
        var modelId  = document.getElementById('pdsSelectModel').value;

        if (!brandId) { alert('브랜드를 선택해주세요.'); return; }

        // 내 차종 저장 체크
        var saveCheck = document.getElementById('pdsSaveMyCar');
        if (saveCheck && saveCheck.checked) {
            pdsSaveMyCar(brandId, seriesId, modelId);
        }

        // 검색 URL 생성 - 그누보드 쇼핑몰 리스트로 이동 (ca_id 기반)
        // 브랜드 > 그누보드 상품 카테고리로 매핑 (쿼리스트링으로 차종 전달)
        var url = '<?php echo G5_SHOP_URL; ?>/list.php?pds_brand=' + brandId;
        if (seriesId) url += '&pds_series=' + seriesId;
        if (modelId)  url += '&pds_model='  + modelId;

        window.location.href = url;
    };

    window.pdsSaveMyCar = function(brandId, seriesId, modelId) {
        var formData = new FormData();
        formData.append('brand_id',  brandId  || 0);
        formData.append('series_id', seriesId || 0);
        formData.append('model_id',  modelId  || 0);
        fetch(PDS_API + '?action=save_member_car', {
            method: 'POST',
            body: formData
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                console.log('차종 저장 완료:', res.data);
            }
        });
    };

    window.pdsResetMyCar = function(e) {
        e.preventDefault();
        document.getElementById('pdsSelectBrand').value = '';
        var selSeries = document.getElementById('pdsSelectSeries');
        var selModel  = document.getElementById('pdsSelectModel');
        selSeries.innerHTML = '<option value="">② 시리즈/연식 선택</option>';
        selSeries.disabled  = true;
        selModel.innerHTML  = '<option value="">③ 모델 선택</option>';
        selModel.disabled   = true;
        brandItems.forEach(function(i) { i.classList.remove('selected'); });
        memberCar = {brand_id:0, series_id:0, model_id:0};
        var infoEl = document.querySelector('.pds-my-car-info');
        if (infoEl) infoEl.style.display = 'none';
    };

})();
</script>
