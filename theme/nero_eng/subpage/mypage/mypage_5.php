<?php
include "../../../../common.php";

$tNum="마이페이지";
$sNum="마이페이지";

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 로그인 체크
if (!$is_member && !$is_nonemember) {
    alert("로그인 후 이용할 수 있습니다.", G5_URL);
}

include_once(G5_THEME_PATH.'/head2.php');
?>

<div class="mypage common">
  <div class="width">
    <div class="sub_menu sub_menu_nonmem">
        <ul class="">
            <li class="menu_on"><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_5.php" class="menu_on">회원정보</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_6.php">학술대회 신청 내역</a></li>
            <li><a href="<?php echo G5_THEME_URL ?>/subpage/mypage/mypage_7.php">초록 제출 및 수정</a></li>
        </ul>
    </div>


    <h2>회원정보 수정</h2>
    <table> 

        <tr>
            <th>성명</th>
            <td><?=get_session("ss_nonemb_name")?></td>
        </tr>
        <tr>
            <th>생년월일</th>
            <td><?=date("Y.m.d", strtotime(get_session("ss_nonemb_birth")))?></td>
        </tr>
       
    </table>



  </div>
</div>



<?php
include_once(G5_THEME_PATH.'/tail.php');
