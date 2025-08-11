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
				<li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_01.php" class="menu_on">개최 예정 학술집담회</a></li>
				<li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=symposium_last">지난 학술집담회 자료실</a></li>
			</ul>
		</div>
		<h2 class="contents_title"><?=$conference['sy_title']?></h2>

		<div class="event_menu">
			<ul class="event_02">
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_01.php#event_point">개요</a></li>
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_02.php#event_point">인사말</a></li>
				<li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_03.php#event_point">일정</a></li>
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_04.php#event_point">연자</a></li>
				<li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_05.php#event_point">장소</a></li>
				<li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=event_02#bo_list">공지</a></li>
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



            <!-- <div class="tiem_table">
        <table>
            <tr>
                <th>일시</th>
                <th>강의내용</th>
                <th>연자</th>
            </tr>
            <tr>
                <td class="tiem">14:20 ~ 15:00</td>
                <td>
                    <div class="ko">분과별 학술모임</div>
                    <div class="en">Sectional Academic Meeting</div>
                </td>
                <td>
                    <div class="ko">-</div>
                    <div class="en"></div>
                </td>
            </tr>
            <tr>
                <td class="tiem">15:00 ~ 15:40</td>
                <td class="background">
                    <div class="ko">장기적 안정성을 위한 연조직 전략; 전통적인 기법부터 최신 경향까지</div>
                    <div class="en">Redefining Implant Longevity:Advances in Peri-implant Keratinized Tissue Augmentation</div>
                </td>
                <td class="background">
                    <div class="ko">김용진 원장</div>
                    <div class="en">Dr. Kim Yongjin</div>
                </td>
            </tr>
            <tr>
                <td class="tiem">15:40 ~ 16:20</td>
                <td class="background">
                    <div class="ko">ISD; 최적의 임플란트 심미와 기능을 위한 수술부위에 대한 다각적인 접근</div>
                    <div class="en">Implant Site Development; multidisciplinary approach</div>
                </td>
                <td class="background">
                    <div class="ko">함대원 원장</div>
                    <div class="en">Dr. Daewon Ham</div>
                </td>
            </tr>
            <tr>
                <td class="tiem">16:20 ~ 16:40</td>
                <td>
                    <div class="ko">논의진행</div>
                    <div class="en">Sectional Academic Meeting</div>
                </td>
                <td>
                    <div class="ko">이창규 부회장</div>
                    <div class="en">Dr. ChangKyu Lee</div>
                </td>
            </tr>
            <tr>
                <td class="tiem">16:40 ~ 17:20</td>
                <td class="background">
                    <div class="ko">“전치부 임플란트: 단순함 속에 숨은 전략”</div>
                    <div class="en">“Anterior Implants: Hidden Strategies Behind Simplicity”</div>
                </td>
                <td class="background">
                    <div class="ko">김문수 원장임</div>
                    <div class="en">Dr. Moonsoo Kim</div>
                </td>
            </tr>
            <tr>
                <td class="tiem">17:20 ~ 18:00</td>
                <td class="background">
                    <div class="ko">두려움 없이, 안전하게: 진정마취로 여는 내 치과의 미래</div>
                    <div class="en">Beyond Fear: Safe Sedation in My Dental Practice</div>
                </td>
                <td class="background">
                    <div class="ko">이재일 원장</div>
                    <div class="en">Dr. Jae Il Lee</div>
                </td>
            </tr>
            <tr>
                <td class="tiem">18:00 ~ 18:20</td>
                <td>
                    <div class="ko">논의진행</div>
                    <div class="en">Discussion Progress</div>
                </td>
                <td>
                    <div class="ko">임요한 원장</div>
                    <div class="en">Dr. Yohan Lim</div>
                </td>
            </tr>
            <tr>
                <td class="tiem">19:00 ~ 20:00</td>
                <td>
                    <div class="ko">평의원회</div>
                    <div class="en">Council Meeting</div>
                </td>
                <td>
                    <div class="ko">-</div>
                    <div class="en"></div>
                </td>
            </tr>
        </table>
    </div> -->





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
