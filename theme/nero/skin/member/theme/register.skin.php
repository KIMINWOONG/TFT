<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/info.css">', 0);

?>

<!-- 회원가입약관 동의 시작 { -->
<div class="register width common">
    <h2>회원가입(Membership registration)</h2>
    <div class="register_step">
        <div class="item on">
            <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon1.png" alt=""> -->
            <p>STEP1. 약관 동의</p>
        </div>
        <div class="item">
            <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon2.png" alt=""> -->
            <p>STEP2. 회원 정보</p>
        </div>
        <div class="item">
            <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon3.png" alt=""> -->
            <p>STEP3. 가입 완료</p>
        </div>
    </div>

    <form  name="fregister" id="fregister" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">



    <?php
    // 소셜로그인 사용시 소셜로그인 버튼
    @include_once(get_social_skin_path().'/social_register.skin.php');
    ?>
    <section id="fregister_term">

            <h3>이용약관</h3>
           
        
        <textarea readonly><?php echo get_text($config['cf_stipulation']) ?></textarea>
         <fieldset class="fregister_agree">
            <input type="checkbox" name="agree" value="1" id="agree11" class="selec_chk">
            <label for="agree11"><span></span>
            이용약관에 동의합니다.
            <b class="sound_only">회원가입약관의 내용에 동의합니다.</b></label>
        </fieldset>
    </section>

    <section id="fregister_private">
        <h3>개인정보처리방침안내</h3>
       
        
        <textarea readonly><?php echo get_text($config['cf_privacy']) ?></textarea>
        <fieldset class="fregister_agree">
            <input type="checkbox" name="agree2" value="1" id="agree21" class="selec_chk">
            <label for="agree21"><span></span>
            개인정보 수집 및 이용에 동의합니다.<b class="sound_only">개인정보처리방침안내의 내용에 동의합니다.</b></label>
       </fieldset>
        
    </section>
<!-- 
	<div id="fregister_chkall" class="chk_all fregister_agree">
        <input type="checkbox" name="chk_all" id="chk_all" class="selec_chk">
        <label for="chk_all"><span></span>회원가입 약관에 모두 동의합니다</label>
    </div> -->

    <div class="btn_confirm">
    	<a href="<?php echo G5_URL ?>" class="btn_close">취소</a>
        <button type="submit" class="btn_submit">다음</button>
    </div>

    </form>
  
    <script>
    function fregister_submit(f)
    {
        if (!f.agree.checked) {
            alert("회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
            f.agree.focus();
            return false;
        }

        if (!f.agree2.checked) {
            alert("개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
            f.agree2.focus();
            return false;
        }

        return true;
    }

    jQuery(function($){
        // 모두선택
        $("input[name=chk_all]").click(function() {
            if ($(this).prop('checked')) {
                $("input[name^=agree]").prop('checked', true);
            } else {
                $("input[name^=agree]").prop("checked", false);
            }
        });
    });

    </script>
</div>
<!-- } 회원가입 약관 동의 끝 -->
