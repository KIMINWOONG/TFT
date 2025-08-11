<?php
$sub_menu = "600300";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

$sql_common = " from g5_conference_summary cs 
                left join g5_conference c on cs.as_sy_id = c.sy_id 
                left join g5_conference_registration cr on cs.as_cr_id = cr.cr_id ";

$sql_search = " where (sy_gubun='2') ";

// 검색 조건 처리
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "cs.as_title_kor":
            $sql_search .= " (cs.as_title_kor like '%$stx%') ";
            break;
        case "cs.as_submitter":
            $sql_search .= " (cs.as_submitter like '%$stx%') ";
            break;
        case "cs.as_presenter_name":
            $sql_search .= " (cs.as_presenter_name like '%$stx%') ";
            break;
        case "c.sy_title":
            $sql_search .= " (c.sy_title like '%$stx%') ";
            break;
        default:
            $sql_search .= " (cs.as_title_kor like '%$stx%' or cs.as_submitter like '%$stx%' or cs.as_presenter_name like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

// 필터 조건
$sy_id = isset($_GET['sy_id']) ? (int)$_GET['sy_id'] : 0;
if ($sy_id) {
    $sql_search .= " and cs.as_sy_id = {$sy_id} ";
}

$status = isset($_GET['status']) ? $_GET['status'] : '';
if ($status) {
    $sql_search .= " and cs.as_status = '{$status}' ";
}

$field = isset($_GET['field']) ? $_GET['field'] : '';
if ($field) {
    $sql_search .= " and cs.as_presentation_field = '{$field}' ";
}

if (!$sst) {
    $sst = "cs.as_submit_date";
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

$sql = " select cs.*, c.sy_title, c.sy_sdate, c.sy_edate, cr.cr_name_kor, cr.cr_mb_id, cr.cr_nonemb_name 
         {$sql_common} {$sql_search} {$sql_order} 
         limit {$from_record}, {$rows} ";
$result = sql_query($sql);

// 학술학술대회 목록
$conference_list = sql_query("SELECT * FROM g5_conference where sy_gubun='2' ORDER BY sy_sdate DESC, sy_id DESC");

// 통계 데이터
$stats_sql = "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN cs.as_status = 'submitted' THEN 1 END) as submitted,
                COUNT(CASE WHEN cs.as_status = 'reviewed' THEN 1 END) as reviewed,
                COUNT(CASE WHEN cs.as_status = 'accepted' THEN 1 END) as accepted,
                COUNT(CASE WHEN cs.as_status = 'rejected' THEN 1 END) as rejected
              FROM g5_conference_summary cs left join g5_conference c on cs.as_sy_id = c.sy_id  
              " . str_replace($sql_common, '', $sql_search);
$stats = sql_fetch($stats_sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '초록 관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

$colspan = 8;

$status_text = array(
    'submitted' => '제출됨',
    'reviewed' => '심사중', 
    'revision_requested' => '수정요청',
    'accepted' => '승인',
    'rejected' => '반려'
);
?>

<style>
.abstract_stats {
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
    font-size: 11px;
    font-weight: bold;
    color: white;
}

.status_submitted { background: #6c757d; }
.status_reviewed { background: #ffc107; color: #000; }
.status_accepted { background: #28a745; }
.status_rejected { background: #dc3545; }

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
    font-size: 12px;
    font-weight: bold;
}

.filter_item select,
.filter_item input {
    padding: 4px 6px;
    font-size: 12px;
    border: 1px solid #ddd;
    border-radius: 3px;
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
<div class="abstract_stats">
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['total'])?></div>
        <div class="stat_label">전체</div>
    </div>
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['submitted'])?></div>
        <div class="stat_label">제출됨</div>
    </div>
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['reviewed'])?></div>
        <div class="stat_label">심사중</div>
    </div>
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['revision_requested'])?></div>
        <div class="stat_label">수정요청</div>
    </div>
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['accepted'])?></div>
        <div class="stat_label">채택</div>
    </div>
    <div class="stat_item">
        <div class="stat_number"><?=number_format($stats['rejected'])?></div>
        <div class="stat_label">탈락</div>
    </div>
</div>

<!-- 필터 섹션 -->
<div class="filter_section">
    <form method="get" action="">
        <div class="filter_row">
            <div class="filter_item">
                <label>학술대회</label>
                <select name="sy_id">
                    <option value="">전체</option>
                    <?php while ($conf = sql_fetch_array($conference_list)) { ?>
                    <option value="<?=$conf['sy_id']?>" <?=($sy_id == $conf['sy_id']) ? 'selected' : ''?>>
                        [<?=$conf['sy_sdate']."~".$conf['sy_edate']?>] <?=$conf['sy_title']?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            
            <div class="filter_item">
                <label>상태</label>
                <select name="status">
                    <option value="">전체</option>
                    <option value="submitted" <?=($status == 'submitted') ? 'selected' : ''?>>제출됨</option>
					<option value="revision_requested" <?=($status == 'revision_requested') ? 'selected' : ''?>>수정요청</option>
                    <option value="reviewed" <?=($status == 'reviewed') ? 'selected' : ''?>>심사중</option>
                    <option value="accepted" <?=($status == 'accepted') ? 'selected' : ''?>>채택</option>
                    <option value="rejected" <?=($status == 'rejected') ? 'selected' : ''?>>탈락</option>
                </select>
            </div>
            
            <div class="filter_item">
                <label>분야</label>
                <select name="field">
                    <option value="">전체</option>
                    <option value="구강" <?=($field == '구강') ? 'selected' : ''?>>구강</option>
                    <option value="예방" <?=($field == '예방') ? 'selected' : ''?>>예방</option>
                    <option value="치주" <?=($field == '치주') ? 'selected' : ''?>>치주</option>
                    <option value="소아치과" <?=($field == '소아치과') ? 'selected' : ''?>>소아치과</option>
                    <option value="치과" <?=($field == '치과') ? 'selected' : ''?>>치과</option>
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
    <input type="hidden" name="status" value="<?php echo $status ?>">
    <input type="hidden" name="field" value="<?php echo $field ?>">
    
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="cs.as_title_kor" <?php echo get_selected($sfl, "cs.as_title_kor", true); ?>>논문제목</option>
        <option value="cs.as_submitter" <?php echo get_selected($sfl, "cs.as_submitter"); ?>>제출자</option>
        <option value="cs.as_presenter_name" <?php echo get_selected($sfl, "cs.as_presenter_name"); ?>>발표자</option>
        <option value="c.sy_title" <?php echo get_selected($sfl, "c.sy_title"); ?>>학술대회</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
    <input type="submit" value="검색" class="btn_submit">
</form>

<form name="flist" id="flist" action="./symposium_summary_list_update.php" onsubmit="return flist_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sy_id" value="<?php echo $sy_id ?>">
    <input type="hidden" name="status" value="<?php echo $status ?>">
    <input type="hidden" name="field" value="<?php echo $field ?>">
    <input type="hidden" name="token" value="<?php echo isset($token) ? $token : ''; ?>">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
                <tr>
                    <th scope="col">
                        <label for="chkall" class="sound_only">초록 전체</label>
                        <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                    </th>
                    <th scope="col">제출일</th>
                    <th scope="col">학술대회</th>
                    <th scope="col">논문제목</th>
                    <th scope="col">제출자</th>
                    <th scope="col">발표분야/발표유형</th>
                    <th scope="col">상태</th>
                    <th scope="col">관리</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    $status_class = 'status_' . $row['as_status'];
                    
                    $view_link = '<a href="./symposium_summary_view.php?as_id='.$row['as_id'].'&'.$qstr.'" class="btn btn_03 btn_xs">상세</a>';
                    
                    $bg = 'bg' . ($i % 2);
                ?>
                <tr class="<?php echo $bg; ?>">
                    <td class="td_chk">
                        <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
                        <input type="hidden" name="as_id[<?=$i?>]" value="<?=$row['as_id']?>">
                    </td>
                    <td class="td_datetime">
                        <?=date('m-d H:i', strtotime($row['as_submit_date']))?>
                    </td>
                    <td class="td_left">
                        <?=$row['sy_sdate']."~".$row['sy_edate']?><br>
                        <span style="font-size: 16px; color: #666;">
                            <?=mb_substr($row['sy_title'], 0, 15)?>
                        </span>
                    </td>
                    <td class="td_left">
                        <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" 
                             title="<?=htmlspecialchars($row['as_title_kor'])?>">
                            <?=htmlspecialchars($row['as_title_kor'])?>
                        </div>
                        <?php if ($row['as_title_eng']) { ?>
                        <div style="font-size: 11px; color: #666; margin-top: 2px;">
                            <?=mb_substr(htmlspecialchars($row['as_title_eng']), 0, 30)?>...
                        </div>
                        <?php } ?>
                    </td>
                    <td>
                        <?=htmlspecialchars($row['as_submitter'])?>
                        <br>
                        <span style="font-size: 11px; color: #666;">
                            <?php if ($row['cr_mb_id']) { ?>
                                <?=htmlspecialchars($row['cr_name_kor'])?> (회원)
                            <?php } else { ?>
                                <?=htmlspecialchars($row['cr_nonemb_name'])?> (비회원)
                            <?php } ?>
                        </span>
                    </td>
                    <td class="td_center">
                        <?=$row['as_presentation_field']?><br>
                        <?=$row['as_presentation_type']?>
                    </td>
                    <td class="td_center">
                        <span class="status_badge <?=$status_class?>">
                            <?=$status_text[$row['as_status']]?>
                        </span>
                    </td>
                    <td class="td_mng td_mng_s">
                        <?php echo $view_link ?>
                        <br>
                        <?php if ($row['as_status'] != 'accepted') { ?>
                        <button type="button" class="btn btn_01 btn_xs" onclick="changeStatus(<?=$row['as_id']?>, 'accepted')">채택</button>
                        <?php } ?>
                        <?php if ($row['as_status'] != 'rejected') { ?>
                        <button type="button" class="btn btn_02 btn_xs" onclick="changeStatus(<?=$row['as_id']?>, 'rejected')">탈락</button>
                        <?php } ?>
                        <?php if ($row['as_file_path']) { ?>
                        <br><a href="./abstract_download.php?as_id=<?=$row['as_id']?>" class="btn btn_03 btn_xs">파일</a>
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
            <input type="submit" name="act_button" value="선택채택" onclick="document.pressed=this.value" class="btn_01 btn">
            <input type="submit" name="act_button" value="선택탈락" onclick="document.pressed=this.value" class="btn_02 btn">
            <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn_02 btn">
        <?php } ?>
		<!--
        <a href="./abstract_excel_export.php?<?php echo $qstr ?>" class="btn_03 btn">엑셀 다운로드</a>
        <a href="./abstract_statistics.php" class="btn_01 btn">통계 보기</a>
		-->
    </div>
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page='); ?>

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
    } else if (document.pressed == "선택채택") {
        if (!confirm("선택한 초록을 채택하시겠습니까?")) {
            return false;
        }
    } else if (document.pressed == "선택탈락") {
        if (!confirm("선택한 초록을 탈락시키시겠습니까?")) {
            return false;
        }
    }

    return true;
}

// 상태 변경
function changeStatus(as_id, status) {
    const statusText = {
        'accepted': '채택',
        'rejected': '탈락',
        'reviewed': '심사중'
    };
    
    if (confirm(`정말로 ${statusText[status]}하시겠습니까?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = './abstract_status_update.php';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'as_id';
        idInput.value = as_id;
        
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