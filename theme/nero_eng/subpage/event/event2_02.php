<?php
include "../../../../common.php";

$tNum = "Academic Events";
$sNum = "Academic Seminar";
$bNum="2";
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
 <h2 class="contents_title"><?=$conference['sy_title']?></h2>

 <div class="event_menu">
    <ul class="event_02">
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_01.php#event_point">개요</a></li>
        <li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_02.php#event_point">인사말</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_03.php#event_point">일정</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_04.php#event_point">연자</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_05.php#event_point">장소</a></li>
        <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=event_02#bo_list">공지</a></li>
    </ul>
 </div>

 <div class="content_wrap">
    <h4 class="sub_title2">
        초대의 글
    </h4>

    <div class="greeting">
<?=$conference['sy_greeting']?>
    </div>
    
 </div>


  </div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
