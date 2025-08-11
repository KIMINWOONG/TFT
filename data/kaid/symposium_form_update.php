<?php
$sub_menu = '300100';
include_once('./_common.php');

check_demo();

$w = isset($_REQUEST['w']) ? $_REQUEST['w'] : '';
$sy_id = isset($_REQUEST['sy_id']) ? (int)$_REQUEST['sy_id'] : 0;

if ($w == 'd') {
    auth_check_menu($auth, $sub_menu, "d");
} else {
    auth_check_menu($auth, $sub_menu, "w");
}

check_admin_token();

// 학술대회 이미지 저장 디렉토리 생성
$conference_dir = G5_DATA_PATH."/conference";
@mkdir($conference_dir, G5_DIR_PERMISSION);
@chmod($conference_dir, G5_DIR_PERMISSION);

// 년도별 디렉토리 생성 (선택사항)
$sy_year = isset($_REQUEST['sy_year']) ? $_REQUEST['sy_year'] : date('Y');
$year_dir = $conference_dir;
@mkdir($year_dir, G5_DIR_PERMISSION);
@chmod($year_dir, G5_DIR_PERMISSION);

/**
 * 새 학술대회 생성
 */
if ($w == "") {
    // 필수 항목 검증
    if (!$_REQUEST['sy_title']) {
        alert('학술대회 제목을 입력해주세요.');
    }
    
    if (!$_REQUEST['sy_address']) {
        alert('주소를 입력해주세요.');
    }
    
    if (!$_REQUEST['sy_time']) {
        alert('일시를 입력해주세요.');
    }
    
    // 등록 기간 유효성 검사
    $early_start = $_REQUEST['sy_early_reg_start'];
    $early_end = $_REQUEST['sy_early_reg_end'];
    $reg_start = $_REQUEST['sy_reg_start'];
    $reg_end = $_REQUEST['sy_reg_end'];
    
    if ($early_start && $early_end && $early_start >= $early_end) {
        alert('사전등록 시작일시는 종료일시보다 빨라야 합니다.');
    }
    
    if ($reg_start && $reg_end && $reg_start >= $reg_end) {
        alert('일반등록 시작일시는 종료일시보다 빨라야 합니다.');
    }
    
    if ($early_end && $reg_start && $early_end > $reg_start) {
        alert('사전등록 종료일시는 일반등록 시작일시보다 빨라야 합니다.');
    }
    
    // 이미지 업로드 처리
    $main_image_path = '';
    $map_image_path = '';
    
    // 메인 이미지 업로드
    if (isset($_FILES['sy_main_image']) && $_FILES['sy_main_image']['error'] == 0) {
        $main_image_path = upload_conference_image($_FILES['sy_main_image'], $year_dir, 'main');
    }
    
    // 약도 이미지 업로드
    if (isset($_FILES['sy_map_image']) && $_FILES['sy_map_image']['error'] == 0) {
        $map_image_path = upload_conference_image($_FILES['sy_map_image'], $year_dir, 'map');
    }
    
    // 인사말 내용 정리
    $sy_greeting = '';
    if (isset($_REQUEST['sy_greeting'])) {
        $sy_greeting = mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_greeting']);
    }
    
    // 데이터베이스에 저장
    $sql = "INSERT INTO g5_conference SET 
				sy_gubun = '2',
                sy_title = '".mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_title'])."',
                sy_title_en = '".mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_title_en'])."',
                sy_sdate = '".$_REQUEST['sy_sdate']."',
                sy_edate = '".$_REQUEST['sy_edate']."',
                sy_main_image = '".mysqli_real_escape_string($g5['connect_db'], $main_image_path)."',
                sy_greeting = '".$sy_greeting."',
                sy_map_image = '".mysqli_real_escape_string($g5['connect_db'], $map_image_path)."',
                sy_address = '".mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_address'])."',
                sy_time = '".mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_time'])."',
                sy_early_reg_start = ".($early_start ? "'".mysqli_real_escape_string($g5['connect_db'], $early_start)."'" : "NULL").",
                sy_early_reg_end = ".($early_end ? "'".mysqli_real_escape_string($g5['connect_db'], $early_end)."'" : "NULL").",
                sy_reg_start = ".($reg_start ? "'".mysqli_real_escape_string($g5['connect_db'], $reg_start)."'" : "NULL").",
                sy_reg_end = ".($reg_end ? "'".mysqli_real_escape_string($g5['connect_db'], $reg_end)."'" : "NULL").",
                sy_status = '".mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_status'])."',
				sy_fee_member = '{$sy_fee_member}',
				sy_fee_associate = '{$sy_fee_associate}',
				sy_fee_nonmember = '{$sy_fee_nonmember}',
				sy_offfee_member = '{$sy_offfee_member}',
				sy_offfee_associate = '{$sy_offfee_associate}',
				sy_offfee_nonmember = '{$sy_offfee_nonmember}',
				sy_nonmember_enter = '{$sy_nonmember_enter}',
				sy_summary_supply = '{$sy_summary_supply}',
				sy_member_summary_supply = '{$sy_member_summary_supply}',
				sy_place = '{$sy_place}',
				sy_content_1 = '{$sy_content_1}',
				sy_content_2 = '{$sy_content_2}',
				sy_content_3 = '{$sy_content_3}',
				sy_content_4 = '{$sy_content_4}',
				sy_content_5 = '{$sy_content_5}',
                sy_reg_date = NOW()";
    
    $result = sql_query($sql);
    
    if (!$result) {
        alert('학술대회 생성 중 오류가 발생했습니다.\\n'.mysqli_error($g5['connect_db']));
    }
    
    $sy_id = mysqli_insert_id($g5['connect_db']);
    
}

/**
 * 학술대회 수정
 */
else if ($w == "u") {
    if (!$sy_id) {
        alert('학술대회 ID가 없습니다.');
    }
    
    // 기존 데이터 조회
    $conference = sql_fetch("SELECT * FROM g5_conference WHERE sy_id = '{$sy_id}'");
    if (!$conference) {
        alert('존재하지 않는 학술대회입니다.');
    }
    
    // 필수 항목 검증
    if (!$_REQUEST['sy_title']) {
        alert('학술대회 제목을 입력해주세요.');
    }
    
    if (!$_REQUEST['sy_address']) {
        alert('주소를 입력해주세요.');
    }
    
    if (!$_REQUEST['sy_time']) {
        alert('일시를 입력해주세요.');
    }
    
    // 등록 기간 유효성 검사
    $early_start = $_REQUEST['sy_early_reg_start'];
    $early_end = $_REQUEST['sy_early_reg_end'];
    $reg_start = $_REQUEST['sy_reg_start'];
    $reg_end = $_REQUEST['sy_reg_end'];
    
    if ($early_start && $early_end && $early_start >= $early_end) {
        alert('사전등록 시작일시는 종료일시보다 빨라야 합니다.');
    }
    
    if ($reg_start && $reg_end && $reg_start >= $reg_end) {
        alert('일반등록 시작일시는 종료일시보다 빨라야 합니다.');
    }
    
    if ($early_end && $reg_start && $early_end > $reg_start) {
        alert('사전등록 종료일시는 일반등록 시작일시보다 빨라야 합니다.');
    }
    
    // 이미지 처리
    $main_image_path = $conference['sy_main_image'];
    $map_image_path = $conference['sy_map_image'];
    
    // 메인 이미지 삭제 요청
    if (isset($_REQUEST['sy_main_image_del']) && $_REQUEST['sy_main_image_del'] == '1') {
        if ($main_image_path && file_exists($main_image_path)) {
            @unlink($main_image_path);
        }
        $main_image_path = '';
    }
    
    // 약도 이미지 삭제 요청
    if (isset($_REQUEST['sy_map_image_del']) && $_REQUEST['sy_map_image_del'] == '1') {
        if ($map_image_path && file_exists($map_image_path)) {
            @unlink($map_image_path);
        }
        $map_image_path = '';
    }
    
    // 새 메인 이미지 업로드
    if (isset($_FILES['sy_main_image']) && $_FILES['sy_main_image']['error'] == 0) {
        // 기존 파일 삭제
        if ($main_image_path && file_exists($main_image_path)) {
            @unlink($main_image_path);
        }
        $main_image_path = upload_conference_image($_FILES['sy_main_image'], $year_dir, 'main');
    }
    
    // 새 약도 이미지 업로드
    if (isset($_FILES['sy_map_image']) && $_FILES['sy_map_image']['error'] == 0) {
        // 기존 파일 삭제
        if ($map_image_path && file_exists($map_image_path)) {
            @unlink($map_image_path);
        }
        $map_image_path = upload_conference_image($_FILES['sy_map_image'], $year_dir, 'map');
    }
    
    // 인사말 내용 정리
    $sy_greeting = '';
    if (isset($_REQUEST['sy_greeting'])) {
        $sy_greeting = mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_greeting']);
    }
    
    // 데이터베이스 업데이트
    $sql = "UPDATE g5_conference SET
                sy_title = '".mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_title'])."',
                sy_title_en = '".mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_title_en'])."',
                sy_sdate = '".$_REQUEST['sy_sdate']."',
                sy_edate = '".$_REQUEST['sy_edate']."',
                sy_main_image = '".mysqli_real_escape_string($g5['connect_db'], $main_image_path)."',
                sy_greeting = '".$sy_greeting."',
                sy_map_image = '".mysqli_real_escape_string($g5['connect_db'], $map_image_path)."',
                sy_address = '".mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_address'])."',
                sy_time = '".mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_time'])."',
                sy_early_reg_start = ".($early_start ? "'".mysqli_real_escape_string($g5['connect_db'], $early_start)."'" : "NULL").",
                sy_early_reg_end = ".($early_end ? "'".mysqli_real_escape_string($g5['connect_db'], $early_end)."'" : "NULL").",
                sy_reg_start = ".($reg_start ? "'".mysqli_real_escape_string($g5['connect_db'], $reg_start)."'" : "NULL").",
                sy_reg_end = ".($reg_end ? "'".mysqli_real_escape_string($g5['connect_db'], $reg_end)."'" : "NULL").",
                sy_status = '".mysqli_real_escape_string($g5['connect_db'], $_REQUEST['sy_status'])."',
				sy_fee_member = '{$sy_fee_member}',
				sy_fee_associate = '{$sy_fee_associate}',
				sy_fee_nonmember = '{$sy_fee_nonmember}',
				sy_offfee_member = '{$sy_offfee_member}',
				sy_offfee_associate = '{$sy_offfee_associate}',
				sy_offfee_nonmember = '{$sy_offfee_nonmember}',
				sy_nonmember_enter = '{$sy_nonmember_enter}',
 				sy_summary_supply = '{$sy_summary_supply}',
				sy_member_summary_supply = '{$sy_member_summary_supply}',
				sy_place = '{$sy_place}',
				sy_content_1 = '{$sy_content_1}',
				sy_content_2 = '{$sy_content_2}',
				sy_content_3 = '{$sy_content_3}',
				sy_content_4 = '{$sy_content_4}',
				sy_content_5 = '{$sy_content_5}',
               sy_update_date = NOW()
            WHERE sy_id = '{$sy_id}'";
    $result = sql_query($sql);
    
    if (!$result) {
        alert('학술대회 수정 중 오류가 발생했습니다.\\n'.mysqli_error($g5['connect_db']));
    }
    
}

/**
 * 학술대회 삭제
 */
else if ($w == "d") {
    if (!$sy_id) {
        alert('학술대회 ID가 없습니다.');
    }
    
    // 기존 데이터 조회
    $conference = sql_fetch("SELECT * FROM g5_conference WHERE sy_id = '{$sy_id}'");
    if (!$conference) {
        alert('존재하지 않는 학술대회입니다.');
    }
    
    // 연관된 일정 및 연자 데이터 개수 확인
    $schedule_count = sql_fetch("SELECT COUNT(*) as cnt FROM g5_conference_schedule WHERE ss_sy_id = '{$sy_id}'");
    $speaker_count = sql_fetch("SELECT COUNT(*) as cnt FROM g5_conference_speaker WHERE sp_sy_id = '{$sy_id}'");
    
    // 트랜잭션 시작
    sql_query("START TRANSACTION");
    
    try {
        // 이미지 파일 삭제
        if ($conference['sy_main_image'] && file_exists($conference['sy_main_image'])) {
            @unlink($conference['sy_main_image']);
        }
        
        if ($conference['sy_map_image'] && file_exists($conference['sy_map_image'])) {
            @unlink($conference['sy_map_image']);
        }
        
        // 연자 사진 파일들 삭제
        $speakers = sql_query("SELECT sp_photo FROM g5_conference_speaker WHERE sp_sy_id = '{$sy_id}' AND sp_photo != ''");
        while ($speaker = sql_fetch_array($speakers)) {
            if (file_exists($speaker['sp_photo'])) {
                @unlink($speaker['sp_photo']);
            }
        }
        
        // 데이터베이스에서 삭제 (외래키 제약조건에 의해 자동 삭제)
        $result = sql_query("DELETE FROM g5_conference WHERE sy_id = '{$sy_id}'");
        
        if (!$result) {
            throw new Exception('학술대회 삭제 중 오류가 발생했습니다: '.mysqli_error($g5['connect_db']));
        }
        
        // 트랜잭션 커밋
        sql_query("COMMIT");
        
        
    } catch (Exception $e) {
        // 트랜잭션 롤백
        sql_query("ROLLBACK");
        alert($e->getMessage());
    }
}

/**
 * 학술대회 이미지 업로드 함수
 */
function upload_conference_image($file, $upload_dir, $prefix = '') {
    global $sy_id;
    
    // 업로드 가능한 이미지 확장자
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    
    $file_info = pathinfo($file['name']);
    $extension = strtolower($file_info['extension']);
    
    // 확장자 검사
    if (!in_array($extension, $allowed_extensions)) {
        alert('이미지 파일만 업로드 가능합니다. (jpg, jpeg, png, gif, webp)');
    }
    
    // 파일 크기 검사 (5MB 제한)
    $max_size = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $max_size) {
        alert('파일 크기는 5MB를 초과할 수 없습니다.');
    }
    
    // 안전한 파일명 생성
    $safe_name = $prefix ? $prefix.'_' : '';
    $safe_name .= date('YmdHis').'_'.sprintf('%04d', rand(1, 9999)).'.'.$extension;
    
    $upload_path = $upload_dir.'/'.$safe_name;
    
    // 파일 업로드
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        alert('파일 업로드에 실패했습니다.');
    }
    
    // 파일 권한 설정
    @chmod($upload_path, G5_FILE_PERMISSION);
    
    return $safe_name;
}


// 리다이렉트 처리
if ($w == "" || $w == "u") {
    $msg = ($w == "") ? "학술대회가 생성되었습니다." : "학술대회가 수정되었습니다.";
    alert($msg, "./symposium_form.php?w=u&sy_id=$sy_id");
} else {
    alert("학술대회가 삭제되었습니다.", "./symposium_list.php");
}
?>