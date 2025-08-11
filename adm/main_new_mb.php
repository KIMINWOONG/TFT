
<!-- #adm_new member -->
<div id="sh_adm_new_mb">

    <h3>신규가입 회원 현황<a href="<?php echo G5_ADMIN_URL ?>/member_list.php"></a></h3>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <colgroup>
        <col width="16.6%" />
        <col width="16.6%" />
        <col width="" />
        <col width="16.6%" />
        <col width="16.6%" />
        <col width="13%" />
    </colgroup>
      <tr>
        <th scope="col"><p>아이디</p></th>
        <th scope="col"><p>이름</p></th>
        <th scope="col"><p>휴대폰</p></th>
        <th scope="col"><p>가입일</p></th>
        <th scope="col"><p>최종접속</p></th>
        <th scope="col"><p>권한</p></th>
      </tr>
      <?php
                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    // 접근가능한 그룹수
                    $sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
                    $row2 = sql_fetch($sql2);
                    $group = "";
                    if ($row2['cnt']) {
                        $group = '<a href="./boardgroupmember_form.php?mb_id=' . $row['mb_id'] . '">' . $row2['cnt'] . '</a>';
                    }

                    if ($is_admin == 'group') {
                        $s_mod = '';
                        $s_del = '';
                    } else {
                        $s_mod = '<a href="./member_form.php?$qstr&amp;w=u&amp;mb_id=' . $row['mb_id'] . '">수정</a>';
                        $s_del = '<a href="./member_delete.php?' . $qstr . '&amp;w=d&amp;mb_id=' . $row['mb_id'] . '&amp;url=' . $_SERVER['SCRIPT_NAME'] . '" onclick="return delete_confirm(this);">삭제</a>';
                    }
                    $s_grp = '<a href="./boardgroupmember_form.php?mb_id=' . $row['mb_id'] . '">그룹</a>';

                    $leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date("Ymd", G5_SERVER_TIME);
                    $intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date("Ymd", G5_SERVER_TIME);

                    $mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

                    $mb_id = $row['mb_id'];
                ?>     
              <tr>
        <td><?php echo $mb_id ?></td>
        <td><?php echo get_text($row['mb_name']) ?></td>
        <td><?php echo $row['mb_hp'] ?></td>
        <td><?php echo substr($row['mb_datetime'], 0, 10) ?></td>
        <td><?php echo substr($row['mb_today_login'], 0, 10) ?></td>
        <td><?php echo $row['mb_level'] ?></td>
      </tr>
      <?php
                }
                if ($i == 0) {
                    echo '<tr><td colspan="' . $colspan . '" class="empty_table">자료가 없습니다.</td></tr>';
                }
                ?>     
          </table>
	<div class="mb_count">
    	<ul>
        	<li><p class="c_blue"><?php echo number_format($total_count) ?></p>전체회원</li>
            <li><p class="c_orange"><?php echo number_format($qq['cnt']) ?></p>오늘 가입회원</li>
            <li><p><?php echo number_format($leave['cnt']) ?></p>오늘 탈퇴회원</li>
            <li><p><?php echo number_format($leave_count) ?></p>총 탈퇴회원</li>
        </ul>
    	
    </div>
	
</div>
<!-- #adm_new member -->
