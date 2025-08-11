<?php
include "../../../../common.php";

$tNum = "학술행사";
$sNum = "학술집담회 안내";
$bNum="2";
$g5['title'] = "";

//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$conference=sql_fetch("select * from g5_conference where sy_gubun='1' and sy_status='active' order by sy_id desc limit 0,1 ");

// 집담회가 없는 경우 처리
// if (!$conference) {
//     $conference = ['sy_title' => '집담회 정보가 없습니다.', 'sy_id' => 0];
// }

// 해당 집담회의 연자 목록 가져오기
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
		<?php
		if($conference['sy_id']){
		?>
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
					<h3><?php echo htmlspecialchars($speaker['sp_name_en']); ?></h3>
					<h6><?php echo htmlspecialchars($speaker['sp_specialty']); ?></h6>
					<h4><?php echo htmlspecialchars($speaker['sp_specialty_en']); ?></h4>
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
<!--
				<div class="item">
					<div class="img_box">
						<img src="<?php echo G5_THEME_IMG_URL ?>/event_img7.png" alt="">
					</div>
					<h5>함대원 원장</h5>
					<h3>Dr. Daewon Ham</h3>
					<h6>이안맨하튼치과</h6>
					<h4>Ian Manhattan Dental Clinic</h4>
				</div>
				<div class="item">
					<div class="img_box">
						<img src="<?php echo G5_THEME_IMG_URL ?>/event_img8.png" alt="">
					</div>
					<h5>김문수 원장</h5>
					<h3>Dr. Moonsoo Kim</h3>
					<h6>램브란트 치과</h6>
					<h4>Lamplant Sangin Dental Clinic</h4>
				</div>
				<div class="item">
					<div class="img_box">
						<img src="<?php echo G5_THEME_IMG_URL ?>/event_img9.png" alt="">
					</div>
					<h5>이재일 원장</h5>
					<h3>Dr. Jae Il Lee</h3>
					<h6>닥터재일치과의원</h6>
					<h4>Doctor Jaeil Dental Clinic</h4>
				</div>
-->
		<?php
		}else{
		?>
			<div class="page_none">
				<div class="text">
					<img src="<?php echo G5_THEME_IMG_URL ?>/page_none.png" alt="">
					<h4>예정된 학술 집담회가 없습니다.</h4>
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
