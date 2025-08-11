<?php
include "../../../../common.php";

$tNum="학술행사";
$sNum="학술대회 안내";
$bNum="7";
$g5['title'] = "";

$annual_fee_paid = false;
if ($is_member) {
    // 연회비 납부 여부 확인
    $current_year = date('Y');
    $membership_check = sql_fetch("SELECT * FROM g5_membership WHERE mb_member_id = '{$member['mb_id']}' AND (now() between mb_start_date and mb_end_date) AND mb_status = 'completed' AND mb_type = 'annual'");
    $annual_fee_paid = $membership_check ? true : false;

	if($annual_fee_paid){
		$amount=$conference['sy_fee_member'];
	}else{
		$amount=$conference['sy_fee_associate'];
	}
}else{
	$annual_fee_paid = true;
	$amount=$conference['sy_fee_nonmember'];
}

//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$conference=sql_fetch("select * from g5_conference where sy_gubun='2' and sy_status='active' order by sy_id desc limit 0,1 ");

$current_date = date('Y-m-d');
$is_early_reg_period = ($current_date >= $conference['sy_early_reg_start'] && $current_date <= $conference['sy_early_reg_end']);
$is_reg_period = ($current_date >= $conference['sy_reg_start'] && $current_date <= $conference['sy_reg_end']);

include_once(G5_THEME_PATH.'/head.php');
?>

<div class="common event_2" id="event_point">
	<div class="width">
		<div class="sub_menu">
			<ul>
				<li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_01.php" class="menu_on">개최 예정 학술대회</a></li>
				<li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=conference_last">지난 학술대회 자료실</a></li>
			</ul>
		</div>
		<h2 class="contents_title"><?=$conference['sy_title']?></h2>

		<div class="event_menu">
			<ul class="event_02">
				<li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_01.php#event_point">개요</a></li>
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_02.php#event_point">인사말</a></li>
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_03.php#event_point">일정</a></li>
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_04.php#event_point">연자</a></li>
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_05.php#event_point">장소</a>
				<li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=event_01#bo_list">공지</a></li>
			</ul>
		</div>

		<div class="content_wrap">
		<?php
		if($conference['sy_id']){
		?>
			<div class="img"><img src="<?php echo G5_DATA_URL ?>/conference/<?=$conference['sy_main_image']?>" alt=""></div>

			<div class="wrap">
				<div class="latest_top_wr">
				<?php
				echo latest('theme/basic', 'event_01', 4, 40);		// 최소설치시 자동생성되는 공지사항게시판
				?>
			</div>
			<div class="quick_menu">
				<h3>퀵 메뉴</h3> 
				<div class="items">
					<a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_06.php" class="item">
						<img src="<?php echo G5_THEME_IMG_URL ?>/event_icon1.png" alt="">
						<p>등록 안내</p>
					</a>
					<?php
					if ($is_early_reg_period || $is_reg_period){
						if($annual_fee_paid){
							$사전등록URL=G5_THEME_URL."/subpage/event/event2_07.php";
						}else{
							$사전등록URL="javascript:alert('연회비 납부가 필요합니다');location.href='../mypage/mypage_2.php';";
						}
					?>
					<a href="<?php echo $사전등록URL; ?>" class="item">
						<img src="<?php echo G5_THEME_IMG_URL ?>/event_icon2.png" alt="">
						<p>사전등록 신청</p>
					</a>
					<?php
					}
					?>
					<a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_08.php" class="item">
						<img src="<?php echo G5_THEME_IMG_URL ?>/event_icon3.png" alt="">
						<p>등록 신청 확인</p>
					</a>
				  
				</div>
			</div>
		<?php
		}else{
		?>
			<div class="page_none">
				<div class="text">
					<img src="<?php echo G5_THEME_IMG_URL ?>/page_none.png" alt="">
					<h4>예정된 학술 대회가 없습니다.</h4>
				</div>
			</div>
		<?php
		}
		?>
		 </div>


	</div>
</div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
