<?php

if(isset($_POST['hp']) && count($_POST['hp'])>0){
	$wr_1=implode("-", $_POST['hp']);

	$sql="update {$write_table} set wr_1='{$wr_1}' where wr_id='{$wr_id}'";
	sql_query($sql);
}

if($w==""){
	$receive_mem=array();
	$sql="select * from g5_member where mb_level>1 and find_in_set('{$ca_name}',mb_1)>0";
	$tmp_result=sql_query($sql);
	while($tmp_row=sql_fetch_array($tmp_result)){
		$receive_mem[]=$tmp_row;
	}
	if(count($receive_mem)>0){
		include_once(G5_LIB_PATH.'/mailer.lib.php');

		ob_start();
?>
QNA가 등록되었습니다.<BR>
답변 부탁합니다.
<?
		$content = ob_get_contents();
		ob_end_clean();

		$from_name=$config['cf_admin_email_name'];
		$from_email=$config['cf_admin_email'];
		$subject="[KAID] {$wr_name}님의 QNA가 접수 되었습니다.";

		for($zz=0;$zz<count($receive_mem);$zz++){
			$to_email=$receive_mem[$zz]['mb_email'];
			mailer($from_name, $from_email, $to_email, $subject, $content, 1);
		}
	}

}elseif($w=="r"){
	include_once(G5_LIB_PATH.'/mailer.lib.php');

    ob_start();
?>
작성하신 QNA의 답변이 완료되었습니다.<br>
<a href="<?=G5_BBS_URL?>/board.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>">답변 바로가기</a>
<?
    $content = ob_get_contents();
    ob_end_clean();

	$from_name=$config['cf_admin_email_name'];
	$from_email=$config['cf_admin_email'];
	$to_email=$write['wr_email'];
	$subject="[KAID] 작성하신 QNA의 답변이 완료되었습니다.";


	mailer($from_name, $from_email, $to_email, $subject, $content, 1);

}