<?php
include "../../../../common.php";

$tNum = "Academic Events";
$sNum = "Academic Seminar";
$bNum="2";
$g5['title'] = "";

//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


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
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_02.php#event_point">인사말</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_03.php#event_point">일정</a></li>
        <li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_04.php#event_point">연자</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_05.php#event_point">장소</a></li>
        <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=event_02#bo_list">공지</a></li>
    </ul>
 </div>

 <div class="content_wrap">
    <h4 class="sub_title2">
        연자 소개
    </h4>

    <div class="introduce">
        <div class="item">
            <div class="img_box">
                <img src="<?php echo G5_THEME_IMG_URL ?>/event_img6.png" alt="">
            </div>
            <h5>김용진 원장</h5>
            <h6>에스미르치과</h6>
        </div>
        <div class="item">
            <div class="img_box">
                <img src="<?php echo G5_THEME_IMG_URL ?>/event_img7.png" alt="">
            </div>
            <h5>함대원 원장</h5>
            <h6>이안맨하튼치과</h6>
        </div>
        <div class="item">
            <div class="img_box">
                <img src="<?php echo G5_THEME_IMG_URL ?>/event_img8.png" alt="">
            </div>
            <h5>김문수 원장</h5>
            <h6>램브란트 치과</h6>
        </div>
        <div class="item">
            <div class="img_box">
                <img src="<?php echo G5_THEME_IMG_URL ?>/event_img9.png" alt="">
            </div>
            <h5>이재일 원장</h5>
            <h6>닥터재일치과의원</h6>
        </div>
    </div>

 </div>


  </div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
