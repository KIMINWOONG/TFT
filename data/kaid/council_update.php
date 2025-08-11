<?php
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$mode = $_POST['mode'];
$co_id = (int)$_POST['co_id'];

// 필수 입력 체크
if (!$_POST['co_name']) {
    alert('이름을 입력하세요.');
}

if (!$_POST['co_category']) {
    alert('카테고리를 선택하세요.');
}

// 입력값 정리
$co_name = clean_xss_tags($_POST['co_name']);
$co_category = clean_xss_tags($_POST['co_category']);
$co_department = clean_xss_tags($_POST['co_department']);
$co_career = ($_POST['co_career']);
$co_order = (int)$_POST['co_order'];

// 이미지 처리
$co_image = '';
$old_image = '';

// 업로드 디렉토리 생성
$upload_dir = G5_DATA_PATH.'/council';
if (!is_dir($upload_dir)) {
    @mkdir($upload_dir, G5_DIR_PERMISSION);
    @chmod($upload_dir, G5_DIR_PERMISSION);
}

// 기존 이미지 정보 가져오기
if ($mode == 'edit' && $co_id) {
    $sql = "SELECT co_image FROM g5_council WHERE co_id = $co_id";
    $result = sql_query($sql);
    $row = sql_fetch_array($result);
    $old_image = $row['co_image'];
}

// 이미지 삭제 체크
if ($_POST['del_image'] && $old_image) {
    if (file_exists($upload_dir.'/'.$old_image)) {
        unlink($upload_dir.'/'.$old_image);
    }
    $old_image = '';
}

// 새 이미지 업로드
if ($_FILES['co_image']['name']) {
    $file = $_FILES['co_image'];
    
    // 파일 확장자 체크
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_extensions)) {
        alert('이미지 파일만 업로드 가능합니다. (jpg, jpeg, png, gif)');
    }
    
    // 파일 크기 체크 (5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        alert('파일 크기는 5MB 이하만 가능합니다.');
    }
    
    // 파일명 생성
    $new_filename = 'council_' . time() . '_' . mt_rand(1000, 9999) . '.' . $file_extension;
    $upload_path = $upload_dir . '/' . $new_filename;
    
    // 파일 이동
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        @chmod($upload_path, G5_FILE_PERMISSION);
        
        // 기존 이미지 삭제
        if ($old_image && file_exists($upload_dir.'/'.$old_image)) {
            unlink($upload_dir.'/'.$old_image);
        }
        
        $co_image = $new_filename;
    } else {
        alert('이미지 업로드에 실패했습니다.');
    }
} else {
    // 새 이미지가 없으면 기존 이미지 유지
    $co_image = $old_image;
}

// 데이터베이스 처리
if ($mode == 'edit' && $co_id) {
    // 수정
    $sql = "UPDATE g5_council SET 
                co_name = '$co_name',
                co_category = '$co_category',
                co_department = '$co_department',
                co_career = '$co_career',
                co_order = $co_order,
                co_image = '$co_image'
            WHERE co_id = $co_id";
    
    if (sql_query($sql)) {
        alert('평의원 정보가 수정되었습니다.', './council_list.php');
    } else {
        alert('평의원 정보 수정에 실패했습니다.');
    }
} else {
    // 등록
    $sql = "INSERT INTO g5_council SET 
                co_name = '$co_name',
                co_category = '$co_category',
                co_department = '$co_department',
                co_career = '$co_career',
                co_order = $co_order,
                co_image = '$co_image',
                co_datetime = '" . G5_TIME_YMDHIS . "'";
    
    if (sql_query($sql)) {
        alert('평의원이 등록되었습니다.', './council_list.php');
    } else {
        alert('평의원 등록에 실패했습니다.');
    }
}
?>