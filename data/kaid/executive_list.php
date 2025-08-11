<?php
$sub_menu = "200400";
include_once('./_common.php');

// 관리자 권한 체크
if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
}

$g5['title'] = '임원관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

// 페이징 처리
$page = (int)$_GET['page'] ?: 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// 임원 목록 조회
$sql = "SELECT * FROM g5_executive ORDER BY ex_order ASC, ex_id DESC LIMIT $offset, $per_page";
$result = sql_query($sql);

// 전체 개수
$total_sql = "SELECT COUNT(*) as cnt FROM g5_executive";
$total_result = sql_fetch($total_sql);
$total_count = $total_result['cnt'];
$total_page = ceil($total_count / $per_page);

// 삭제 처리
if ($_POST['mode'] == 'delete' && $_POST['chk']) {
    foreach ($_POST['chk'] as $id) {
        $id = (int)$id;
        // 기존 이미지 파일 삭제
        $img_sql = "SELECT ex_image FROM g5_executive WHERE ex_id = $id";
        $img_row = sql_fetch($img_sql);
        if ($img_row['ex_image'] && file_exists(G5_DATA_PATH.'/executive/'.$img_row['ex_image'])) {
            unlink(G5_DATA_PATH.'/executive/'.$img_row['ex_image']);
        }
        sql_query("DELETE FROM g5_executive WHERE ex_id = $id");
    }
    alert('선택한 임원정보가 삭제되었습니다.', './executive_list.php');
}
?>

<div class="local_ov01 local_ov">
    <span class="btn_ov01">
        <span class="ov_txt">전체 임원 수</span>
        <span class="ov_num"> <?php echo number_format($total_count); ?>명</span>
    </span>
</div>

<div class="local_desc01 local_desc">
    <p>임원 정보를 관리할 수 있습니다. 순서는 숫자가 작을수록 먼저 표시됩니다.</p>
</div>

<form name="fexecutive" method="post" action="./executive_list.php">
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption>임원관리 목록</caption>
    <thead>
    <tr>
        <th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form)"></th>
        <th scope="col">순서</th>
        <th scope="col">이미지</th>
        <th scope="col">이름</th>
        <th scope="col">카테고리</th>
        <th scope="col">부서</th>
        <th scope="col">등록일</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (sql_num_rows($result) == 0) {
        echo '<tr><td colspan="8" class="empty_table">등록된 임원이 없습니다.</td></tr>';
    } else {
        while ($row = sql_fetch_array($result)) {
    ?>
    <tr>
        <td><input type="checkbox" name="chk[]" value="<?php echo $row['ex_id']; ?>"></td>
        <td><?php echo $row['ex_order']; ?></td>
        <td>
            <?php if ($row['ex_image']) { ?>
                <img src="<?php echo G5_DATA_URL; ?>/executive/<?php echo $row['ex_image']; ?>" alt="<?php echo $row['ex_name']; ?>" style="width:50px;height:50px;object-fit:cover;border-radius:50%;">
            <?php } else { ?>
                <div style="width:50px;height:50px;background:#ddd;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#888;">사진</div>
            <?php } ?>
        </td>
        <td><?php echo $row['ex_name']; ?></td>
        <td><?php echo $row['ex_category']; ?></td>
        <td><?php echo $row['ex_department']; ?></td>
        <td><?php echo substr($row['ex_datetime'], 0, 10); ?></td>
        <td>
            <a href="./executive_form.php?mode=edit&ex_id=<?php echo $row['ex_id']; ?>" class="btn btn_03">수정</a>
        </td>
    </tr>
    <?php
        }
    }
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./executive_form.php" class="btn btn_01">임원 추가</a>
    <input type="hidden" name="mode" value="">
    <button type="button" onclick="fexecutive_submit('delete');" class="btn btn_02">선택삭제</button>
</div>

</form>

<?php
// 페이징
echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './executive_list.php?page=');
?>

<script>
function check_all(frm) {
    var f = document.fexecutive;
    var chk = document.getElementsByName("chk[]");
    
    if (f.chkall.checked) {
        for (var i=0; i<chk.length; i++) {
            chk[i].checked = true;
        }
    } else {
        for (var i=0; i<chk.length; i++) {
            chk[i].checked = false;
        }
    }
}

function fexecutive_submit(mode) {
    var f = document.fexecutive;
    
    if (mode == 'delete') {
        var chk_count = 0;
        var chk = document.getElementsByName("chk[]");
        for (var i=0; i<chk.length; i++) {
            if (chk[i].checked) chk_count++;
        }
        
        if (chk_count == 0) {
            alert('삭제할 임원을 선택해주세요.');
            return false;
        }
        
        if (!confirm('선택한 임원정보를 삭제하시겠습니까?')) {
            return false;
        }
    }
    
    f.mode.value = mode;
    f.submit();
}
</script>

<style>
.empty_table { padding: 50px 0; text-align: center; color: #999; }
.btn_fixed_top { margin-top: 20px; }
.btn_fixed_top .btn { margin-right: 5px; }
</style>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>