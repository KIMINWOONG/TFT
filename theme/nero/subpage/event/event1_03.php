<?php
include "../../../../common.php";

$tNum="학술행사";
$sNum="학술대회 안내";
$bNum="7";
$g5['title'] = "";

//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$conference=sql_fetch("select * from g5_conference where sy_gubun='2' and sy_status='active' order by sy_id desc limit 0,1 ");

$current_date = date('Y-m-d');
$is_early_reg_period = ($current_date >= $conference['sy_early_reg_start'] && $current_date <= $conference['sy_early_reg_end']);
$is_reg_period = ($current_date >= $conference['sy_reg_start'] && $current_date <= $conference['sy_reg_end']);

// 해당 집담회의 일정 목록 가져오기
$schedule_list = [];
if ($conference['sy_id'] > 0) {
    $schedule_sql = "SELECT * FROM g5_conference_schedule WHERE ss_sy_id = {$conference['sy_id']} ORDER BY ss_order ASC";
    $schedule_result = sql_query($schedule_sql);
    
    while ($schedule = sql_fetch_array($schedule_result)) {
        $schedule_list[] = $schedule;
    }
}

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
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_01.php#event_point">개요</a></li>
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_02.php#event_point">인사말</a></li>
				<li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_03.php#event_point">일정</a></li>
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_04.php#event_point">연자</a></li>
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_05.php#event_point">장소</a></ li>
				<li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=event_01#bo_list">공지</a></li>
			</ul>
		</div>

		<div class="content_wrap">
		<?php
		if($conference['sy_id']){
		?>
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
						<td class="<?php echo $bg_class; ?>" <?php echo $bg_style; ?>>
							<div class="ko"><?php echo htmlspecialchars($schedule['ss_title']); ?></div>
							<div class="en"><?php echo htmlspecialchars($schedule['ss_title_en']); ?></div>
						</td>
						<td class="<?php echo $bg_class; ?>" <?php echo $bg_style; ?>>
							<div class="ko"><?php echo $schedule['ss_speaker'] ? htmlspecialchars($schedule['ss_speaker']) : '-'; ?></div>
							<div class="en"><?php echo $schedule['ss_speaker_en'] ? htmlspecialchars($schedule['ss_speaker_en']) : '-'; ?></div>
						</td>
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


<?php
include_once(G5_THEME_PATH.'/tail.php');
