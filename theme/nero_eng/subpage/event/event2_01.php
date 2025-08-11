<?php
include "../../../../common.php";

$tNum = "Academic Events";
$sNum = "Academic Seminar";
$bNum="2";
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
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_01.php" class="menu_on">개최 예정 학술집담회</a></li>
            <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=symposium_last">지난 학술집담회 자료실</a></li>
        </ul>
    </div>
 <h2 class="contents_title"><?=$conference['sy_title']?></h2>

 <div class="event_menu">
    <ul class="event_02">
        <li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_01.php#event_point">개요</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_02.php#event_point">인사말</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_03.php#event_point">일정</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_04.php#event_point">연자</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_05.php#event_point">장소</a></ li>
        <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=event_02#bo_list">공지</a></li>
    </ul>
 </div>

 <div class="content_wrap">
    <div class="img"><img src="<?php echo G5_DATA_URL ?>/conference/<?=$conference['sy_main_image']?>" alt=""></div>

    <div class="wrap">
        <div class="latest_top_wr">
        <?php
    	echo latest('theme/basic', 'event_02', 4, 40);		// 최소설치시 자동생성되는 공지사항게시판
        ?>
    </div>
    <div class="quick_menu">
        <h3>퀵 메뉴</h3> 
        <div class="items">
            <a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_06.php" class="item">
                <img src="<?php echo G5_THEME_IMG_URL ?>/event_icon1.png" alt="">
                <p>등록 안내</p>
            </a>
			<?php
			if ($is_early_reg_period || $is_reg_period){
			?>
            <a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_07.php" class="item">
                <img src="<?php echo G5_THEME_IMG_URL ?>/event_icon2.png" alt="">
                <p>사전등록 신청</p>
            </a>
			<?php
			}
			?>
            <a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_08.php" class="item">
                <img src="<?php echo G5_THEME_IMG_URL ?>/event_icon3.png" alt="">
                <p>등록 신청 확인</p>
            </a>
          
        </div>
    </div>
    </div>
 </div>


  </div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
