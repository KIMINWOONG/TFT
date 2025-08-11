<?php
include "../../../../common.php";

$tNum="학술행사";
$sNum="학술대회 안내";
$bNum="7";
$g5['title'] = "";

//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$conference=sql_fetch("select * from g5_conference where sy_id='1' ");

$current_date = date('Y-m-d');
$is_early_reg_period = ($current_date >= $conference['sy_early_reg_start'] && $current_date <= $conference['sy_early_reg_end']);
$is_reg_period = ($current_date >= $conference['sy_reg_start'] && $current_date <= $conference['sy_reg_end']);

$schedule_list = [];
if ($conference['sy_id'] > 0) {
    $schedule_sql = "SELECT * FROM g5_conference_schedule WHERE ss_sy_id = {$conference['sy_id']} ORDER BY ss_order ASC";
    $schedule_result = sql_query($schedule_sql);
    
    while ($schedule = sql_fetch_array($schedule_result)) {
        $schedule_list[] = $schedule;
    }
}

$speaker_list = [];
if ($conference['sy_id'] > 0) {
    $speaker_sql = "SELECT * FROM g5_conference_speaker WHERE sp_sy_id = {$conference['sy_id']} ORDER BY sp_order ASC, sp_id ASC";
    $speaker_result = sql_query($speaker_sql);
    
    while ($speaker = sql_fetch_array($speaker_result)) {
        $speaker_list[] = $speaker;
    }
}

include_once(G5_THEME_PATH.'/head.php');
?>
<style>
.tabcont{display:none;}
</style>
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
				<li class="event_on"><a href="javascript:void(0)" class="tab_btn">개요</a></li>
				<li><a href="javascript:void(0)" class="tab_btn">인사말</a></li>
				<li><a href="javascript:void(0)" class="tab_btn">일정</a></li>
				<li><a href="javascript:void(0)" class="tab_btn">연자</a></li>
				<li><a href="javascript:void(0)" class="tab_btn">장소</a>
				<li><a href="javascript:void(0)" class="tab_btn">공지</a></li>
			</ul>
		</div>

		<div class="content_wrap tabcont" style="display:block;">
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
					<a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_06.php" class="item">
						<img src="<?php echo G5_THEME_IMG_URL ?>/event_icon1.png" alt="">
						<p>등록 안내</p>
					</a>
					<?php
					if ($is_early_reg_period || $is_reg_period){
					?>
					<a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_07.php" class="item">
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
		 </div>


	</div>
	<div class="content_wrap tabcont">
			<h4 class="sub_title2">
				초대의 글
			</h4>

			<div class="greeting">
			<?=$conference['sy_greeting']?>
			</div>
	</div>
	<div class="content_wrap tabcont">
			<h4 class="sub_title2">
				일정<span class="scroll_768"> * 좌우로 스크롤 해주세요.</span>
			</h4>

			<div class="tiem_table">
				<table>
					<tr>
						<th>일시</th>
						<th>강의내용</th>
						<th>연자</th>
					</tr>
					<?php 
					if (count($schedule_list) > 0) {
						foreach ($schedule_list as $schedule) {
							// 배경색이 설정된 경우 background 클래스 적용
							$bg_class = '';
							$bg_style = '';
							
							if (!empty($schedule['ss_bg_color']) && $schedule['ss_bg_color'] !== '#ffffff') {
								$bg_class = 'background';
								$bg_style = 'style="background-color: ' . $schedule['ss_bg_color'] . ';"';
							}
					?>
					<tr>
						<td class="tiem"><?php echo htmlspecialchars($schedule['ss_time']); ?></td>
						<td class="<?php echo $bg_class; ?>" <?php echo $bg_style; ?>><?php echo htmlspecialchars($schedule['ss_title']); ?></td>
						<td class="<?php echo $bg_class; ?>" <?php echo $bg_style; ?>><?php echo $schedule['ss_speaker'] ? htmlspecialchars($schedule['ss_speaker']) : '-'; ?></td>
					</tr>
					<?php 
						}
					} else {
					?>
					<tr>
						<td colspan="3" style="text-align: center; padding: 40px; color: #666;">
							등록된 일정이 없습니다.
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>

	</div>
	<div class="content_wrap tabcont">
			<h4 class="sub_title2">연자 소개</h4>

			<?php if (count($speaker_list) > 0) { ?>
			<div class="introduce">
				<?php foreach ($speaker_list as $speaker) { ?>
				<div class="item">
					<div class="img_box">
						<?php if (!empty($speaker['sp_photo'])) { ?>
							<img src="<?php echo $speaker['sp_photo']; ?>" alt="<?php echo htmlspecialchars($speaker['sp_name']); ?>">
						<?php } else { ?>
							<img src="<?php echo G5_THEME_IMG_URL ?>/profile2.png" alt="<?php echo htmlspecialchars($speaker['sp_name']); ?>">
						<?php } ?>
					</div>
					<h5><?php echo htmlspecialchars($speaker['sp_name']); ?></h5>
					<h6><?php echo $speaker['sp_specialty'] ? htmlspecialchars($speaker['sp_specialty']) : ''; ?></h6>
				</div>
				<?php } ?>
			</div>
			<?php } else { ?>
			<div class="no_speakers">
				<div class="no_content">
					<p>등록된 연자가 없습니다.</p>
				</div>
			</div>
			<?php } ?>

	</div>
	<div class="content_wrap tabcont">
			<h4 class="sub_title2">
				장소 안내
			</h4>
 
			<div class="map_img">
				<img src="<?php echo G5_DATA_URL ?>/conference/<?=$conference['sy_map_image']?>" alt="">
			</div>

    
			<div class="map_address">
				<?=$conference['sy_address']?>
			</div>

			<div class="map_time">
			 <span>일시 : </span>   <?=$conference['sy_time']?>
			</div>

	</div>
</div>
</div>
<script>
$(function(){
	$(".event_02 .tab_btn").on("click", function(){
		index=$(this).parent().index();
		$(".tabcont").hide();
		$(".event_02 li").removeClass("event_on");

		$(this).closest("li").addClass("event_on");
		$(".tabcont").eq(index).show();
	});
});
</script>

<?php
include_once(G5_THEME_PATH.'/tail.php');
