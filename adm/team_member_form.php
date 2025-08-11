<?php
$sub_menu = "300830";
include_once('./_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    goto_url(G5_URL);
}

$w = isset($_GET['w']) ? clean_xss_tags($_GET['w']) : '';
$mb_id = isset($_GET['mb_id']) ? (int)$_GET['mb_id'] : 0;

if ($w == 'u' && $mb_id) {
    $sql = "SELECT * FROM g5_team_members WHERE mb_id = '{$mb_id}'";
    $mb = sql_fetch($sql);
    if (!$mb['mb_id']) {
        alert('존재하지 않는 구성원입니다.');
        goto_url('./team_member.php');
    }
}

$g5['title'] = '구성원 ' . ($w == 'u' ? '수정' : '등록');
include_once('./admin.head.php');
?>

<form name="fmember" id="fmember" action="./team_member_form_update.php" onsubmit="return fmember_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="mb_id" value="<?php echo $mb_id ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
        <caption>구성원 정보 입력</caption>
        <tbody>
            <tr>
                <th scope="row"><label for="mb_name">이름<strong class="sound_only">필수</strong></label></th>
                <td><input type="text" name="mb_name" value="<?php echo $mb['mb_name'] ?>" id="mb_name" required class="required frm_input" size="20"></td>
            </tr>
            <tr>
                <th scope="row"><label for="mb_position">직책<strong class="sound_only">필수</strong></label></th>
                <td><input type="text" name="mb_position" value="<?php echo $mb['mb_position'] ?>" id="mb_position" required class="required frm_input" size="20"></td>
            </tr>
            <tr>
                <th scope="row"><label for="mb_specialty">전문분야<strong class="sound_only">필수</strong></label></th>
                <td><textarea name="mb_specialty" id="mb_specialty" class=""><?php echo $mb['mb_specialty'] ?></textarea></td>
            </tr>
            <tr>
                <th scope="row"><label for="mb_education">학력 및 이력<strong class="sound_only">필수</strong></label></th>
                <td><textarea name="mb_education" id="mb_education"  class=""><?php echo $mb['mb_education'] ?></textarea></td>
            </tr>
			<!--
            <tr>
                <th scope="row"><label for="mb_contact">연락처<strong class="sound_only">필수</strong></label></th>
                <td><input type="text" name="mb_contact" value="<?php echo $mb['mb_contact'] ?>" id="mb_contact" class="frm_input" size="30" oninput="autoHyphen(this)" maxlength="13"></td>
            </tr>
			-->
            <tr>
                <th scope="row"><label for="mb_thumbnail">썸네일 이미지</label></th>
                <td>
                    <input type="file" name="mb_thumbnail" id="mb_thumbnail">
                    <?php
                    if ($mb['mb_thumbnail']) {
                        echo '<br><img src="/data/team/'.$mb['mb_thumbnail'].'" style="height:100px;">';
                        echo '<br><label><input type="checkbox" name="mb_thumbnail_del" value="1"> 삭제</label>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="mb_intro">간단소개<strong class="sound_only">필수</strong></label></th>
                <td><input type="text" name="mb_intro" id="mb_intro" value="<?php echo $mb['mb_intro'] ?>" class="frm_input" size=50></td>
            </tr>
            <tr>
                <th scope="row"><label for="mb_order">출력 순서</label></th>
                <td><input type="number" name="mb_order" value="<?php echo $mb['mb_order'] ?>" id="mb_order" class="frm_input" size="10"></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="./team_member.php" class="btn_cancel">목록</a>
</div>

</form>

<script>
$(function(){
	autoHyphen = function(target){
	 target.value = target.value
        .replace(/[^0-9]/g, '')
        .replace(/(^02.{0}|^01.{1}|[0-9]{3,4})([0-9]{3,4})([0-9]{4})/g, "$1-$2-$3");
	};
});

function fmember_submit(f) {
    if (!f.mb_name.value) {
        alert('이름을 입력하세요.');
        f.mb_name.focus();
        return false;
    }
    if (!f.mb_position.value) {
        alert('직책을 입력하세요.');
        f.mb_position.focus();
        return false;
    }
    if (!f.mb_specialty.value) {
        alert('전문분야를 입력하세요.');
        f.mb_specialty.focus();
        return false;
    }
    if (!f.mb_education.value) {
        alert('학력 및 이력을 입력하세요.');
        f.mb_education.focus();
        return false;
    }
	/**
    if (!f.mb_contact.value) {
        alert('연락처를 입력하세요.');
        f.mb_contact.focus();
        return false;
    }
	*/
    if (!f.mb_intro.value) {
        alert('간단소개를 입력하세요.');
        f.mb_intro.focus();
        return false;
    }
    return true;
}
</script>

<?php
include_once('./admin.tail.php');
?>