<?php
$sub_menu = '100400';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '관리자 가이드';
include_once('./admin.head.php');
?>
    <ul class="anchor">
        <li><a href="#box1">소셜 로그인</a></li>
        <li><a href="#box2">팝업창</a></li>
        <li><a href="#box3">관리자 정보 수정</a></li>
        <li><a href="#box4">게시판 권한 설정</a></li>
    </ul>
<div class="guide_wrap">
    <div class="box" id="box1">
        <div class="gu_title">
            <h2>소셜 로그인 (SNS : Social Network Service)</h2>
            <h3><a href="/adm/config_form.php#anc_cf_sns">[바로가기]</a></h3>
        </div>
        <span>
            <img src="<?php echo G5_ADMIN_URL ?>/img/gu4.png" alt="">
        </span>
    </div>
    <div class="box" id="box2">
        <div class="gu_title">
            <h2>팝업창 등록 및 관리 가이드</h2>
            <h3>환경설정 > 팝업레이어 관리 > 새창관리추가 <a href="/adm/newwinlist.php">[바로가기]</a></h3>
        </div>
        <span>
            <img src="<?php echo G5_ADMIN_URL ?>/img/gu1.png" alt="">
        </span>
    </div>
    <div class="box" id="box3">
        <div class="gu_title">
            <h2>관리자 정보 수정 (비밀번호, 관리자 이름, 닉네임)</h2>
            <h3>회원관리  <a href="/adm/member_list.php">[바로가기]</a></h3>
        </div>
        <span>
            <img src="<?php echo G5_ADMIN_URL ?>/img/gu2.png" alt="" style="margin-bottom: 20px">
            <img src="<?php echo G5_ADMIN_URL ?>/img/gu3.png" alt="">
        </span>
    </div>
    <div class="box" id="box4">
        <div class="gu_title">
            <h2>게시판 권한 설정</h2>
            <h3>권한 1은 비회원 / 2는 회원이상입니다. <a href="/adm/board_list.php">[권한 설정 바로가기]</a></h3>
        </div>
        <span>
            <img src="<?php echo G5_ADMIN_URL ?>/img/gu5.png" alt="" style="margin-bottom: 20px">
            <img src="<?php echo G5_ADMIN_URL ?>/img/gu6.png" alt="">
        </span>
    </div>
</div>

<?php
include_once('./admin.tail.php');
