<?php
/**
 * 파츠디에스 - 메인 홈 위젯 설정
 * 경로: /partsds/admin/pds_home_config.php
 *
 * 기능:
 *  1. 메인 홈 차종선택 + 파츠그리드 위젯 ON/OFF 및 세부 옵션 설정
 *  2. pds_config 테이블(g5_pds_config)에 저장
 *  3. 저장 즉시 프론트 반영
 *
 * 사전조건: install_partsds_config.sql 실행 완료
 */
include_once('../../_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    exit;
}

define('PDS_CFG_TABLE', G5_TABLE_PREFIX . 'pds_config');

/* ── 설정 헬퍼 함수 ──────────────────────────────────────── */
function pds_cfg_get($key, $default = '') {
    $r = sql_fetch("SELECT cfg_val FROM `" . PDS_CFG_TABLE . "` WHERE cfg_key = '" . sql_escape_string($key) . "'");
    return ($r && isset($r['cfg_val']) && $r['cfg_val'] !== '') ? $r['cfg_val'] : $default;
}
function pds_cfg_set($key, $val, $memo = '') {
    $k = sql_escape_string($key);
    $v = sql_escape_string($val);
    $m = sql_escape_string($memo);
    sql_query("INSERT INTO `" . PDS_CFG_TABLE . "` (cfg_key, cfg_val, cfg_memo)
               VALUES ('{$k}', '{$v}', '{$m}')
               ON DUPLICATE KEY UPDATE cfg_val = '{$v}'");
}

/* ── 테이블 존재 여부 확인 ──────────────────────────────── */
$tbl_check = sql_fetch("SELECT COUNT(*) AS cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '" . PDS_CFG_TABLE . "'");
$tbl_exists = ($tbl_check['cnt'] > 0);

/* ── POST 처리 (저장) ───────────────────────────────────── */
$save_msg = '';
if ($tbl_exists && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pds_save'])) {
    $fields = [
        'home_widget_use'        => ['default' => 'n', 'memo' => '메인 홈 위젯 사용'],
        'home_car_selector_use'  => ['default' => 'n', 'memo' => '차종 선택 UI'],
        'home_car_selector_title'=> ['default' => '',  'memo' => '차종 선택 안내문구'],
        'home_oem_search_use'    => ['default' => 'n', 'memo' => 'OEM 검색창'],
        'home_oem_placeholder'   => ['default' => '',  'memo' => 'OEM 검색 placeholder'],
        'home_parts_grid_use'    => ['default' => 'n', 'memo' => '파츠 그리드'],
        'home_parts_grid_title'  => ['default' => 'PARTS', 'memo' => '파츠 그리드 제목'],
        'home_parts_grid_cols'   => ['default' => '6', 'memo' => '그리드 열 수'],
        'home_parts_notice'      => ['default' => '',  'memo' => '그리드 하단 안내문'],
        'home_widget_position'   => ['default' => 'top', 'memo' => '위젯 위치'],
        'home_brand_bar_use'     => ['default' => 'n', 'memo' => '브랜드 로고 바'],
        'home_brand_bar_title'   => ['default' => '',  'memo' => '브랜드 바 제목'],
        'search_url_mode'        => ['default' => 'ca_id', 'memo' => '검색 URL 방식'],
        'home_widget_css_extra'  => ['default' => '',  'memo' => '추가 CSS'],
    ];

    // 체크박스 (y/n)
    $checkboxes = ['home_widget_use','home_car_selector_use','home_oem_search_use',
                   'home_parts_grid_use','home_brand_bar_use'];
    foreach ($checkboxes as $cb) {
        $_POST[$cb] = isset($_POST[$cb]) ? 'y' : 'n';
    }

    foreach ($fields as $key => $info) {
        $val = isset($_POST[$key]) ? trim($_POST[$key]) : $info['default'];
        // 열 수 유효성
        if ($key === 'home_parts_grid_cols' && !in_array($val, ['4','5','6'])) $val = '6';
        // URL 방식 유효성
        if ($key === 'search_url_mode' && !in_array($val, ['ca_id','filter'])) $val = 'ca_id';
        pds_cfg_set($key, $val, $info['memo']);
    }
    $save_msg = '✅ 설정이 저장되었습니다.';
}

/* ── 현재 설정값 로드 ───────────────────────────────────── */
$cfg = [];
if ($tbl_exists) {
    $cfg = [
        'home_widget_use'        => pds_cfg_get('home_widget_use',        'y'),
        'home_car_selector_use'  => pds_cfg_get('home_car_selector_use',  'y'),
        'home_car_selector_title'=> pds_cfg_get('home_car_selector_title','차량을 선택하면 해당 차종에 맞는 부품을 검색할 수 있습니다.'),
        'home_oem_search_use'    => pds_cfg_get('home_oem_search_use',    'y'),
        'home_oem_placeholder'   => pds_cfg_get('home_oem_placeholder',   '예) 부품번호 (OEM No.)'),
        'home_parts_grid_use'    => pds_cfg_get('home_parts_grid_use',    'y'),
        'home_parts_grid_title'  => pds_cfg_get('home_parts_grid_title',  'PARTS'),
        'home_parts_grid_cols'   => pds_cfg_get('home_parts_grid_cols',   '6'),
        'home_parts_notice'      => pds_cfg_get('home_parts_notice',      '그 밖의 부품은 문의 주세요 | 이미지는 실제 제품과 다를 수 있으니 제품명을 기준으로 구매 부탁드립니다'),
        'home_widget_position'   => pds_cfg_get('home_widget_position',   'top'),
        'home_brand_bar_use'     => pds_cfg_get('home_brand_bar_use',     'y'),
        'home_brand_bar_title'   => pds_cfg_get('home_brand_bar_title',   '수입자동차 전문 부품'),
        'search_url_mode'        => pds_cfg_get('search_url_mode',        'ca_id'),
        'home_widget_css_extra'  => pds_cfg_get('home_widget_css_extra',  ''),
    ];
}

$yn = function($key) use ($cfg) { return ($cfg[$key] ?? 'n') === 'y'; };
$val = function($key, $default = '') use ($cfg) { return htmlspecialchars($cfg[$key] ?? $default); };

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>메인 홈 위젯 설정 - PartsDS 관리</title>
<style>
*, *::before, *::after { box-sizing: border-box; }
body { font-family: -apple-system, "Malgun Gothic", sans-serif; background: #f4f6f9; margin: 0; padding: 0; color: #222; font-size: 14px; }
.pds-wrap { max-width: 900px; margin: 0 auto; padding: 24px 20px; }
.pds-title { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
.pds-title h1 { font-size: 22px; font-weight: 700; margin: 0; }
.badge { background: #f59e0b; color: #fff; font-size: 11px; padding: 3px 9px; border-radius: 20px; font-weight: 700; }

.card { background: #fff; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.08); padding: 24px; margin-bottom: 20px; }
.card-title { font-size: 15px; font-weight: 700; margin: 0 0 18px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb; color: #1e40af; display: flex; align-items: center; gap: 8px; }
.section-icon { font-size: 18px; }

.form-row { display: grid; grid-template-columns: 200px 1fr; gap: 12px; align-items: start; padding: 10px 0; border-bottom: 1px solid #f3f4f6; }
.form-row:last-child { border-bottom: none; }
.form-label { font-size: 13px; font-weight: 600; color: #374151; padding-top: 8px; }
.form-desc { font-size: 11px; color: #9ca3af; margin-top: 3px; font-weight: 400; }
.form-ctrl { display: flex; flex-direction: column; gap: 4px; }

input[type="text"], textarea, select {
    width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px;
    font-size: 13px; background: #fff; color: #222; transition: border-color .15s;
    font-family: inherit;
}
textarea { resize: vertical; min-height: 60px; }
input:focus, textarea:focus, select:focus { outline: none; border-color: #2563eb; }

/* 토글 스위치 */
.toggle-wrap { display: flex; align-items: center; gap: 10px; }
.toggle { position: relative; display: inline-block; width: 46px; height: 26px; }
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
    background: #d1d5db; border-radius: 26px; transition: .3s; }
.toggle-slider:before { position: absolute; content: ""; height: 20px; width: 20px;
    left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: .3s; }
input:checked + .toggle-slider { background: #2563eb; }
input:checked + .toggle-slider:before { transform: translateX(20px); }
.toggle-label { font-size: 13px; color: #374151; }

/* 라디오 그룹 */
.radio-group { display: flex; gap: 20px; padding-top: 6px; }
.radio-group label { display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 13px; }

/* 컬럼 선택 */
.col-select { display: flex; gap: 10px; padding-top: 4px; }
.col-btn { border: 2px solid #e5e7eb; background: #fff; border-radius: 8px; padding: 8px 18px;
    font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s; color: #6b7280; }
.col-btn.active, .col-btn:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }
input[type="radio"].col-radio { display: none; }

.btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 24px;
    border: none; border-radius: 7px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all .15s; }
.btn-primary { background: #2563eb; color: #fff; }
.btn-primary:hover { background: #1d4ed8; }
.btn-secondary { background: #6b7280; color: #fff; }

.alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;
    padding: 12px 18px; border-radius: 8px; margin-bottom: 18px; font-weight: 600; }
.alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a;
    padding: 12px 18px; border-radius: 8px; margin-bottom: 18px; }
.alert-info { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;
    padding: 10px 14px; border-radius: 6px; font-size: 12px; margin-top: 6px; }

.preview-box { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 8px;
    padding: 14px; margin-top: 8px; font-size: 12px; color: #6b7280; }
.preview-box strong { color: #1e40af; }

@media (max-width: 640px) {
    .form-row { grid-template-columns: 1fr; }
    .form-label { padding-top: 0; }
}
</style>
</head>
<body>
<div class="pds-wrap">

    <div class="pds-title">
        <h1>🏠 메인 홈 위젯 설정</h1>
        <span class="badge">PartsDS</span>
        <a href="parts_master.php" style="margin-left:auto;font-size:12px;color:#6b7280;text-decoration:none;">← 마스터 관리</a>
    </div>

    <?php if ($save_msg): ?>
    <div class="alert-success"><?= $save_msg ?></div>
    <?php endif; ?>

    <?php if (!$tbl_exists): ?>
    <div class="alert-warning">
        ⚠️ <strong>pds_config 테이블이 없습니다.</strong><br>
        <code>partsds/install_partsds_config.sql</code>을 phpMyAdmin에서 먼저 실행해주세요.<br>
        그러면 이 페이지에서 설정을 저장할 수 있습니다.
    </div>
    <?php else: ?>

    <form method="POST" action="">
    <input type="hidden" name="pds_save" value="1">

    <!-- ══ 1. 위젯 전체 ON/OFF ══ -->
    <div class="card">
        <div class="card-title"><span class="section-icon">⚡</span> 위젯 전체 사용 여부</div>

        <div class="form-row">
            <div class="form-label">
                메인 홈 위젯 활성화
                <div class="form-desc">꺼두면 메인 홈에 아무것도 표시되지 않음</div>
            </div>
            <div class="form-ctrl">
                <div class="toggle-wrap">
                    <label class="toggle">
                        <input type="checkbox" name="home_widget_use" <?= $yn('home_widget_use') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="toggle-label">차종선택 + 파츠그리드 위젯 표시</span>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">
                위젯 위치
                <div class="form-desc">쇼핑몰 메인 상품목록 기준</div>
            </div>
            <div class="form-ctrl">
                <div class="radio-group">
                    <label>
                        <input type="radio" name="home_widget_position" value="top" <?= ($cfg['home_widget_position']??'top')==='top' ? 'checked' : '' ?>>
                        상품목록 위 (권장)
                    </label>
                    <label>
                        <input type="radio" name="home_widget_position" value="bottom" <?= ($cfg['home_widget_position']??'top')==='bottom' ? 'checked' : '' ?>>
                        상품목록 아래
                    </label>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">
                차종 검색 URL 방식
                <div class="form-desc">검색 결과 연결 방식</div>
            </div>
            <div class="form-ctrl">
                <div class="radio-group">
                    <label>
                        <input type="radio" name="search_url_mode" value="ca_id" <?= ($cfg['search_url_mode']??'ca_id')==='ca_id' ? 'checked' : '' ?>>
                        ca_id 분류 기반 <small style="color:#9ca3af;">(권장: /shop/list.php?ca_id=2010)</small>
                    </label>
                    <label>
                        <input type="radio" name="search_url_mode" value="filter" <?= ($cfg['search_url_mode']??'ca_id')==='filter' ? 'checked' : '' ?>>
                        파라미터 필터 기반 <small style="color:#9ca3af;">(?pds_brand_id=1&pds_series_id=2)</small>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ 2. 차종 선택 섹션 ══ -->
    <div class="card">
        <div class="card-title"><span class="section-icon">🚗</span> 차종 선택 UI</div>

        <div class="form-row">
            <div class="form-label">차종 선택 UI 표시</div>
            <div class="form-ctrl">
                <div class="toggle-wrap">
                    <label class="toggle">
                        <input type="checkbox" name="home_car_selector_use" <?= $yn('home_car_selector_use') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="toggle-label">브랜드 → 시리즈 → 모델 선택 드롭다운 표시</span>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">
                안내 문구
                <div class="form-desc">차종 선택 영역 상단 설명 텍스트</div>
            </div>
            <div class="form-ctrl">
                <input type="text" name="home_car_selector_title"
                    value="<?= $val('home_car_selector_title') ?>"
                    placeholder="차량을 선택하면 해당 차종에 맞는 부품을 검색할 수 있습니다.">
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">OEM 번호 검색창</div>
            <div class="form-ctrl">
                <div class="toggle-wrap">
                    <label class="toggle">
                        <input type="checkbox" name="home_oem_search_use" <?= $yn('home_oem_search_use') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="toggle-label">OEM 부품번호 직접 검색 입력창 표시</span>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">
                OEM 검색창 Placeholder
                <div class="form-desc">검색창 내 안내 텍스트</div>
            </div>
            <div class="form-ctrl">
                <input type="text" name="home_oem_placeholder"
                    value="<?= $val('home_oem_placeholder') ?>"
                    placeholder="예) 부품번호 (OEM No.)">
            </div>
        </div>
    </div>

    <!-- ══ 3. 파츠 카테고리 그리드 ══ -->
    <div class="card">
        <div class="card-title"><span class="section-icon">🔧</span> 파츠 카테고리 그리드</div>

        <div class="form-row">
            <div class="form-label">파츠 그리드 표시</div>
            <div class="form-ctrl">
                <div class="toggle-wrap">
                    <label class="toggle">
                        <input type="checkbox" name="home_parts_grid_use" <?= $yn('home_parts_grid_use') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="toggle-label">41개 파츠 카테고리 이미지 그리드 표시</span>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">
                섹션 제목
                <div class="form-desc">그리드 위에 표시될 타이틀</div>
            </div>
            <div class="form-ctrl">
                <input type="text" name="home_parts_grid_title"
                    value="<?= $val('home_parts_grid_title') ?>" placeholder="PARTS">
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">
                한 줄 열 수
                <div class="form-desc">데스크톱 기준 카드 수</div>
            </div>
            <div class="form-ctrl">
                <div class="col-select" id="colBtns">
                    <?php foreach (['4','5','6'] as $c): ?>
                    <label>
                        <input type="radio" name="home_parts_grid_cols" value="<?= $c ?>"
                            <?= ($cfg['home_parts_grid_cols']??'6')===$c ? 'checked' : '' ?>>
                        <span class="col-btn <?= ($cfg['home_parts_grid_cols']??'6')===$c ? 'active' : '' ?>"><?= $c ?>열</span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">
                하단 안내문
                <div class="form-desc">그리드 아래 작은 글씨</div>
            </div>
            <div class="form-ctrl">
                <input type="text" name="home_parts_notice"
                    value="<?= $val('home_parts_notice') ?>"
                    placeholder="그 밖의 부품은 문의 주세요 | 이미지는 실제 제품과 다를 수 있으니...">
            </div>
        </div>
    </div>

    <!-- ══ 4. 브랜드 로고 바 ══ -->
    <div class="card">
        <div class="card-title"><span class="section-icon">🏢</span> 브랜드 로고 바</div>

        <div class="form-row">
            <div class="form-label">브랜드 바 표시</div>
            <div class="form-ctrl">
                <div class="toggle-wrap">
                    <label class="toggle">
                        <input type="checkbox" name="home_brand_bar_use" <?= $yn('home_brand_bar_use') ? 'checked' : '' ?>>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="toggle-label">등록된 차종 브랜드 로고를 가로 바로 표시</span>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">
                바 제목
                <div class="form-desc">브랜드 로고 위 텍스트</div>
            </div>
            <div class="form-ctrl">
                <input type="text" name="home_brand_bar_title"
                    value="<?= $val('home_brand_bar_title') ?>" placeholder="수입자동차 전문 부품">
            </div>
        </div>
    </div>

    <!-- ══ 5. 추가 CSS ══ -->
    <div class="card">
        <div class="card-title"><span class="section-icon">🎨</span> 고급 설정</div>

        <div class="form-row">
            <div class="form-label">
                추가 인라인 CSS
                <div class="form-desc">위젯에만 적용되는 커스텀 스타일</div>
            </div>
            <div class="form-ctrl">
                <textarea name="home_widget_css_extra" rows="4" placeholder=".pds-home-widget { max-width: 1200px; }"><?= $val('home_widget_css_extra') ?></textarea>
                <div class="alert-info">
                    💡 <code>.pds-home-widget</code> 클래스를 활용하면 위젯 전체 스타일을 제어할 수 있습니다.
                </div>
            </div>
        </div>
    </div>

    <!-- ══ 안내 박스 ══ -->
    <div class="preview-box" style="margin-bottom:20px;">
        <strong>📌 연동 방법</strong><br>
        설정 저장 후, 메인 홈 파일에서 아래 코드 한 줄이면 위젯이 자동 표시됩니다:<br><br>
        <code style="background:#e5e7eb;padding:3px 8px;border-radius:4px;font-size:12px;">
            &lt;?php include_once(G5_PATH.'/partsds/pds_home_widget.php'); ?&gt;
        </code><br><br>
        <strong>📁 include 위치 (아이윰 기준)</strong><br>
        • <code>eyoom/user/main.php</code> (아이윰 커뮤니티 메인)<br>
        • 쇼핑몰 메인: 아이윰 테마 shop 스킨 파일에 추가<br>
        • 또는 <strong>아이윰 관리자 → 메인디자인 → 추가 영역</strong>에 직접 삽입
    </div>

    <!-- 저장 버튼 -->
    <div style="display:flex; gap:12px; justify-content:flex-end; padding-bottom:40px;">
        <button type="button" class="btn btn-secondary" onclick="history.back()">취소</button>
        <button type="submit" class="btn btn-primary">💾 설정 저장</button>
    </div>

    </form>
    <?php endif; ?>

</div><!-- /pds-wrap -->

<script>
// 컬럼 선택 버튼 active 처리
document.querySelectorAll('#colBtns input[type="radio"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('#colBtns .col-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        this.nextElementSibling.classList.add('active');
    });
});
</script>
</body>
</html>
