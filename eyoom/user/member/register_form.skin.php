<?php
/**
 * 파츠디에스 - 이윰빌더 회원가입 폼 사전 처리 (eyoom user hook)
 * 경로: /eyoom/user/member/register_form.skin.php
 *
 * 이윰빌더 /eyoom/core/member/register_form.skin.php 에서 자동으로 include 됨
 * 이 파일은 register_form.skin.html.php 가 include 되기 전에 실행됩니다.
 * partsds.extend.php 의 register_form_before 이벤트에서 이미 $partsds_car_field_html 이 준비됩니다.
 * 이 파일에서는 추가 작업이 필요할 경우만 처리합니다.
 */
if (!defined('_EYOOM_')) exit;

// extend 파일에서 이미 $partsds_car_field_html 이 준비되어 있으므로
// 추가 작업이 없으면 빈 파일로 두어도 됩니다.
// register_form.skin.html.php 에서 해당 변수를 출력합니다.
