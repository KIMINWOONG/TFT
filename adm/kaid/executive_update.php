<?php
include_once('./_common.php');

// 관리자 권한 체크
if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
}

$mode = $_POST['mode'];
$ex_id = (int)$_POST['ex_id'];

// 필수 입력값 체크
if (!$_POST['ex_name']) {
    alert('이름을 입력해주세요.');
}

if (!$_POST['ex_category']) {
    alert('카테고리를 선택해주세요.');
}

// 데이터 정리
$ex_name = clean_xss_tags($_POST['ex_name']);
$ex_category = clean_xss_tags($_POST['ex_category']);
$ex_department = clean_xss_tags($_POST['ex_department']);
$ex_career = ($_POST['ex_career']);
$ex_order = (int)$_POST['ex_order'];

// 이미지 처리
$ex_image = '';
$upload_dir = G5_DATA_PATH.'/executive';

// 업로드 디렉토리가 없으면 생성
if (!is_dir($upload_dir)) {
    @mkdir($upload_dir, G5_DIR_PERMISSION, true);
    @chmod($upload_dir, G5_DIR_PERMISSION);
}

// 기존 이미지 정보 조회 (수정 모드일 때)
$old_image = '';
if ($mode == 'edit' && $ex_id) {
    $sql = "SELECT ex_image FROM g5_executive WHERE ex_id = $ex_id";
    $row = sql_fetch($sql);
    $old_image = $row['ex_image'];
}

// 이미지 삭제 처리
if ($_POST['del_image'] && $old_image) {
    if (file_exists($upload_dir.'/'.$old_image)) {
        unlink($upload_dir.'/'.$old_image);
    }
    $old_image = '';
}

// 새 이미지 업로드 처리
if ($_FILES['ex_image']['name']) {
    $file = $_FILES['ex_image'];
    
    // 파일 유효성 검사
    if ($file['error'] == 0) {
        $allow_ext = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file_ext, $allow_ext)) {
            alert('이미지 파일만 업로드 가능합니다.');
        }
        
        // 파일 크기 체크 (2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            alert('파일 크기는 2MB 이하로 업로드해주세요.');
        }
        
        // 파일명 생성
        $new_filename = date('YmdHis') . '_' . mt_rand(1000, 9999) . '.' . $file_ext;
        $upload_path = $upload_dir . '/' . $new_filename;
        
        // 파일 업로드
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            // 기존 이미지 삭제
            if ($old_image && file_exists($upload_dir.'/'.$old_image)) {
                unlink($upload_dir.'/'.$old_image);
            }
            $ex_image = $new_filename;
        } else {
            alert('이미지 업로드에 실패했습니다.');
        }
    }
} else {
    // 새 이미지가 없으면 기존 이미지 유지
    $ex_image = $old_image;
}

// 데이터베이스 저장
if ($mode == 'edit' && $ex_id) {
    // 수정
    $sql = "UPDATE g5_executive SET 
                ex_name = '$ex_name',
                ex_category = '$ex_category',
                ex_department = '$ex_department',
                ex_career = '$ex_career',
                ex_order = $ex_order,
                ex_image = '$ex_image'
            WHERE ex_id = $ex_id";
    
    if (sql_query($sql)) {
        alert('임원 정보가 수정되었습니다.', './executive_list.php');
    } else {
        alert('수정에 실패했습니다.');
    }
} else {
    // 등록
    $sql = "INSERT INTO g5_executive SET 
                ex_name = '$ex_name',
                ex_category = '$ex_category',
                ex_department = '$ex_department',
                ex_career = '$ex_career',
                ex_order = $ex_order,
                ex_image = '$ex_image',
                ex_datetime = NOW()";
    
    if (sql_query($sql)) {
        alert('임원 정보가 등록되었습니다.', './executive_list.php');
    } else {
        alert('등록에 실패했습니다.');
    }
}
?>