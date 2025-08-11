<?php
include "../../../../common.php";

if(!$is_member) alert("로그인 후 이용 가능합니다.");

$sql="update g5_membership set mb_payment_method='' where mb_member_id='{$member['mb_id']}' and mb_payment_method='bank' and mb_status='pending'";
sql_query($sql);

goto_url("mypage_2.php");
?>