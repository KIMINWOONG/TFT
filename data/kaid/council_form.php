<?php
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$mode = $_GET['mode'] ?? 'add';
$co_id = (int)$_GET['co_id'];

$g5['title'] = ($mode == 'edit') ? '평의원 수정' : '평의원 등록';
include_once(G5_ADMIN_PATH.'/admin.head.php');

$write = array();
if ($mode == 'edit' && $co_id) {
    $sql = "SELECT * FROM g5_council WHERE co_id = $co_id";
    $result = sql_query($sql);
    if (!sql_num_rows($result)) {
        alert('존재하지 않는 평의원입니다.');
    }
    $write = sql_fetch_array($result);
}

// 기본값 설정
$write['co_name'] = $write['co_name'] ?? '';
$write['co_category'] = $write['co_category'] ?? '';
$write['co_department'] = $write['co_department'] ?? '';
$write['co_career'] = $write['co_career'] ?? '';
$write['co_order'] = $write['co_order'] ?? 0;
$write['co_image'] = $write['co_image'] ?? '';
?>

<div class="local_desc01 local_desc">
    <p>평의원 정보를 입력하세요.</p>
</div>

<form name="fcouncil" method="post" action="./council_update.php" enctype="multipart/form-data">
<input type="hidden" name="mode" value="<?php echo $mode; ?>">
<input type="hidden" name="co_id" value="<?php echo $co_id; ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>평의원 정보</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="co_name">이름<strong class="sound_only">필수</strong></label></th>
        <td>
            <input type="text" name="co_name" id="co_name" value="<?php echo $write['co_name']; ?>" required class="frm_input required" size="30" maxlength="50">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="co_category">카테고리<strong class="sound_only">필수</strong></label></th>
        <td>
            <select name="co_category" id="co_category" required class="frm_input required">
                <option value="">선택하세요</option>
                <option value="당연직" <?php echo ($write['co_category'] == '당연직') ? 'selected' : ''; ?>>당연직</option>
                <option value="선출직" <?php echo ($write['co_category'] == '선출직') ? 'selected' : ''; ?>>선출직</option>
                <option value="감사" <?php echo ($write['co_category'] == '감사') ? 'selected' : ''; ?>>감사</option>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="co_department">부서</label></th>
        <td>
            <input type="text" name="co_department" id="co_department" value="<?php echo $write['co_department']; ?>" class="frm_input" size="30" maxlength="50">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="co_order">순서</label></th>
        <td>
            <input type="number" name="co_order" id="co_order" value="<?php echo $write['co_order']; ?>" class="frm_input" min="0" max="9999">
            <span class="frm_info">숫자가 작을수록 먼저 출력됩니다.</span>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="co_image">이미지</label></th>
        <td>
            <?php if ($write['co_image']) { ?>
                <div style="margin-bottom:10px;">
                    <img src="<?php echo G5_DATA_URL; ?>/council/<?php echo $write['co_image']; ?>" alt="현재 이미지" style="width:100px;height:100px;object-fit:cover;border-radius:50%;">
                    <br><label><input type="checkbox" name="del_image" value="1"> 이미지 삭제</label>
                </div>
            <?php } ?>
            <input type="file" name="co_image" id="co_image" accept="image/*">
            <span class="frm_info">이미지 파일만 업로드 가능합니다. (jpg, jpeg, png, gif)</span>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="co_career">경력</label></th>
        <td>
            <textarea name="co_career" id="co_career" class="frm_input" rows="8" cols="80"><?php echo $write['co_career']; ?></textarea>
            <span class="frm_info">줄바꿈으로 경력을 구분하여 입력하세요.</span>
        </td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <input type="submit" value="확인" class="btn btn_01">
    <a href="./council_list.php" class="btn btn_02">목록</a>
</div>

</form>

<script>
function fcouncil_submit() {
    var f = document.fcouncil;
    
    if (!$('#co_name').val()) {
        alert('이름을 입력하세요.');
        $('#co_name').focus();
        return false;
    }
    
    if (!$('#co_category').val()) {
        alert('카테고리를 선택하세요.');
        $('#co_category').focus();
        return false;
    }
    
    return true;
}

// 이미지 미리보기
$('#co_image').change(function() {
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = '<div style="margin-top:10px;"><img src="' + e.target.result + '" style="width:100px;height:100px;object-fit:cover;border-radius:50%;"></div>';
            $('#co_image').parent().append(preview);
        };
        reader.readAsDataURL(file);
    }
});

$(document).ready(function() {
    $("form[name=fcouncil]").submit(function() {
        return fcouncil_submit();
    });
});
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>