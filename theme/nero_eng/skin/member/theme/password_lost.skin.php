<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 회원정보 찾기 시작 { -->
<div id="find_info" class="new_win common find_idpw">
    <h1>아이디/비밀번호 찾기</h1>

    <div class="tab-container">
         <div class="tabs">
            <button class="tab_btn tab_active" onclick="showContent(0)">아이디 찾기</button>
            <button class="tab_btn" onclick="showContent(1)">비밀번호 찾기</button>
        </div>
       
        <div class="contents">
            <!-- 아이디찾기 -->
            <div class="tab_content" id="content-0">
                <h2><span>ID</span>를 잊으셨나요?</h2>

                <div class="login_wrap_box">
                <label for="login_id">Name</label>
                <input type="text" name="mb_id" id="login_id" required class="frm_input required" size="20" maxLength="20" >
              </div>
              <div class="login_wrap_box">
                <label for="">E-mail</label>
                <input type="text" name="" id="" required class="frm_input required" size="20"  maxLength="20">
              </div>

              <button class="find_id">아이디 찾기</button>
            </div>

            <!-- 비밀번호 찾기 -->
            <div class="tab_content" id="content-1">
                <h2><span>비밀번호</span>를 잊으셨나요?</h2>

                <div class="login_wrap_box">
                <label for="login_id">Name</label>
                <input type="text" name="mb_id" id="login_id" required class="frm_input required" size="20" maxLength="20" >
              </div>
               <div class="login_wrap_box">
                <label for="">아이디</label>
                <input type="text" name="" id="" required class="frm_input required" size="20"  maxLength="20">
              </div>
              <div class="login_wrap_box">
                <label for="">E-mail</label>
                <input type="text" name="" id="" required class="frm_input required" size="20"  maxLength="20">
              </div>

              <button class="find_id">비밀번호 찾기</button>
            </div>
        </div>


       
    </div>

   


    <!-- <div class="new_win_con">
        <form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
        <fieldset id="info_fs">
            <p>
                회원가입 시 등록하신 이메일 주소를 입력해 주세요.<br>
                해당 이메일로 아이디와 비밀번호 정보를 보내드립니다.
            </p>
            <label for="mb_email" class="sound_only">E-mail 주소<strong class="sound_only">필수</strong></label>
            <input type="text" name="mb_email" id="mb_email" required class="required frm_input full_input email" size="30" placeholder="E-mail 주소">
        </fieldset>
        <?php echo captcha_html();  ?>
        <div class="win_btn">
            <button type="submit" class="btn_submit">확인</button>
            <button type="button" onclick="window.close();" class="btn_close">창닫기</button>  
        </div>
        </form>
    </div> -->
</div>

<script>
function fpasswordlost_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    return true;
}

$(function() {
    var sw = screen.width;
    var sh = screen.height;
    var cw = document.body.clientWidth;
    var ch = document.body.clientHeight;
    var top  = sh / 2 - ch / 2 - 100;
    var left = sw / 2 - cw / 2;
    moveTo(left, top);
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
            const tabs = document.querySelectorAll(".tab_btn");
            tabs.forEach(tab => {
                tab.classList.remove("tab_active");
            });

            // 선택한 탭에 'active' 클래스 추가
            tabs[index].classList.add("tab_active");
        }

        // 페이지 로드 시 첫 번째 탭 활성화
        document.addEventListener("DOMContentLoaded", function() {
            showContent(0);
        });
    </script>
<!-- } 회원정보 찾기 끝 -->