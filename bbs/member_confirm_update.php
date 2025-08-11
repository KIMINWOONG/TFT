<?php
include_once('./_common.php');

header('Content-Type: application/json');

// POST 데이터 받기
$mb_id = trim($_POST['mb_id']);
$mb_email = trim($_POST['mb_email']);
$mb_password = trim($_POST['mb_password']);

$response = array();

// 입력값 검증
if (!$mb_id || !$mb_email || !$mb_password) {
    $response['result'] = 'error';
    $response['message'] = '모든 정보를 입력해주세요.';
    echo json_encode($response);
    exit;
}

// 회원 정보 조회
$sql = " select mb_id, mb_password, mb_email from {$g5['member_table']} where mb_id = '{$mb_id}' ";
$member_info = sql_fetch($sql);

if (!$member_info['mb_id']) {
    $response['result'] = 'error';
    $response['message'] = '존재하지 않는 회원입니다.';
    echo json_encode($response);
    exit;
}

// 이메일 확인
if ($member_info['mb_email'] !== $mb_email) {
    $response['result'] = 'error';
    $response['message'] = '이메일이 일치하지 않습니다.';
    echo json_encode($response);
    exit;
}

// 비밀번호 확인
if (!check_password($mb_password, $member_info['mb_password'])) {
    $response['result'] = 'error';
    $response['message'] = '비밀번호가 일치하지 않습니다.';
    echo json_encode($response);
    exit;
}

// 세션에 확인 정보 저장 (보안을 위해)
$_SESSION['member_confirm_time'] = time();
$_SESSION['member_confirm_id'] = $mb_id;

$response['result'] = 'success';
$response['message'] = '확인되었습니다.';
$response['mb_id'] = $mb_id;

echo json_encode($response);
?>