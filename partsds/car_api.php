<?php
/**
 * 파츠디에스 - 자동차 브랜드/시리즈/모델 AJAX API
 * 경로: /partsds/car_api.php
 * 
 * GET /partsds/car_api.php?action=brands              -> 전체 브랜드 목록
 * GET /partsds/car_api.php?action=series&brand_id=1   -> 브랜드별 시리즈 목록
 * GET /partsds/car_api.php?action=models&series_id=1  -> 시리즈별 모델 목록
 * GET /partsds/car_api.php?action=member_car          -> 로그인 회원 저장 차종
 */
include_once('./_common.php');

header('Content-Type: application/json; charset=utf-8');

$action = isset($_GET['action']) ? preg_replace('/[^a-z_]/', '', $_GET['action']) : '';

switch ($action) {

    case 'brands':
        $result = [];
        $res = sql_query("SELECT brand_id, brand_name, brand_name_en, brand_logo 
                          FROM `" . G5_TABLE_PREFIX . "car_brand` 
                          WHERE brand_use = 1 
                          ORDER BY brand_order, brand_id");
        while ($row = sql_fetch_array($res)) {
            $result[] = [
                'id'      => (int)$row['brand_id'],
                'name'    => $row['brand_name'],
                'name_en' => $row['brand_name_en'],
                'logo'    => $row['brand_logo'] ? G5_URL . '/' . ltrim($row['brand_logo'], '/') : '',
            ];
        }
        echo json_encode(['success' => true, 'data' => $result]);
        break;

    case 'series':
        $brand_id = isset($_GET['brand_id']) ? (int)$_GET['brand_id'] : 0;
        if (!$brand_id) {
            echo json_encode(['success' => false, 'message' => 'brand_id 필요']);
            break;
        }
        $result = [];
        $res = sql_query("SELECT cs.series_id, cs.series_name, cs.ca_id, sc.ca_id as sc_ca_id
                          FROM `" . G5_TABLE_PREFIX . "car_series` cs
                          LEFT JOIN `" . G5_TABLE_PREFIX . "shop_category` sc ON sc.ca_id = cs.ca_id
                          WHERE cs.brand_id = {$brand_id} AND cs.series_use = 1 
                          ORDER BY cs.series_order, cs.series_id");
        while ($row = sql_fetch_array($res)) {
            $result[] = [
                'id'    => (int)$row['series_id'],
                'name'  => $row['series_name'],
                'ca_id' => $row['ca_id'],
            ];
        }
        echo json_encode(['success' => true, 'data' => $result]);
        break;

    case 'models':
        $series_id = isset($_GET['series_id']) ? (int)$_GET['series_id'] : 0;
        if (!$series_id) {
            echo json_encode(['success' => false, 'message' => 'series_id 필요']);
            break;
        }
        $result = [];
        $res = sql_query("SELECT cm.model_id, cm.model_name, cm.model_year, cm.ca_id, sc.ca_id as sc_ca_id
                          FROM `" . G5_TABLE_PREFIX . "car_model` cm
                          LEFT JOIN `" . G5_TABLE_PREFIX . "shop_category` sc ON sc.ca_id = cm.ca_id
                          WHERE cm.series_id = {$series_id} AND cm.model_use = 1 
                          ORDER BY cm.model_order, cm.model_id");
        while ($row = sql_fetch_array($res)) {
            $result[] = [
                'id'         => (int)$row['model_id'],
                'name'       => $row['model_name'] . ($row['model_year'] ? ' (' . $row['model_year'] . ')' : ''),
                'name_plain' => $row['model_name'],
                'year'       => $row['model_year'],
                'ca_id'      => $row['ca_id'],
            ];
        }
        echo json_encode(['success' => true, 'data' => $result]);
        break;

    case 'member_car':
        // 로그인한 회원의 저장된 차종 정보 반환
        if (!$is_member) {
            echo json_encode(['success' => false, 'data' => null]);
            break;
        }
        $mb = sql_fetch("SELECT mb_1, mb_2, mb_3, mb_4, mb_5, mb_6 
                         FROM `" . G5_TABLE_PREFIX . "member` 
                         WHERE mb_id = '" . sql_escape_string($member['mb_id']) . "'");
        echo json_encode([
            'success'   => true,
            'data'      => [
                'brand_name'  => $mb['mb_1'],
                'series_name' => $mb['mb_2'],
                'model_name'  => $mb['mb_3'],
                'brand_id'    => (int)$mb['mb_4'],
                'series_id'   => (int)$mb['mb_5'],
                'model_id'    => (int)$mb['mb_6'],
            ]
        ]);
        break;

    case 'save_member_car':
        // 로그인 회원 차종 저장 (POST)
        if (!$is_member) {
            echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
            break;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'POST 요청 필요']);
            break;
        }
        $brand_id  = isset($_POST['brand_id'])  ? (int)$_POST['brand_id']  : 0;
        $series_id = isset($_POST['series_id']) ? (int)$_POST['series_id'] : 0;
        $model_id  = isset($_POST['model_id'])  ? (int)$_POST['model_id']  : 0;

        // 이름 조회
        $brand_name  = '';
        $series_name = '';
        $model_name  = '';

        if ($brand_id) {
            $row = sql_fetch("SELECT brand_name FROM `" . G5_TABLE_PREFIX . "car_brand` WHERE brand_id = {$brand_id}");
            $brand_name = $row['brand_name'];
        }
        if ($series_id) {
            $row = sql_fetch("SELECT series_name FROM `" . G5_TABLE_PREFIX . "car_series` WHERE series_id = {$series_id}");
            $series_name = $row['series_name'];
        }
        if ($model_id) {
            $row = sql_fetch("SELECT model_name FROM `" . G5_TABLE_PREFIX . "car_model` WHERE model_id = {$model_id}");
            $model_name = $row['model_name'];
        }

        $mb_id_safe = sql_escape_string($member['mb_id']);
        sql_query("UPDATE `" . G5_TABLE_PREFIX . "member` SET 
                    mb_1 = '" . sql_escape_string($brand_name)  . "',
                    mb_2 = '" . sql_escape_string($series_name) . "',
                    mb_3 = '" . sql_escape_string($model_name)  . "',
                    mb_4 = '" . $brand_id  . "',
                    mb_5 = '" . $series_id . "',
                    mb_6 = '" . $model_id  . "'
                   WHERE mb_id = '{$mb_id_safe}'");

        echo json_encode([
            'success' => true,
            'message' => '차종이 저장되었습니다.',
            'data'    => [
                'brand_name'  => $brand_name,
                'series_name' => $series_name,
                'model_name'  => $model_name,
                'brand_id'    => $brand_id,
                'series_id'   => $series_id,
                'model_id'    => $model_id,
            ]
        ]);
        break;

    /**
     * 임시 setup 액션: g5_car_model.ca_id 벤츠/BMW 업데이트
     * 사용법: /partsds/car_api.php?action=setup_ca_id&key=partsds2026
     * 완료 후 이 case를 삭제하세요
     */
    case 'setup_ca_id':
        // 간단한 보안키 확인
        $setup_key = isset($_GET['key']) ? $_GET['key'] : '';
        if ($setup_key !== 'partsds2026') {
            echo json_encode(['success' => false, 'message' => '인증 실패']);
            break;
        }

        $prefix = G5_TABLE_PREFIX;
        $updated = 0;
        $errors  = 0;

        // 시리즈 ca_id 캐시 (벤츠 brand_id=1, BMW brand_id=2)
        $series_map = [];
        $res = sql_query("SELECT series_id, ca_id FROM `{$prefix}car_series` WHERE ca_id != '' AND brand_id IN (1,2)");
        while ($row = sql_fetch_array($res)) {
            $series_map[(int)$row['series_id']] = $row['ca_id'];
        }

        // 미완료 모델 시리즈별로 처리
        foreach ($series_map as $series_id => $sca) {
            $res2 = sql_query("SELECT model_id, model_order FROM `{$prefix}car_model` WHERE series_id = {$series_id} AND ca_id = ''");
            $rows = [];
            while ($r = sql_fetch_array($res2)) $rows[] = $r;
            if (empty($rows)) continue;

            // CASE WHEN UPDATE
            $cases = '';
            $ids   = [];
            foreach ($rows as $r) {
                $ca = (string)((int)$sca * 100 + (int)$r['model_order']);
                if (strlen($ca) <= 10) {
                    $cases .= "WHEN " . (int)$r['model_id'] . " THEN '{$ca}' ";
                    $ids[]  = (int)$r['model_id'];
                }
            }
            if ($cases && $ids) {
                $id_str = implode(',', $ids);
                sql_query("UPDATE `{$prefix}car_model` SET ca_id = CASE model_id {$cases} END WHERE model_id IN ({$id_str})");
                $updated += count($ids);
            }
        }

        // 결과 확인
        $row = sql_fetch("SELECT COUNT(*) as cnt FROM `{$prefix}car_model` WHERE ca_id != ''");
        $total_done = (int)$row['cnt'];
        $row2 = sql_fetch("SELECT COUNT(*) as cnt FROM `{$prefix}car_model` WHERE ca_id = ''");
        $total_left = (int)$row2['cnt'];

        // 검증 샘플
        $sample = [];
        $res3 = sql_query("SELECT cm.model_id, cm.model_name, cm.ca_id, sc.ca_name
                           FROM `{$prefix}car_model` cm
                           LEFT JOIN `{$prefix}shop_category` sc ON cm.ca_id = sc.ca_id
                           JOIN `{$prefix}car_series` cs ON cm.series_id = cs.series_id
                           WHERE cs.brand_id = 1 AND cs.series_order = 1
                           ORDER BY cm.model_order LIMIT 5");
        while ($r = sql_fetch_array($res3)) {
            $sample[] = [
                'model_id'  => $r['model_id'],
                'model_name'=> $r['model_name'],
                'ca_id'     => $r['ca_id'],
                'shop_name' => $r['ca_name'],
                'matched'   => !empty($r['ca_name']),
            ];
        }

        echo json_encode([
            'success'     => true,
            'updated'     => $updated,
            'total_done'  => $total_done,
            'total_left'  => $total_left,
            'total_model' => $total_done + $total_left,
            'sample'      => $sample,
            'message'     => "완료! 총 {$total_done}개 ca_id 연결됨",
        ], JSON_UNESCAPED_UNICODE);
        break;

    default:
        echo json_encode(['success' => false, 'message' => '잘못된 요청']);
        break;
}
