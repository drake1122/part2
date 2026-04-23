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
 * @return array  상품 it_id 배열 (raw strings, SQL escape 전)
 */
function pds_get_car_items($brand_id, $series_id, $model_id) {
    $brand_id  = (int)$brand_id;
    $series_id = (int)$series_id;
    $model_id  = (int)$model_id;

    $where = [];
    if ($model_id)       $where[] = "model_id  = {$model_id}";
    elseif ($series_id)  $where[] = "series_id = {$series_id}";
    elseif ($brand_id)   $where[] = "brand_id  = {$brand_id}";

    if (!$where) return [];

    // item_car 테이블 존재 확인
    $table_check = @sql_fetch("SELECT COUNT(*) AS cnt FROM information_schema.TABLES 
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . G5_TABLE_PREFIX . "item_car'");
    if (empty($table_check['cnt'])) return [];

    $sql = "SELECT DISTINCT it_id FROM `" . G5_TABLE_PREFIX . "item_car` WHERE " . implode(' AND ', $where);
    $res = sql_query($sql);

    $ids = [];
    while ($row = sql_fetch_array($res)) {
        if ($row['it_id']) {
            $ids[] = sql_escape_string($row['it_id']);
        }
    }
    return $ids;
}

/**
 * 차종 필터 정보 반환 (브랜드명, 시리즈명, 모델명)
 */
function pds_get_car_filter_info($brand_id, $series_id, $model_id) {
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
 * 차종 필터 바 HTML 반환 (상품 목록 상단에 표시)
 * $auto: true이면 로그인 회원 차종 자동 적용 안내 표시
 */
function pds_render_filter_bar($brand_id, $series_id, $model_id, $auto = false) {
    if (!$brand_id) return '';

    $info = pds_get_car_filter_info($brand_id, $series_id, $model_id);

    $label = $info['brand'];
    if ($info['series']) $label .= ' > ' . $info['series'];
    if ($info['model'])  $label .= ' > ' . $info['model'];

    // 필터 해제 URL (pds_no_filter=1 → 자동 필터 비활성화)
    $clear_url = strtok($_SERVER['REQUEST_URI'], '?');
    $ca_id = isset($_GET['ca_id']) ? htmlspecialchars($_GET['ca_id']) : '';
    $clear_params = $ca_id ? '?ca_id=' . $ca_id . '&pds_no_filter=1' : '?pds_no_filter=1';
    $clear_url .= $clear_params;

    // 전체보기 URL (필터 없이)
    $all_url = strtok($_SERVER['REQUEST_URI'], '?');
    if ($ca_id) $all_url .= '?ca_id=' . $ca_id . '&pds_no_filter=1';
    else $all_url .= '?pds_no_filter=1';

    ob_start();
    ?>
    <div class="pds-filter-bar" style="padding:10px 15px; background:<?php echo $auto ? '#fff8e1' : '#f8f9fa'; ?>; border:1px solid <?php echo $auto ? '#ffe082' : '#dee2e6'; ?>; border-radius:4px; margin-bottom:15px; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <?php if ($auto): ?>
        <span style="font-size:12px; color:#f57c00; font-weight:bold;"><i class="fas fa-car"></i> 내 차종 자동 필터:</span>
        <?php else: ?>
        <span style="font-size:13px; color:#666;"><i class="fas fa-car"></i> 차종 필터:</span>
        <?php endif; ?>
        <span style="background:#c0392b; color:#fff; padding:3px 10px; border-radius:20px; font-size:13px;">
            <i class="fas fa-check"></i> <?php echo htmlspecialchars($label); ?>
        </span>
        <?php if ($auto): ?>
        <a href="<?php echo $all_url; ?>" style="color:#999; font-size:12px; text-decoration:none;">전체 상품 보기</a>
        <?php else: ?>
        <a href="<?php echo $clear_url; ?>" style="color:#999; font-size:12px; text-decoration:none;">✕ 필터 해제</a>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
