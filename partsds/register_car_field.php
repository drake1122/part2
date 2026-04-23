<?php
/**
 * 파츠디에스 - 회원가입/수정 차종 선택 필드 HTML
 * 경로: /partsds/register_car_field.php
 */
if (!defined('_GNUBOARD_')) exit;

function partsds_car_field_html($member = []) {
    global $g5;

    $saved_brand_id  = isset($member['mb_4']) ? (int)$member['mb_4']  : 0;
    $saved_series_id = isset($member['mb_5']) ? (int)$member['mb_5']  : 0;
    $saved_model_id  = isset($member['mb_6']) ? (int)$member['mb_6']  : 0;
    $saved_brand_name  = isset($member['mb_1']) ? htmlspecialchars($member['mb_1']) : '';
    $saved_series_name = isset($member['mb_2']) ? htmlspecialchars($member['mb_2']) : '';
    $saved_model_name  = isset($member['mb_3']) ? htmlspecialchars($member['mb_3']) : '';

    // 브랜드 목록
    $brands = [];
    $res = sql_query("SELECT brand_id, brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_use = 1 ORDER BY brand_order, brand_id");
    while ($row = sql_fetch_array($res)) { $brands[] = $row; }

    // 저장된 시리즈 목록 (이미 선택된 브랜드가 있을 때)
    $series_list = [];
    if ($saved_brand_id) {
        $res = sql_query("SELECT series_id, series_name FROM `" . G5_TABLE_PREFIX . "car_series` WHERE brand_id = {$saved_brand_id} AND series_use = 1 ORDER BY series_order, series_id");
        while ($row = sql_fetch_array($res)) { $series_list[] = $row; }
    }

    // 저장된 모델 목록
    $model_list = [];
    if ($saved_series_id) {
        $res = sql_query("SELECT model_id, model_name, model_year FROM `" . G5_TABLE_PREFIX . "car_model` WHERE series_id = {$saved_series_id} AND model_use = 1 ORDER BY model_order, model_id");
        while ($row = sql_fetch_array($res)) { $model_list[] = $row; }
    }

    $api_url = G5_URL . '/partsds/car_api.php';

    ob_start();
    ?>
    <!-- 파츠디에스 차종 선택 -->
    <link rel="stylesheet" href="<?php echo G5_URL; ?>/partsds/css/brand_selector.css?ver=<?php echo G5_CSS_VER; ?>">
    <tr>
        <th scope="row">
            <label>내 차량 선택 <span class="sound_only">(선택)</span></label>
        </th>
        <td>
            <div class="pds-reg-car-wrap">
                <div class="pds-reg-car-selectors">
                    <!-- 브랜드 -->
                    <select id="regSelectBrand" name="pds_brand_id" class="pds-select" style="max-width:200px;">
                        <option value="">① 브랜드 선택</option>
                        <?php foreach ($brands as $b): ?>
                        <option value="<?php echo (int)$b['brand_id']; ?>"
                                <?php if ($saved_brand_id == $b['brand_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($b['brand_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- 시리즈 -->
                    <select id="regSelectSeries" name="pds_series_id" class="pds-select" style="max-width:200px;"
                            <?php if (!$saved_brand_id) echo 'disabled'; ?>>
                        <option value="">② 시리즈/연식 선택</option>
                        <?php foreach ($series_list as $s): ?>
                        <option value="<?php echo (int)$s['series_id']; ?>"
                                <?php if ($saved_series_id == $s['series_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($s['series_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <!-- 모델 -->
                    <select id="regSelectModel" name="pds_model_id" class="pds-select" style="max-width:220px;"
                            <?php if (!$saved_series_id) echo 'disabled'; ?>>
                        <option value="">③ 모델 선택</option>
                        <?php foreach ($model_list as $m): ?>
                        <option value="<?php echo (int)$m['model_id']; ?>"
                                <?php if ($saved_model_id == $m['model_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($m['model_name'] . ($m['model_year'] ? ' (' . $m['model_year'] . ')' : '')); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- hidden 이름 필드 -->
                <input type="hidden" id="regBrandName"  name="pds_brand_name"  value="<?php echo $saved_brand_name; ?>">
                <input type="hidden" id="regSeriesName" name="pds_series_name" value="<?php echo $saved_series_name; ?>">
                <input type="hidden" id="regModelName"  name="pds_model_name"  value="<?php echo $saved_model_name; ?>">
                <p class="pds-field-desc" style="font-size:12px; color:#888; margin-top:6px;">
                    <i class="fas fa-info-circle"></i> 차종을 저장하면 로그인 시 자동으로 맞는 부품을 필터링합니다.
                </p>
            </div>
        </td>
    </tr>
    <script>
    (function(){
        var API = '<?php echo $api_url; ?>';
        var selBrand  = document.getElementById('regSelectBrand');
        var selSeries = document.getElementById('regSelectSeries');
        var selModel  = document.getElementById('regSelectModel');

        selBrand.addEventListener('change', function() {
            document.getElementById('regBrandName').value = this.options[this.selectedIndex].text !== '① 브랜드 선택' ? this.options[this.selectedIndex].text : '';
            selSeries.innerHTML = '<option value="">② 시리즈/연식 선택</option>';
            selSeries.disabled  = true;
            selModel.innerHTML  = '<option value="">③ 모델 선택</option>';
            selModel.disabled   = true;
            document.getElementById('regSeriesName').value = '';
            document.getElementById('regModelName').value  = '';
            if (!this.value) return;
            fetch(API + '?action=series&brand_id=' + this.value)
                .then(function(r){ return r.json(); })
                .then(function(res){
                    if (res.success) {
                        res.data.forEach(function(s){
                            var o = document.createElement('option');
                            o.value = s.id; o.textContent = s.name;
                            selSeries.appendChild(o);
                        });
                        selSeries.disabled = false;
                    }
                });
        });

        selSeries.addEventListener('change', function() {
            document.getElementById('regSeriesName').value = this.options[this.selectedIndex].text !== '② 시리즈/연식 선택' ? this.options[this.selectedIndex].text : '';
            selModel.innerHTML = '<option value="">③ 모델 선택</option>';
            selModel.disabled  = true;
            document.getElementById('regModelName').value = '';
            if (!this.value) return;
            fetch(API + '?action=models&series_id=' + this.value)
                .then(function(r){ return r.json(); })
                .then(function(res){
                    if (res.success) {
                        res.data.forEach(function(m){
                            var o = document.createElement('option');
                            o.value = m.id; o.textContent = m.name;
                            selModel.appendChild(o);
                        });
                        selModel.disabled = false;
                    }
                });
        });

        selModel.addEventListener('change', function() {
            document.getElementById('regModelName').value = this.options[this.selectedIndex].text !== '③ 모델 선택' ? this.options[this.selectedIndex].text : '';
        });
    })();
    </script>
    <?php
    return ob_get_clean();
}

/**
 * 이윰빌더 테마용 차종 선택 필드 HTML (fieldset > div.row > section 구조)
 */
function partsds_car_field_html_eyoom($member = []) {
    global $g5;

    $saved_brand_id  = isset($member['mb_4']) ? (int)$member['mb_4']  : 0;
    $saved_series_id = isset($member['mb_5']) ? (int)$member['mb_5']  : 0;
    $saved_model_id  = isset($member['mb_6']) ? (int)$member['mb_6']  : 0;
    $saved_brand_name  = isset($member['mb_1']) ? htmlspecialchars($member['mb_1']) : '';
    $saved_series_name = isset($member['mb_2']) ? htmlspecialchars($member['mb_2']) : '';
    $saved_model_name  = isset($member['mb_3']) ? htmlspecialchars($member['mb_3']) : '';

    $brands = [];
    $res = sql_query("SELECT brand_id, brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_use = 1 ORDER BY brand_order, brand_id");
    while ($row = sql_fetch_array($res)) { $brands[] = $row; }

    $series_list = [];
    if ($saved_brand_id) {
        $res = sql_query("SELECT series_id, series_name FROM `" . G5_TABLE_PREFIX . "car_series` WHERE brand_id = {$saved_brand_id} AND series_use = 1 ORDER BY series_order, series_id");
        while ($row = sql_fetch_array($res)) { $series_list[] = $row; }
    }

    $model_list = [];
    if ($saved_series_id) {
        $res = sql_query("SELECT model_id, model_name, model_year FROM `" . G5_TABLE_PREFIX . "car_model` WHERE series_id = {$saved_series_id} AND model_use = 1 ORDER BY model_order, model_id");
        while ($row = sql_fetch_array($res)) { $model_list[] = $row; }
    }

    $api_url = G5_URL . '/partsds/car_api.php';

    ob_start();
    ?>
    <!-- 파츠디에스 차종 선택 (이윰빌더 테마) -->
    <div class="row">
        <section class="col-lg-12">
            <label class="label">내 차량 선택 <small style="color:#888; font-weight:normal;">(선택)</small></label>
            <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center; margin-bottom:6px;">
                <select id="eyRegSelectBrand" name="pds_brand_id"
                        style="flex:1; min-width:150px; height:40px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:14px;">
                    <option value="">① 브랜드 선택</option>
                    <?php foreach ($brands as $b): ?>
                    <option value="<?php echo (int)$b['brand_id']; ?>"
                            <?php if ($saved_brand_id == $b['brand_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($b['brand_name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <select id="eyRegSelectSeries" name="pds_series_id"
                        style="flex:1; min-width:150px; height:40px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:14px;"
                        <?php if (!$saved_brand_id) echo 'disabled'; ?>>
                    <option value="">② 시리즈/연식 선택</option>
                    <?php foreach ($series_list as $s): ?>
                    <option value="<?php echo (int)$s['series_id']; ?>"
                            <?php if ($saved_series_id == $s['series_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($s['series_name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <select id="eyRegSelectModel" name="pds_model_id"
                        style="flex:1; min-width:160px; height:40px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:14px;"
                        <?php if (!$saved_series_id) echo 'disabled'; ?>>
                    <option value="">③ 모델 선택</option>
                    <?php foreach ($model_list as $m): ?>
                    <option value="<?php echo (int)$m['model_id']; ?>"
                            <?php if ($saved_model_id == $m['model_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($m['model_name'] . ($m['model_year'] ? ' (' . $m['model_year'] . ')' : '')); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" id="eyRegBrandName"  name="pds_brand_name"  value="<?php echo $saved_brand_name; ?>">
            <input type="hidden" id="eyRegSeriesName" name="pds_series_name" value="<?php echo $saved_series_name; ?>">
            <input type="hidden" id="eyRegModelName"  name="pds_model_name"  value="<?php echo $saved_model_name; ?>">
            <small style="color:#888;">
                <i class="fas fa-info-circle"></i> 차종 저장 시 로그인하면 맞는 부품이 자동 필터링됩니다.
            </small>
        </section>
    </div>
    <script>
    (function(){
        var API = '<?php echo $api_url; ?>';
        var selBrand  = document.getElementById('eyRegSelectBrand');
        var selSeries = document.getElementById('eyRegSelectSeries');
        var selModel  = document.getElementById('eyRegSelectModel');

        selBrand.addEventListener('change', function() {
            document.getElementById('eyRegBrandName').value = this.value ? this.options[this.selectedIndex].text : '';
            selSeries.innerHTML = '<option value="">② 시리즈/연식 선택</option>';
            selSeries.disabled  = true;
            selModel.innerHTML  = '<option value="">③ 모델 선택</option>';
            selModel.disabled   = true;
            document.getElementById('eyRegSeriesName').value = '';
            document.getElementById('eyRegModelName').value  = '';
            if (!this.value) return;
            fetch(API + '?action=series&brand_id=' + this.value)
                .then(function(r){ return r.json(); })
                .then(function(res){
                    if (res.success && res.data.length) {
                        res.data.forEach(function(s){
                            var o = document.createElement('option');
                            o.value = s.id; o.textContent = s.name;
                            selSeries.appendChild(o);
                        });
                        selSeries.disabled = false;
                    }
                });
        });

        selSeries.addEventListener('change', function() {
            document.getElementById('eyRegSeriesName').value = this.value ? this.options[this.selectedIndex].text : '';
            selModel.innerHTML = '<option value="">③ 모델 선택</option>';
            selModel.disabled  = true;
            document.getElementById('eyRegModelName').value = '';
            if (!this.value) return;
            fetch(API + '?action=models&series_id=' + this.value)
                .then(function(r){ return r.json(); })
                .then(function(res){
                    if (res.success && res.data.length) {
                        res.data.forEach(function(m){
                            var o = document.createElement('option');
                            o.value = m.id; o.textContent = m.name;
                            selModel.appendChild(o);
                        });
                        selModel.disabled = false;
                    }
                });
        });

        selModel.addEventListener('change', function() {
            document.getElementById('eyRegModelName').value = this.value ? this.options[this.selectedIndex].text : '';
        });
    })();
    </script>
    <?php
    return ob_get_clean();
}
