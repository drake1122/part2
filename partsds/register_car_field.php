<?php
/**
 * 파츠디에스 - 회원가입/수정 추가 필드 HTML
 * 경로: /partsds/register_car_field.php
 *
 * ┌── 회원 유형 구분 ────────────────────────────────────────┐
 * │  mb_7  = 'normal' | 'business'  (일반 / 사업자)        │
 * │  mb_8  = 사업자등록번호                                  │
 * │  mb_9  = 업체명                                         │
 * │  mb_10 = 담당자명                                        │
 * └──────────────────────────────────────────────────────────┘
 * ┌── 차종 정보 ─────────────────────────────────────────────┐
 * │  mb_1  = 브랜드명  / mb_4 = brand_id                    │
 * │  mb_2  = 시리즈명  / mb_5 = series_id                   │
 * │  mb_3  = 모델명    / mb_6 = model_id                    │
 * └──────────────────────────────────────────────────────────┘
 */
if (!defined('_GNUBOARD_')) exit;

/**
 * 이윰빌더 테마용 (메인 함수)
 * No-AJAX: PHP에서 모든 데이터를 미리 로드 → JS 변수로 삽입
 */
function partsds_car_field_html_eyoom($member = []) {
    global $g5;

    // ── 저장된 차종/회원유형 읽기 ──
    $saved_brand_id    = isset($member['mb_4'])  ? (int)$member['mb_4']       : 0;
    $saved_series_id   = isset($member['mb_5'])  ? (int)$member['mb_5']       : 0;
    $saved_model_id    = isset($member['mb_6'])  ? (int)$member['mb_6']       : 0;
    $saved_brand_name  = isset($member['mb_1'])  ? htmlspecialchars($member['mb_1'])  : '';
    $saved_series_name = isset($member['mb_2'])  ? htmlspecialchars($member['mb_2'])  : '';
    $saved_model_name  = isset($member['mb_3'])  ? htmlspecialchars($member['mb_3'])  : '';
    $saved_mb_type     = isset($member['mb_7'])  ? $member['mb_7']             : 'normal';
    $saved_biz_no      = isset($member['mb_8'])  ? htmlspecialchars($member['mb_8'])  : '';
    $saved_biz_name    = isset($member['mb_9'])  ? htmlspecialchars($member['mb_9'])  : '';
    $saved_biz_ceo     = isset($member['mb_10']) ? htmlspecialchars($member['mb_10']) : '';

    // ── 브랜드 목록 ──
    $brands = [];
    $res = sql_query("SELECT brand_id, brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_use = 1 ORDER BY brand_order, brand_id");
    while ($row = sql_fetch_array($res)) { $brands[] = $row; }

    // ── 전체 시리즈 데이터 (No-AJAX용) ──
    $all_series = [];
    $res = sql_query("SELECT series_id, brand_id, series_name FROM `" . G5_TABLE_PREFIX . "car_series` WHERE series_use = 1 ORDER BY series_order, series_id");
    while ($row = sql_fetch_array($res)) {
        $all_series[(int)$row['brand_id']][] = ['id' => (int)$row['series_id'], 'name' => $row['series_name']];
    }

    // ── 전체 모델 데이터 (No-AJAX용) ──
    $all_models = [];
    $res = sql_query("SELECT model_id, series_id, model_name, model_year FROM `" . G5_TABLE_PREFIX . "car_model` WHERE model_use = 1 ORDER BY model_order, model_id");
    while ($row = sql_fetch_array($res)) {
        $display = $row['model_name'] . ($row['model_year'] ? ' (' . $row['model_year'] . ')' : '');
        $all_models[(int)$row['series_id']][] = ['id' => (int)$row['model_id'], 'name' => $display];
    }

    ob_start();
?>
<!-- ═══════════════════════════════════════════════════════════════
     파츠디에스 회원가입 추가 필드 v2 (이윰빌더 테마)
     ═══════════════════════════════════════════════════════════════ -->
<style>
/* ═══ 회원 유형 탭 ═══ */
.pds-section        { margin-bottom: 24px; }
.pds-section-title  { font-size: 14px; font-weight: 700; color: #333; margin-bottom: 12px;
                       padding-bottom: 8px; border-bottom: 2px solid #c0392b; display: flex;
                       align-items: center; gap: 8px; }
.pds-section-title i { color: #c0392b; }
/* 탭 */
.pds-type-tabs      { display: flex; border: 1.5px solid #ddd; border-radius: 8px; overflow: hidden;
                       margin-bottom: 14px; }
.pds-type-tab       { flex: 1; padding: 13px 10px; text-align: center; cursor: pointer;
                       font-size: 14px; font-weight: 600; background: #f8f9fa; color: #777;
                       border: none; transition: all .2s; letter-spacing: 0; }
.pds-type-tab:first-child { border-right: 1.5px solid #ddd; }
.pds-type-tab.pds-active  { background: #c0392b; color: #fff; }
.pds-type-tab:not(.pds-active):hover { background: #f0f0f0; }
/* 사업자 정보 */
.pds-biz-box        { display: none; background: #fdf5f5; border: 1.5px solid #f0b8b8;
                       border-radius: 8px; padding: 16px; margin-bottom: 14px; }
.pds-biz-box.pds-show { display: block; }
.pds-biz-row        { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; flex-wrap: wrap; }
.pds-biz-label      { font-size: 13px; color: #555; font-weight: 600; min-width: 100px; }
.pds-biz-input      { flex: 1; min-width: 180px; height: 40px; border: 1.5px solid #ddd;
                       border-radius: 6px; padding: 0 12px; font-size: 14px;
                       transition: border-color .2s; }
.pds-biz-input:focus { outline: none; border-color: #c0392b; }
.pds-biz-input.pds-error { border-color: #c0392b; background: #fff5f5; }
.pds-biz-notice     { font-size: 12px; color: #999; margin-top: 6px; }
.pds-biz-notice i   { color: #c0392b; }
/* 차종 선택 */
.pds-car-box        { background: #f8f9fa; border: 1.5px solid #e0e3e8; border-radius: 8px;
                       padding: 16px; }
.pds-car-selects    { display: flex; gap: 8px; flex-wrap: wrap; }
.pds-car-sel        { flex: 1; min-width: 140px; height: 42px; border: 1.5px solid #ddd;
                       border-radius: 6px; padding: 0 10px; font-size: 14px; background: #fff;
                       transition: border-color .2s; }
.pds-car-sel:focus  { outline: none; border-color: #c0392b; }
.pds-car-sel:disabled { background: #f5f5f5; color: #aaa; cursor: not-allowed; }
.pds-car-badge      { display: none; margin-top: 10px; padding: 7px 14px; background: #e8f5e9;
                       border: 1px solid #a5d6a7; border-radius: 20px; font-size: 12px;
                       color: #2e7d32; font-weight: 600; }
.pds-car-badge.pds-show { display: inline-flex; align-items: center; gap: 6px; }
.pds-car-notice     { font-size: 12px; color: #999; margin-top: 8px; }
.pds-car-notice i   { color: #c0392b; }
/* 회원 유형 뱃지 */
.pds-type-desc      { font-size: 12px; color: #888; margin-top: 8px; padding: 8px 12px;
                       background: #f9f9f9; border-radius: 4px; border-left: 3px solid #c0392b; }
</style>

<?php /* ══ 1. 회원 유형 ══ */ ?>
<div class="row">
    <section class="col-lg-12">
        <label class="label">
            회원 유형 <strong class="text-crimson">*</strong>
        </label>
        <div class="pds-section">
            <input type="hidden" name="mb_7" id="pdsMbType" value="<?php echo htmlspecialchars($saved_mb_type); ?>">

            <div class="pds-type-tabs">
                <button type="button"
                        id="pdsTabNormal"
                        class="pds-type-tab <?php echo ($saved_mb_type !== 'business') ? 'pds-active' : ''; ?>"
                        onclick="pdsSetMemberType('normal')">
                    <i class="fas fa-user" style="margin-right:5px;"></i> 일반 회원
                </button>
                <button type="button"
                        id="pdsTabBusiness"
                        class="pds-type-tab <?php echo ($saved_mb_type === 'business') ? 'pds-active' : ''; ?>"
                        onclick="pdsSetMemberType('business')">
                    <i class="fas fa-building" style="margin-right:5px;"></i> 사업자 회원
                </button>
            </div>

            <div id="pdsTypeDesc" class="pds-type-desc">
                <?php if ($saved_mb_type === 'business'): ?>
                🏢 <strong>사업자 회원</strong>: 관리자 승인 후 도매가 및 추가 혜택이 적용됩니다.
                <?php else: ?>
                👤 <strong>일반 회원</strong>: 기본 서비스를 이용하실 수 있습니다.
                <?php endif; ?>
            </div>

            <?php /* ══ 2. 사업자 정보 ══ */ ?>
            <div id="pdsBizBox" class="pds-biz-box <?php echo ($saved_mb_type === 'business') ? 'pds-show' : ''; ?>">
                <div class="pds-biz-row">
                    <span class="pds-biz-label">사업자등록번호 <span style="color:#c0392b;">*</span></span>
                    <input type="text" class="pds-biz-input" name="mb_8" id="pdsBizNo"
                           value="<?php echo $saved_biz_no; ?>"
                           placeholder="000-00-00000" maxlength="20"
                           oninput="pdsFormatBizNo(this)">
                </div>
                <div class="pds-biz-row">
                    <span class="pds-biz-label">업체명 <span style="color:#c0392b;">*</span></span>
                    <input type="text" class="pds-biz-input" name="mb_9" id="pdsBizName"
                           value="<?php echo $saved_biz_name; ?>"
                           placeholder="업체명을 입력하세요" maxlength="100">
                </div>
                <div class="pds-biz-row">
                    <span class="pds-biz-label">담당자명</span>
                    <input type="text" class="pds-biz-input" name="mb_10" id="pdsBizCeo"
                           value="<?php echo $saved_biz_ceo; ?>"
                           placeholder="담당자 이름 (선택)" maxlength="50">
                </div>
                <p class="pds-biz-notice">
                    <i class="fas fa-info-circle"></i>
                    사업자등록번호는 하이픈(-) 포함 형식(000-00-00000)으로 입력해주세요.<br>
                    사업자 회원은 관리자 검토 후 도매가 등 추가 혜택이 적용됩니다.
                </p>
            </div>
        </div>
    </section>
</div>

<?php /* ══ 3. 차종 선택 ══ */ ?>
<div class="row">
    <section class="col-lg-12">
        <label class="label">
            내 차량 선택
            <small style="font-size:12px; color:#888; font-weight:normal;">(선택사항 – 등록 시 자동 필터 적용)</small>
        </label>
        <div class="pds-car-box">
            <div class="pds-car-selects">
                <!-- 브랜드 -->
                <select id="eyRegSelectBrand" name="pds_brand_id" class="pds-car-sel">
                    <option value="">① 브랜드 선택</option>
                    <?php foreach ($brands as $b): ?>
                    <option value="<?php echo (int)$b['brand_id']; ?>"
                            <?php if ($saved_brand_id == $b['brand_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($b['brand_name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <!-- 시리즈 -->
                <select id="eyRegSelectSeries" name="pds_series_id" class="pds-car-sel"
                        <?php if (!$saved_brand_id) echo 'disabled'; ?>>
                    <option value="">② 시리즈/연식 선택</option>
                    <?php if ($saved_brand_id && !empty($all_series[$saved_brand_id])): ?>
                        <?php foreach ($all_series[$saved_brand_id] as $s): ?>
                        <option value="<?php echo $s['id']; ?>"
                                <?php if ($saved_series_id == $s['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($s['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <!-- 모델 -->
                <select id="eyRegSelectModel" name="pds_model_id" class="pds-car-sel"
                        <?php if (!$saved_series_id) echo 'disabled'; ?>>
                    <option value="">③ 모델 선택</option>
                    <?php if ($saved_series_id && !empty($all_models[$saved_series_id])): ?>
                        <?php foreach ($all_models[$saved_series_id] as $m): ?>
                        <option value="<?php echo $m['id']; ?>"
                                <?php if ($saved_model_id == $m['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($m['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- 숨김 필드: 차종명 (pds_* 네임스페이스) -->
            <input type="hidden" id="eyRegBrandName"  name="pds_brand_name"  value="<?php echo $saved_brand_name; ?>">
            <input type="hidden" id="eyRegSeriesName" name="pds_series_name" value="<?php echo $saved_series_name; ?>">
            <input type="hidden" id="eyRegModelName"  name="pds_model_name"  value="<?php echo $saved_model_name; ?>">
            <!-- 숨김 필드: 그누보드 mb_1~6 직접 매핑 (register_form_update.php 가 읽는 필드) -->
            <input type="hidden" id="eyRegMb1"  name="mb_1"  value="<?php echo $saved_brand_name; ?>">
            <input type="hidden" id="eyRegMb2"  name="mb_2"  value="<?php echo $saved_series_name; ?>">
            <input type="hidden" id="eyRegMb3"  name="mb_3"  value="<?php echo $saved_model_name; ?>">
            <input type="hidden" id="eyRegMb4"  name="mb_4"  value="<?php echo $saved_brand_id; ?>">
            <input type="hidden" id="eyRegMb5"  name="mb_5"  value="<?php echo $saved_series_id; ?>">
            <input type="hidden" id="eyRegMb6"  name="mb_6"  value="<?php echo $saved_model_id; ?>">

            <!-- 선택 뱃지 -->
            <div id="eyCarBadge" class="pds-car-badge <?php echo $saved_brand_name ? 'pds-show' : ''; ?>">
                <i class="fas fa-car"></i>
                <?php
                echo $saved_brand_name;
                if ($saved_series_name) echo ' &rsaquo; ' . $saved_series_name;
                if ($saved_model_name)  echo ' &rsaquo; ' . $saved_model_name;
                ?>
            </div>

            <p class="pds-car-notice">
                <i class="fas fa-info-circle"></i>
                차종 등록 시 로그인 후 자동으로 해당 차종에 맞는 부품이 필터링됩니다.
                (나중에 프로필에서 변경 가능)
            </p>
        </div>
    </section>
</div>

<script>
(function(){
    'use strict';

    // ── No-AJAX 데이터 ──
    var PDS_SERIES = <?php echo json_encode($all_series, JSON_UNESCAPED_UNICODE); ?>;
    var PDS_MODELS = <?php echo json_encode($all_models, JSON_UNESCAPED_UNICODE); ?>;

    var selBrand  = document.getElementById('eyRegSelectBrand');
    var selSeries = document.getElementById('eyRegSelectSeries');
    var selModel  = document.getElementById('eyRegSelectModel');
    var badge     = document.getElementById('eyCarBadge');
    var inBrand   = document.getElementById('eyRegBrandName');
    var inSeries  = document.getElementById('eyRegSeriesName');
    var inModel   = document.getElementById('eyRegModelName');
    // 그누보드 mb_1~6 동기화 숨김 필드
    var inMb1 = document.getElementById('eyRegMb1');
    var inMb2 = document.getElementById('eyRegMb2');
    var inMb3 = document.getElementById('eyRegMb3');
    var inMb4 = document.getElementById('eyRegMb4');
    var inMb5 = document.getElementById('eyRegMb5');
    var inMb6 = document.getElementById('eyRegMb6');

    function syncMbFields() {
        if (inMb1) inMb1.value = inBrand.value;
        if (inMb2) inMb2.value = inSeries.value;
        if (inMb3) inMb3.value = inModel.value;
        if (inMb4) inMb4.value = selBrand.value  || '';
        if (inMb5) inMb5.value = selSeries.value || '';
        if (inMb6) inMb6.value = selModel.value  || '';
    }

    // ── 브랜드 변경 ──
    selBrand.addEventListener('change', function() {
        var bid = this.value;
        inBrand.value  = bid ? this.options[this.selectedIndex].text : '';
        inSeries.value = '';
        inModel.value  = '';

        pdsResetSelect(selSeries, '② 시리즈/연식 선택', true);
        pdsResetSelect(selModel,  '③ 모델 선택', true);
        syncMbFields();
        updateBadge();

        if (!bid) return;
        var list = PDS_SERIES[bid] || [];
        list.forEach(function(s) {
            var o = new Option(s.name, s.id);
            selSeries.add(o);
        });
        selSeries.disabled = (list.length === 0);
    });

    // ── 시리즈 변경 ──
    selSeries.addEventListener('change', function() {
        var sid = this.value;
        inSeries.value = sid ? this.options[this.selectedIndex].text : '';
        inModel.value  = '';

        pdsResetSelect(selModel, '③ 모델 선택', true);
        syncMbFields();
        updateBadge();

        if (!sid) return;
        var list = PDS_MODELS[sid] || [];
        list.forEach(function(m) {
            var o = new Option(m.name, m.id);
            selModel.add(o);
        });
        selModel.disabled = (list.length === 0);
    });

    // ── 모델 변경 ──
    selModel.addEventListener('change', function() {
        inModel.value = this.value ? this.options[this.selectedIndex].text : '';
        syncMbFields();
        updateBadge();
    });

    // ── 셀렉트 초기화 ──
    function pdsResetSelect(sel, placeholder, disable) {
        sel.innerHTML = '<option value="">' + placeholder + '</option>';
        sel.disabled  = !!disable;
    }

    // ── 선택 뱃지 업데이트 ──
    function updateBadge() {
        var bn = inBrand.value;
        var sn = inSeries.value;
        var mn = inModel.value;
        if (bn) {
            badge.innerHTML = '<i class="fas fa-car"></i> ' + bn +
                (sn ? ' <span style="color:#888;">&rsaquo;</span> ' + sn : '') +
                (mn ? ' <span style="color:#888;">&rsaquo;</span> ' + mn : '');
            badge.classList.add('pds-show');
        } else {
            badge.classList.remove('pds-show');
            badge.innerHTML = '';
        }
    }

    // ── 전역 노출 ──
    window.pdsUpdateCarBadge = updateBadge;
})();

// ── 회원 유형 전환 ──
function pdsSetMemberType(type) {
    document.getElementById('pdsMbType').value = type;

    var tabNormal   = document.getElementById('pdsTabNormal');
    var tabBusiness = document.getElementById('pdsTabBusiness');
    var bizBox      = document.getElementById('pdsBizBox');
    var typeDesc    = document.getElementById('pdsTypeDesc');

    if (type === 'business') {
        tabNormal.classList.remove('pds-active');
        tabBusiness.classList.add('pds-active');
        bizBox.classList.add('pds-show');
        typeDesc.innerHTML = '🏢 <strong>사업자 회원</strong>: 관리자 승인 후 도매가 및 추가 혜택이 적용됩니다.';
    } else {
        tabBusiness.classList.remove('pds-active');
        tabNormal.classList.add('pds-active');
        bizBox.classList.remove('pds-show');
        typeDesc.innerHTML = '👤 <strong>일반 회원</strong>: 기본 서비스를 이용하실 수 있습니다.';
    }
}

// ── 사업자등록번호 자동 포맷 (000-00-00000) ──
function pdsFormatBizNo(el) {
    var v = el.value.replace(/[^0-9]/g, '');
    if (v.length > 10) v = v.slice(0, 10);
    if (v.length > 5)       v = v.slice(0,3) + '-' + v.slice(3,5) + '-' + v.slice(5);
    else if (v.length > 3)  v = v.slice(0,3) + '-' + v.slice(3);
    el.value = v;
}

// ── 폼 제출 전 유효성 검사 ──
(function(){
    var form = document.querySelector('form[name="fregisterform"]');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        var type = document.getElementById('pdsMbType').value;
        if (type !== 'business') return true;

        var bizNo   = document.getElementById('pdsBizNo');
        var bizName = document.getElementById('pdsBizName');
        var ok = true;

        // 사업자등록번호 검사 (000-00-00000 형식)
        var noVal = bizNo.value.replace(/[^0-9]/g, '');
        if (!noVal || noVal.length !== 10) {
            bizNo.classList.add('pds-error');
            alert('올바른 사업자등록번호(10자리)를 입력해주세요.\n예) 123-45-67890');
            bizNo.focus();
            e.preventDefault();
            return false;
        } else {
            bizNo.classList.remove('pds-error');
        }

        // 업체명 검사
        if (!bizName.value.trim()) {
            bizName.classList.add('pds-error');
            alert('업체명을 입력해주세요.');
            bizName.focus();
            e.preventDefault();
            return false;
        } else {
            bizName.classList.remove('pds-error');
        }

        return true;
    }, true); // capture: 기존 폼 핸들러보다 먼저 실행
})();
</script>
<?php
    return ob_get_clean();
}

/**
 * 그누보드 기본 테마용 (table 레이아웃)
 * 이윰빌더 함수를 그대로 사용 (레이아웃 동일)
 */
function partsds_car_field_html($member = []) {
    return partsds_car_field_html_eyoom($member);
}
