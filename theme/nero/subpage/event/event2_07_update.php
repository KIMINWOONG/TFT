<?php
include "../../../../common.php";
    // 필수 입력 검증
    if (empty($_POST['member_class'])) {
        alert("회원구분을 선택해주세요.");
    }
    if (empty($_POST['name_kor'])) {
        alert("성명(국문)을 입력해주세요.");
    }
    if (empty($_POST['name_eng'])) {
        alert("성명(영문)을 입력해주세요.");
    }
    if (empty($_POST['email'])) {
        alert("이메일을 입력해주세요.");
    }
    if (!$is_member && empty($_POST['password'])) {
        alert("비밀번호를 입력해주세요.");
    }
    
$annual_fee_paid = false;
if ($is_member) {
    // 연회비 납부 여부 확인
    $membership_check = sql_fetch("SELECT * FROM g5_membership WHERE mb_member_id = '{$member['mb_id']}' AND (now() between mb_start_date and mb_end_date) AND mb_status = 'completed' AND mb_type = 'annual'");
    $annual_fee_paid = $membership_check ? true : false;
}


    // 등록비 계산
    $registration_fee = 0;
    switch($_POST['member_class']) {
        case 'member':
            $registration_fee = $conference['sy_fee_member'];
            break;
        case 'student':
        case 'fellow':
            $registration_fee = $conference['sy_fee_associate'];
            break;
        default:
            $registration_fee = $conference['sy_fee_nonmember'];
    }

	if($is_nonemember){
		$cr_nonemb_name=get_session("ss_nonemb_name");
		$cr_nonemb_birth=get_session("ss_nonemb_birth");
	}
    
    // 데이터베이스 저장
    $sql = "INSERT INTO g5_conference_registration SET
            cr_sy_id = '{$sy_id}',
            cr_reg_type = '{$reg_type}',
            cr_member_class = '".sql_real_escape_string($_POST['member_class'])."',
            cr_title = '".sql_real_escape_string($_POST['title'])."',
            cr_name_kor = '".sql_real_escape_string($_POST['name_kor'])."',
            cr_name_eng = '".sql_real_escape_string($_POST['name_eng'])."',
            cr_license_number = '".sql_real_escape_string($_POST['license_number'])."',
            cr_has_no_license = '".(isset($_POST['no_license']) ? 1 : 0)."',
            cr_hospital_name = '".sql_real_escape_string($_POST['hospital_name'])."',
            cr_department = '".sql_real_escape_string($_POST['department'])."',
            cr_work_zip = '".sql_real_escape_string($_POST['work_zip'])."',
            cr_work_addr1 = '".sql_real_escape_string($_POST['work_addr1'])."',
            cr_work_addr2 = '".sql_real_escape_string($_POST['work_addr2'])."',
            cr_work_phone1 = '".sql_real_escape_string($_POST['work_phone1'])."',
            cr_work_phone2 = '".sql_real_escape_string($_POST['work_phone2'])."',
            cr_work_phone3 = '".sql_real_escape_string($_POST['work_phone3'])."',
            cr_mobile_carrier = '".sql_real_escape_string($_POST['mobile_carrier'])."',
            cr_mobile1 = '".sql_real_escape_string($_POST['mobile1'])."',
            cr_mobile2 = '".sql_real_escape_string($_POST['mobile2'])."',
            cr_email = '".sql_real_escape_string($_POST['email'])."',
            cr_password = '".($is_member ? '' : password_hash($_POST['password'], PASSWORD_DEFAULT))."',
            cr_mb_id = '".($is_member ? $member['mb_id'] : '')."',
			cr_nonemb_name = '{$cr_nonemb_name}',
			cr_nonemb_birth = '{$cr_nonemb_birth}',
            cr_annual_fee_status = '".($annual_fee_paid ? 'paid' : 'unpaid')."',
            cr_payment_method = '".sql_real_escape_string($_POST['payment_method'])."',
            cr_registration_fee = '{$_POST['amount']}',
			cr_od_id = '".$orderId."',
            cr_reg_date = NOW()";
    
    $result = sql_query($sql);

	$cr_id = sql_insert_id();

	if((int)$_POST['amount']==0){
		$sql="update g5_conference_registration set cr_payment_status='completed' where cr_id='{$cr_id}'";
		sql_query($sql);
	}
    
    if ($result) {
        $reg_id = sql_insert_id();
        alert("사전등록이 완료되었습니다.", G5_THEME_URL."/subpage/event/event2_08.php");
    } else {
        alert("등록 중 오류가 발생했습니다. 다시 시도해주세요.");
    }
?>