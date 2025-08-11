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
 <h2 class="contents_title"><?=$conference['sy_title']?> - 등록안내</h2>

 <div class="event_menu">
    <ul class="event_02_2">
        <li class="event_on"><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_06.php#event_point">등록 안내</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_07.php#event_point">사전등록 신청</a></li>
        <li><a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_08.php#event_point">등록 신청 확인</a></li>
    </ul>
 </div>

 <div class="content_wrap register_info">
    <div class="item">
        <h4 class="sub_title2">사전등록 기간</h4>
        <div class="date_wrap">
            <div class="left_title">사전등록 기간</div>
            <div class="right_date">1차 <span>2025. 06. 27(금) ~ 2025.07.04(금)</span></div>
        </div>
        <ul>
            <li><span class="dot"></span><p>사전등록기간 마감 이후에는 등록비가 현장등록비로 변경됩니다.</p></li>
        </ul>
    </div>

     <div class="item">
        <h4 class="sub_title2">등록비 안내</h4>
        <table>
            <tr>
                <th style="width:20%;">구분</th>
                <th style="width:40%;">학회회원</th>
                <th style="width:40%;">비회원</th>
            </tr>
            <tr>
                <td class="table_back" style="width:20%;">사전등록</td>
                <td><span class="color_b">0원</span></td>
                <td><span class="color_b">불가</span></td>
            </tr>
            <tr>
                <td class="table_back" style="width:20%;">현장등록</td>
                <td>0원</td>
                <td>8만원</td>
            </tr>
        </table>
        <h6 class="color_b">★등록비 : 학회회원 중 연회비 미납 시 참석불가</h6>
    </div>

    <div class="item">
        <h4 class="sub_title2">등록 방법</h4>
        <ul>
            <li><span class="dot"></span><p>홈페이지에서 필히 등록 신청서를 작성 후 등록비를 온라인 결제 또는 입금하여 주십시오.</p></li>
            <li><span class="dot"></span><p>사전 등록 시 입력하신 E-mail로 등록 확인 메일이 발송됩니다.</p></li>
            <li><span class="dot"></span><p>등록비 결제 영수증과 명찰은 학회 당일 등록데스크에서 배부합니다.</p></li>
        </ul>
    </div>

    <div class="item">
        <h4 class="sub_title2">결제 방법</h4>
        <ul>
            <li><span class="dot"></span><p>신용카드</p></li>
            <li><span class="dot"></span><p>계좌이체</p></li>
        </ul>
    </div>

    <div class="item">
        <h4 class="sub_title2">보수교육 점수</h4>
        <ul>
            <li><span class="dot"></span><p>대한치과의사협회 보수교육점수 </p><h6 class="color_b"> 4점</h6></li>
            <li><span class="dot"></span><p>대힌치과이식임플란트학회 인증의 / 우수임플란트임상의 점수 </p><h6 class="color_b"> 10점</h6></li>
        </ul>
    </div>

    <div class="item">
        <h4 class="sub_title2">등록 문의</h4>
        <ul>
            <li><span class="dot"></span><p>학회사무국 : TEL.02-2273-3875 / FAX.02-2273-3871</p></li>
            <li><span class="dot"></span><p>E-mail: kaid@kaidimplant.or.kr</p></li>
        </ul>
    </div>

    <div class="item">
        <h4 class="sub_title2">안내드립니다. </h4>
        <ul class="blue_box">
            <li><span class="dot"></span><p>학부학생, 외국학생은 무료이며, 사전등록 필수입니다.(현장등록 불가)</p></li>
            <li><span class="dot"></span><p>대한치과의사협회비 3회 이상 미납 시 모든 보수교육기관은 별도의 등록비가 요청됩니다.</p></li>
            <li><span class="dot"></span><p>치협 회비 납부여부는 대한치과의사협회 홈페이지 www.kda.or.kr 로그인 후 마이페이지에서 사전에 확인하시기 바랍니다.</p></li>
            <li><span class="dot"></span><p>주차는 서울대학교치과병원 건물에 한하며, 현장 유료구입입니다.</p></li>
            <li><span class="dot"></span><p>사전등록기간 이후 취소 시 환불 불가</p></li>
        </ul>
    </div>
    




 </div>

 <!-- 회원일경우 등록 페이지로 넘어가는 버튼 -->
 <a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_07.php" class="btn_style_baisc">사전등록 하러가기</a>

 <!-- 비회원일경우 팝업창뜨기 -->
 <a href="javascript:void(0)"  onclick="pop_open()" class="btn_style_baisc">사전등록 하러가기</a>


 <!-- 비회원일경우 뜨는 팝업 -->
<div id="my_popup" class="my_pop_wrap">

  <div class="my_pop-inner">
    <div class="my_pop-text my_pop">
     <div class="pop_title">
        
        <div>
            <h5>비회원은 현장등록만 가능합니다.</h5>
        </div>
     </div>

   

     <div class="pop_btn_wrap">
        <div class="my_pop_ok" onclick="pop_close();">확인</div>
     </div>
    </div>
    
  </div>
</div>


  </div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
