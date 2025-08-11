<?php
// 그누보드 설정 파일 포함 (경로는 실제 설정에 맞게 수정)
include_once('./_common.php');

header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? '';

switch($action) {
    case 'get_events':
        getEvents();
        break;
    case 'get_event':
        getEvent();
        break;
    case 'add_event':
        addEvent();
        break;
    case 'update_event':
        updateEvent();
        break;
    case 'delete_event':
        deleteEvent();
        break;
    default:
        echo json_encode(['success' => false, 'message' => '잘못된 요청입니다.']);
}

function getEvents() {
    global $g5;
    
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    
    $sql = "SELECT * FROM g5_calendar_events 
            WHERE (start_date <= '$end_date' AND end_date >= '$start_date')
            ORDER BY start_date ASC";
    
    $result = sql_query($sql);
    $events = [];
    
    while($row = sql_fetch_array($result)) {
        $events[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'content' => $row['content'],
            'start_date' => $row['start_date'],
            'end_date' => $row['end_date'],
            'url' => $row['url'],
            'color' => $row['color'],
            'height' => $row['height']
        ];
    }
    
    echo json_encode($events);
}

function getEvent() {
    global $g5;
    
    $id = (int)$_POST['id'];
    
    $sql = "SELECT * FROM g5_calendar_events WHERE id = $id";
    $row = sql_fetch($sql);
    
    if($row) {
        echo json_encode([
            'id' => $row['id'],
            'title' => $row['title'],
            'content' => $row['content'],
            'start_date' => $row['start_date'],
            'end_date' => $row['end_date'],
            'url' => $row['url'],
            'color' => $row['color'],
            'height' => $row['height']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => '일정을 찾을 수 없습니다.']);
    }
}

function addEvent() {
    global $g5, $member;
    
    // 로그인 체크 (필요시)
    // if(!$member['mb_id']) {
    //     echo json_encode(['success' => false, 'message' => '로그인이 필요합니다.']);
    //     return;
    // }
    
    $title = clean_xss_tags($_POST['title'] ?? '');
    $content = clean_xss_tags($_POST['content'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $url = clean_xss_tags($_POST['url'] ?? '');
    $color = $_POST['color'] ?? '#007bff';
    $height = (int)($_POST['height'] ?? 2);
    $mb_id = $member['mb_id'] ?? '';
    
    // 유효성 검사
    if(empty($title) || empty($start_date) || empty($end_date)) {
        echo json_encode(['success' => false, 'message' => '필수 항목을 입력해주세요.']);
        return;
    }
    
    if($start_date > $end_date) {
        echo json_encode(['success' => false, 'message' => '종료일은 시작일보다 늦어야 합니다.']);
        return;
    }
    
    $sql = "INSERT INTO g5_calendar_events 
            (title, content, start_date, end_date, url, color, height, mb_id, reg_date) 
            VALUES 
            ('$title', '$content', '$start_date', '$end_date', '$url', '$color', $height, '$mb_id', NOW())";
    
    if(sql_query($sql)) {
        echo json_encode(['success' => true, 'message' => '일정이 추가되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'message' => '일정 추가 중 오류가 발생했습니다.']);
    }
}

function updateEvent() {
    global $g5, $member;
    
    $id = (int)$_POST['event_id'];
    $title = clean_xss_tags($_POST['title'] ?? '');
    $content = clean_xss_tags($_POST['content'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $url = clean_xss_tags($_POST['url'] ?? '');
    $color = $_POST['color'] ?? '#007bff';
    $height = (int)($_POST['height'] ?? 2);
    
    // 유효성 검사
    if(empty($title) || empty($start_date) || empty($end_date)) {
        echo json_encode(['success' => false, 'message' => '필수 항목을 입력해주세요.']);
        return;
    }
    
    if($start_date > $end_date) {
        echo json_encode(['success' => false, 'message' => '종료일은 시작일보다 늦어야 합니다.']);
        return;
    }
    
    // 권한 체크 (필요시)
    // $sql = "SELECT mb_id FROM g5_calendar_events WHERE id = $id";
    // $row = sql_fetch($sql);
    // if($row['mb_id'] != $member['mb_id'] && !$is_admin) {
    //     echo json_encode(['success' => false, 'message' => '수정 권한이 없습니다.']);
    //     return;
    // }
    
    $sql = "UPDATE g5_calendar_events SET 
            title = '$title',
            content = '$content',
            start_date = '$start_date',
            end_date = '$end_date',
            url = '$url',
            color = '$color',
            height = $height,
            mod_date = NOW()
            WHERE id = $id";
    
    if(sql_query($sql)) {
        echo json_encode(['success' => true, 'message' => '일정이 수정되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'message' => '일정 수정 중 오류가 발생했습니다.']);
    }
}

function deleteEvent() {
    global $g5, $member;
    
    $id = (int)$_POST['id'];
    
    // 권한 체크 (필요시)
    // $sql = "SELECT mb_id FROM g5_calendar_events WHERE id = $id";
    // $row = sql_fetch($sql);
    // if($row['mb_id'] != $member['mb_id'] && !$is_admin) {
    //     echo json_encode(['success' => false, 'message' => '삭제 권한이 없습니다.']);
    //     return;
    // }
    
    $sql = "DELETE FROM g5_calendar_events WHERE id = $id";
    
    if(sql_query($sql)) {
        echo json_encode(['success' => true, 'message' => '일정이 삭제되었습니다.']);
    } else {
        echo json_encode(['success' => false, 'message' => '일정 삭제 중 오류가 발생했습니다.']);
    }
}
?>