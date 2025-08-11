<?php
include_once './_common.php';

// AJAX 요청만 허용
if (!isset($_POST['ajax']) || $_POST['ajax'] != 'find_member') {
    exit('잘못된 접근입니다.');
}

header('Content-Type: application/json');

// 아이디 찾기 처리
if ($_POST['find_type'] == 'id') {
    $mb_name = trim($_POST['mb_name']);
    $mb_email = trim($_POST['mb_email']);
    
    // 입력값 검증
    if (empty($mb_name) || empty($mb_email)) {
        echo json_encode([
            'success' => false, 
            'message' => "모든 정보를 입력해주세요."
        ]);
        exit;
    }
    
    // 이메일 형식 검증
    if (!filter_var($mb_email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'success' => false, 
            'message' => "올바른 이메일 형식을 입력해주세요."
        ]);
        exit;
    }
    
    // SQL 인젝션 방지
    $mb_name = sql_real_escape_string($mb_name);
    $mb_email = sql_real_escape_string($mb_email);
    
    // 데이터베이스 조회
    $sql = "SELECT mb_id FROM {$g5['member_table']} WHERE mb_name = '{$mb_name}' AND mb_email = '{$mb_email}' AND mb_leave_date = ''";
    $result = sql_query($sql);
    
    if ($row = sql_fetch_array($result)) {
        $found_id = $row['mb_id'];
        // 아이디 마스킹 처리 (보안)
        $id_length = strlen($found_id);
        if ($id_length <= 3) {
            $masked_id = $found_id;
        } else {
            $masked_id = substr($found_id, 0, 4) . str_repeat('*', $id_length - 4);
        }
        
        echo json_encode([
            'success' => true, 
            'message' => "회원님의 아이디는 '{$masked_id}' 입니다."
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => "입력하신 정보와 일치하는 회원정보가 없습니다."
        ]);
    }
} 
// 비밀번호 찾기/변경 처리
else if ($_POST['find_type'] == 'password') {
    $mb_id = trim($_POST['mb_id']);
    $mb_hp = trim($_POST['mb_hp']);
    $mb_password = trim($_POST['mb_password']);
    $mb_password_re = trim($_POST['mb_password_re']);
    
    // 입력값 검증
    if (empty($mb_id) || empty($mb_hp) || empty($mb_password) || empty($mb_password_re)) {
        echo json_encode([
            'success' => false, 
            'message' => "모든 정보를 입력해주세요."
        ]);
        exit;
    }
    
    // 비밀번호 일치 확인
    if ($mb_password !== $mb_password_re) {
        echo json_encode([
            'success' => false, 
            'message' => "비밀번호가 일치하지 않습니다."
        ]);
        exit;
    }
    
    // 비밀번호 길이 확인
    if (strlen($mb_password) < 4) {
        echo json_encode([
            'success' => false, 
            'message' => "비밀번호는 4자리 이상이어야 합니다."
        ]);
        exit;
    }
    
    // 아이디 형식 검증
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $mb_id)) {
        echo json_encode([
            'success' => false, 
            'message' => "올바른 아이디 형식을 입력해주세요."
        ]);
        exit;
    }
    
    // 휴대폰 번호 형식 검증
	/**
    $mb_hp = str_replace('-', '', $mb_hp);
    if (!preg_match('/^01[0-9]{8,9}$/', $mb_hp)) {
        echo json_encode([
            'success' => false, 
            'message' => "올바른 휴대폰 번호를 입력해주세요."
        ]);
        exit;
    }
	*/
    
    // SQL 인젝션 방지
    $mb_id = sql_real_escape_string($mb_id);
    $mb_hp = sql_real_escape_string($mb_hp);
    
    // 회원 정보 확인
    $sql = "SELECT mb_no, mb_name FROM {$g5['member_table']} WHERE mb_id = '{$mb_id}' AND mb_hp = '{$mb_hp}' AND mb_leave_date = ''";
	$debug=$sql;
    $result = sql_query($sql);
    
    if ($row = sql_fetch_array($result)) {
        // 비밀번호 암호화
        $password_hash = get_encrypt_string($mb_password);
        
        // 비밀번호 업데이트
        $update_sql = "UPDATE {$g5['member_table']} SET mb_password = '{$password_hash}' WHERE mb_id = '{$mb_id}'";
        
        if (sql_query($update_sql)) {
            // 비밀번호 변경 로그 (선택사항)
            $log_sql = "INSERT INTO {$g5['member_table']}_log SET 
                        mb_id = '{$mb_id}', 
                        log_type = 'password_change', 
                        log_datetime = NOW(), 
                        log_ip = '{$_SERVER['REMOTE_ADDR']}'";
            // sql_query($log_sql); // 로그 테이블이 있는 경우만 사용
            
            echo json_encode([
                'success' => true, 
                'message' => "비밀번호가 성공적으로 변경되었습니다."
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => "비밀번호 변경 중 오류가 발생했습니다. 다시 시도해주세요."
            ]);
        }
    } else {
        echo json_encode([
            'success' => false, 
            'message' => "입력하신 정보와 일치하는 회원정보가 없습니다.",
			'debug' => $debug
        ]);
    }
} 
// 잘못된 요청 타입
else {
    echo json_encode([
        'success' => false, 
        'message' => "잘못된 요청입니다."
    ]);
}
?>