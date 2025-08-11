<?php
if (!defined('_INDEX_')) define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

//$conference=sql_fetch("select * from g5_conference where sy_status='active' order by sy_id desc limit 0,1 ");
$conference=sql_fetch("select * from g5_conference where sy_status='active' order by sy_sdate asc limit 0,1 ");
$weekarr=array("일","월","화","수","목","금","토");
$weektxt=$weekarr[date("w",strtotime($conference['sy_sdate']))];
$sdate_text=date("Y년 m월 d일(".$weektxt.")",strtotime($conference['sy_sdate']));

if($conference['sy_gubun']=="1"){
	$conference_url=G5_THEME_URL."/subpage/event/event2_01.php";
	$location_url=G5_THEME_URL."/subpage/event/event2_05.php";
}else{
	$conference_url=G5_THEME_URL."/subpage/event/event1_01.php";
	$location_url=G5_THEME_URL."/subpage/event/event1_05.php";
}
if($is_member || $is_nonemember){
	if($is_member){
		$summary_url=G5_THEME_URL."/subpage/mypage/mypage_4.php";
		$memberpay_url=G5_THEME_URL."/subpage/mypage/mypage_2.php";
	}else{
		$summary_url=G5_THEME_URL."/subpage/mypage/mypage_7.php";
		$memberpay_url="javascript:alert('연회비는 회원들만 납부하실 수 있습니다.');";
	}
}else{
	$summary_url="javascript:alert('로인인 후 이용해 주세요.');";
	$memberpay_url="javascript:alert('로인인 후 이용해 주세요.');";
}

include_once(G5_THEME_PATH.'/head.php');
?>

<section class="section Hfull">
	<div class="ani-in Hfull">
    <!-- 메인슬라이드 -->
	<div class="swiper_visual ani delay1">
    <div class="swiper-wrapper">
		<div class="swiper-slide" style="" data-value="<?php echo G5_THEME_IMG_URL ?>/main.png">
            <div class="main_visual">
                <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/main.png" alt="" class="pc">
                <img src="<?php echo G5_THEME_IMG_URL ?>/t_visual01.png" alt="" class="tab">
                <img src="<?php echo G5_THEME_IMG_URL ?>/m_visual01.png" alt="" class="mob"> -->
            </div>
			<div class="maintext_box width">
				<?php
				if($conference['sy_id']){//학술행사가 있을때
				?>
                <h2 class="mainT1" data-swiper-animation="fadeInUp" data-duration=".6s">
                    <?=$conference['sy_title']?>
                </h2>
                <h3 class="mainT2" data-swiper-animation="fadeInUp" data-duration=".7s" data-delay="0.9s">
                    <?=$conference['sy_title_en']?>
                </h3>
                <h4 data-swiper-animation="fadeInUp" data-duration=".7s" data-delay="0.9s">
                    <span>일자 : <?=$sdate_text?> </span> 
                    <span>장소 : <?=$conference['sy_place']?></span>
                </h4>
                <a href="<?php echo $conference_url ?>" data-swiper-animation="fadeInUp" data-duration=".6s" class="btn_more">자세히보기</a>
				<?php
				}else{ //학술행사가 없을때
				?>
				<h2 class="mainT1" data-swiper-animation="fadeInUp" data-duration=".6s">
					대한치과이식임플란트학회에<br>오신걸을 환영합니다.
				</h2>
				<?php
				}
				?>


                <div class="left_wrap">
                    <div class="left_menu">
                        <a href="<?php echo $location_url;?>" class="main_btn">
                            <h5>오시는길 / 문의</h5>
                            <h6>오시는길 바로가기</h6>
                            <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon2.png" alt="" class="img">
                            <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon4.png" alt="" class="btn_icon">
                        </a>
                    </div>
                    <div class="left_menu">
                        <a href="<?php echo $conference_url ?>" class="main_btn">
                            <h5>학술행사 사전등록</h5>
                            <h6>학술행사 사전등록하러 가기</h6>
                            <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon1.png" alt="" class="img">
                            <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon4.png" alt="" class="btn_icon">
                        </a> 
                    
                        <a href="<?php echo $summary_url; ?>" class="main_btn">
                            <h5>초록 제출</h5>
                            <h6>초록 제출 바로가기</h6>
                            <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon3.png" alt="" class="img">
                            <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon4.png" alt="" class="btn_icon">
                        </a>
                    </div>
				</div>
            </div>
        </div>
	</div>
<!--
    <div class="swiper-navigation">
		<div class="swiper-button-prev">
			<img src="<?php echo G5_THEME_IMG_URL ?>/left_icon1.png" alt="" >
		</div>
		<div class="swiper-button-next">
			<img src="<?php echo G5_THEME_IMG_URL ?>/right_icon1.png" alt="" >
		</div>
	</div>
-->

	</div>
	</div>
</section>
<?php
// 검색 결과 조회
$search_sql = "SELECT mb_name, mb_work_name, mb_work_addr1, mb_work_addr2, mb_memclass, mb_title 
               FROM {$g5['member_table']} 
               {$where_clause} 
               ORDER BY mb_name ASC 
               LIMIT {$offset}, {$rows_per_page}";

$search_result = sql_query($search_sql);

// 지역 목록 조회 (중복 제거)
/**
$region_sql = "SELECT DISTINCT SUBSTRING_INDEX(mb_work_addr1, ' ', 1) as region 
               FROM {$g5['member_table']} 
               WHERE mb_work_addr1 != '' AND mb_search_agree = '동의' 
               ORDER BY region";
$region_result = sql_query($region_sql);
// 병원명 목록 조회 (중복 제거)
$hospital_sql = "SELECT DISTINCT mb_work_name 
                 FROM {$g5['member_table']} 
                 WHERE mb_work_name != '' AND mb_search_agree = '동의' 
                 ORDER BY mb_work_name";
$hospital_result = sql_query($hospital_sql);
*/
?>
<section class="search">
    <div class="width">
		<form id="searchForm" method="get" action="<?=G5_THEME_URL?>/subpage/about/07.php">
        <div>
            <h2>학회 회원 검색</h2>
            <button type="submiy">검색</button>
        </div>
        <div>
            <select name="region" id="region">
							<option value="서울" <?php echo ($search_region == "서울") ? 'selected' : ''; ?>>서울</option>
							<option value="부산" <?php echo ($search_region == "부산") ? 'selected' : ''; ?>>부산</option>
							<option value="인천" <?php echo ($search_region == "인천") ? 'selected' : ''; ?>>인천</option>
							<option value="울산" <?php echo ($search_region == "울산") ? 'selected' : ''; ?>>울산</option>
							<option value="광주" <?php echo ($search_region == "광주") ? 'selected' : ''; ?>>광주</option>
							<option value="대구" <?php echo ($search_region == "대구") ? 'selected' : ''; ?>>대구</option>
							<option value="대전" <?php echo ($search_region == "대전") ? 'selected' : ''; ?>>대전</option>
							<option value="세종" <?php echo ($search_region == "세종") ? 'selected' : ''; ?>>세종</option>
							<option value="경기" <?php echo ($search_region == "경기") ? 'selected' : ''; ?>>경기</option>
							<option value="강원" <?php echo ($search_region == "강원") ? 'selected' : ''; ?>>강원</option>
							<option value="경남" <?php echo ($search_region == "경남") ? 'selected' : ''; ?>>경남</option>
							<option value="경북" <?php echo ($search_region == "경북") ? 'selected' : ''; ?>>경북</option>
							<option value="전남" <?php echo ($search_region == "전남") ? 'selected' : ''; ?>>전남</option>
							<option value="전북" <?php echo ($search_region == "전북") ? 'selected' : ''; ?>>전북</option>
							<option value="충남" <?php echo ($search_region == "충남") ? 'selected' : ''; ?>>충남</option>
							<option value="충북" <?php echo ($search_region == "충북") ? 'selected' : ''; ?>>충북</option>
							<option value="제주" <?php echo ($search_region == "제주") ? 'selected' : ''; ?>>제주</option>
            </select>
           <!--  <select name="hospital" id="hospital">
                <option value="">병원명</option>
                        <?php while ($row = sql_fetch_array($hospital_result)): ?>
                            <option value="<?php echo $row['mb_work_name']; ?>" <?php echo ($search_hospital == $row['mb_work_name']) ? 'selected' : ''; ?>>
                                <?php echo $row['mb_work_name']; ?>
                            </option>
                        <?php endwhile; ?>
            </select> -->

			<div class="text_wrap">
                        <input type="text" name="hospital" id="hospital" placeholder="병원명을 입력해주세요." value="<?php echo $mb_work_name; ?>">
                    </div>
            <div class="text_wrap">
                <input type="text" name="name" id="name" placeholder="성명">
                <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon5.png" alt="">
            </div>
		</div>
		</form>
    </div>
<script>
$(document).ready(function() {
    // 지역 선택시 병원명 필터링 (선택사항)
	/**
    $('#region').on('change', function() {
        var selectedRegion = $(this).val();
        if (selectedRegion !== '') {
            // AJAX로 해당 지역의 병원명만 가져오기
            $.ajax({
                url: '<?=G5_THEME_URL?>/subpage/get_hospitals.php',
                type: 'GET',
                data: { region: selectedRegion },
                dataType: 'json',
                success: function(data) {
                    var hospitalSelect = $('#hospital');
                    hospitalSelect.empty();
                    hospitalSelect.append('<option value="">병원명</option>');
                    
                    $.each(data, function(index, hospital) {
                        hospitalSelect.append('<option value="' + hospital + '">' + hospital + '</option>');
                    });
                }
            });
        }
    });
	*/
});
</script>    
</section>

<div class="main_bg_wrap">
<section class="section2">
    <div class="left_notice">
        <div class="bbs_contents">
       
        <div class="notice_btns">
            <h4>새소식</h4>
            <div class="button_wrap">
                <button class="notice_btn btn_active" onclick="showContent(0)">공지사항</button>
                <button class="notice_btn" onclick="showContent(1)">보도자료</button>
                <button class="notice_btn" onclick="showContent(2)">학술자료실</button>
                <a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=notice" class="notice_more">+</a>
            </div>
            </div>
            

        <div class="contents">
            <div class="bbs_content" id="content-0">
 <div class="latest_top_wr">
        <?php
    	echo latest('theme/basic2', 'notice', 6, 40);		// 최소설치시 자동생성되는 공지사항게시판
        ?>
    </div>
            </div>
            <div class="bbs_content" id="content-1">
 <div class="latest_top_wr">
        <?php
    	echo latest('theme/basic2', 'industry_news', 6, 40);		// 최소설치시 자동생성되는 공지사항게시판
        ?>
    </div>
            </div>
            <div class="bbs_content" id="content-2">
 <div class="latest_top_wr">
        <?php
    	echo latest('theme/basic2', 'academic', 6, 40);		// 학술자료실 게시판
        ?>
    </div>
            </div>
        </div>

<script>
        function showContent(index) {
            // 모든 콘텐츠를 숨김 처리
            const contents = document.querySelectorAll(".bbs_content");
            contents.forEach(content => {
                content.style.display = "none";
            });

            // 선택한 콘텐츠 표시
            document.getElementById(`content-${index}`).style.display = "block";

            // 모든 탭에서 'active' 클래스 제거
            const tabs = document.querySelectorAll(".notice_btn");
            tabs.forEach(tab => {
                tab.classList.remove("btn_active");
            });

            // 선택한 탭에 'active' 클래스 추가
            tabs[index].classList.add("btn_active");
        }

        // 페이지 로드 시 첫 번째 탭 활성화
        document.addEventListener("DOMContentLoaded", function() {
            showContent(0);
        });
    </script>
        
    </div>
        
    </div>
    <div class="right_date">
		<div class="schedule_wrap">
		</div>
<script>
$(function(){

	get_schedule('<?=date("Y-m-d")?>');
});
	function get_schedule(d){
		$.ajax({
			type:"get",
			url:"/theme/nero/subpage/get_schedule_calendar.php",
			data:"date="+d,
			success:function(res){
				$(".schedule_wrap").html(res);
			},
		});
	}
</script>
 

    </div>
</section>

<section class="section3">
    <div class="left_call">
        <img src="<?php echo G5_THEME_IMG_URL ?>/tel_icon.png" alt="">
        <div class="text_box">
            <h3>상담 및 문의</h3>
            <!-- <h6>010-3449-2568</h6> -->
            <h5>평일 오전 9시부터 오후 6시까지 카카오톡으로 상담 가능합니다.</h5>
            <a href="https://pf.kakao.com/_AExomn" target="balnk">카카오톡 문의</a>
        </div>

    </div>
    <div class="right_qmenu">
        <h4>자주 찾는 메뉴</h4>
        <div class="items">
            <a href="<?php echo G5_BBS_URL ?>/register.php" class="item">
                <div class="img_box">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon6.png" alt="">
                </div>
                <p>회원가입</p>
            </a> 
            <a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_01.php " class="item">
                <div class="img_box">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon7.png" alt="">
                </div>
                <p>학술대회</p>
            </a>
            <a href="http://www.jdir.org/main.html" target="blank_" class="item">
                <div class="img_box">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon8.png" alt="">
                </div>
                <p>논문투고</p>
            </a>
            <a href="<?php echo $memberpay_url ?>"  class="item">
                <div class="img_box">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon9.png" alt="">
                </div>
                <p>연회비</p>
            </a>
        </div>
    </div>
</section>

<section class="section4">
  <div class="slider-wrapper">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        <a href="https://www.evertisimplant.com" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark1.png" alt="">
        </a>
        <a href="http://www.kuwotech.com/kr/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark2.png" alt="">
        </a>
        <a href="https://www.osstem.com/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark3.png" alt="">
        </a>
        <a href="https://dentis.co.kr/main/main.do" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark4.png" alt="">
        </a>
        <a href="https://www.cowellmedi.com/kr/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark5.png" alt="">
         </a>
        <a href="https://www.neobiotech.co.kr/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark6.png" alt="">
        </a>
        <a href="https://www.dio.co.kr/index.do" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark7.png" alt="">
        </a>
        <a href="http://www.2080bok.com/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark8.png" alt="">
        </a>
        <a href="https://purgobio.com/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark9.png" alt="">
        </a>
        <a href="https://zeronecellvane.com/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark10.png" alt="">
        </a>
        <a href="https://imegagen.com/ko/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark11.png" alt="">
        </a>
        <a href="https://www.straumann.com/kr/ko/dental-professionals.html" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark12.png" alt="">
        </a>
        <a href="http://in-sol.co.kr/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark13.png" alt="">
        </a>
        <a href="https://www.geistlich.com/ko-kr/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark14.png" alt="">
        </a>
        <a href="http://www.dalimmedical.co.kr/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark15.png" alt="">
        </a>
          <a href="https://renewmedical.net/" target="blank" class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark16.png" alt="">
        </a>
      </div>
    </div>

    <div class="controls">
      <div class="swiper-button-prev"><img src="<?php echo G5_THEME_IMG_URL ?>/left.png" alt=""></div>
      <div class="swiper-controllbar">
        <div class="swiper-controllbar-box">
          <div class="auto-stop"><img src="<?php echo G5_THEME_IMG_URL ?>/pause.png" alt=""></div>
          <div class="auto-start"><img src="<?php echo G5_THEME_IMG_URL ?>/play.png" alt=""></div>
        </div>
      </div>
      <div class="swiper-button-next"><img src="<?php echo G5_THEME_IMG_URL ?>/right.png" alt=""></div>
      
    </div>
  </div>
   <script>
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: 5,
      spaceBetween: 20,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      loop: true,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      breakpoints:{
        100:{
            slidesPerView: 2,
        },
        450:{
            slidesPerView: 2,
        },
        500:{
            slidesPerView: 3,
        },
        769:{
            slidesPerView: 4,
        },
        1025:{
            slidesPerView: 5,
        },
      },
    });

    // 재생/정지 버튼 기능
    document.querySelector('.auto-stop').addEventListener('click', () => swiper.autoplay.stop());
    document.querySelector('.auto-start').addEventListener('click', () => swiper.autoplay.start());
  </script>
</section>

<div class="main_bg1">
    <img src="<?php echo G5_THEME_IMG_URL ?>/bg1.png" alt="">
</div>
<div class="main_bg2">
    <img src="<?php echo G5_THEME_IMG_URL ?>/bg.png" alt="">
</div>


</div>




<?php
include_once(G5_THEME_PATH.'/tail.php');
