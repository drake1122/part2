<?php
/**
 * 파츠디에스 관리자 - 회원 유형 관리
 * 경로: /partsds/admin/member_type_list.php
 *
 * - 일반회원 / 사업자회원 목록 조회
 * - 사업자회원 승인/반려 처리
 * - 차종 등록 현황 확인
 */
include_once('../../_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    exit;
}

$tab    = isset($_GET['tab'])     ? preg_replace('/[^a-z]/', '', $_GET['tab'])     : 'all';
$action = isset($_POST['action']) ? preg_replace('/[^a-z_]/', '', $_POST['action']) : '';
$msg    = '';

// ── POST 처리 (사업자 승인/반려) ──
if ($action === 'approve_business') {
    $mb_id   = isset($_POST['mb_id'])   ? trim($_POST['mb_id'])                         : '';
    $mb_level= isset($_POST['mb_level'])? (int)$_POST['mb_level']                       : 3; // 사업자 레벨
    if ($mb_id) {
        sql_query("UPDATE `{$g5['g5_member_table']}` SET mb_level={$mb_level}
                   WHERE mb_id='" . sql_escape_string($mb_id) . "'");
        $msg = "'{$mb_id}' 회원의 사업자 등급이 {$mb_level}으로 변경되었습니다.";
    }
} elseif ($action === 'set_normal') {
    $mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
    if ($mb_id) {
        sql_query("UPDATE `{$g5['g5_member_table']}` SET mb_level=2
                   WHERE mb_id='" . sql_escape_string($mb_id) . "'");
        $msg = "'{$mb_id}' 회원을 일반회원(레벨2)으로 변경했습니다.";
    }
}

// ── 통계 ──
$total_cnt   = sql_fetch("SELECT COUNT(*) AS cnt FROM `{$g5['g5_member_table']}`");
$normal_cnt  = sql_fetch("SELECT COUNT(*) AS cnt FROM `{$g5['g5_member_table']}` WHERE mb_7='normal' OR mb_7='' OR mb_7 IS NULL");
$biz_cnt     = sql_fetch("SELECT COUNT(*) AS cnt FROM `{$g5['g5_member_table']}` WHERE mb_7='business'");
$car_cnt     = sql_fetch("SELECT COUNT(*) AS cnt FROM `{$g5['g5_member_table']}` WHERE mb_4 IS NOT NULL AND mb_4 != '' AND mb_4 != '0'");

// ── 목록 쿼리 ──
$where = '';
$page  = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$rows  = 20;
$from  = ($page - 1) * $rows;

switch ($tab) {
    case 'business': $where = "WHERE mb_7='business'"; break;
    case 'normal':   $where = "WHERE (mb_7='normal' OR mb_7='' OR mb_7 IS NULL)"; break;
    case 'car':      $where = "WHERE mb_4 IS NOT NULL AND mb_4 != '' AND mb_4 != '0'"; break;
    default:         $where = ''; break;
}

$srch_text = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($srch_text) {
    $safe_q = sql_escape_string($srch_text);
    $srch_cond = " AND (mb_id LIKE '%{$safe_q}%' OR mb_name LIKE '%{$safe_q}%' OR mb_9 LIKE '%{$safe_q}%')";
    $where .= ($where ? $srch_cond : 'WHERE 1' . $srch_cond);
}

$total_row = sql_fetch("SELECT COUNT(*) AS cnt FROM `{$g5['g5_member_table']}` {$where}");
$total_page = ceil($total_row['cnt'] / $rows);

$members = [];
$res = sql_query("SELECT mb_id, mb_name, mb_email, mb_tel, mb_hp, mb_level,
                         mb_7, mb_8, mb_9, mb_10,
                         mb_1, mb_2, mb_3, mb_4, mb_5, mb_6,
                         mb_datetime, mb_today
                  FROM `{$g5['g5_member_table']}`
                  {$where}
                  ORDER BY mb_datetime DESC
                  LIMIT {$from}, {$rows}");
while ($row = sql_fetch_array($res)) $members[] = $row;

// ── 헤더 ──
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<div class="local_desc01 local_desc">
    <strong>파츠디에스 회원 유형 관리</strong>
    <ul>
        <li>회원 유형(일반/사업자)과 차종 등록 현황을 확인하고 사업자 회원 등급을 조정할 수 있습니다.</li>
        <li>사업자 회원 가입 후 <strong>승인(레벨 상향)</strong>으로 도매가 등 추가 혜택을 적용하세요.</li>
    </ul>
</div>

<?php if ($msg): ?>
<div class="local_desc02 local_desc" style="background:#d5f5e3; border:1px solid #a5d6a7; border-radius:4px; padding:10px 14px; margin-bottom:12px; color:#1e8449;">
    ✅ <?php echo htmlspecialchars($msg); ?>
</div>
<?php endif; ?>

<!-- 통계 -->
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:16px;">
    <?php
    $stats = [
        ['전체 회원', $total_cnt['cnt'], '#333'],
        ['일반 회원', $normal_cnt['cnt'], '#27ae60'],
        ['사업자 회원', $biz_cnt['cnt'], '#2980b9'],
        ['차종 등록', $car_cnt['cnt'], '#c0392b'],
    ];
    foreach ($stats as $s): ?>
    <div style="background:#fff; border:1px solid #ddd; border-radius:6px; padding:12px 16px; text-align:center; box-shadow:0 1px 3px rgba(0,0,0,.08);">
        <div style="font-size:22px; font-weight:700; color:<?php echo $s[2]; ?>;"><?php echo number_format($s[1]); ?></div>
        <div style="font-size:12px; color:#888; margin-top:4px;"><?php echo $s[0]; ?></div>
    </div>
    <?php endforeach; ?>
</div>

<!-- 탭 -->
<div style="border-bottom:2px solid #c0392b; margin-bottom:12px; display:flex; gap:0; align-items:flex-end;">
    <?php
    $tabs = [
        ['all', '전체 회원', $total_cnt['cnt']],
        ['business', '사업자 회원', $biz_cnt['cnt']],
        ['normal', '일반 회원', $normal_cnt['cnt']],
        ['car', '차종 등록 회원', $car_cnt['cnt']],
    ];
    foreach ($tabs as $t): ?>
    <a href="?tab=<?php echo $t[0]; ?>"
       style="display:inline-block; padding:8px 18px; font-size:14px; text-decoration:none;
              background:<?php echo $tab===$t[0] ? '#c0392b' : '#f5f5f5'; ?>;
              color:<?php echo $tab===$t[0] ? '#fff' : '#555'; ?>;
              border-radius:4px 4px 0 0; margin-right:2px;">
        <?php echo $t[1]; ?> <span style="font-size:11px;">(<?php echo $t[2]; ?>)</span>
    </a>
    <?php endforeach; ?>
</div>

<!-- 검색 -->
<form method="get" action="" style="margin-bottom:12px; display:flex; gap:6px; align-items:center;">
    <input type="hidden" name="tab" value="<?php echo htmlspecialchars($tab); ?>">
    <input type="text" name="q" value="<?php echo htmlspecialchars($srch_text); ?>"
           placeholder="아이디, 이름, 업체명 검색"
           style="height:36px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:13px; flex:1; max-width:300px;">
    <button type="submit" class="btn btn-small btn-default">검색</button>
    <?php if ($srch_text): ?>
    <a href="?tab=<?php echo htmlspecialchars($tab); ?>" class="btn btn-small btn-default">초기화</a>
    <?php endif; ?>
</form>

<!-- 목록 테이블 -->
<div class="local_list_top local_list_top2">
    <strong class="local_ov01">총 <em><?php echo number_format($total_row['cnt']); ?></em>명</strong>
</div>

<div class="tbl_head01 tbl_wrap">
<table>
<thead>
<tr>
    <th scope="col">아이디</th>
    <th scope="col">이름</th>
    <th scope="col">유형</th>
    <th scope="col">사업자정보</th>
    <th scope="col">차종</th>
    <th scope="col">레벨</th>
    <th scope="col">가입일</th>
    <th scope="col">관리</th>
</tr>
</thead>
<tbody>
<?php if (empty($members)): ?>
<tr><td colspan="8" class="empty_table">해당 회원이 없습니다.</td></tr>
<?php else: ?>
<?php foreach ($members as $m): ?>
<tr>
    <td>
        <a href="<?php echo G5_ADMIN_URL; ?>/member_form.php?mb_id=<?php echo urlencode($m['mb_id']); ?>">
            <?php echo htmlspecialchars($m['mb_id']); ?>
        </a>
    </td>
    <td><?php echo htmlspecialchars($m['mb_name']); ?></td>
    <td>
        <?php if ($m['mb_7'] === 'business'): ?>
        <span style="display:inline-block; padding:2px 8px; background:#dbeafe; color:#1d4ed8; border-radius:3px; font-size:11px; font-weight:700;">사업자</span>
        <?php else: ?>
        <span style="display:inline-block; padding:2px 8px; background:#d1fae5; color:#065f46; border-radius:3px; font-size:11px; font-weight:700;">일반</span>
        <?php endif; ?>
    </td>
    <td>
        <?php if ($m['mb_7'] === 'business'): ?>
        <small>
            <?php if ($m['mb_8']): ?><b>사업자번호:</b> <?php echo htmlspecialchars($m['mb_8']); ?><br><?php endif; ?>
            <?php if ($m['mb_9']): ?><b>업체명:</b> <?php echo htmlspecialchars($m['mb_9']); ?><br><?php endif; ?>
            <?php if ($m['mb_10']): ?><b>담당자:</b> <?php echo htmlspecialchars($m['mb_10']); ?><?php endif; ?>
        </small>
        <?php else: ?>
        <span style="color:#aaa;">-</span>
        <?php endif; ?>
    </td>
    <td>
        <?php
        $car_parts = array_filter([$m['mb_1'], $m['mb_2'], $m['mb_3']]);
        if ($car_parts) {
            echo '<span style="font-size:12px; color:#c0392b;"><i class="fa fa-car"></i> ';
            echo htmlspecialchars(implode(' › ', $car_parts));
            echo '</span>';
        } else {
            echo '<span style="color:#ccc;">미등록</span>';
        }
        ?>
    </td>
    <td>
        <span style="display:inline-block; padding:2px 8px; background:#f3f4f6; border-radius:3px; font-size:12px; font-weight:600;">
            Lv.<?php echo (int)$m['mb_level']; ?>
        </span>
    </td>
    <td style="font-size:12px;"><?php echo substr($m['mb_datetime'], 0, 10); ?></td>
    <td>
        <?php if ($m['mb_7'] === 'business'): ?>
        <!-- 사업자 승인/레벨 변경 -->
        <form method="post" style="display:inline;" onsubmit="return confirm('레벨을 변경하시겠습니까?');">
            <input type="hidden" name="action" value="approve_business">
            <input type="hidden" name="mb_id"  value="<?php echo htmlspecialchars($m['mb_id']); ?>">
            <select name="mb_level" style="height:28px; border:1px solid #ddd; border-radius:3px; font-size:12px;">
                <?php for ($lv=1; $lv<=10; $lv++): ?>
                <option value="<?php echo $lv; ?>" <?php if ($m['mb_level']==$lv) echo 'selected'; ?>>
                    Lv.<?php echo $lv; ?>
                </option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn btn-small btn-success" style="height:28px; font-size:12px;">적용</button>
        </form>
        <?php else: ?>
        <span style="color:#ccc; font-size:12px;">-</span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>

<!-- 페이징 -->
<?php
$qstr = "tab={$tab}";
if ($srch_text) $qstr .= '&q=' . urlencode($srch_text);
echo get_paging($config['cf_write_pages'], $page, $total_page, '?' . $qstr . '&page=');
?>

<div style="margin-top:20px; padding:12px 16px; background:#f9f9f9; border:1px solid #e5e7eb; border-radius:6px; font-size:13px; color:#666;">
    <strong>💡 안내</strong><br>
    • 사업자 회원 가입 후 이 화면에서 레벨(Lv)을 조정하여 도매가 등 추가 혜택을 적용할 수 있습니다.<br>
    • 사업자등록번호 확인 후 레벨을 올려주세요. (예: 일반=2, 사업자=3)<br>
    • 회원 차종은 로그인 시 쇼핑몰 상품 목록에서 자동 필터로 적용됩니다.
</div>

<?php include_once(G5_ADMIN_PATH . '/admin.tail.php'); ?>
