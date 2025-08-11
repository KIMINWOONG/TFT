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
        <li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_03.php#event_point">일정</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_04.php#event_point">연자</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_05.php#event_point">장소</a></li>
        <li><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=event_02#bo_list">공지</a></li>
    </ul>
 </div>

 <div class="content_wrap">
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
            <tr>
                <td class="tiem">14:20 ~ 15:00</td>
                <td>분과별 학술모임</td>
                <td>-</td>
            </tr>
            <tr>
                <td class="tiem">15:00 ~ 15:40</td>
                <td class="background">장기적인 안정성을 위한 연조직 전략 : 전통적인 기법부터 최신 경향까지</td>
                <td class="background">김용진 원장</td>
            </tr>
            <tr>
                <td class="tiem">15:40 ~ 16:20</td>
                <td class="background">ISD : 최적의 임플란트 심미와 기능을 위한 수술부위에 대한 다각적인 접근</td>
                <td class="background">함대원 원장</td>
            </tr>
            <tr>
                <td class="tiem">16:20 ~ 16:40</td>
                <td>논의 진행</td>
                <td>이창규 부회장, 임요한 고문</td>
            </tr>
            <tr>
                <td class="tiem">16:40 ~ 17:20</td>
                <td class="background">“전치부 임플란트 : 단순함 속에 숨은 전략”</td>
                <td class="background">김문수 원장</td>
            </tr>
            <tr>
                <td class="tiem">17:20 ~ 18:00</td>
                <td class="background">두려움 없이 , 안전하게 : 진정마취로 여는 내 치과의 미래</td>
                <td class="background">이재일 원장</td>
            </tr>
            <tr>
                <td class="tiem">18:00 ~ 18:20</td>
                <td>논의 진행</td>
                <td>이창규 부회장, 임요한 고문</td>
            </tr>
            <tr>
                <td class="tiem">19:00 ~ 20:00</td>
                <td>평의원회</td>
                <td>-</td>
            </tr>
        </table>
    </div>
 </div>


  </div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
