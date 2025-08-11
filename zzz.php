<?php
include_once('./_common.php');

$sql="select * from g5_member where mb_level='5' ";
$mb_result=sql_query($sql);
while($mb=sql_fetch_array($mb_result)){
	$sql="select * From g5_membership where mb_member_id='{$mb['mb_id']}'";
	$mr_row=sql_fetch($sql);
	if(!$mr_row['mb_id']){
		$annual_sql = " INSERT INTO g5_membership SET
                        mb_type = 'annual',
                        mb_content = '2025년 연회비',
                        mb_amount = '0',
                        mb_start_date = '2025-01-01',
                        mb_end_date = '2125-12-31',
                        mb_due_date = '2025-01-01',
                        mb_status = 'completed',
                        mb_member_id = '{$mb['mb_id']}',
                        mb_year = '2025',
                        mb_note = '회원가입시 자동 생성',
						mb_payment_method = '현금',
						mb_approve_date = NOW(),
                        mb_reg_date = NOW() ";
						echo $annual_sql;exit;
		sql_query($annual_sql);

	}


}