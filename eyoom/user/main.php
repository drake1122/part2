<?php
/**
 * file : /eyoom/user/main.php
 *
 * 아이윰 메인 홈 사용자 확장 훅
 * /eyoom/main.php 에서 @include_once 로 자동 로드됨
 *
 * PartsDS 메인 홈 위젯 (차종선택 + OEM검색 + 파츠그리드)
 * - 관리자 설정: /partsds/admin/pds_home_config.php
 * - 위젯 본체:   /partsds/pds_home_widget.php
 */
if (!defined('_EYOOM_')) exit;

// _MAIN_ 상수로 메인 홈인지 확인 (eyoom/main.php에서 define)
// sql_fetch 함수가 로드됐는지도 확인 (그누보드5 DB 함수 필요)
if (defined('_MAIN_') && function_exists('sql_fetch')) {
    include_once(G5_PATH . '/partsds/pds_home_widget.php');
}
