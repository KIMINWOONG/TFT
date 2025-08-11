<?php
$sub_menu = "300830";
include_once('./_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    goto_url(G5_URL);
}

$sql = "SELECT * FROM g5_team_members ORDER BY mb_order ASC, mb_id DESC";
$result = sql_query($sql);

$g5['title'] = '구성원 관리';
include_once('./admin.head.php');
?>

<div class="local_ov01 local_ov">
    <?php echo '전체 구성원 '.number_format(sql_num_rows($result)).'명'; ?>
</div>

<div class="btn_add01 btn_add">
    <a href="./team_member_form.php" class="btn_add_opt">구성원 추가</a>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
        <caption>구성원 목록</caption>
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">이름</th>
                <th scope="col">직책</th>
                <th scope="col">간단소개</th>
                <th scope="col">연락처</th>
                <th scope="col">출력순서</th>
                <th scope="col">등록일</th>
                <th scope="col">관리</th>
            </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
        ?>
            <tr>
                <td class="td_num"><?php echo $row['mb_id']; ?></td>
                <td class="td_name"><?php echo $row['mb_name']; ?></td>
                <td class="td_position"><?php echo $row['mb_position']; ?></td>
                <td class="td_specialty"><?php echo $row['mb_intro']; ?></td>
                <td class="td_contact"><?php echo $row['mb_contact']; ?></td>
                <td class="td_order"><?php echo $row['mb_order']; ?></td>
                <td class="td_datetime"><?php echo substr($row['mb_regdate'], 0, 10); ?></td>
                <td class="td_mng td_mng_s">
                    <a href="./team_member_form.php?w=u&mb_id=<?php echo $row['mb_id']; ?>" class="btn btn_03">수정</a>
                    <a href="./team_member_delete.php?mb_id=<?php echo $row['mb_id']; ?>" onclick="return confirm('정말 삭제하시겠습니까?');" class="btn btn_02">삭제</a>
                </td>
            </tr>
        <?php
        }
        if ($i == 0) {
            echo '<tr><td colspan="8" class="empty_table">등록된 구성원이 없습니다.</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<?php
include_once('./admin.tail.php');
?>