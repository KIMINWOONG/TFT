<?php
include_once('./_common.php');

// 관리자 체크
if (!$is_admin) {
    die(json_encode(['success' => false, 'message' => '관리자만 접근할 수 있습니다.']));
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        addSpeaker();
        break;
    case 'update':
        updateSpeaker();
        break;
    case 'delete':
        deleteSpeaker();
        break;
    case 'get_speaker':
        getSpeaker();
        break;
    case 'update_order':
        updateSpeakerOrder();
        break;
    default:
        die(json_encode(['success' => false, 'message' => '잘못된 요청입니다.']));
}

// 연자 추가
function addSpeaker() {
    global $g5;
    
    $sy_id = (int)$_POST['sy_id'];
    $sp_name = trim($_POST['sp_name']);
    $sp_name_en = trim($_POST['sp_name_en']);
    $sp_specialty = trim($_POST['sp_specialty']);
    $sp_specialty_en = trim($_POST['sp_specialty_en']);
    $sp_order = (int)$_POST['sp_order'];
    
    if (!$sy_id || !$sp_name) {
        die(json_encode(['success' => false, 'message' => '필수 항목을 입력해주세요.']));
    }
    
    // 사진 업로드 처리
    $sp_photo = '';
    if (isset($_FILES['sp_photo']) && $_FILES['sp_photo']['error'] == 0) {
        $upload_dir = G5_DATA_PATH . '/conference/speaker/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['sp_photo']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = date('YmdHis') . '_' . uniqid() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['sp_photo']['tmp_name'], $upload_path)) {
                $sp_photo = G5_DATA_URL . '/conference/speaker/' . $new_filename;
            }
        }
    }
    
    // 동일한 순서가 있으면 뒤로 밀기
    $sql = "UPDATE g5_conference_speaker SET sp_order = sp_order + 1 WHERE sp_sy_id = $sy_id AND sp_order >= $sp_order";
    sql_query($sql);
    
    // 연자 추가
    $sql = "INSERT INTO g5_conference_speaker (sp_sy_id, sp_name,sp_name_en, sp_specialty,sp_specialty_en, sp_photo, sp_order) VALUES ($sy_id, '$sp_name', '$sp_name_en', '$sp_specialty', '$sp_specialty_en', '$sp_photo', $sp_order)";
    $result = sql_query($sql);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => '연자가 등록되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다.']);
    }
}

// 연자 수정
function updateSpeaker() {
    global $g5;
    
    $sp_id = (int)$_POST['sp_id'];
    $sy_id = (int)$_POST['sy_id'];
    $sp_name = trim($_POST['sp_name']);
    $sp_name_en = trim($_POST['sp_name_en']);
    $sp_specialty = trim($_POST['sp_specialty']);
    $sp_specialty_en = trim($_POST['sp_specialty_en']);
    $sp_order = (int)$_POST['sp_order'];
    
    if (!$sp_id || !$sy_id || !$sp_name) {
        die(json_encode(['success' => false, 'message' => '필수 항목을 입력해주세요.']));
    }
    
    // 기존 데이터 조회
    $existing_sql = "SELECT * FROM g5_conference_speaker WHERE sp_id = $sp_id";
    $existing = sql_fetch($existing_sql);
    
    if (!$existing) {
        die(json_encode(['success' => false, 'message' => '존재하지 않는 연자입니다.']));
    }
    
    $sp_photo = $existing['sp_photo'];
    
    // 사진 업로드 처리
    if (isset($_FILES['sp_photo']) && $_FILES['sp_photo']['error'] == 0) {
        $upload_dir = G5_DATA_PATH . '/conference/speaker/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['sp_photo']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = date('YmdHis') . '_' . uniqid() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['sp_photo']['tmp_name'], $upload_path)) {
                // 기존 사진 파일 삭제
                if ($sp_photo && file_exists(str_replace(G5_DATA_URL, G5_DATA_PATH, $sp_photo))) {
                    unlink(str_replace(G5_DATA_URL, G5_DATA_PATH, $sp_photo));
                }
                $sp_photo = G5_DATA_URL . '/conference/speaker/' . $new_filename;
            }
        }
    }
    
    // 순서 변경 처리
    if ($existing['sp_order'] != $sp_order) {
        if ($existing['sp_order'] < $sp_order) {
            // 순서가 뒤로 이동하는 경우
            $sql = "UPDATE g5_conference_speaker SET sp_order = sp_order - 1 WHERE sp_sy_id = $sy_id AND sp_order > {$existing['sp_order']} AND sp_order <= $sp_order AND sp_id != $sp_id";
        } else {
            // 순서가 앞으로 이동하는 경우
            $sql = "UPDATE g5_conference_speaker SET sp_order = sp_order + 1 WHERE sp_sy_id = $sy_id AND sp_order >= $sp_order AND sp_order < {$existing['sp_order']} AND sp_id != $sp_id";
        }
        sql_query($sql);
    }
    
    // 연자 수정
    $sql = "UPDATE g5_conference_speaker SET sp_name = '$sp_name', sp_name_en = '$sp_name_en', sp_specialty = '$sp_specialty', sp_specialty_en = '$sp_specialty_en', sp_photo = '$sp_photo', sp_order = $sp_order WHERE sp_id = $sp_id";
    $result = sql_query($sql);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => '연자가 수정되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다.']);
    }
}

// 연자 삭제
function deleteSpeaker() {
    global $g5;
    
    $sp_id = (int)$_POST['sp_id'];
    
    if (!$sp_id) {
        die(json_encode(['success' => false, 'message' => '연자 ID가 필요합니다.']));
    }
    
    // 기존 데이터 조회
    $existing_sql = "SELECT * FROM g5_conference_speaker WHERE sp_id = $sp_id";
    $existing = sql_fetch($existing_sql);
    
    if (!$existing) {
        die(json_encode(['success' => false, 'message' => '존재하지 않는 연자입니다.']));
    }
    
    // 사진 파일 삭제
    if ($existing['sp_photo'] && file_exists(str_replace(G5_DATA_URL, G5_DATA_PATH, $existing['sp_photo']))) {
        unlink(str_replace(G5_DATA_URL, G5_DATA_PATH, $existing['sp_photo']));
    }
    
    // 연자 삭제
    $sql = "DELETE FROM g5_conference_speaker WHERE sp_id = $sp_id";
    $result = sql_query($sql);
    
    if ($result) {
        // 순서 재정렬
        $sql = "UPDATE g5_conference_speaker SET sp_order = sp_order - 1 WHERE sp_sy_id = {$existing['sp_sy_id']} AND sp_order > {$existing['sp_order']}";
        sql_query($sql);
        
        echo json_encode(['success' => true, 'message' => '연자가 삭제되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다.']);
    }
}

// 연자 정보 조회
function getSpeaker() {
    $sp_id = (int)$_GET['sp_id'];
    
    if (!$sp_id) {
        die(json_encode(['success' => false, 'message' => '연자 ID가 필요합니다.']));
    }
    
    $sql = "SELECT * FROM g5_conference_speaker WHERE sp_id = $sp_id";
    $speaker = sql_fetch($sql);
    
    if ($speaker) {
        echo json_encode(['success' => true, 'speaker' => $speaker]);
    } else {
        echo json_encode(['success' => false, 'message' => '연자를 찾을 수 없습니다.']);
    }
}

// 연자 순서 업데이트
function updateSpeakerOrder() {
    // POST 데이터 확인
    if (!isset($_POST['order_data'])) {
        die(json_encode(['success' => false, 'message' => '순서 데이터가 전송되지 않았습니다.']));
    }
    
    $order_data_string = $_POST['order_data'];
    
    // 디버깅을 위한 로그 (실제 서비스에서는 제거)
    error_log("Received order_data: " . $order_data_string);
    
    // JSON 문자열 정리
    $order_data_string = trim($order_data_string);
    $order_data_string = stripslashes($order_data_string);
    
    // JSON 파싱 시도
    $order_data = json_decode($order_data_string, true);
    
    // JSON 파싱 오류 체크
    if (json_last_error() !== JSON_ERROR_NONE) {
        // 직접 파싱 시도 (간단한 배열 형태)
        if (preg_match_all('/\{"sp_id":(\d+),"sp_order":(\d+)\}/', $order_data_string, $matches, PREG_SET_ORDER)) {
            $order_data = [];
            foreach ($matches as $match) {
                $order_data[] = [
                    'sp_id' => (int)$match[1],
                    'sp_order' => (int)$match[2]
                ];
            }
        } else {
            die(json_encode(['success' => false, 'message' => 'JSON 파싱 오류: ' . json_last_error_msg() . ' - 원본: ' . substr($order_data_string, 0, 100)]));
        }
    }
    
    // 배열 데이터 체크
    if (!is_array($order_data) || empty($order_data)) {
        die(json_encode(['success' => false, 'message' => '순서 데이터가 배열이 아니거나 비어있습니다.']));
    }
    
    $success = true;
    $error_message = '';
    $updated_count = 0;
    
    foreach ($order_data as $index => $item) {
        // 데이터 타입 체크
        if (!is_array($item)) {
            $error_message = "순서 데이터 항목[$index]이 배열이 아닙니다.";
            $success = false;
            break;
        }
        
        // 필수 키 체크
        if (!isset($item['sp_id']) || !isset($item['sp_order'])) {
            $error_message = "순서 데이터 항목[$index]에 필수 키가 없습니다.";
            $success = false;
            break;
        }
        
        $sp_id = (int)$item['sp_id'];
        $sp_order = (int)$item['sp_order'];
        
        // 값 유효성 체크
        if ($sp_id <= 0 || $sp_order <= 0) {
            $error_message = "순서 데이터 항목[$index]의 값이 유효하지 않습니다. (sp_id: $sp_id, sp_order: $sp_order)";
            $success = false;
            break;
        }
        
        // 데이터베이스 업데이트
        $sql = "UPDATE g5_conference_speaker SET sp_order = '$sp_order' WHERE sp_id = '$sp_id'";
        $result = sql_query($sql);
        
        if (!$result) {
            $error_message = "데이터베이스 업데이트 오류 (sp_id: $sp_id)";
            $success = false;
            break;
        }
        
        $updated_count++;
    }
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => "순서가 업데이트되었습니다. ($updated_count 개 항목)"]);
    } else {
        echo json_encode(['success' => false, 'message' => '순서 업데이트 중 오류: ' . $error_message]);
    }
}
?>