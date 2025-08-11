<?php
include_once('./_common.php');
include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');
include_once(G5_LIB_PATH.'/register.lib.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// 리퍼러 체크
referer_check();

if (!($w == '' || $w == 'u')) {
    alert('w 값이 제대로 넘어오지 않았습니다.');
}

if ($w == 'u' && $is_admin == 'super') {
    if (file_exists(G5_PATH.'/DEMO'))
        alert('데모 화면에서는 하실(보실) 수 없는 작업입니다.');
}

if (run_replace('register_member_chk_captcha', !chk_captcha(), $w)) {
    alert('자동등록방지 숫자가 틀렸습니다.');
}

if($w == 'u')
    $mb_id = isset($_SESSION['ss_mb_id']) ? trim($_SESSION['ss_mb_id']) : '';
else if($w == '')
    $mb_id = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
else
    alert('잘못된 접근입니다', G5_URL);

if(!$mb_id)
    alert('회원아이디 값이 없습니다. 올바른 방법으로 이용해 주십시오.');

if ($w == '') {
    $required_mb_id = 'required';
    $required = 'required';
} else {
    $required_mb_id = '';
    $required = '';
}

if ($w == 'u') {
    $mb = get_member($_POST['mb_id']);
    if (!$mb['mb_id']) {
        alert('존재하지 않는 회원자료입니다.');
    }
    
    // 본인확인
    if ($member['mb_id'] != $mb['mb_id']) {
        if ($is_admin != 'super')
            alert('본인의 정보만 수정할 수 있습니다.');
    }
    
    $mb_id = $member['mb_id'];
    $mb_nick = $member['mb_nick'];
} else {
    $mb_id = $_POST['mb_id'];
    $mb_nick = $_POST['mb_nick'];
}

// 필수 입력 체크
if (!$_POST['mb_memclass']) {
    alert("회원구분을 선택해주세요.");
}

if (!$_POST['mb_title']) {
    alert("호칭을 선택해주세요.");
}

if (!trim($_POST['mb_name'])) {
    alert("국문 성명을 입력해주세요.");
}

if (!trim($_POST['mb_name_en'])) {
    alert("영문 성명을 입력해주세요.");
}

if (!$_POST['mb_birth']) {
    alert("생년월일을 입력해주세요.");
}

if (!$_POST['mb_sex']) {
    alert("성별을 선택해주세요.");
}

if (!$_POST['mb_license_none'] && !trim($_POST['mb_license_no'])) {
    alert("면허번호를 입력하거나 '면허번호 없음'을 체크해주세요.");
}

if (!trim($_POST['mb_hp'])) {
    alert("휴대전화 번호를 입력해주세요.");
}

if ($_POST['mb_memclass'] == 'member' && !$_POST['mb_job_class']) {
    alert("직군을 선택해주세요.");
}

if ($_POST['mb_memclass'] == 'student' && !$_POST['mb_student_class']) {
    alert("학생구분을 선택해주세요.");
}
if($mb_location_type=="domestic"){
	if (!$_POST['mb_school']) {
		alert("출신학교를 선택해주세요.");
	}
	$_POST['mb_school_etc']="";
}
if($mb_location_type=="international"){
	if (!$_POST['mb_school_etc']) {
		alert("출신학교를 선택해주세요.");
	}
	$_POST['mb_school']="";
}


if (!$_POST['mb_major']) {
    alert("전공과목을 선택해주세요.");
}

if ($_POST['mb_major'] == '기타(전공)' && !trim($_POST['mb_major_etc'])) {
    alert("전공과목 기타 내용을 입력해주세요.");
}

if (!$_POST['mb_branch']) {
    alert("분과를 선택해주세요.");
}

if (!trim($_POST['mb_work_name'])) {
    alert("근무지명을 입력해주세요.");
}

if (!trim($_POST['mb_work_zip'])) {
    alert("근무지 우편번호를 입력해주세요.");
}

if (!trim($_POST['mb_work_addr1'])) {
    alert("근무지 기본주소를 입력해주세요.");
}

// 근무지 전화번호 조합
$mb_work_tel = '';
if ($_POST['mb_work_tel1'] && $_POST['mb_work_tel2'] && $_POST['mb_work_tel3']) {
    $mb_work_tel = $_POST['mb_work_tel1'] . '-' . $_POST['mb_work_tel2'] . '-' . $_POST['mb_work_tel3'];
} else {
    alert("근무지 전화번호를 모두 입력해주세요.");
}

if($w==""){
	if (!$_POST['mb_search_agree']) {
		alert("회원 검색 동의 여부를 선택해주세요.");
	}

	if (!isset($_POST['mb_mailling'])) {
		alert("안내 메일 수신 동의 여부를 선택해주세요.");
	}

	if (!isset($_POST['mb_sms'])) {
		alert("SMS 수신 동의 여부를 선택해주세요.");
	}

	if (!$_POST['mb_privacy_agree']) {
		alert("고유식별번호 수집 동의 여부를 선택해주세요.");
	}

	if ($_POST['mb_privacy_agree'] == '동의하지 않음') {
		alert("고유식별번호 수집에 동의하지 않으면 회원가입이 불가합니다.");
	}
}

// 아이디 검사
if ($w == '') {
    $mb_id = trim($_POST['mb_id']);
    if (!$mb_id) {
        alert('아이디를 입력하십시오.');
    }

    if (strlen($mb_id) < 3) {
        alert('아이디는 최소 3글자 이상 입력하십시오.');
    }

    if (strlen($mb_id) > 20) {
        alert('아이디는 최대 20글자까지 입력 가능합니다.');
    }

    if (!preg_match("/^[a-z0-9_]+$/i", $mb_id)) {
        alert('아이디는 영문자, 숫자, _ 만 사용 가능합니다.');
    }

    $result = sql_query(" select mb_id from {$g5['member_table']} where mb_id = '$mb_id' ");
    if (sql_num_rows($result)) {
        alert('이미 사용 중인 아이디입니다.');
    }

    if (exist_mb_id($mb_id)) {
        alert('이미 사용 중인 아이디입니다.');
    }
}

// 비밀번호 검사
if ($w == '' || ($w == 'u' && $_POST['mb_password'])) {
    $mb_password = trim($_POST['mb_password']);
    $mb_password_re = trim($_POST['mb_password_re']);

    if (!$mb_password) {
        alert('비밀번호를 입력하십시오.');
    }

    if (strlen($mb_password) < 3) {
        alert('비밀번호는 최소 3글자 이상 입력하십시오.');
    }

    if ($mb_password != $mb_password_re) {
        alert('비밀번호가 일치하지 않습니다.');
    }
}

// 이메일 검사
$mb_email = trim($_POST['mb_email']);
if (!$mb_email) {
    alert('이메일을 입력하십시오.');
}

if (!filter_var($mb_email, FILTER_VALIDATE_EMAIL)) {
    alert('올바른 이메일 형식이 아닙니다.');
}

if ($w == '' || ($w == 'u' && $member['mb_email'] != $mb_email)) {
    $result = sql_query(" select count(*) as cnt from {$g5['member_table']} where mb_email = '$mb_email' ");
    $row = sql_fetch_array($result);
    if ($row['cnt']) {
        alert('이미 사용 중인 이메일입니다.');
    }
}

// 석/박사 졸업정보 처리
$graduate_data = array();
if (isset($_POST['graduate_degree']) && is_array($_POST['graduate_degree'])) {
    for ($i = 0; $i < count($_POST['graduate_degree']); $i++) {
        if (!empty($_POST['graduate_degree'][$i]) || !empty($_POST['graduate_school_major'][$i])) {
            $graduate_data[] = array(
                'degree' => clean_xss_tags($_POST['graduate_degree'][$i]),
                'school_major' => clean_xss_tags($_POST['graduate_school_major'][$i]),
                'admission_year' => clean_xss_tags($_POST['graduate_admission_year'][$i]),
                'graduation_year' => clean_xss_tags($_POST['graduate_graduation_year'][$i])
            );
        }
    }
}
$mb_graduate_data = json_encode($graduate_data, JSON_UNESCAPED_UNICODE);

// 파일 업로드 처리
$mb_certi_files = array('', '', '');
$upload_path = G5_DATA_PATH.'/member_files/';

if (!is_dir($upload_path)) {
    @mkdir($upload_path, G5_DIR_PERMISSION);
    @chmod($upload_path, G5_DIR_PERMISSION);
}

for ($i = 1; $i <= 3; $i++) {
	if ($w == 'u') {
		$existing_file = $mb['mb_certi_file{$i}'];
		$delete_file = isset($_POST["mb_certi_file{$i}_del"]) ? $_POST["mb_certi_file{$i}_del"] : '0';
		if($delete_file == '1') {
			$old_file_path = $upload_path . $existing_file;
			@unlink($old_file_path);
		}
	}
    if (isset($_FILES["mb_certi_file{$i}"]) && $_FILES["mb_certi_file{$i}"]['tmp_name']) {
        $file = $_FILES["mb_certi_file{$i}"];
        
        // 파일 크기 체크 (5MB)
        if ($file['size'] > 5242880) {
            alert("파일 크기는 5MB 이하로 업로드해주세요.");
        }
        
        // 파일 확장자 체크
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, array('jpg', 'jpeg', 'png', 'pdf'))) {
            alert("jpg, png, PDF 파일만 업로드 가능합니다.");
        }
        
        // 파일명 생성
        $filename = $mb_id . '_certi_' . $i . '_' . date('YmdHis') . '.' . $ext;
        $filepath = $upload_path . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $mb_certi_files[$i-1] = $filename;
        }
    }
}

// 데이터 정리
$mb_name = clean_xss_tags(trim($_POST['mb_name']));
$mb_name_en = clean_xss_tags(trim($_POST['mb_name_en']));
$mb_title = clean_xss_tags($_POST['mb_title']);
$mb_memclass = clean_xss_tags($_POST['mb_memclass']);
$mb_birth = clean_xss_tags($_POST['mb_birth']);
$mb_birth=substr($mb_birth,0,4)."-".substr($mb_birth,4,2)."-".substr($mb_birth,6,2);
$mb_sex = clean_xss_tags($_POST['mb_sex']);
$mb_license_no = clean_xss_tags(trim($_POST['mb_license_no']));
$mb_license_none = isset($_POST['mb_license_none']) ? 1 : 0;
$mb_hp_country = clean_xss_tags($_POST['mb_hp_country']);
$mb_hp = clean_xss_tags(trim($_POST['mb_hp']));
$mb_job_class = clean_xss_tags($_POST['mb_job_class']);
$mb_student_class = clean_xss_tags($_POST['mb_student_class']);
$mb_school = clean_xss_tags($_POST['mb_school']);
$mb_major = clean_xss_tags($_POST['mb_major']);
$mb_major_etc = clean_xss_tags(trim($_POST['mb_major_etc']));
$mb_branch = clean_xss_tags($_POST['mb_branch']);
$mb_school_etc = clean_xss_tags($_POST['mb_school_etc']);
$mb_work_name = clean_xss_tags(trim($_POST['mb_work_name']));
$mb_work_zip = clean_xss_tags(trim($_POST['mb_work_zip']));
$mb_work_addr1 = clean_xss_tags(trim($_POST['mb_work_addr1']));
$mb_work_addr2 = clean_xss_tags(trim($_POST['mb_work_addr2']));
$mb_search_agree = clean_xss_tags($_POST['mb_search_agree']);
$mb_mailling = (int)$_POST['mb_mailling'];
$mb_sms = (int)$_POST['mb_sms'];
$mb_privacy_agree = clean_xss_tags($_POST['mb_privacy_agree']);

// 새로 추가된 필드들
$mb_bachelor_admission_year = clean_xss_tags($_POST['mb_bachelor_admission_year']);
$mb_bachelor_graduation_year = clean_xss_tags($_POST['mb_bachelor_graduation_year']);
$mb_mail_address_type = clean_xss_tags($_POST['mb_mail_address_type']);
$mb_employment_status = isset($_POST['mb_employment_status']) ? clean_xss_tags($_POST['mb_employment_status']) : '';

// 면허번호 없음 체크시 면허번호 비우기
if ($mb_license_none) {
    $mb_license_no = '';
}

if ($w == '') {
    // 신규 가입
    $mb_password = get_encrypt_string($mb_password);
    $mb_datetime = G5_TIME_YMDHIS;
    $mb_ip = $_SERVER['REMOTE_ADDR'];
    
    $sql = " insert into {$g5['member_table']}
                set mb_id = '$mb_id',
                    mb_password = '$mb_password',
                    mb_name = '$mb_name',
                    mb_nick = '$mb_id',
                    mb_email = '$mb_email',
                    mb_homepage = '',
                    mb_level = '{$config['cf_register_level']}',
                    mb_sex = '$mb_sex',
                    mb_birth = '$mb_birth',
                    mb_tel = '',
                    mb_hp = '$mb_hp',
                    mb_zip1 = '',
                    mb_zip2 = '',
                    mb_addr1 = '',
                    mb_addr2 = '',
                    mb_addr3 = '',
                    mb_addr_jibeon = '',
                    mb_signature = '',
                    mb_recommend = '',
                    mb_point = '{$config['cf_register_point']}',
                    mb_today_login = '$mb_datetime',
                    mb_datetime = '$mb_datetime',
                    mb_ip = '$mb_ip',
                    mb_email_certify = '',
                    mb_memo = '',
                    mb_lost_certify = '',
                    mb_open = '0',
                    mb_open_date = '0000-00-00',
                    mb_profile = '',
                    mb_memo_call = '',
                    mb_1 = '',
                    mb_2 = '',
                    mb_3 = '',
                    mb_4 = '',
                    mb_5 = '',
                    mb_6 = '',
                    mb_7 = '',
                    mb_8 = '',
                    mb_9 = '',
                    mb_10 = '',
                    mb_memclass = '$mb_memclass',
                    mb_title = '$mb_title',
                    mb_name_en = '$mb_name_en',
                    mb_license_no = '$mb_license_no',
                    mb_license_none = '$mb_license_none',
                    mb_hp_country = '$mb_hp_country',
                    mb_job_class = '$mb_job_class',
                    mb_student_class = '$mb_student_class',
                    mb_certi_file1 = '{$mb_certi_files[0]}',
                    mb_certi_file2 = '{$mb_certi_files[1]}',
                    mb_certi_file3 = '{$mb_certi_files[2]}',
					mb_location_type = '{$mb_location_type}',
                    mb_school = '$mb_school',
                    mb_major = '$mb_major',
                    mb_major_etc = '$mb_major_etc',
                    mb_branch = '$mb_branch',
                    mb_school_etc = '$mb_school_etc',
                    mb_bachelor_admission_year = '$mb_bachelor_admission_year',
                    mb_bachelor_graduation_year = '$mb_bachelor_graduation_year',
                    mb_graduate_data = '$mb_graduate_data',
                    mb_mail_address_type = '$mb_mail_address_type',
                    mb_employment_status = '$mb_employment_status',
                    mb_work_name = '$mb_work_name',
                    mb_work_zip = '$mb_work_zip',
                    mb_work_addr1 = '$mb_work_addr1',
                    mb_work_addr2 = '$mb_work_addr2',
                    mb_work_tel = '$mb_work_tel',
                    mb_sms = '$mb_sms',
                    mb_mailling = '$mb_mailling',
                    mb_search_agree = '$mb_search_agree',
                    mb_privacy_agree = '$mb_privacy_agree' ";
    
    sql_query($sql);
// 입회비 등록 처리
    $entrance_fee = 0;
    $annual_fee = 0;
    
    // 회원구분에 따른 회비 설정
    if ($mb_memclass == 'member') {
        // 일반회원 (치과의사, 기공사, 간호사)
        $entrance_fee = 120000;  // 입회비 50,000원
        $annual_fee = 120000;   // 연회비 120,000원
    } else if ($mb_memclass == 'student') {
        // 학생회원 (학생, 전공의)
        $entrance_fee = 60000;  // 입회비 30,000원
        $annual_fee = 60000;    // 연회비 60,000원
    }
    
    $current_year = date('Y');
    $current_date = date('Y-m-d');
    $end_date = date('Y-m-d', strtotime('+1 year'));
    
    // 입회비 등록
	/**
    if ($entrance_fee > 0) {
        $entrance_sql = " INSERT INTO g5_membership SET
                            mb_type = 'entrance',
                            mb_content = '{$current_year}년 입회비',
                            mb_amount = '{$entrance_fee}',
                            mb_start_date = '{$current_date}',
                            mb_end_date = '{$end_date}',
                            mb_due_date = '{$current_date}',
                            mb_status = 'pending',
                            mb_member_id = '{$mb_id}',
                            mb_year = '{$current_year}',
                            mb_note = '회원가입시 자동 생성',
                            mb_reg_date = NOW() ";
        sql_query($entrance_sql);
    }
	*/
    
    // 연회비 등록
    if ($annual_fee > 0) {
        $annual_sql = " INSERT INTO g5_membership SET
                        mb_type = 'annual',
                        mb_content = '{$current_year}년 연회비',
                        mb_amount = '{$annual_fee}',
                        mb_start_date = '{$current_date}',
                        mb_end_date = '{$end_date}',
                        mb_due_date = '{$current_date}',
                        mb_status = 'pending',
                        mb_member_id = '{$mb_id}',
                        mb_year = '{$current_year}',
                        mb_note = '회원가입시 자동 생성',
                        mb_reg_date = NOW() ";
        sql_query($annual_sql);
    }

    // 포인트 부여
    if ($config['cf_register_point'] > 0) {
        insert_point($mb_id, $config['cf_register_point'], '회원가입 축하', '@member', $mb_id, '회원가입');
    }
    
    // 추천인 처리
    if ($config['cf_use_recommend'] && $_POST['mb_recommend']) {
        $mb_recommend = clean_xss_tags($_POST['mb_recommend']);
        $result = sql_query(" select mb_id from {$g5['member_table']} where mb_id = '$mb_recommend' ");
        if (sql_num_rows($result)) {
            sql_query(" update {$g5['member_table']} set mb_recommend = '$mb_recommend' where mb_id = '$mb_id' ");
            
            if ($config['cf_recommend_point'] > 0) {
                insert_point($mb_recommend, $config['cf_recommend_point'], $mb_id.'의 추천인', '@member', $mb_recommend, $mb_id.' 추천');
            }
        }
    }
    
    // 회원가입 완료 메일 발송
    if ($config['cf_email_mb_member']) {
        $subject = "[".$config['cf_title']."] 회원가입을 축하드립니다.";
        $content = $mb_name."님의 회원가입을 진심으로 축하드립니다.\n\n";
        $content .= "아이디 : ".$mb_id."\n";
        $content .= "이름 : ".$mb_name."\n";
        $content .= "이메일 : ".$mb_email."\n\n";
        $content .= "감사합니다.\n\n";
        $content .= $config['cf_title']." 드림\n";
        $content .= G5_URL;
        
        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $mb_email, $subject, $content, 0);
    }
    
    // 관리자에게 회원가입 알림 메일
    if (true || $config['cf_email_mb_super_admin']) {
        $subject = "[".$config['cf_title']."] 새로운 회원이 가입했습니다.";
        $content = "새로운 회원이 가입했습니다.\n\n";
        $content .= "아이디 : ".$mb_id."\n";
        $content .= "이름 : ".$mb_name."\n";
        $content .= "이메일 : ".$mb_email."\n";
        $content .= "가입일시 : ".$mb_datetime."\n\n";
        $content .= "관리자 페이지 : ".G5_ADMIN_URL;
        
        //mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $config['cf_admin_email'], $subject, $content, 0);
		$receive_mem=array();
		$sql="select * from g5_member where mb_level='6'";
		$tmp_result=sql_query($sql);
		while($tmp_row=sql_fetch_array($tmp_result)){
			if($tmp_row['mb_email']){
				mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $tmp_row['mb_email'], $subject, $content, 0);
			}
		}
    }

	set_session('ss_mb_reg', $mb_id);
	set_session('ss_mb_id', $mb_id);

    goto_url(G5_BBS_URL.'/register_result.php');
    
} else if ($w == 'u') {
    // 정보 수정
    $sql_password = "";
    if ($_POST['mb_password']) {
        $mb_password = get_encrypt_string($_POST['mb_password']);
        $sql_password = " , mb_password = '$mb_password' ";
    }
    
    $sql = " update {$g5['member_table']}
                set mb_name = '$mb_name',
                    mb_email = '$mb_email',
                    mb_sex = '$mb_sex',
                    mb_birth = '$mb_birth',
                    mb_hp = '$mb_hp',
                    mb_memclass = '$mb_memclass',
                    mb_title = '$mb_title',
                    mb_name_en = '$mb_name_en',
                    mb_license_no = '$mb_license_no',
                    mb_license_none = '$mb_license_none',
                    mb_hp_country = '$mb_hp_country',
                    mb_job_class = '$mb_job_class',
                    mb_student_class = '$mb_student_class',
                    mb_certi_file1 = '{$mb_certi_files[0]}',
                    mb_certi_file2 = '{$mb_certi_files[1]}',
                    mb_certi_file3 = '{$mb_certi_files[2]}',
					mb_location_type = '{$mb_location_type}',
                    mb_school = '$mb_school',
                    mb_major = '$mb_major',
                    mb_major_etc = '$mb_major_etc',
                    mb_branch = '$mb_branch',
                    mb_school_etc = '$mb_school_etc',
                    mb_bachelor_admission_year = '$mb_bachelor_admission_year',
                    mb_bachelor_graduation_year = '$mb_bachelor_graduation_year',
                    mb_graduate_data = '$mb_graduate_data',
                    mb_mail_address_type = '$mb_mail_address_type',
                    mb_employment_status = '$mb_employment_status',
                    mb_work_name = '$mb_work_name',
                    mb_work_zip = '$mb_work_zip',
                    mb_work_addr1 = '$mb_work_addr1',
                    mb_work_addr2 = '$mb_work_addr2',
                    mb_work_tel = '$mb_work_tel'
                    $sql_password
              where mb_id = '$mb_id' ";
    
    sql_query($sql);
    
    goto_url(G5_THEME_URL.'/subpage/mypage/mypage_1.php');
}