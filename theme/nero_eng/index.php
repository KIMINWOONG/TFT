<?php
if (!defined('_INDEX_')) define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

$conference=sql_fetch("select * from g5_conference where sy_status='active' order by sy_id desc limit 0,1 ");
$weekarr=array("일","월","화","수","목","금","토");
$weektxt=$weekarr[date("w",strtotime($conference['sy_sdate']))];
$sdate_text=date("Y년 m월 d일(".$weektxt.")",strtotime($conference['sy_sdate']));

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
                <a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_01.php"data-swiper-animation="fadeInUp" data-duration=".6s" class="btn_more">자세히보기</a>

                <div class="left_wrap">
                    <div class="left_menu">
                        <a href="<?php echo G5_THEME_URL ?>/subpage/about/08.php" class="main_btn">
                            <h5>Directions/Contact</h5>
                            <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon2.png" alt="" class="img">
                            <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon4.png" alt="" class="btn_icon">
                        </a>
                    </div>
                    <div class="left_menu">
                        <a href="<?php echo G5_THEME_URL ?>/subpage/event/event2_07.php" class="main_btn">
                            <h5>Conference Pre-registration</h5>
                            <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon1.png" alt="" class="img">
                            <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon4.png" alt="" class="btn_icon">
                        </a> 
                    
                        <a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_4.php" class="main_btn">
                            <h5>Submit Abstract</h5>
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
?>
<section class="search">
    <div class="width">
		<form id="searchForm" method="get" action="<?=G5_THEME_URL?>/subpage/about/07.php">
        <div>
            <h2>Member Search</h2>
            <button type="submiy">Search</button>
        </div>
        <div>
            <select name="region" id="region">
                <option value="">Region</option>
                        <?php while ($row = sql_fetch_array($region_result)): ?>
                            <option value="<?php echo $row['region']; ?>" <?php echo ($search_region == $row['region']) ? 'selected' : ''; ?>>
                                <?php echo $row['region']; ?>
                            </option>
                        <?php endwhile; ?>
            </select>
            <select name="hospital" id="hospital">
                <option value="">Hospital Name</option>
                        <?php while ($row = sql_fetch_array($hospital_result)): ?>
                            <option value="<?php echo $row['mb_work_name']; ?>" <?php echo ($search_hospital == $row['mb_work_name']) ? 'selected' : ''; ?>>
                                <?php echo $row['mb_work_name']; ?>
                            </option>
                        <?php endwhile; ?>
            </select>
            <div class="text_wrap">
                <input type="text" name="name" id="name" placeholder="name">
                <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon5.png" alt="">
            </div>
		</div>
		</form>
    </div>
<script>
$(document).ready(function() {
    // 지역 선택시 병원명 필터링 (선택사항)
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
});
</script>    
</section>

<div class="main_bg_wrap">
<section class="section2">
    <div class="left_notice">
        <div class="bbs_contents">
       
        <div class="notice_btns">
            <h4>News</h4>
            <div class="button_wrap">
                <button class="notice_btn btn_active" onclick="showContent(0)">Notices</button>
                <button class="notice_btn" onclick="showContent(1)">Industry News</button>
                <button class="notice_btn" onclick="showContent(2)">Academic Resources</button>
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
            <h3>Customer Service</h3>
            <h6>02-2273-3875</h6>
            <h5>Available weekdays 9 AM – 6 PM</h5>
            <a href="#">Email Inquiry</a>
        </div>

    </div>
    <div class="right_qmenu">
        <h4>Quick Menu</h4>
        <div class="items">
            <a href="<?php echo G5_BBS_URL ?>/register.php" class="item">
                <div class="img_box">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon6.png" alt="">
                </div>
                <p>Sign Up</p>
            </a> 
            <a href="<?php echo G5_THEME_URL ?>/subpage/event/event1_01.php " class="item">
                <div class="img_box">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon7.png" alt="">
                </div>
                <p>Conference</p>
            </a>
            <a href="http://www.jdir.org/main.html" target="blank_" class="item">
                <div class="img_box">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon8.png" alt="">
                </div>
                <p>Submit Paper</p>
            </a>
            <a href="#"  class="item">
                <div class="img_box">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/main_icon9.png" alt="">
                </div>
                <p>Annual Fee</p>
            </a>
        </div>
    </div>
</section>

<section class="section4">
  <div class="slider-wrapper">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark.png" alt="">
        </div>
        <div class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark.png" alt="">
        </div>
        <div class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark.png" alt="">
        </div>
        <div class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark.png" alt="">
        </div>
        <div class="swiper-slide">
          <img src="<?php echo G5_THEME_IMG_URL ?>/mark.png" alt="">
        </div>
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
            slidesPerView: 1,
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
