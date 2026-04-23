<?php
/**
 * 파츠디에스 - 상품 목록 차종 필터 처리
 * 경로: /partsds/car_list_filter.php
 * 
 * shop/list.php 에서 include 하거나 extend hook 으로 호출
 * 차종 파라미터(pds_brand, pds_series, pds_model)에 따라 상품 ID 목록 반환
 */
if (!defined('_GNUBOARD_')) exit;

/**
 * 차종 필터에 해당하는 상품 ID 목록 반환
 * @param int $brand_id
 * @param int $series_id
 * @param int $model_id
 * @return array  상품 it_id 배열
 */
function pds_get_car_items($brand_id, $series_id, $model_id) {
    global $g5;

    $where = [];
    if ($model_id)  $where[] = "model_id  = " . (int)$model_id;
    elseif ($series_id) $where[] = "series_id = " . (int)$series_id;
    elseif ($brand_id)  $where[] = "brand_id  = " . (int)$brand_id;

    if (!$where) return [];

    $sql = "SELECT DISTINCT it_id FROM `" . G5_TABLE_PREFIX . "item_car` WHERE " . implode(' AND ', $where);
    $res = sql_query($sql);

    $ids = [];
    while ($row = sql_fetch_array($res)) {
        $ids[] = "'" . sql_escape_string($row['it_id']) . "'";
    }
    return $ids;
}

/**
 * 차종 필터 정보 반환 (브랜드명, 시리즈명, 모델명)
 */
function pds_get_car_filter_info($brand_id, $series_id, $model_id) {
    global $g5;
    $info = ['brand' => '', 'series' => '', 'model' => ''];

    if ($brand_id) {
        $row = sql_fetch("SELECT brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_id = " . (int)$brand_id);
        $info['brand'] = $row['brand_name'];
    }
    if ($series_id) {
        $row = sql_fetch("SELECT series_name FROM `" . G5_TABLE_PREFIX . "car_series` WHERE series_id = " . (int)$series_id);
        $info['series'] = $row['series_name'];
    }
    if ($model_id) {
        $row = sql_fetch("SELECT model_name FROM `" . G5_TABLE_PREFIX . "car_model` WHERE model_id = " . (int)$model_id);
        $info['model'] = $row['model_name'];
    }
    return $info;
}

/**
 * 차종 필터 HTML 출력
 */
function pds_render_filter_bar($brand_id, $series_id, $model_id) {
    if (!$brand_id) return '';

    $info = pds_get_car_filter_info($brand_id, $series_id, $model_id);

    $label = $info['brand'];
    if ($info['series']) $label .= ' > ' . $info['series'];
    if ($info['model'])  $label .= ' > ' . $info['model'];

    $clear_url = strtok($_SERVER['REQUEST_URI'], '?');
    // ca_id만 유지
    $ca_id = isset($_GET['ca_id']) ? htmlspecialchars($_GET['ca_id']) : '';
    if ($ca_id) $clear_url .= '?ca_id=' . $ca_id;

    ob_start();
    ?>
    <div class="pds-filter-bar">
        <span class="pds-filter-label"><i class="fas fa-car"></i> 차종 필터:</span>
        <span class="pds-filter-tag"><i class="fas fa-check"></i> <?php echo htmlspecialchars($label); ?></span>
        <a href="<?php echo $clear_url; ?>" class="pds-filter-clear">✕ 필터 해제</a>
    </div>
    <?php
    return ob_get_clean();
}
