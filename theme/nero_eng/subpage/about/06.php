<?php
include "../../../../common.php";

$tNum="About the Society";
$sNum="Officers";
$bNum="106";
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


include_once(G5_THEME_PATH.'/head.php');
?>

<div class="officers common">
    <div class="width">
        <h2 class="contents_title">대한치과이식임플란트학회 임원 소개</h2>

         <div class="tab-container">

         
        <div class="btn_wrap1">
            <div class="button_wrap"><button class="btn_1 btn_active1 entire" onclick="showContent(0)">전체</button></div>
            
            <div class="btn_1_wrap">
                <button class="btn_1" onclick="showContent(1)">지역별</button>
                <button class="btn_1" onclick="showContent(2)">전체 분과별</button>
                <button class="btn_1" onclick="showContent(3)">구강외과분과</button>
                <button class="btn_1" onclick="showContent(4)">보철분과</button>
                <button class="btn_1" onclick="showContent(5)">연구분과</button>
                <button class="btn_1" onclick="showContent(6)">이색재료</button>
                <button class="btn_1" onclick="showContent(7)">통합지의학분과</button>
                <button class="btn_1" onclick="showContent(8)">치주분과</button>
            </div>
            
        </div>
       
        <div class="contents1"> 
            <div class="tab_content" id="content-0">
                <div class="items">
                <a href="javascript:void(0)" class="profile_popup1 item" data-id="1">
                    <img src="<?php echo G5_THEME_IMG_URL ?>/profile.png" alt="">
                    <div class="name_wrap">
                        <h5>당진이손치과병원</h5>
                        <h6>이창규</h6>
                        <img src="<?php echo G5_THEME_IMG_URL ?>/pop_arrow.png" alt="">
                    </div>
                </a>
                <a href="javascript:void(0)" class="profile_popup2 item" >
                    <img src="<?php echo G5_THEME_IMG_URL ?>/profile.png" alt="">
                    <div class="name_wrap">
                        <h5>당진이손치과병원</h5>
                        <h6>이창규</h6>
                        <img src="<?php echo G5_THEME_IMG_URL ?>/pop_arrow.png" alt="">
                    </div>
                </a>
                <a href="javascript:void(0)" class="profile_popup3 item" >
                    <img src="<?php echo G5_THEME_IMG_URL ?>/profile.png" alt="">
                    <div class="name_wrap">
                        <h5>당진이손치과병원</h5>
                        <h6>이창규</h6>
                        <img src="<?php echo G5_THEME_IMG_URL ?>/pop_arrow.png" alt="">
                    </div>
                </a>
                <a href="javascript:void(0)" class="profile_popup4 item" >
                    <img src="<?php echo G5_THEME_IMG_URL ?>/profile.png" alt="">
                    <div class="name_wrap">
                        <h5>당진이손치과병원</h5>
                        <h6>이창규</h6>
                        <img src="<?php echo G5_THEME_IMG_URL ?>/pop_arrow.png" alt="">
                    </div>
                </a>
                <a href="javascript:void(0)" class="profile_popup5 item" >
                    <img src="<?php echo G5_THEME_IMG_URL ?>/profile.png" alt="">
                    <div class="name_wrap">
                        <h5>당진이손치과병원</h5>
                        <h6>이창규</h6>
                        <img src="<?php echo G5_THEME_IMG_URL ?>/pop_arrow.png" alt="">
                    </div>
                </a>
                <a href="javascript:void(0)" class="profile_popup6 item" >
                    <img src="<?php echo G5_THEME_IMG_URL ?>/profile.png" alt="">
                    <div class="name_wrap">
                        <h5>당진이손치과병원</h5>
                        <h6>이창규</h6>
                        <img src="<?php echo G5_THEME_IMG_URL ?>/pop_arrow.png" alt="">
                    </div>
                </a>
              
                </div>
            </div>
            <div class="tab_content" id="content-1">탭 1의 콘텐츠입니다.</div>
            <div class="tab_content" id="content-2">탭 2의 콘텐츠입니다.</div>
            <div class="tab_content" id="content-3">탭 3의 콘텐츠입니다.</div>
            <div class="tab_content" id="content-4">탭 4의 콘텐츠입니다.</div>
            <div class="tab_content" id="content-5">탭 5의 콘텐츠입니다.</div>
            <div class="tab_content" id="content-6">탭 6의 콘텐츠입니다.</div>
            <div class="tab_content" id="content-7">탭 7의 콘텐츠입니다.</div>
            <div class="tab_content" id="content-8">탭 8의 콘텐츠입니다.</div>
        </div>


    </div>


    <!-- 페이징 -->
        <div class="page_wrap">
            <button class="prev_2">
                <img src="<?php echo G5_THEME_IMG_URL ?>/prev2.png" alt="">
            </button>
            <button class="prev_1">
                <img src="<?php echo G5_THEME_IMG_URL ?>/prev1.png" alt="">
            </button>
            <ul>
                <li><a href="#" class="page_active">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">6</a></li>
                <li><a href="#">7</a></li>
                <li><a href="#">8</a></li>
                <li><a href="#">9</a></li>
                <li><a href="#">10</a></li>
            </ul>
            <button class="next_1">
                <img src="<?php echo G5_THEME_IMG_URL ?>/next1.png" alt="">
            </button>
            <button class="next_2">
                <img src="<?php echo G5_THEME_IMG_URL ?>/next2.png" alt="">
            </button>
        </div>
        <!-- //페이징 -->

    </div> 
</div>

<div id="pro_popup1" class="pop_wrap1">
    <div class="pop_inner">
        <div class="profile_img">
        <img src="<?php echo G5_THEME_IMG_URL ?>/profile.png" alt="">
        </div>
        
        <div class="text_box">
            <h6>보철분과, 자문위원</h6>
            <h3>김명주(자문)</h3>
            <h4>약력</h4>
            <ul>
                <li><span></span><p>서울대학교 치의학 대학원 치과보철과 과장 및 주임교수 역임</p></li>
                <li><span></span><p>서울대학교 치과병원 중앙기공실장, 진료협력팀장, 의료정보- 빅데이터 센터장 역임</p></li>
                <li><span></span><p>서울대학교 치의학 대학원 치과보철과 교수</p></li>
                <li><span></span><p>서울대학교 치과병원 교육역량개발실장</p></li>
            </ul>
        </div>
        
        <div class="pop-close" onclick="pop_close();">
            <img src="<?php echo G5_THEME_IMG_URL ?>/close.png" alt="">
        </div>
    </div>
  </div>

  <div id="pro_popup2" class="pop_wrap1">
    <div class="pop_inner">
        <div class="profile_img">
        <img src="<?php echo G5_THEME_IMG_URL ?>/profile.png" alt="">
        </div>
        
        <div class="text_box">
            <h6>보철분과, 자문위원</h6>
            <h3>김명주(자문)</h3>
            <h4>약력</h4>
            <ul>
                <li><span></span><p>서울대학교 치의학 대학원 치과보철과 과장 및 주임교수 역임</p></li>
                <li><span></span><p>서울대학교 치과병원 중앙기공실장, 진료협력팀장, 의료정보- 빅데이터 센터장 역임</p></li>
                <li><span></span><p>서울대학교 치의학 대학원 치과보철과 교수</p></li>
                <li><span></span><p>서울대학교 치과병원 교육역량개발실장</p></li>
            </ul>
        </div>
        
        <div class="pop-close" onclick="pop_close();">
            <img src="<?php echo G5_THEME_IMG_URL ?>/close.png" alt="">
        </div>
    </div>
  </div>


  
        

  <script>
$(function () {
  // 모든 클래스가 'profile_popup숫자' 형태인 요소에 대해 처리
  $("[class^='profile_popup']").on("click", function () {
    // 정규식으로 숫자 추출
    const classList = $(this).attr("class").split(" ");
    let popupNum = null;

    classList.forEach(cls => {
      const match = cls.match(/^profile_popup(\d+)$/);
      if (match) {
        popupNum = match[1];
      }
    });

    if (popupNum) {
      $("#pro_popup" + popupNum).fadeIn(500);
    }
  });

  // 닫기 버튼 클릭 시 해당 팝업 닫기
  $(document).on("click", ".pop-close", function () {
    $(this).closest(".pop_wrap1").fadeOut(300);
  });
});
    
</script>

  <script>
        function showContent(index) {
            // 모든 콘텐츠를 숨김 처리
            const contents = document.querySelectorAll(".tab_content");
            contents.forEach(content => {
                content.style.display = "none";
            });

            // 선택한 콘텐츠 표시
            document.getElementById(`content-${index}`).style.display = "block";

            // 모든 탭에서 'active' 클래스 제거
            const tabs = document.querySelectorAll(".btn_1");
            tabs.forEach(tab => {
                tab.classList.remove("btn_active1");
            });

            // 선택한 탭에 'active' 클래스 추가
            tabs[index].classList.add("btn_active1");
        }

        // 페이지 로드 시 첫 번째 탭 활성화
        document.addEventListener("DOMContentLoaded", function() {
            showContent(0);
        });
    </script>



<?php
include_once(G5_THEME_PATH.'/tail.php');
