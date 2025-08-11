<?php
include_once('./_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    goto_url(G5_URL);
}

function my_upload_file($file, $upload_dir = './data/member', $allowed_ext = ['jpg', 'jpeg', 'png', 'gif']) {
    // 업로드 된 파일이 없거나 에러가 있는 경우
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return false;
    }

    // 디렉토리가 없으면 생성
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // 파일 확장자 확인
    $tmp = explode('.', $file['name']);
    $ext = strtolower(end($tmp));
    if (!in_array($ext, $allowed_ext)) {
        return false;
    }
    // 새 파일명 생성 (현재시간_랜덤문자열.확장자)
    $filename = date('YmdHis') . '_' . substr(md5(uniqid()), 0, 8) . '.' . $ext;
    $filepath = $upload_dir . '/' . $filename;

	// 파일 업로드
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return $filepath;
    }

    return false;
}


$w = isset($_POST['w']) ? clean_xss_tags($_POST['w']) : '';
$mb_id = isset($_POST['mb_id']) ? (int)$_POST['mb_id'] : 0;

// 필수 값 체크
$mb_name = isset($_POST['mb_name']) ? clean_xss_tags($_POST['mb_name']) : '';
$mb_position = isset($_POST['mb_position']) ? clean_xss_tags($_POST['mb_position']) : '';
$mb_specialty = isset($_POST['mb_specialty']) ? ($_POST['mb_specialty']) : '';
$mb_education = isset($_POST['mb_education']) ? ($_POST['mb_education']) : '';
$mb_contact = isset($_POST['mb_contact']) ? clean_xss_tags($_POST['mb_contact']) : '';
$mb_intro = isset($_POST['mb_intro']) ? clean_xss_tags($_POST['mb_intro']) : '';
$mb_order = isset($_POST['mb_order']) ? (int)$_POST['mb_order'] : 0;

if (!$mb_name || !$mb_position || !$mb_specialty || !$mb_education || !$mb_intro) {
    alert('필수 항목이 누락되었습니다.');
    exit;
}
// 이미지 업로드 처리
$mb_thumbnail = '';
if ($w == 'u' && isset($_POST['mb_thumbnail_del']) && $_POST['mb_thumbnail_del']) {
    // 기존 파일 삭제
    $prev_file = sql_fetch("SELECT mb_thumbnail FROM g5_team_members WHERE mb_id = '{$mb_id}'");
    if ($prev_file['mb_thumbnail'] && file_exists(G5_DATA_PATH.'/member/'.$prev_file['mb_thumbnail'])) {
        @unlink(G5_DATA_PATH.'/member/'.$prev_file['mb_thumbnail']);
    }
} else if (isset($_FILES['mb_thumbnail']) && $_FILES['mb_thumbnail']['name']) {
    // 새 파일 업로드
    $upload_dir = G5_DATA_PATH.'/team';

    $file_ext = pathinfo($_FILES['mb_thumbnail']['name'], PATHINFO_EXTENSION);
    $file_name = uniqid('member_').'.'.strtolower($file_ext);

	if (move_uploaded_file($_FILES['mb_thumbnail']['tmp_name'], $upload_dir.'/'.$file_name)) {
        $mb_thumbnail = $file_name;
        
        // 수정 시 이전 파일 삭제
        if ($w == 'u') {
            $prev_file = sql_fetch("SELECT mb_thumbnail FROM trae_members WHERE mb_id = '{$mb_id}'");
            if ($prev_file['mb_thumbnail'] && file_exists(G5_DATA_PATH.'/member/'.$prev_file['mb_thumbnail'])) {
                @unlink(G5_DATA_PATH.'/member/'.$prev_file['mb_thumbnail']);
            }
        }
    } else {
        alert('파일 업로드에 실패했습니다.');
        exit;
    }
}

// DB 처리
if ($w == '') {
    $sql = "INSERT INTO g5_team_members
                SET mb_name = '{$mb_name}',
                    mb_position = '{$mb_position}',
                    mb_specialty = '{$mb_specialty}',
                    mb_education = '{$mb_education}',
                    mb_contact = '{$mb_contact}',
                    mb_thumbnail = '{$mb_thumbnail}',
                    mb_intro = '{$mb_intro}',
                    mb_order = '{$mb_order}',
                    mb_regdate = NOW()";
    sql_query($sql);
    $mb_id = sql_insert_id();
} else if ($w == 'u') {
    $sql = "UPDATE g5_team_members
                SET mb_name = '{$mb_name}',
                    mb_position = '{$mb_position}',
                    mb_specialty = '{$mb_specialty}',
                    mb_education = '{$mb_education}',
                    mb_contact = '{$mb_contact}',";
    if ($mb_thumbnail) {
        $sql .= " mb_thumbnail = '{$mb_thumbnail}',";
    } else if (isset($_POST['mb_thumbnail_del']) && $_POST['mb_thumbnail_del']) {
        $sql .= " mb_thumbnail = '',";
    }
    $sql .= " mb_intro = '{$mb_intro}',
             mb_order = '{$mb_order}'
             WHERE mb_id = '{$mb_id}'";
    sql_query($sql);
}

if ($w == '') {
    alert('구성원이 등록되었습니다.', './team_member.php');
} else {
    alert('구성원정보가 수정되었습니다.', './team_member.php');
}
?>