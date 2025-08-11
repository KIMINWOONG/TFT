<?php
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '평의원관리';
include_once(G5_ADMIN_PATH.'/admin.head.php');

$sql_common = " FROM g5_council ";

$sql_search = "";
if ($stx) {
    $sql_search .= " AND (co_name LIKE '%$stx%' OR co_department LIKE '%$stx%') ";
}

if (!$sst) {
    $sst = 'co_order, co_id';
    $sod = 'asc';
}

$sql_order = " ORDER BY $sst $sod ";

$sql = " SELECT COUNT(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page = ceil($total_count / $rows);
if ($page < 1) $page = 1;
if ($page > $total_page) $page = $total_page;
$from_record = ($page - 1) * $rows;

$sql = " SELECT * {$sql_common} {$sql_search} {$sql_order} LIMIT {$from_record}, {$rows} ";
$result = sql_query($sql);

// 선택삭제 처리
if ($_POST['mode'] == 'delete' && is_array($_POST['chk'])) {
    foreach ($_POST['chk'] as $id) {
        $id = (int)$id;
        // 이미지 파일 삭제
        $img_sql = "SELECT co_image FROM g5_council WHERE co_id = $id";
        $img_result = sql_query($img_sql);
        $img_row = sql_fetch_array($img_result);
        if ($img_row['co_image'] && file_exists(G5_DATA_PATH.'/council/'.$img_row['co_image'])) {
            unlink(G5_DATA_PATH.'/council/'.$img_row['co_image']);
        }
        sql_query("DELETE FROM g5_council WHERE co_id = $id");
    }
    goto_url('./council_list.php');
}
?>

<div class="local_ov01 local_ov">
    <?php echo $g5['title']; ?> 목록
    <span class="btn_ov01"><span class="ov_txt">전체 </span><span class="ov_num"><?php echo number_format($total_count); ?>건 </span></span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<fieldset>
    <legend>평의원 검색</legend>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
    <input type="submit" class="btn_submit" value="검색">
</fieldset>
</form>

<form name="fcouncil" id="fcouncil" method="post">
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">평의원 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
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
    for ($i=0; $row=sql_fetch_array($result); $i++) {
    ?>
    <tr class="<?php echo $list[$i]['class']; ?>">
        <td><input type="checkbox" name="chk[]" value="<?php echo $row['co_id']; ?>"></td>
        <td><?php echo $row['co_order']; ?></td>
        <td>
            <?php if ($row['co_image']) { ?>
                <img src="<?php echo G5_DATA_URL; ?>/council/<?php echo $row['co_image']; ?>" alt="<?php echo $row['co_name']; ?>" style="width:50px;height:50px;object-fit:cover;border-radius:50%;">
            <?php } else { ?>
                <div style="width:50px;height:50px;background:#ddd;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#888;">사진</div>
            <?php } ?>
        </td>
        <td><?php echo $row['co_name']; ?></td>
        <td><?php echo $row['co_category']; ?></td>
        <td><?php echo $row['co_department']; ?></td>
        <td><?php echo substr($row['co_datetime'], 0, 10); ?></td>
        <td>
            <a href="./council_form.php?mode=edit&co_id=<?php echo $row['co_id']; ?>" class="btn btn_03">수정</a>
        </td>
    </tr>
    <?php
        }
    if ($i == 0 && sql_num_rows($result) == 0) {
        echo '<tr><td colspan="8" class="empty_table">등록된 평의원이 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./council_form.php" class="btn btn_01">평의원 추가</a>
    <input type="hidden" name="mode" value="">
    <button type="button" onclick="fcouncil_submit('delete');" class="btn btn_02">선택삭제</button>
</div>

</form>

<?php
// 페이징
echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './council_list.php?page=');
?>

<script>
function check_all(frm) {
    var f = document.fcouncil;
    var chk = document.getElementsByName("chk[]");
    
    if (f.chkall.checked) {
        for (var i=0; i<chk.length; i++)
            chk[i].checked = true;
    } else {
        for (var i=0; i<chk.length; i++)
            chk[i].checked = false;
    }
}

function fcouncil_submit(mode) {
    var f = document.fcouncil;
    
    if (mode == 'delete') {
        if (!is_checked("chk[]")) {
            alert("삭제할 평의원을 하나 이상 선택하세요.");
            return false;
        }
        
        if (!confirm("선택한 평의원을 정말 삭제하시겠습니까?")) {
            return false;
        }
    }
    
    f.mode.value = mode;
    f.submit();
    
    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>