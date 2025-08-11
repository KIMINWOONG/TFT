<?php
include "../../../../common.php";

$tNum="Academic Events";
$sNum="Conference Information";
$bNum="7";
$g5['title'] = "";

//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$conference=sql_fetch("select * from g5_conference where sy_status='active' order by sy_id desc limit 0,1 ");

$current_date = date('Y-m-d');
$is_early_reg_period = ($current_date >= $conference['sy_early_reg_start'] && $current_date <= $conference['sy_early_reg_end']);
$is_reg_period = ($current_date >= $conference['sy_reg_start'] && $current_date <= $conference['sy_reg_end']);

include_once(G5_THEME_PATH.'/head.php');
?>

<div class="common event_2" id="event_point">
  <div class="width">
    <div class="sub_menu">
        <ul>
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_01.php" class="menu_on">개최 예정 학술집담회</a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=conference_last">지난 학술집담회 자료실</a></li>
        </ul>
    </div>
 <h2 class="contents_title">2025년 서울학술집담회</h2>

 <div class="event_menu">
    <ul class="event_02">
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_01.php#event_point">개요</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_02.php#event_point">인사말</a></li>
        <li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_03.php#event_point">일정</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_04.php#event_point">연자</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_05.php#event_point">장소</a></ li>
        <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=event_01#bo_list">공지</a></li>
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
