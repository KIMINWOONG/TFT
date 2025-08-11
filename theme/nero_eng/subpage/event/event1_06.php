<?php
include "../../../../common.php";

$tNum="Academic Events";
$sNum="Conference Information";
$bNum="7";
$g5['title'] = "";

//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$conference=sql_fetch("select * from g5_conference where sy_status='active' order by sy_id desc limit 0,1 ");


include_once(G5_THEME_PATH.'/head.php');
?>

<div class="common event_2" id="event_point">
  <div class="width">
    <div class="sub_menu">
        <ul>
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_01.php" class="menu_on">개최 예정 학술집담회</a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=symposium_last">지난 학술집담회 자료실</a></li>
        </ul>
    </div>
 <h2 class="contents_title"> - 등록안내</h2>

 <div class="event_menu">
    <ul class="event_02_2">
        <li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_06.php#event_point">등록 안내</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_07.php#event_point">사전등록 신청</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_08.php#event_point">등록 신청 확인</a></li>
    </ul>
 </div>

  <div class="content_wrap">
    <div class="page_none">
        <div class="text">
            <img src="<?php echo G5_THEME_IMG_URL ?>/page_none.png" alt="">
            <h4>예정된 학술 대회가 없습니다.</h4>
        </div>
    </div>
  
 </div>




  </div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
