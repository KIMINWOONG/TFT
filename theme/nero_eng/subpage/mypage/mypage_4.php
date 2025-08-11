<?php
include "../../../../common.php";

$tNum="마이페이지";
$sNum="마이페이지";

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 로그인 체크
if (!$is_member) {
    alert("로그인 후 이용할 수 있습니다.", G5_URL);
}

include_once(G5_THEME_PATH.'/head2.php');
?>

<div class="mypage common">
  <div class="width">
    <div class="sub_menu">
        <ul>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_1.php">회원정보 수정</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_2.php">회비 납부 내역</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_3.php">학술대회 신청 내역</a></li>
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_4.php">초록 제출 및 수정</a></li>
        </ul>
    </div>

<?php
$sql_common = " from g5_conference_registration cr 
                left join g5_conference c on cr.cr_sy_id = c.sy_id 
                left join g5_conference_summary cs on (cr.cr_id = cs.as_cr_id AND cs.cr_nonemb_name = '{$nonemb_name}' AND cs.cr_nonemb_birth = '{$nonemb_birth}') ";
$sql_search = " where (1) ";
$limit[] = "(cr.cr_mb_id = '{$member['mb_id']}')";
$limit[] = "cr_status not in('cancelled')";
if(count($limit)>0){
	$wheres=" and ".implode(" and ", $limit);
}

if (!$sst) {
    $sst  = "sy_id ";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";
$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$wheres} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
    $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
}
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select cr.*, c.*, cs.as_id, cs.as_status, cs.as_submit_date {$sql_common} {$sql_search} {$wheres} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);
$start_item=$total_count-(($page-1)*$rows);
?>
    <h2>초록 제출 내역</h2>
    <div class="application">
        <table>
            <tr>
                <th style="width:7%;">No.</th>
                <th style="width:48%;">신청내용</th>
                <th style="width:25%;">기간</th>
                <th style="width:20%;">초록제출</th>
            </tr>
	<?php
	for($i=0;$row=sql_fetch_array($result);$i++){
		$status_map = array(
			'pending' => '미납',
			'completed' => '완료',
			'cancelled' => '취소'
		);

        // 초록 제출 상태 확인
        $abstract_submitted = !empty($row['as_id']); // 초록이 제출되었는지 확인
        $abstract_allowed = ($row['sy_summary_supply'] == "1"); // 초록 제출이 허용되는지 확인
	?>
            <tr>
                <td style="width:7%;"><?=$start_item--?></td>
                <td style="width:48%;"><?=$row['sy_title']?></td>
                <td style="width:25%;"><?=$row['sy_edate']?>까지</td>
                <td style="width:20%;">
                    <?php if ($abstract_submitted) { ?>
                        <!-- 초록이 제출된 경우 -->
						<?php if($row['as_status']=="revision_requested"){?>
							<a href="<?=G5_THEME_URL?>/subpage/mypage/mypage_8.php?cr_id=<?=$row['cr_id']?>" class="modify">수정하기</a>
						<?php }else{?>
							<span class="complete">제출완료</span>
						<?php } ?>
                    <?php } else if ($abstract_allowed) { ?>
                        <!-- 초록 제출이 가능한 경우 -->
                        <a href="<?=G5_THEME_URL?>/subpage/mypage/mypage_8.php?cr_id=<?=$row['cr_id']?>" class="not_submit">미제출</a>
                    <?php } else { ?>
                        <!-- 초록 제출이 제한된 경우 -->
                        <a href="javascript:void(0)" onclick="pop_open()" class="not_submit">제출불가</a>
                    <?php } ?>
				</td>
            </tr>
	<?php
	}
	if($i==0){
		echo '<tr><td colspan=10 align=center>등록된 데이터가 없습니다.</td></tr>';
	}
	?>
        </table>
    </div>
	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page='); ?>

   


  </div>
</div>



<?php
include_once(G5_THEME_PATH.'/tail.php');
