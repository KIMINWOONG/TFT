<?php
$sub_menu = "200100";
require_once "./_common.php";
require_once G5_LIB_PATH . "/register.lib.php";
require_once G5_LIB_PATH . '/thumbnail.lib.php';

if ($w == 'u') {
    check_demo();
}

auth_check_menu($auth, $sub_menu, 'w');

check_admin_token();

$mb_id          = isset($_POST['mb_id']) ? trim($_POST['mb_id']) : '';
$mb_password    = isset($_POST['mb_password']) ? trim($_POST['mb_password']) : '';
$mb_certify_case = isset($_POST['mb_certify_case']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['mb_certify_case']) : '';
$mb_certify     = isset($_POST['mb_certify']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['mb_certify']) : '';
$mb_zip         = isset($_POST['mb_zip']) ? preg_replace('/[^0-9a-z_]/i', '', $_POST['mb_zip']) : '';

// 관리자가 자동등록방지를 사용해야 할 경우 ( 회원의 비밀번호 변경시 캡챠를 체크한다 )
if ($mb_password && function_exists('get_admin_captcha_by') && get_admin_captcha_by()) {
    include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');

    if (!chk_captcha()) {
        alert('자동등록방지 숫자가 틀렸습니다.');
    }
}

// 휴대폰번호 체크
$mb_hp = hyphen_hp_number($_POST['mb_hp']);
if ($mb_hp) {
    $result = exist_mb_hp($mb_hp, $mb_id);
    if ($result) {
        alert($result);
    }
}

// 인증정보처리
if ($mb_certify_case && $mb_certify) {
    $mb_certify = isset($_POST['mb_certify_case']) ? preg_replace('/[^0-9a-z_]/i', '', (string)$_POST['mb_certify_case']) : '';
    $mb_adult = isset($_POST['mb_adult']) ? preg_replace('/[^0-9a-z_]/i', '', (string)$_POST['mb_adult']) : '';
} else {
    $mb_certify = '';
    $mb_adult = 0;
}

$mb_zip1 = substr($mb_zip, 0, 3);
$mb_zip2 = substr($mb_zip, 3);

$mb_email = isset($_POST['mb_email']) ? get_email_address(trim($_POST['mb_email'])) : '';
$mb_nick = isset($_POST['mb_nick']) ? trim(strip_tags($_POST['mb_nick'])) : '';

if ($msg = valid_mb_nick($mb_nick)) {
    alert($msg, "", true, true);
}

$posts = array();
$check_keys = array(
    'mb_name',
    'mb_homepage',
    'mb_tel',
    'mb_addr1',
    'mb_addr2',
    'mb_addr3',
    'mb_addr_jibeon',
    'mb_signature',
    'mb_leave_date',
    'mb_intercept_date',
    'mb_mailling',
    'mb_sms',
    'mb_open',
    'mb_profile',
    'mb_level',
    'mb_memclass',
    'mb_title',
    'mb_name_en',
    'mb_birth',
    'mb_sex',
    'mb_license_no',
    'mb_license_none',
    'mb_hp_country',
    'mb_job_class',
    'mb_student_class',
    'mb_school',
    'mb_major',
    'mb_major_etc',
    'mb_branch',
    'mb_school_etc',
    'mb_bachelor_admission_year',
    'mb_bachelor_graduation_year',
    'mb_mail_address_type',
    'mb_employment_status',
    'mb_work_name',
    'mb_work_zip',
    'mb_work_addr1',
    'mb_work_addr2',
    'mb_work_tel',
    'mb_search_agree',
    'mb_privacy_agree'
);

for ($i = 2; $i <= 10; $i++) {
    $check_keys[] = 'mb_' . $i;
}

foreach ($check_keys as $key) {
    if( in_array($key, array('mb_signature', 'mb_profile')) ){
        $posts[$key] = isset($_POST[$key]) ? clean_xss_tags($_POST[$key], 1, 1, 0, 0) : '';
    } else {
        $posts[$key] = isset($_POST[$key]) ? clean_xss_tags($_POST[$key], 1, 1) : '';
    }
}
if(isset($_POST['mb_1']) && count($_POST['mb_1'])>0){
	$posts['mb_1']=implode(",",$_POST['mb_1']);
}else{
	$posts['mb_1']="";
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
$mb_memo = isset($_POST['mb_memo']) ? $_POST['mb_memo'] : '';

// 재직증명서 파일 업로드 처리
$upload_path = G5_DATA_PATH.'/member_files/';
if (!is_dir($upload_path)) {
    @mkdir($upload_path, G5_DIR_PERMISSION);
    @chmod($upload_path, G5_DIR_PERMISSION);
}

$mb_certi_files = array('', '', '');
if ($w == 'u') {
    $mb_old = get_member($mb_id);
    for ($i = 1; $i <= 3; $i++) {
        $mb_certi_files[$i-1] = $mb_old["mb_certi_file{$i}"];
    }
}

for ($i = 1; $i <= 3; $i++) {
    // 파일 삭제 처리
    if ($w == 'u' && isset($_POST["mb_certi_file{$i}_del"]) && $_POST["mb_certi_file{$i}_del"] == '1') {
        if ($mb_certi_files[$i-1]) {
            $old_file_path = $upload_path . $mb_certi_files[$i-1];
            @unlink($old_file_path);
            $mb_certi_files[$i-1] = '';
        }
    }
    
    // 새 파일 업로드
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
        
        // 기존 파일 삭제
        if ($mb_certi_files[$i-1]) {
            $old_file_path = $upload_path . $mb_certi_files[$i-1];
            @unlink($old_file_path);
        }
        
        // 파일명 생성
        $filename = $mb_id . '_certi_' . $i . '_' . date('YmdHis') . '.' . $ext;
        $filepath = $upload_path . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $mb_certi_files[$i-1] = $filename;
        }
    }
}
$posts['mb_birth']=substr($posts['mb_birth'],0,4)."-".substr($posts['mb_birth'],4,2)."-".substr($posts['mb_birth'],6,2);
$sql_common = "  mb_name = '{$posts['mb_name']}',
                 mb_nick = '{$mb_nick}',
                 mb_email = '{$mb_email}',
                 mb_homepage = '{$posts['mb_homepage']}',
                 mb_tel = '{$posts['mb_tel']}',
                 mb_hp = '{$mb_hp}',
                 mb_certify = '{$mb_certify}',
                 mb_adult = '{$mb_adult}',
                 mb_zip1 = '$mb_zip1',
                 mb_zip2 = '$mb_zip2',
                 mb_addr1 = '{$posts['mb_addr1']}',
                 mb_addr2 = '{$posts['mb_addr2']}',
                 mb_addr3 = '{$posts['mb_addr3']}',
                 mb_addr_jibeon = '{$posts['mb_addr_jibeon']}',
                 mb_signature = '{$posts['mb_signature']}',
                 mb_leave_date = '{$posts['mb_leave_date']}',
                 mb_intercept_date='{$posts['mb_intercept_date']}',
                 mb_memo = '{$mb_memo}',
                 mb_mailling = '{$posts['mb_mailling']}',
                 mb_sms = '{$posts['mb_sms']}',
                 mb_open = '{$posts['mb_open']}',
                 mb_profile = '{$posts['mb_profile']}',
                 mb_level = '{$posts['mb_level']}',
                 mb_1 = '{$posts['mb_1']}',
                 mb_2 = '{$posts['mb_2']}',
                 mb_3 = '{$posts['mb_3']}',
                 mb_4 = '{$posts['mb_4']}',
                 mb_5 = '{$posts['mb_5']}',
                 mb_6 = '{$posts['mb_6']}',
                 mb_7 = '{$posts['mb_7']}',
                 mb_8 = '{$posts['mb_8']}',
                 mb_9 = '{$posts['mb_9']}',
                 mb_10 = '{$posts['mb_10']}', 
                 mb_memclass = '{$posts['mb_memclass']}',
                 mb_title = '{$posts['mb_title']}',
                 mb_name_en = '{$posts['mb_name_en']}',
                 mb_birth = '{$posts['mb_birth']}',
                 mb_sex = '{$posts['mb_sex']}',
                 mb_license_no = '{$posts['mb_license_no']}',
                 mb_license_none = '{$posts['mb_license_none']}',
                 mb_hp_country = '{$posts['mb_hp_country']}',
                 mb_job_class = '{$posts['mb_job_class']}',
                 mb_student_class = '{$posts['mb_student_class']}',
                 mb_certi_file1 = '{$mb_certi_files[0]}',
                 mb_certi_file2 = '{$mb_certi_files[1]}',
                 mb_certi_file3 = '{$mb_certi_files[2]}',
                 mb_school = '{$posts['mb_school']}',
                 mb_major = '{$posts['mb_major']}',
                 mb_major_etc = '{$posts['mb_major_etc']}',
                 mb_branch = '{$posts['mb_branch']}',
                 mb_school_etc = '{$posts['mb_school_etc']}',
                 mb_bachelor_admission_year = '{$posts['mb_bachelor_admission_year']}',
                 mb_bachelor_graduation_year = '{$posts['mb_bachelor_graduation_year']}',
                 mb_graduate_data = '{$mb_graduate_data}',
                 mb_mail_address_type = '{$posts['mb_mail_address_type']}',
                 mb_employment_status = '{$posts['mb_employment_status']}',
                 mb_work_name = '{$posts['mb_work_name']}',
                 mb_work_zip = '{$posts['mb_work_zip']}',
                 mb_work_addr1 = '{$posts['mb_work_addr1']}',
                 mb_work_addr2 = '{$posts['mb_work_addr2']}',
                 mb_work_tel = '{$posts['mb_work_tel']}',
                 mb_search_agree = '{$posts['mb_search_agree']}',
                 mb_privacy_agree = '{$posts['mb_privacy_agree']}',
				 mb_gubun = '{$mb_gubun}',
				 mb_location_type = '{$mb_location_type}' ";

if ($w == '') {
    $mb = get_member($mb_id);
    if (isset($mb['mb_id']) && $mb['mb_id']) {
        alert('이미 존재하는 회원아이디입니다.\\nＩＤ : ' . $mb['mb_id'] . '\\n이름 : ' . $mb['mb_name'] . '\\n닉네임 : ' . $mb['mb_nick'] . '\\n메일 : ' . $mb['mb_email']);
    }

    // 닉네임중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$mb_nick}' ";
    $row = sql_fetch($sql);
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }

    // 이메일중복체크
	/**
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$mb_email}' ";
    $row = sql_fetch($sql);
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 이메일입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }
	*/

    sql_query(" insert into {$g5['member_table']} set mb_id = '{$mb_id}', mb_password = '" . get_encrypt_string($mb_password) . "', mb_datetime = '" . G5_TIME_YMDHIS . "', mb_ip = '{$_SERVER['REMOTE_ADDR']}', mb_email_certify = '" . G5_TIME_YMDHIS . "', {$sql_common} ");
} elseif ($w == 'u') {
    $mb = get_member($mb_id);
    if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
        alert('존재하지 않는 회원자료입니다.');
    }

    if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level']) {
        alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');
    }

    if ($is_admin !== 'super' && is_admin($mb['mb_id']) === 'super') {
        alert('최고관리자의 비밀번호를 수정할수 없습니다.');
    }

    if ($mb_id === $member['mb_id'] && $_POST['mb_level'] != $mb['mb_level']) {
        alert($mb['mb_id'] . ' : 로그인 중인 관리자 레벨은 수정할 수 없습니다.');
    }

    if ($posts['mb_leave_date'] || $posts['mb_intercept_date']){
        if ($member['mb_id'] === $mb['mb_id'] || is_admin($mb['mb_id']) === 'super'){
            alert('해당 관리자의 탈퇴 일자 또는 접근 차단 일자를 수정할 수 없습니다.');
        }
    }

    // 닉네임중복체크
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_nick = '{$mb_nick}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 닉네임입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }

    // 이메일중복체크
	/**
    $sql = " select mb_id, mb_name, mb_nick, mb_email from {$g5['member_table']} where mb_email = '{$mb_email}' and mb_id <> '$mb_id' ";
    $row = sql_fetch($sql);
    if (isset($row['mb_id']) && $row['mb_id']) {
        alert('이미 존재하는 이메일입니다.\\nＩＤ : ' . $row['mb_id'] . '\\n이름 : ' . $row['mb_name'] . '\\n닉네임 : ' . $row['mb_nick'] . '\\n메일 : ' . $row['mb_email']);
    }
	*/

    if ($mb_password) {
        $sql_password = " , mb_password = '" . get_encrypt_string($mb_password) . "' ";
    } else {
        $sql_password = "";
    }

    if (isset($passive_certify) && $passive_certify) {
        $sql_certify = " , mb_email_certify = '" . G5_TIME_YMDHIS . "' ";
    } else {
        $sql_certify = "";
    }

    $sql = " update {$g5['member_table']}
                set {$sql_common}
                     {$sql_password}
                     {$sql_certify}
                where mb_id = '{$mb_id}' ";
    sql_query($sql);
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

if ($w == '' || $w == 'u') {
    $mb_dir = substr($mb_id, 0, 2);
    $mb_icon_img = get_mb_icon_name($mb_id) . '.gif';

    // 회원 아이콘 삭제
    if (isset($del_mb_icon) && $del_mb_icon) {
        @unlink(G5_DATA_PATH . '/member/' . $mb_dir . '/' . $mb_icon_img);
    }

    $image_regex = "/(\.(gif|jpe?g|png))$/i";

    // 아이콘 업로드
    if (isset($_FILES['mb_icon']) && is_uploaded_file($_FILES['mb_icon']['tmp_name'])) {
        if (!preg_match($image_regex, $_FILES['mb_icon']['name'])) {
            alert($_FILES['mb_icon']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }

        if (preg_match($image_regex, $_FILES['mb_icon']['name'])) {
            $mb_icon_dir = G5_DATA_PATH . '/member/' . $mb_dir;
            @mkdir($mb_icon_dir, G5_DIR_PERMISSION);
            @chmod($mb_icon_dir, G5_DIR_PERMISSION);

            $dest_path = $mb_icon_dir . '/' . $mb_icon_img;

            move_uploaded_file($_FILES['mb_icon']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size) {
                    if ($size[0] > $config['cf_member_icon_width'] || $size[1] > $config['cf_member_icon_height']) {
                        $thumb = null;
                        if ($size[2] === 2 || $size[2] === 3) {
                            //jpg 또는 png 파일 적용
                            $thumb = thumbnail($mb_icon_img, $mb_icon_dir, $mb_icon_dir, $config['cf_member_icon_width'], $config['cf_member_icon_height'], true, true);
                            if ($thumb) {
                                @unlink($dest_path);
                                rename($mb_icon_dir . '/' . $thumb, $dest_path);
                            }
                        }
                        if (!$thumb) {
                            // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                            @unlink($dest_path);
                        }
                    }
                }
            }
        }
    }

    $mb_img_dir = G5_DATA_PATH . '/member_image/';
    if (!is_dir($mb_img_dir)) {
        @mkdir($mb_img_dir, G5_DIR_PERMISSION);
        @chmod($mb_img_dir, G5_DIR_PERMISSION);
    }
    $mb_img_dir .= substr($mb_id, 0, 2);

    // 회원 이미지 삭제
    if (isset($del_mb_img) && $del_mb_img) {
        @unlink($mb_img_dir . '/' . $mb_icon_img);
    }

    // 아이콘 업로드
    if (isset($_FILES['mb_img']) && is_uploaded_file($_FILES['mb_img']['tmp_name'])) {
        if (!preg_match($image_regex, $_FILES['mb_img']['name'])) {
            alert($_FILES['mb_img']['name'] . '은(는) 이미지 파일이 아닙니다.');
        }

        if (preg_match($image_regex, $_FILES['mb_img']['name'])) {
            @mkdir($mb_img_dir, G5_DIR_PERMISSION);
            @chmod($mb_img_dir, G5_DIR_PERMISSION);

            $dest_path = $mb_img_dir . '/' . $mb_icon_img;

            move_uploaded_file($_FILES['mb_img']['tmp_name'], $dest_path);
            chmod($dest_path, G5_FILE_PERMISSION);

            if (file_exists($dest_path)) {
                $size = @getimagesize($dest_path);
                if ($size) {
                    if ($size[0] > $config['cf_member_img_width'] || $size[1] > $config['cf_member_img_height']) {
                        $thumb = null;
                        if ($size[2] === 2 || $size[2] === 3) {
                            //jpg 또는 png 파일 적용
                            $thumb = thumbnail($mb_icon_img, $mb_img_dir, $mb_img_dir, $config['cf_member_img_width'], $config['cf_member_img_height'], true, true);
                            if ($thumb) {
                                @unlink($dest_path);
                                rename($mb_img_dir . '/' . $thumb, $dest_path);
                            }
                        }
                        if (!$thumb) {
                            // 아이콘의 폭 또는 높이가 설정값 보다 크다면 이미 업로드 된 아이콘 삭제
                            @unlink($dest_path);
                        }
                    }
                }
            }
        }
    }
}
if (function_exists('get_admin_captcha_by')) {
    get_admin_captcha_by('remove');
}

run_event('admin_member_form_update', $w, $mb_id);

goto_url('./member_form.php?' . $qstr . '&amp;w=u&amp;mb_id=' . $mb_id, false);
