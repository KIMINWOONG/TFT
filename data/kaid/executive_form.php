<?php
$sub_menu = "200400";
include_once('./_common.php');

// 관리자 권한 체크
if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
}

$mode = $_GET['mode'] ?? 'add';
$ex_id = (int)$_GET['ex_id'];

$g5['title'] = $mode == 'edit' ? '임원 수정' : '임원 추가';
include_once(G5_ADMIN_PATH.'/admin.head.php');

// 수정 모드일 때 기존 데이터 조회
$write = array();
if ($mode == 'edit' && $ex_id) {
    $sql = "SELECT * FROM g5_executive WHERE ex_id = $ex_id";
    $write = sql_fetch($sql);
    if (!$write) {
        alert('해당 임원정보를 찾을 수 없습니다.');
    }
}

// 기본값 설정
$write['ex_name'] = $write['ex_name'] ?? '';
$write['ex_category'] = $write['ex_category'] ?? '';
$write['ex_department'] = $write['ex_department'] ?? '';
$write['ex_career'] = $write['ex_career'] ?? '';
$write['ex_order'] = $write['ex_order'] ?? 0;
$write['ex_image'] = $write['ex_image'] ?? '';
?>

<div class="local_desc01 local_desc">
    <p><?php echo $mode == 'edit' ? '임원 정보를 수정합니다.' : '새로운 임원 정보를 추가합니다.'; ?></p>
</div>

<form name="fexecutive" method="post" action="./executive_update.php" enctype="multipart/form-data">
<input type="hidden" name="mode" value="<?php echo $mode; ?>">
<input type="hidden" name="ex_id" value="<?php echo $ex_id; ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>임원 정보 입력</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="ex_name">이름<strong class="sound_only">필수</strong></label></th>
        <td>
            <input type="text" name="ex_name" id="ex_name" value="<?php echo $write['ex_name']; ?>" required class="frm_input required" size="30" maxlength="50">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="ex_category">카테고리<strong class="sound_only">필수</strong></label></th>
        <td>
            <select name="ex_category" id="ex_category" required class="frm_input required">
                <option value="">카테고리 선택</option>
                <option value="지역별" <?php echo ($write['ex_category'] == '지역별') ? 'selected' : ''; ?>>지역별</option>
                <option value="전체분과별" <?php echo ($write['ex_category'] == '전체분과별') ? 'selected' : ''; ?>>전체분과별</option>
                <option value="연구 분과" <?php echo ($write['ex_category'] == '연구 분과') ? 'selected' : ''; ?>>연구 분과</option>
                <option value="영상 및 AI 분과" <?php echo ($write['ex_category'] == '영상 및 AI 분과') ? 'selected' : ''; ?>>영상 및 AI 분과</option>
                <option value="구강외과 분과" <?php echo ($write['ex_category'] == '구강외과 분과') ? 'selected' : ''; ?>>구강외과 분과</option>
                <option value="치주 분과" <?php echo ($write['ex_category'] == '치주 분과') ? 'selected' : ''; ?>>치주 분과</option>
                <option value="보철 분과" <?php echo ($write['ex_category'] == '보철 분과') ? 'selected' : ''; ?>>보철 분과</option>
                <option value="통합치의학 및 장애인치과 분과" <?php echo ($write['ex_category'] == '통합치의학 및 장애인치과 분과') ? 'selected' : ''; ?>>통합치의학 및 장애인치과 분과</option>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="ex_department">부서</label></th>
        <td>
            <input type="text" name="ex_department" id="ex_department" value="<?php echo $write['ex_department']; ?>" class="frm_input" size="30" maxlength="50">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="ex_order">순서</label></th>
        <td>
            <input type="number" name="ex_order" id="ex_order" value="<?php echo $write['ex_order']; ?>" class="frm_input" min="0" max="9999">
            <span class="frm_info">숫자가 작을수록 먼저 표시됩니다.</span>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="ex_image">이미지</label></th>
        <td>
            <?php if ($write['ex_image']) { ?>
                <div style="margin-bottom:10px;">
                    <img src="<?php echo G5_DATA_URL; ?>/executive/<?php echo $write['ex_image']; ?>" alt="현재 이미지" style="width:100px;height:100px;object-fit:cover;border-radius:50%;">
                    <br>
                    <label><input type="checkbox" name="del_image" value="1"> 이미지 삭제</label>
                </div>
            <?php } ?>
            <input type="file" name="ex_image" id="ex_image" accept="image/*">
            <span class="frm_info">권장 크기: 200x200px 이상의 정사각형 이미지</span>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="ex_career">경력</label></th>
        <td>
            <textarea name="ex_career" id="ex_career" class="frm_input" rows="8" cols="80"><?php echo $write['ex_career']; ?></textarea>
            <span class="frm_info">경력 사항을 입력해주세요. 줄바꿈으로 구분됩니다.</span>
        </td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <input type="submit" value="<?php echo $mode == 'edit' ? '수정' : '등록'; ?>" class="btn btn_01">
    <a href="./executive_list.php" class="btn btn_02">목록</a>
</div>

</form>

<script>
$(function() {
    // 폼 유효성 검사
    $('form[name="fexecutive"]').submit(function() {
        if (!$('#ex_name').val()) {
            alert('이름을 입력해주세요.');
            $('#ex_name').focus();
            return false;
        }
        
        if (!$('#ex_category').val()) {
            alert('카테고리를 선택해주세요.');
            $('#ex_category').focus();
            return false;
        }
        
        return true;
    });
    
    // 이미지 미리보기
    $('#ex_image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = $('<div class="img_preview" style="margin-top:10px;"><img src="' + e.target.result + '" style="width:100px;height:100px;object-fit:cover;border-radius:50%;"><br><small>미리보기</small></div>');
                $('.img_preview').remove();
                $('#ex_image').parent().append(preview);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>

<style>
.frm_info { color: #666; font-size: 12px; margin-left: 10px; }
.img_preview { margin-top: 10px; }
.btn_fixed_top { margin-top: 20px; }
.btn_fixed_top .btn { margin-right: 5px; }
</style>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>