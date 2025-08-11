<?php
include "../../../../common.php";

if (!defined('_GNUBOARD_')) exit;

// 로그인 체크 (회원 또는 비회원)
if (!$is_member && !$is_nonemember) {
    alert("회원만 이용할 수 있습니다.", G5_URL);
}

// POST 데이터 검증
if (!$_POST || !isset($_POST['mode'])) {
    alert("잘못된 접근입니다.");
}

$mode = $_POST['mode']; // submit 또는 update
$as_cr_id = (int)$_POST['as_cr_id'];

// 활성화된 학술집담회 정보 가져오기
$conference = sql_fetch("SELECT * FROM g5_conference WHERE sy_status='active' ORDER BY sy_id DESC LIMIT 0,1");

if (!$conference) {
    alert("현재 진행중인 학술집담회가 없습니다.");
}

// 회원 등록 정보 확인
$registration = sql_fetch("SELECT * FROM g5_conference_registration WHERE cr_id = '{$as_cr_id}' AND cr_status = 'registered'");

if (!$registration) {
    alert("학술집담회 등록 정보를 찾을 수 없습니다.");
}

// 권한 확인 (회원인 경우 mb_id 확인, 비회원인 경우 세션 확인)
if ($is_member) {
    if ($registration['cr_mb_id'] !== $member['mb_id']) {
        alert("본인의 등록 정보만 수정할 수 있습니다.");
    }
} else {
    // 비회원인 경우 추가 권한 확인 로직 (필요시 구현)
    $nonemb_name = get_session("ss_nonemb_name");
    $nonemb_birth = get_session("ss_nonemb_birth");
    
    if (empty($nonemb_name) || empty($nonemb_birth)) {
        alert("세션이 만료되었습니다. 다시 로그인해주세요.");
    }
}

// 필수 입력 검증
$required_fields = [
    'submitter' => '제출자',
    'title_kor' => '논문제목',
    'institution' => '소속기관',
    'abstract_kor' => '초록',
    'presentation_field' => '발표분야',
    'presentation_type' => '발표유형',
    'presenter_name' => '발표자명',
    'presenter_institution' => '발표자 소속기관',
    'presenter_mobile_carrier' => '휴대전화 통신사',
    'presenter_mobile1' => '휴대전화',
    'presenter_mobile2' => '휴대전화'
];

foreach ($required_fields as $field => $name) {
    if (empty($_POST[$field])) {
        alert($name . "을(를) 입력해주세요.");
    }
}

// 저자 검증
if (empty($_POST['authors']) || !is_array($_POST['authors'])) {
    alert("저자명을 입력해주세요.");
}

$has_author = false;
foreach ($_POST['authors'] as $author) {
    if (!empty(trim($author))) {
        $has_author = true;
        break;
    }
}

if (!$has_author) {
    alert("최소 한 명의 저자를 입력해주세요.");
}

// 파일 업로드 처리
$save_filename = '';
$file_name = '';
$file_size = 0;
if($_POST['abstract_file_del']){
        if ($is_member) {
            $existing = sql_fetch("SELECT * FROM g5_conference_summary WHERE as_id = '{$as_id}' AND as_cr_id = '{$as_cr_id}' AND cr_mb_id = '{$member['mb_id']}'");
        } else {
            $existing = sql_fetch("SELECT * FROM g5_conference_summary WHERE as_id = '{$as_id}' AND as_cr_id = '{$as_cr_id}' AND cr_nonemb_name = '{$nonemb_name}' AND cr_nonemb_birth = '{$nonemb_birth}'");
        }
		@unlink(G5_DATA_PATH.'/summary/'.$conference['sy_id'].'/'.$existing['as_file_path']);
		sql_query("UPDATE g5_conference_summary SET as_file_path='', as_file_name='' where as_id='{$as_id}'");
}
if (isset($_FILES['abstract_file']) && $_FILES['abstract_file']['error'] == 0) {
    $allowed_extensions = array('pdf', 'doc', 'docx');
    $file_extension = strtolower(pathinfo($_FILES['abstract_file']['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_extensions)) {
        alert("PDF, Word 파일만 업로드 가능합니다.");
    }
    
    if ($_FILES['abstract_file']['size'] > 10 * 1024 * 1024) { // 10MB 제한
        alert("파일 크기는 10MB 이하만 가능합니다.");
    }
    
    // 파일 저장 디렉토리 생성
    $upload_dir = G5_DATA_PATH . '/summary/' . $conference['sy_id'];
    if (!is_dir($upload_dir)) {
        @mkdir($upload_dir, 0755, true);
    }
    
    // 파일명 생성 (중복 방지)
    $file_name = $_FILES['abstract_file']['name'];
    $save_filename = date('YmdHis') . '_' . uniqid() . '.' . $file_extension;
    $file_full_path = $upload_dir . '/' . $save_filename;
    
    if (!move_uploaded_file($_FILES['abstract_file']['tmp_name'], $file_full_path)) {
        alert("파일 업로드에 실패했습니다.");
    }
    
    $file_size = $_FILES['abstract_file']['size'];
}

// 데이터베이스 처리
try {
    if ($mode === 'update') {
        // 수정 모드
        $as_id = (int)$_POST['as_id'];
        
        // 기존 초록 정보 확인
        if ($is_member) {
            $existing = sql_fetch("SELECT * FROM g5_conference_summary WHERE as_id = '{$as_id}' AND as_cr_id = '{$as_cr_id}' AND cr_mb_id = '{$member['mb_id']}'");
        } else {
            $existing = sql_fetch("SELECT * FROM g5_conference_summary WHERE as_id = '{$as_id}' AND as_cr_id = '{$as_cr_id}' AND cr_nonemb_name = '{$nonemb_name}' AND cr_nonemb_birth = '{$nonemb_birth}'");
        }
        
        if (!$existing) {
            alert("수정할 초록을 찾을 수 없습니다.");
        }
        
        // 초록 정보 업데이트
        $sql = "UPDATE g5_conference_summary SET
                as_submitter = '".sql_real_escape_string($_POST['submitter'])."',
                as_title_kor = '".sql_real_escape_string($_POST['title_kor'])."',
                as_title_eng = '".sql_real_escape_string($_POST['title_eng'])."',
                as_institution = '".sql_real_escape_string($_POST['institution'])."',
                as_language = '".sql_real_escape_string($_POST['language'])."',
                as_abstract_kor = '".sql_real_escape_string($_POST['abstract_kor'])."',
                as_abstract_eng = '".sql_real_escape_string($_POST['abstract_eng'])."',
                ".($save_filename ? "as_file_path = '{$save_filename}', as_file_name = '".sql_real_escape_string($file_name)."', as_file_size = {$file_size}," : "")."
                as_presentation_field = '".sql_real_escape_string($_POST['presentation_field'])."',
                as_presentation_type = '".sql_real_escape_string($_POST['presentation_type'])."',
                as_presenter_name = '".sql_real_escape_string($_POST['presenter_name'])."',
                as_presenter_institution = '".sql_real_escape_string($_POST['presenter_institution'])."',
                as_presenter_home_phone1 = '".sql_real_escape_string($_POST['presenter_home_phone1'])."',
                as_presenter_home_phone2 = '".sql_real_escape_string($_POST['presenter_home_phone2'])."',
                as_presenter_home_phone3 = '".sql_real_escape_string($_POST['presenter_home_phone3'])."',
                as_presenter_mobile_carrier = '".sql_real_escape_string($_POST['presenter_mobile_carrier'])."',
                as_presenter_mobile1 = '".sql_real_escape_string($_POST['presenter_mobile1'])."',
                as_presenter_mobile2 = '".sql_real_escape_string($_POST['presenter_mobile2'])."',
                as_presenter_email = '".sql_real_escape_string($_POST['presenter_email'])."',
                as_status = 'submitted',
                as_update_date = NOW()
                WHERE as_id = '{$as_id}'";
        $action = 'updated';
        $message = '초록이 성공적으로 수정되었습니다.';
        
    } else {
        // 새 제출 모드
        
        // 중복 제출 체크
        if ($is_member) {
            $existing_check = sql_fetch("SELECT as_id FROM g5_conference_summary WHERE as_cr_id = '{$as_cr_id}' AND cr_mb_id = '{$member['mb_id']}'");
        } else {
            $existing_check = sql_fetch("SELECT as_id FROM g5_conference_summary WHERE as_cr_id = '{$as_cr_id}' AND cr_nonemb_name = '{$nonemb_name}' AND cr_nonemb_birth = '{$nonemb_birth}'");
        }
        
        if ($existing_check) {
            alert("이미 초록을 제출하셨습니다. 수정을 원하시면 수정 페이지를 이용해주세요.");
        }
        
        // 새 초록 등록
        $sql = "INSERT INTO g5_conference_summary SET
                as_sy_id = '{$conference['sy_id']}',
                as_cr_id = '{$as_cr_id}',
                ".($is_member ? "cr_mb_id = '{$member['mb_id']}'," : "cr_nonemb_name = '{$nonemb_name}', cr_nonemb_birth = '{$nonemb_birth}',")."
                as_submitter = '".sql_real_escape_string($_POST['submitter'])."',
                as_title_kor = '".sql_real_escape_string($_POST['title_kor'])."',
                as_title_eng = '".sql_real_escape_string($_POST['title_eng'])."',
                as_institution = '".sql_real_escape_string($_POST['institution'])."',
                as_language = '".sql_real_escape_string($_POST['language'])."',
                as_abstract_kor = '".sql_real_escape_string($_POST['abstract_kor'])."',
                as_abstract_eng = '".sql_real_escape_string($_POST['abstract_eng'])."',
                as_file_path = '{$save_filename}',
                as_file_name = '".sql_real_escape_string($file_name)."',
                as_file_size = {$file_size},
                as_presentation_field = '".sql_real_escape_string($_POST['presentation_field'])."',
                as_presentation_type = '".sql_real_escape_string($_POST['presentation_type'])."',
                as_presenter_name = '".sql_real_escape_string($_POST['presenter_name'])."',
                as_presenter_institution = '".sql_real_escape_string($_POST['presenter_institution'])."',
                as_presenter_home_phone1 = '".sql_real_escape_string($_POST['presenter_home_phone1'])."',
                as_presenter_home_phone2 = '".sql_real_escape_string($_POST['presenter_home_phone2'])."',
                as_presenter_home_phone3 = '".sql_real_escape_string($_POST['presenter_home_phone3'])."',
                as_presenter_mobile_carrier = '".sql_real_escape_string($_POST['presenter_mobile_carrier'])."',
                as_presenter_mobile1 = '".sql_real_escape_string($_POST['presenter_mobile1'])."',
                as_presenter_mobile2 = '".sql_real_escape_string($_POST['presenter_mobile2'])."',
                as_presenter_email = '".sql_real_escape_string($_POST['presenter_email'])."',
                as_submit_date = NOW()";
        
        $action = 'submitted';
        $message = '초록이 성공적으로 제출되었습니다.';
    }
    
    $result = sql_query($sql);
    
    if ($result) {
        if ($mode === 'submit') {
            $as_id = sql_insert_id();
        }
        
        // 기존 저자 정보 삭제 후 새로 입력
        sql_query("DELETE FROM g5_conference_summary_authors WHERE aa_as_id = '{$as_id}'");
        
        // 저자 정보 저장
        if (is_array($_POST['authors'])) {
            foreach ($_POST['authors'] as $index => $author_name) {
                if (!empty(trim($author_name))) {
                    $author_role = $_POST['author_roles'][$index] ?? '공동저자';
                    $is_presenter = isset($_POST['is_presenter'][$index]) ? 1 : 0;
                    
                    $author_sql = "INSERT INTO g5_conference_summary_authors SET
                                   aa_as_id = '{$as_id}',
                                   aa_name = '".sql_real_escape_string($author_name)."',
                                   aa_role = '".sql_real_escape_string($author_role)."',
                                   aa_is_presenter = {$is_presenter},
                                   aa_order = ".($index + 1);
                    sql_query($author_sql);
                }
            }
        }
        
        
		if($is_member){
			$link=G5_THEME_URL."/subpage/mypage/mypage_4.php";
		}else{
			$link=G5_THEME_URL."/subpage/mypage/mypage_6.php";
		}
		echo "<script>
                alert('{$message}');
                location.href = '".$link."';
              </script>";
        exit;
        
    } else {
        alert("처리 중 오류가 발생했습니다. 다시 시도해주세요.");
    }
    
} catch (Exception $e) {
    alert("처리 중 오류가 발생했습니다: " . $e->getMessage());
}
?>