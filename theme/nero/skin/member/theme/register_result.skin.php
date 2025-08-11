<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/info.css">', 0);
?>

<!-- 회원가입결과 시작 { -->
<div id="reg_result " class="register width common">
      <h2>회원가입</h2>
    <div class="register_step">
        <div class="on2">
        <div class="item">
            <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon1.png" alt=""> -->
            <p>STEP1. 약관 동의</p>
        </div>
        <div class="item">
            <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon4.png" alt=""> -->
            <p>STEP2. 회원 정보</p>
        </div>
        
        <div class="item">
            <!-- <img src="<?php echo G5_THEME_IMG_URL ?>/register_icon5.png" alt=""> -->
            <p>STEP3. 가입 완료</p>
        </div>
        </div>
    </div>
    <div class="reg_result_p">
    	<img src="<?php echo G5_THEME_IMG_URL ?>/register_icon6.png" alt="">
        <h3>회원가입 완료</h3>
        <h4><?php echo get_text($mb['mb_name']); ?>님(<?php echo get_text($mb['mb_id']); ?>)의 회원가입이 <br>
        성공적으로 완료되었습니다.</h4>
        
        <div class="txtbox">
           *회원가입 내역 확인 및 수정은 <span>마이페이지 > 회원정보수정</span>에서 가능합니다.
        </div>
    </div>

    <div class="btn_center">
     <a href="<?php echo G5_BBS_URL ?>/login.php" class="reg_login_btn" >로그인 하러가기</a>
    </div>
 
    <?php if (is_use_email_certify()) {  ?>
    <p class="result_txt">
        회원 가입 시 입력하신 이메일 주소로 인증메일이 발송되었습니다.<br>
        발송된 인증메일을 확인하신 후 인증처리를 하시면 사이트를 원활하게 이용하실 수 있습니다.
    </p>
    <div id="result_email">
        <span>아이디</span>
        <strong><?php echo $mb['mb_id'] ?></strong><br>
        <span>이메일 주소</span>
        <strong><?php echo $mb['mb_email'] ?></strong>
    </div>
    <p>
        이메일 주소를 잘못 입력하셨다면, 사이트 관리자에게 문의해주시기 바랍니다.
    </p>
    <?php }  ?>

    <!-- <p class="result_txt">
        회원님의 비밀번호는 아무도 알 수 없는 암호화 코드로 저장되므로 안심하셔도 좋습니다.<br>
        아이디, 비밀번호 분실시에는 회원가입시 입력하신 이메일 주소를 이용하여 찾을 수 있습니다.
    </p>

    <p class="result_txt">
        회원 탈퇴는 언제든지 가능하며 일정기간이 지난 후, 회원님의 정보는 삭제하고 있습니다.<br>
        감사합니다.
    </p> -->
</div>
<!-- } 회원가입결과 끝 -->

