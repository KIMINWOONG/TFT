<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$delete_str = "";
if ($w == 'x') $delete_str = "댓";
if ($w == 'u') $g5['title'] = $delete_str."글 수정";
else if ($w == 'd' || $w == 'x') $g5['title'] = $delete_str."글 삭제";
else $g5['title'] = $g5['title'];

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 비밀번호 확인 시작 { -->
<div id="pw_confirm" class="mbskin mbskin2">
    <h1>회원 정보 입력</h1>
    <div class="pw_confirm_title">
        <?php if ($w == 'u') { ?>
         <img src="<?php echo G5_THEME_IMG_URL ?>/mem_icon1.png" alt="">
            <p>이 글은 비밀 글입니다.
                <strong>비밀번호를 입력해주세요.</strong>
            </p>
        <?php } else if ($w == 'd' || $w == 'x') {  ?>
         <img src="<?php echo G5_THEME_IMG_URL ?>/mem_icon1.png" alt="">
            <p>이 글은 비밀 글입니다.
                <strong>비밀번호를 입력해주세요.</strong>
            </p>
        <?php } else {  ?>
            <img src="<?php echo G5_THEME_IMG_URL ?>/mem_icon1.png" alt="">
            <p>이 글은 비밀 글입니다.
                <strong>비밀번호를 입력해주세요.</strong>
            </p>
        <!-- <strong>비밀글 기능으로 보호된 글입니다.</strong>
        작성자와 관리자만 열람하실 수 있습니다.<br> 본인이라면 비밀번호를 입력하세요. -->
        <?php }  ?>
        </div>

    <form name="fboardpassword" action="<?php echo $action;  ?>" method="post">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="comment_id" value="<?php echo $comment_id ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">

    <fieldset>
        <label for="pw_wr_password" class="sound_only">비밀번호<strong>필수</strong></label>
        <input type="password" name="wr_password" id="password_wr_password" required class="frm_input required" size="15" maxLength="20" placeholder="비밀번호를 입력해주세요.">
        <div class="lost_info">
        <p>비밀번호 분실시 고객센터로 문의 주세요</p>
        <h6>T. 02-2273-3875</h6>
    </div>

    <div class="btn_wrap">
        <a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=qna">취소</a>
        <input type="submit" value="확인" class="btn_submit">
    </div>
        
    </fieldset>
    </form>

    

</div>
<!-- } 비밀번호 확인 끝 -->