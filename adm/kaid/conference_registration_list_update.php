<?php
$sub_menu = "600210";
require_once './_common.php';

for($i=0;$i<count($_POST['chk']);$i++){
	$k=$_POST['chk'][$i];
	$cr_id=$_POST['cr_id'][$k];

	$sql="delete from g5_conference_registration where cr_id='{$cr_id}'";
	sql_query($sql);
}

echo '<script>alert("삭제되었습니다. ");parent.location.reload();</script>';