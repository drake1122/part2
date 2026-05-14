<?php
/**
 * 아이윰 어드민 브릿지 파일 — 차량 DB 관리
 *
 * 라우팅 패턴: ?dir=partsds&pid=pds_car_db
 * → admin.sub.php 가 EYOOM_ADMIN_CORE_PATH/partsds/pds_car_db.php 를 include
 * → 이 파일이 실제 관리 페이지(G5_PATH/partsds/admin/pds_car_db.php)를 include_once
 */
if (!defined('_EYOOM_IS_ADMIN_')) exit;

$sub_menu = '600700';

include_once(G5_PATH . '/partsds/admin/pds_car_db.php');
