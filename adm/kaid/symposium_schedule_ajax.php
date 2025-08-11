<?php
include_once('./_common.php');

// 관리자 체크
if (!$is_admin) {
    die(json_encode(['success' => false, 'message' => '관리자만 접근할 수 있습니다.']));
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        addSchedule();
        break;
    case 'update':
        updateSchedule();
        break;
    case 'delete':
        deleteSchedule();
        break;
    case 'get_schedule':
        getSchedule();
        break;
    case 'update_order':
        updateScheduleOrder();
        break;
    default:
        die(json_encode(['success' => false, 'message' => '잘못된 요청입니다.']));
}

// 일정 추가
function addSchedule() {
    $sy_id = (int)$_POST['sy_id'];
    $ss_time = trim($_POST['ss_time']);
    $ss_title = trim($_POST['ss_title']);
    $ss_speaker = trim($_POST['ss_speaker']);
    $ss_title_en = trim($_POST['ss_title_en']);
    $ss_speaker_en = trim($_POST['ss_speaker_en']);
    $ss_type = trim($_POST['ss_type']) ?: 'lecture';
    $ss_bg_color = trim($_POST['ss_bg_color']) ?: '#ffffff';
    $ss_order = (int)$_POST['ss_order'];
    
    if (!$sy_id || !$ss_time || !$ss_title) {
        die(json_encode(['success' => false, 'message' => '필수 항목을 입력해주세요.']));
    }
    
    // 동일한 순서가 있으면 뒤로 밀기
    $sql = "UPDATE g5_conference_schedule SET ss_order = ss_order + 1 WHERE ss_sy_id = $sy_id AND ss_order >= $ss_order";
    sql_query($sql);
    
    // 일정 추가
    $sql = "INSERT INTO g5_conference_schedule (ss_sy_id, ss_time, ss_title, ss_title_en, ss_speaker, ss_speaker_en, ss_type, ss_bg_color, ss_order) VALUES ('$sy_id', '$ss_time', '$ss_title', '$ss_title_en', '$ss_speaker', '$ss_speaker_en', '$ss_type', '$ss_bg_color', '$ss_order')";
    $result = sql_query($sql);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => '일정이 등록되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다.']);
    }
}

// 일정 수정
function updateSchedule() {
    $ss_id = (int)$_POST['ss_id'];
    $sy_id = (int)$_POST['sy_id'];
    $ss_time = trim($_POST['ss_time']);
    $ss_title = trim($_POST['ss_title']);
    $ss_speaker = trim($_POST['ss_speaker']);
    $ss_title_en = trim($_POST['ss_title_en']);
    $ss_speaker_en = trim($_POST['ss_speaker_en']);
    $ss_type = trim($_POST['ss_type']) ?: 'lecture';
    $ss_bg_color = trim($_POST['ss_bg_color']) ?: '#ffffff';
    $ss_order = (int)$_POST['ss_order'];
    
    if (!$ss_id || !$sy_id || !$ss_time || !$ss_title) {
        die(json_encode(['success' => false, 'message' => '필수 항목을 입력해주세요.']));
    }
    
    // 기존 데이터 조회
    $existing_sql = "SELECT * FROM g5_conference_schedule WHERE ss_id = $ss_id";
    $existing = sql_fetch($existing_sql);
    
    if (!$existing) {
        die(json_encode(['success' => false, 'message' => '존재하지 않는 일정입니다.']));
    }
    
    // 순서 변경 처리
    if ($existing['ss_order'] != $ss_order) {
        if ($existing['ss_order'] < $ss_order) {
            // 순서가 뒤로 이동하는 경우
            $sql = "UPDATE g5_conference_schedule SET ss_order = ss_order - 1 WHERE ss_sy_id = $sy_id AND ss_order > {$existing['ss_order']} AND ss_order <= $ss_order AND ss_id != $ss_id";
        } else {
            // 순서가 앞으로 이동하는 경우
            $sql = "UPDATE g5_conference_schedule SET ss_order = ss_order + 1 WHERE ss_sy_id = $sy_id AND ss_order >= $ss_order AND ss_order < {$existing['ss_order']} AND ss_id != $ss_id";
        }
        sql_query($sql);
    }
    
    // 일정 수정
    $sql = "UPDATE g5_conference_schedule SET ss_time = '$ss_time', ss_title = '$ss_title', ss_title_en = '$ss_title_en', ss_speaker = '$ss_speaker', ss_speaker_en = '$ss_speaker_en', ss_type = '$ss_type', ss_bg_color = '$ss_bg_color', ss_order = '$ss_order' WHERE ss_id = $ss_id";
    $result = sql_query($sql);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => '일정이 수정되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다.']);
    }
}

// 일정 삭제
function deleteSchedule() {
    $ss_id = (int)$_POST['ss_id'];
    
    if (!$ss_id) {
        die(json_encode(['success' => false, 'message' => '일정 ID가 필요합니다.']));
    }
    
    // 기존 데이터 조회
    $existing_sql = "SELECT * FROM g5_conference_schedule WHERE ss_id = $ss_id";
    $existing = sql_fetch($existing_sql);
    
    if (!$existing) {
        die(json_encode(['success' => false, 'message' => '존재하지 않는 일정입니다.']));
    }
    
    // 일정 삭제
    $sql = "DELETE FROM g5_conference_schedule WHERE ss_id = $ss_id";
    $result = sql_query($sql);
    
    if ($result) {
        // 순서 재정렬
        $sql = "UPDATE g5_conference_schedule SET ss_order = ss_order - 1 WHERE ss_sy_id = {$existing['ss_sy_id']} AND ss_order > {$existing['ss_order']}";
        sql_query($sql);
        
        echo json_encode(['success' => true, 'message' => '일정이 삭제되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'message' => '데이터베이스 오류가 발생했습니다.']);
    }
}

// 일정 정보 조회
function getSchedule() {
    $ss_id = (int)$_GET['ss_id'];
    
    if (!$ss_id) {
        die(json_encode(['success' => false, 'message' => '일정 ID가 필요합니다.']));
    }
    
    $sql = "SELECT * FROM g5_conference_schedule WHERE ss_id = $ss_id";
    $schedule = sql_fetch($sql);
    
    if ($schedule) {
        echo json_encode(['success' => true, 'schedule' => $schedule]);
    } else {
        echo json_encode(['success' => false, 'message' => '일정을 찾을 수 없습니다.']);
    }
}

// 일정 순서 업데이트
function updateScheduleOrder() {
    if (!isset($_POST['order_data'])) {
        die(json_encode(['success' => false, 'message' => '순서 데이터가 전송되지 않았습니다.']));
    }
    
    $order_data_string = $_POST['order_data'];
    $order_data_string = trim($order_data_string);
    $order_data_string = stripslashes($order_data_string);
    
    $order_data = json_decode($order_data_string, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        if (preg_match_all('/\{"ss_id":(\d+),"ss_order":(\d+)\}/', $order_data_string, $matches, PREG_SET_ORDER)) {
            $order_data = [];
            foreach ($matches as $match) {
                $order_data[] = [
                    'ss_id' => (int)$match[1],
                    'ss_order' => (int)$match[2]
                ];
            }
        } else {
            die(json_encode(['success' => false, 'message' => 'JSON 파싱 오류: ' . json_last_error_msg()]));
        }
    }
    
    if (!is_array($order_data) || empty($order_data)) {
        die(json_encode(['success' => false, 'message' => '순서 데이터가 배열이 아니거나 비어있습니다.']));
    }
    
    $success = true;
    $error_message = '';
    $updated_count = 0;
    
    foreach ($order_data as $index => $item) {
        if (!is_array($item)) {
            $error_message = "순서 데이터 항목[$index]이 배열이 아닙니다.";
            $success = false;
            break;
        }
        
        if (!isset($item['ss_id']) || !isset($item['ss_order'])) {
            $error_message = "순서 데이터 항목[$index]에 필수 키가 없습니다.";
            $success = false;
            break;
        }
        
        $ss_id = (int)$item['ss_id'];
        $ss_order = (int)$item['ss_order'];
        
        if ($ss_id <= 0 || $ss_order <= 0) {
            $error_message = "순서 데이터 항목[$index]의 값이 유효하지 않습니다. (ss_id: $ss_id, ss_order: $ss_order)";
            $success = false;
            break;
        }
        
        $sql = "UPDATE g5_conference_schedule SET ss_order = '$ss_order' WHERE ss_id = '$ss_id'";
        $result = sql_query($sql);
        
        if (!$result) {
            $error_message = "데이터베이스 업데이트 오류 (ss_id: $ss_id)";
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