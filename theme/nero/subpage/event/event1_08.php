<?php
include "../../../../common.php";

$tNum="학술행사";
$sNum="학술대회 안내";
$bNum="7";
$g5['title'] = "";

if (!$is_member && !$is_nonemember)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_THEME_URL."/subpage/event/event1_07.php"));

//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$conference=sql_fetch("select * from g5_conference where sy_gubun='2' and sy_status='active' order by sy_id desc limit 0,1 ");


include_once(G5_THEME_PATH.'/head.php');
?>

<div class="common event_2" id="event_point">
  <div class="width">
    <div class="sub_menu">
        <ul>
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_01.php" class="menu_on">개최 예정 학술대회</a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=symposium_last">지난 학술대회 자료실</a></li>
        </ul>
    </div>
 <h2 class="contents_title"><?=$conference['sy_title']?> - 등록 신청 확인</h2>

 <div class="event_menu">
    <ul class="event_02_2">
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_06.php#event_point">등록 안내</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_07.php#event_point">사전등록 신청</a></li>
        <li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_08.php#event_point">등록 신청 확인</a></li>
    </ul>
 </div>

<?php
$limit=array();
$sql_common = " from g5_conference_registration cr 
                left join g5_conference c on cr.cr_sy_id = c.sy_id 
                left join g5_conference_summary cs on (cr.cr_id = cs.as_cr_id) ";

$sql_search = " where (cr_status not in('cancelled')) ";
if($is_member){
	$limit[]="cr.cr_mb_id='{$member['mb_id']}'";
}else{
	$limit[]="(cr.cr_nonemb_name='".get_session("ss_nonemb_name")."' and cr.cr_nonemb_birth='".get_session("ss_nonemb_birth")."')";
}
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
 <div class="content_wrap">
   <h4 class="sub_title2">대회 등록 신청 목록</h4>

   <table class="registe_check">
    <tr>
        <th>No.</th>
        <th>행사명</th>
        <th>대회개최기간</th>
        <th>등록비</th>
        <th>결제 상태</th>
        <th>초록 상태</th>
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
        // 초록 제출이 허용되는지 확인
		$abstract_allowed = (($is_nonemember && $row['sy_summary_supply'] == "1") || ($is_member && $row['sy_member_summary_supply']));
	?>
    <tr>
        <td><?=$start_item--?></td>
        <td><?=$row['sy_title']?></td>
        <td><?=$row['sy_sdate']?> ~ <?=$row['sy_edate']?></td>
        <td><?=number_format($row['cr_registration_fee'])?> 원</td>
        <td><?=$status_map[$row['cr_payment_status']]?></td>
        <td>
                    <?php if ($abstract_submitted) { ?>
                        <!-- 초록이 제출된 경우 -->
						<?php if($row['as_status']=="revision_requested"){?>
							<a href="<?=G5_THEME_URL?>/subpage/mypage/mypage_8.php?cr_id=<?=$row['cr_id']?>" class="modify">수정하기</a>
						<?php }else{?>
							<span class="complete">제출완료</span>
						<?php } ?>
                    <?php } else if ($abstract_allowed) { ?>
                        <!-- 초록 제출이 가능한 경우 -->
                        <a href="<?=G5_THEME_URL?>/subpage/mypage/mypage_4.php?cr_id=<?=$row['cr_id']?>" class="submit">미제출</a>
                    <?php } else { ?>
                        <!-- 초록 제출이 제한된 경우 -->
                        <a href="javascript:void(0)" onclick="pop_open()" class="not_submit">제출불가</a>
                    <?php } ?>




            <!--
            <a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_4.php">미제출</a>
            <span class="color_b">제출</span>-->
        </td>  
    </tr>
	<?php
	}
	if($i==0) {
		echo '<tr><td colspan=10 align=center>신청한 대회가 없습니다</td></tr>';
	}
	?>
   </table>
 </div>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page='); ?>
  </div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
