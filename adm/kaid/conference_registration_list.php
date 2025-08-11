<?php
$sub_menu = "600210";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
}

$sql_common = " from g5_conference_registration cr 
                left join g5_conference c on cr.cr_sy_id = c.sy_id 
                left join g5_member m on cr.cr_mb_id = m.mb_id ";

$sql_search = " where (sy_gubun='1') ";

// 검색 조건 처리
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "cr.cr_name_kor":
            $sql_search .= " (cr.cr_name_kor like '%$stx%') ";
            break;
        case "cr.cr_email":
            $sql_search .= " (cr.cr_email like '%$stx%') ";
            break;
        case "cr.cr_hospital_name":
            $sql_search .= " (cr.cr_hospital_name like '%$stx%') ";
            break;
        case "c.sy_title":
            $sql_search .= " (c.sy_title like '%$stx%') ";
            break;
        default:
            $sql_search .= " (cr.cr_name_kor like '%$stx%' or cr.cr_email like '%$stx%' or cr.cr_hospital_name like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

// 필터 조건
$sy_id = isset($_GET['sy_id']) ? (int)$_GET['sy_id'] : 0;
if ($sy_id) {
    $sql_search .= " and cr.cr_sy_id = {$sy_id} ";
}

$member_type = isset($_GET['member_type']) ? $_GET['member_type'] : '';
if ($member_type == 'member') {
    $sql_search .= " and cr.cr_mb_id IS NOT NULL ";
} else if ($member_type == 'nonmember') {
    $sql_search .= " and cr.cr_mb_id IS NULL ";
}

$status = isset($_GET['status']) ? $_GET['status'] : '';
if ($status) {
    $sql_search .= " and cr.cr_status = '{$status}' ";
}

if (!$sst) {
    $sst = "cr.cr_reg_date";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page = ceil($total_count / $rows);
if ($page < 1) {
    $page = 1;
}
$from_record = ($page - 1) * $rows;

$sql = " select cr.*, c.sy_title, c.sy_sdate, c.sy_edate, m.mb_level, m.mb_work_name
         {$sql_common} {$sql_search} {$sql_order} 
         limit {$from_record}, {$rows} ";
$result = sql_query($sql);

// 학술집담회 목록
$conference_list = sql_query("SELECT * FROM g5_conference where sy_gubun='1' ORDER BY sy_sdate DESC, sy_id DESC");

// 통계 데이터
$stats_sql = "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN cr.cr_mb_id IS NOT NULL THEN 1 END) as members,
                COUNT(CASE WHEN cr.cr_mb_id IS NULL THEN 1 END) as nonmembers,
                COUNT(CASE WHEN cr.cr_status = 'pending' THEN 1 END) as pending,
                COUNT(CASE WHEN cr.cr_status = 'approved' THEN 1 END) as approved,
                COUNT(CASE WHEN cr.cr_status = 'rejected' THEN 1 END) as rejected
              FROM g5_conference_registration cr 
              WHERE 1=1 " . str_replace($sql_common, '', $sql_search);
$stats = sql_fetch($stats_sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '집담회 신청자 관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 9;

$status_text = array(
    'registered' => '신청완료',
    'cancelled' => '취소',
);
?>

<style>
.registration_stats {
    background: #f8f9fa;
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    display: flex;
    gap: 30px;
    align-items: center;
}

.stat_item {
    text-align: center;
}

.stat_number {
    font-size: 20px;
    font-weight: bold;
    color: #007bff;
}

.stat_label {
    font-size: 12px;
    color: #666;
    margin-top: 3px;
}

.status_badge {
    padding: 2px 6px;
    border-radius: 3px;
    font-weight: bold;
    color: white;
}

.status_registered { background: #28a745; }
.status_cancelled { background: #dc3545; }

.member_badge {
    padding: 1px 4px;
    border-radius: 2px;
    font-weight: bold;
}

.member_yes { background: #007bff; color: white; }
.member_no { background: #6c757d; color: white; }

.btn_xs {
    padding: 2px 6px;
    font-size: 11px;
    margin: 1px;
}

.filter_section {
    background: #f8f9fa;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.filter_row {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.filter_item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.filter_item label {
    font-weight: bold;
}

.filter_item select,
.filter_item input {
    padding: 4px 6px;
    border: 1px solid #ddd;
    border-radius: 3px;
}

.registration_detail {
    color: #666;
    margin-top: 2px;
}
</style>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01">
        <span class="ov_txt">전체</span>
        <span class="ov_num"><?php echo number_format($total_count) ?>건</span>
    </span>
</div>

<!-- 통계 요약 -->
<div class="registration_stats">
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['total'])?></div>
        <div class="stat_label">전체</div>
    </div>
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['members'])?></div>
        <div class="stat_label">회원</div>
    </div>
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['nonmembers'])?></div>
        <div class="stat_label">비회원</div>
    </div>
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['pending'])?></div>
        <div class="stat_label">대기중</div>
    </div>
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['approved'])?></div>
        <div class="stat_label">승인</div>
    </div>
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['rejected'])?></div>
        <div class="stat_label">반려</div>
    </div>
</div>

<!-- 필터 섹션 -->
<div class="filter_section">
    <form method="get" action="">
        <div class="filter_row">
            <div class="filter_item">
                <label>학술집담회</label>
                <select name="sy_id">
                    <option value="">전체</option>
                    <?php 
                    sql_data_seek($conference_list, 0);
                    while ($conf = sql_fetch_array($conference_list)) { 
                    ?>
                    <option value="<?=$conf['sy_id']?>" <?=($sy_id == $conf['sy_id']) ? 'selected' : ''?>>
                        <?=$conf['sy_year']?>년 <?=$conf['sy_title']?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="filter_item">
                <label>회원구분</label>
                <select name="member_type">
                    <option value="">전체</option>
                    <option value="member" <?=($member_type == 'member') ? 'selected' : ''?>>회원</option>
                    <option value="nonmember" <?=($member_type == 'nonmember') ? 'selected' : ''?>>비회원</option>
                </select>
            </div>
            
            <div class="filter_item">
                <label>상태</label>
                <select name="status">
                    <option value="">전체</option>
                    <option value="pending" <?=($status == 'pending') ? 'selected' : ''?>>대기중</option>
                    <option value="approved" <?=($status == 'approved') ? 'selected' : ''?>>승인</option>
                    <option value="rejected" <?=($status == 'rejected') ? 'selected' : ''?>>반려</option>
                </select>
            </div>
            
            <div class="filter_item">
                <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
                <input type="hidden" name="stx" value="<?php echo $stx ?>">
                <input type="hidden" name="page" value="<?php echo $page ?>">
                <button type="submit" class="btn btn_02">필터 적용</button>
            </div>
        </div>
    </form>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
    <input type="hidden" name="sy_id" value="<?php echo $sy_id ?>">
    <input type="hidden" name="member_type" value="<?php echo $member_type ?>">
    <input type="hidden" name="status" value="<?php echo $status ?>">
    
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="cr.cr_name_kor" <?php echo get_selected($sfl, "cr.cr_name_kor", true); ?>>신청자명</option>
        <option value="cr.cr_email" <?php echo get_selected($sfl, "cr.cr_email"); ?>>이메일</option>
        <option value="cr.cr_hospital_name" <?php echo get_selected($sfl, "cr.cr_hospital_name"); ?>>소속기관</option>
        <option value="c.sy_title" <?php echo get_selected($sfl, "c.sy_title"); ?>>학술집담회</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
    <input type="submit" value="검색" class="btn_submit">
</form>

<form name="flist" id="flist" action="./conference_registration_list_update.php" onsubmit="return flist_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sy_id" value="<?php echo $sy_id ?>">
    <input type="hidden" name="member_type" value="<?php echo $member_type ?>">
    <input type="hidden" name="status" value="<?php echo $status ?>">
    <input type="hidden" name="token" value="<?php echo isset($token) ? $token : ''; ?>">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
                <tr>
                    <th scope="col">
                        <label for="chkall" class="sound_only">신청자 전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th>
                    <th scope="col">신청일</th>
                    <th scope="col">학술집담회</th>
                    <th scope="col">신청자정보</th>
                    <th scope="col">소속기관</th>
                    <th scope="col">연락처</th>
                    <th scope="col">회원구분</th>
                    <th scope="col">상태</th>
                    <th scope="col" style="min-width:100px;">관리</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    $status_class = 'status_' . $row['cr_status'];
                    $member_class = $row['cr_mb_id'] ? 'member_yes' : 'member_no';
                    $member_text = $row['cr_mb_id'] ? '회원' : '비회원';
                    
                    $view_link = '<a href="./conference_registration_form.php?cr_id='.$row['cr_id'].'&'.$qstr.'" class="btn btn_03 btn_xs">상세</a>';
                    
                    $bg = 'bg' . ($i % 2);
                ?>
                <tr class="<?php echo $bg; ?>">
                    <td class="td_chk">
                        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        <input type="hidden" name="cr_id[<?=$i?>]" value="<?=$row['cr_id']?>">
                    </td>
                    <td class="td_datetime">
                        <?=date('Y-m-d H:i', strtotime($row['cr_reg_date']))?>
                    </td>
                    <td class="td_left">
                        <span >
                            <?=$row['sy_title']?>
                        </span>
                        <div class="registration_detail">
                            집담회: <?=$row['sy_sdate']."~".$row['sy_edate']?>
                        </div>
                    </td>
                    <td class="td_left">
                        <div style="font-weight: bold;">
                            <?php if ($row['cr_mb_id']) { ?>
                                <?=htmlspecialchars($row['cr_name_kor'])?>
                            <?php } else { ?>
                                <?=htmlspecialchars($row['cr_nonemb_name'])?>
                            <?php } ?>
                        </div>
                        <div class="registration_detail">
                            <?=htmlspecialchars($row['cr_email'])?>
                        </div>
                        <?php if ($row['cr_mb_id']) { ?>
                        <div class="registration_detail">
                            ID: <?=$row['cr_mb_id']?> (Lv.<?=$row['mb_level']?>)
                        </div>
                        <?php } ?>
                    </td>
                    <td class="td_left">
                        <?php 
                        // 회원인 경우 회원정보의 근무지명을, 비회원인 경우 신청시 입력한 병원명을 표시
                        $hospital_name = $row['cr_mb_id'] && $row['mb_work_name'] ? $row['mb_work_name'] : $row['cr_hospital_name'];
                        echo htmlspecialchars($hospital_name);
                        ?>
                        <?php if ($row['cr_department']) { ?>
                        <div class="registration_detail">
                            <?=htmlspecialchars($row['cr_department'])?>
                        </div>
                        <?php } ?>
                    </td>
                    <td class="td_center">
                        <?=$row['cr_mobile_carrier']?>-<?=$row['cr_mobile1']?>-<?=$row['cr_mobile2']?>
                        <?php if ($row['cr_phone1']) { ?>
                        <div class="registration_detail">
                            Tel: <?=$row['cr_phone1']?>-<?=$row['cr_phone2']?>-<?=$row['cr_phone3']?>
                        </div>
                        <?php } ?>
                    </td>
                    <td class="td_center">
                        <span class="member_badge <?=$member_class?>">
                            <?=$member_text?>
                        </span>
                    </td>
                    <td class="td_center">
                        <span class="status_badge <?=$status_class?>">
                            <?=$status_text[$row['cr_status']]?>
                        </span>
                    </td>
                    <td class="td_mng td_mng_s">
                        <?php echo $view_link ?>
                        <br>
                        <?php if ($row['cr_status'] != 'registered') { ?>
                        <button type="button" class="btn btn_01 btn_xs" onclick="changeStatus(<?=$row['cr_id']?>, 'registered')">신청완료</button>
                        <?php } ?>
                        <?php if ($row['cr_status'] != 'cancelled') { ?>
                        <button type="button" class="btn btn_02 btn_xs" onclick="changeStatus(<?=$row['cr_id']?>, 'cancelled')">취소</button>
                        <?php } ?>
                    </td>
                </tr>
                <?php
                }
                if ($i == 0) {
                    echo '<tr><td colspan="' . $colspan . '" class="empty_table">자료가 없습니다.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="btn_confirm01 btn_confirm">
        <?php if ($is_admin == 'super') { ?>
			<!--
            <input type="submit" name="act_button" value="선택승인" onclick="document.pressed=this.value" class="btn_01 btn">
            <input type="submit" name="act_button" value="선택반려" onclick="document.pressed=this.value" class="btn_02 btn">
			-->
            <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_02 btn">
        <?php } ?>
        <a href="./registration_excel_export_xlsx.php?<?php echo $qstr ?>&type=conference" class="btn_03 btn">엑셀 다운로드 (XLSX)</a>
				<!--
        <a href="./registration_statistics.php" class="btn_01 btn">통계 보기</a>
		-->
    </div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page='); ?>
<iframe name="hidfrm" style="display:none;"></iframe>

<script>
function flist_submit(f) {
    if (!is_checked("chk[]")) {
        alert(document.pressed + " 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if (document.pressed == "선택삭제") {
        if (!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

	f.target="hidfrm";

    return true;
}

// 상태 변경
function changeStatus(cr_id, status) {
    const statusText = {
        'registered': '신청완료',
        'cancelled': '취소',
    };
		/**
	if(status=="approved"){
		statusText="승인";
	}else if(status=="rejected"){
		statusText="반려";
	}else if(status=="pending"){
		statusText="대기중";
	}
	*/
    
    if (confirm(`정말로 ${statusText[status]}하시겠습니까?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = './registration_status_update.php';
		form.target="hidfrm";
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'cr_id';
        idInput.value = cr_id;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        
        form.appendChild(idInput);
        form.appendChild(statusInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>