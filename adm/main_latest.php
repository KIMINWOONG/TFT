
<!-- #adm_latest -->
<div id="sh_adm_latest">
<?php
$sql_common = " from {$g5['board_new_table']} a, {$g5['board_table']} b, {$g5['group_table']} c where a.bo_table = b.bo_table and b.gr_id = c.gr_id ";

if ($gr_id) {
    $sql_common .= " and b.gr_id = '$gr_id' ";
}
if (isset($view) && $view) {
    if ($view == 'w') {
        $sql_common .= " and a.wr_id = a.wr_parent ";
    } elseif ($view == 'c') {
        $sql_common .= " and a.wr_id <> a.wr_parent ";
    }
}
$sql_order = " order by a.bn_id desc ";

$sql = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$colspan = 6;
?>
    <h3>최근게시물<a href=""></a></h3>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <colgroup>
        <col width="19%" />
        <col width="" />
        <col width="18%" />
        <col width="14%" />
    </colgroup>
      <tr>
        <th scope="col"><p>게시판</p></th>
        <th scope="col"><p>제목</p></th>
        <th scope="col"><p>이름</p></th>
        <th scope="col"><p>일시</p></th>
      </tr>
      <?php
                $sql = " select a.*, b.bo_subject, c.gr_subject, c.gr_id {$sql_common} {$sql_order} limit {$new_write_rows} ";
                $result = sql_query($sql);
                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    $tmp_write_table = $g5['write_prefix'] . $row['bo_table'];

                     // 원글
                    if ($row['wr_id'] == $row['wr_parent']) {
                        $comment = "";
                        $comment_link = "";
                        $row2 = sql_fetch(" select * from $tmp_write_table where wr_id = '{$row['wr_id']}' ");

                        $name = get_sideview($row2['mb_id'], get_text(cut_str($row2['wr_name'], $config['cf_cut_name'])), $row2['wr_email'], $row2['wr_homepage']);
                        // 당일인 경우 시간으로 표시함
                        $datetime = substr($row2['wr_datetime'], 0, 10);
                        $datetime2 = $row2['wr_datetime'];
                        if ($datetime == G5_TIME_YMD) {
                            $datetime2 = substr($datetime2, 11, 5);
                        } else {
                            $datetime2 = substr($datetime2, 5, 5);
                        }
                    } else // 코멘트
                    {
                        $comment = '댓글. ';
                        $comment_link = '#c_' . $row['wr_id'];
                        $row2 = sql_fetch(" select * from {$tmp_write_table} where wr_id = '{$row['wr_parent']}' ");
                        $row3 = sql_fetch(" select mb_id, wr_name, wr_email, wr_homepage, wr_datetime from {$tmp_write_table} where wr_id = '{$row['wr_id']}' ");

                        $name = get_sideview($row3['mb_id'], get_text(cut_str($row3['wr_name'], $config['cf_cut_name'])), $row3['wr_email'], $row3['wr_homepage']);
                        // 당일인 경우 시간으로 표시함
                        $datetime = substr($row3['wr_datetime'], 0, 10);
                        $datetime2 = $row3['wr_datetime'];
                        if ($datetime == G5_TIME_YMD) {
                            $datetime2 = substr($datetime2, 11, 5);
                        } else {
                            $datetime2 = substr($datetime2, 5, 5);
                        }
                    }
                ?>      
		      <tr>
        <td><?php echo cut_str($row['bo_subject'], 20) ?></td>
        <td><a href="<?php echo get_pretty_url($row['bo_table'], $row2['wr_id']); ?><?php echo $comment_link ?>"><?php echo $comment ?><?php echo conv_subject($row2['wr_subject'], 100) ?></a></td>
        <td><span class="sv_wrap">
<a href="<?php echo G5_BBS_URL ?>/profile.php?mb_id=adm" class="sv_member" title=" 자기소개" target="_blank" onclick="return false;"><?php echo $name ?></a>
</td>
        <td><?php echo $datetime ?></td>
      </tr>

      <?php
                }
                if ($i == 0) {
                    echo '<tr><td colspan="' . $colspan . '" class="empty_table">자료가 없습니다.</td></tr>';
                }
                ?>      
		    </table>

</div>
<!-- #adm_latest -->
