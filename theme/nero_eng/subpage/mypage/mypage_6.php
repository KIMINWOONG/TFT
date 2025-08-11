<?php
include "../../../../common.php";

$tNum="마이페이지";
$sNum="마이페이지";

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


include_once(G5_THEME_PATH.'/head2.php');
?>

<div class="mypage common">
  <div class="width">
    <div class="sub_menu sub_menu_nonmem">
        <ul>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_5.php" >회원정보</a></li>
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_6.php">학술대회 신청 내역</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_7.php">초록 제출 및 수정</a></li>
        </ul>
    </div>

<?php
// 비회원 세션 정보 확인
$nonemb_name = get_session("ss_nonemb_name");
$nonemb_birth = get_session("ss_nonemb_birth");

if (!$nonemb_name || !$nonemb_birth) {
    alert("세션이 만료되었습니다. 다시 로그인해주세요.", G5_URL);
}

$sql_common = " from g5_conference_registration cr 
                left join g5_conference c on cr.cr_sy_id = c.sy_id 
                left join g5_conference_summary cs on (cr.cr_id = cs.as_cr_id AND cs.cr_nonemb_name = '{$nonemb_name}' AND cs.cr_nonemb_birth = '{$nonemb_birth}') ";
$sql_search = " where (1) ";
$limit[] = "(cr.cr_nonemb_name = '{$nonemb_name}' and cr.cr_nonemb_birth = '{$nonemb_birth}')";
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

     <h2>학술대회 신청 내역</h2>
    <div class="application">
        <table>
            <tr>
                <th style="width:5%;">No.</th>
                <th style="width:55%;">신청내용</th>
                <th style="width:20%;">초록 제출</th>
                <th style="width:20%;">신청취소</th>
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
                <td style="width:5%;"><?=$start_item--?></td>
                <td style="width:55%;"><?=$row['sy_title']?></td>
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
                        <a href="<?=G5_THEME_URL?>/subpage/mypage/mypage_8.php?cr_id=<?=$row['cr_id']?>" class="submit">미제출</a>
                    <?php } else { ?>
                        <!-- 초록 제출이 제한된 경우 -->
                        <a href="javascript:void(0)" onclick="pop_open()" class="not_submit">제출불가</a>
                    <?php } ?>
				</td>
                <td style="width:20%;"><a href="#">취소하기</a></td>
            </tr>
	<?php
	}
	?>

        </table>
    </div>
	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page='); ?>

<!-- <div id="my_popup" class="my_pop_wrap">

  <div class="my_pop-inner">
    <h3>회원정보 입력</h3>
    <div class="my_pop-text">
     <div class="pop_title">
        <img src="<?php echo G5_THEME_IMG_URL ?>/mem_icon1.png" alt="">
        <div>
            <h4>초록등록을 원하시면 참가등록시 입력한</h4>
            <h5>이메일과 비밀번호를 입력해주세요.</h5>
        </div>
     </div>

     <div class="pop_input_wrap">
        <input type="text" placeholder="이메일을 입력해주세요.">
        <input type="text" placeholder="비밀번호를 입력해주세요.">
     </div>

     <div class="txt">
        <p>비밀번호 분실시 고객센터로 문의 주세요</p>
        <h6>T. 02-2273-3875</h6>
     </div>

     <div class="pop_btn_wrap">
        <div class="my_pop_close" onclick="pop_close();">취소</div>
        <a href="#" class="my_pop_ok">확인 </a>
     </div>
    </div>
    
  </div>
</div> -->

<div id="my_popup" class="my_pop_wrap">

  <div class="my_pop-inner">
    <!-- <h3>안내</h3> -->
    <div class="my_pop-text my_pop">
     <div class="pop_title">
        <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/mem_icon1.png" alt=""> -->
        <div>
            <h5>비회원은 초록 제출이 제한됩니다.</h5>
        </div>
     </div>

   

     <div class="pop_btn_wrap">
        <div class="my_pop_ok" onclick="pop_close();">확인</div>
     </div>
    </div>
    
  </div>
</div>



  </div>
</div>



<?php
include_once(G5_THEME_PATH.'/tail.php');
