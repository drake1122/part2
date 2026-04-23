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
include_once('../_common.php');

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
        $res = sql_query("SELECT series_id, series_name 
                          FROM `" . G5_TABLE_PREFIX . "car_series` 
                          WHERE brand_id = {$brand_id} AND series_use = 1 
                          ORDER BY series_order, series_id");
        while ($row = sql_fetch_array($res)) {
            $result[] = [
                'id'   => (int)$row['series_id'],
                'name' => $row['series_name'],
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
        $res = sql_query("SELECT model_id, model_name, model_year 
                          FROM `" . G5_TABLE_PREFIX . "car_model` 
                          WHERE series_id = {$series_id} AND model_use = 1 
                          ORDER BY model_order, model_id");
        while ($row = sql_fetch_array($res)) {
            $result[] = [
                'id'   => (int)$row['model_id'],
                'name' => $row['model_name'] . ($row['model_year'] ? ' (' . $row['model_year'] . ')' : ''),
                'name_plain' => $row['model_name'],
                'year' => $row['model_year'],
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

    default:
        echo json_encode(['success' => false, 'message' => '잘못된 요청']);
        break;
}
