<?php
/**
 * abstract_status_update.php
 * 초록 상태 업데이트 처리 파일
 * 개별 초록의 상태를 변경하고 로그를 기록합니다.
 */

$sub_menu = "600300";
require_once './_common.php';

// 권한 확인
auth_check_menu($auth, $sub_menu, 'w');

// 데모 사이트 체크
check_demo();

// 관리자 권한 확인
if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
}

// POST 데이터 받기
$as_id = (int)$_POST['as_id'];
$status = trim($_POST['status']);

// 데이터 유효성 검사
if (!$as_id) {
    alert('초록 ID가 올바르지 않습니다.');
}

if (!$status) {
    alert('상태값이 전달되지 않았습니다.');
}

// 허용된 상태값 확인
$allowed_status = array('submitted', 'reviewed', 'revision_requested', 'accepted', 'rejected');
if (!in_array($status, $allowed_status)) {
    alert('올바르지 않은 상태입니다.');
}

// 현재 초록 정보 확인
$abstract_sql = "SELECT cs.*, c.sy_title, c.sy_sdate 
                 FROM g5_conference_summary cs 
                 LEFT JOIN g5_conference c ON cs.as_sy_id = c.sy_id 
                 WHERE cs.as_id = {$as_id}";
$abstract = sql_fetch($abstract_sql);

if (!$abstract) {
    alert('해당 초록을 찾을 수 없습니다.');
}

// 상태가 동일한 경우 처리하지 않음
if ($abstract['as_status'] == $status) {
    $status_display = array(
        'submitted' => '제출됨',
        'reviewed' => '심사중',
        'revision_requested' => '수정요청',
        'accepted' => '채택',
        'rejected' => '탈락'
    );
    alert("현재 상태가 이미 '{$status_display[$status]}'입니다.", $_SERVER['HTTP_REFERER']);
}

// 트랜잭션 시작
sql_query("BEGIN");

try {
    // 상태 업데이트
    $review_date_sql = "";
    if ($status == 'accepted' || $status == 'rejected') {
        // 채택/탈락인 경우 심사완료일 설정
        $review_date_sql = "as_review_date = NOW(),";
    } else if ($status == 'reviewed') {
        // 심사중인 경우 기존 심사완료일 유지 (있다면)
        $review_date_sql = $abstract['as_review_date'] ? "as_review_date = '{$abstract['as_review_date']}'," : "as_review_date = NULL,";
    } else {
        // 제출됨인 경우 심사완료일 초기화
        $review_date_sql = "as_review_date = NULL,";
    }
    
    $update_sql = "UPDATE g5_conference_summary SET 
                   as_status = '{$status}',
                   {$review_date_sql}
                   as_update_date = NOW()
                   WHERE as_id = {$as_id}";
    
    $result = sql_query($update_sql);
    
    if (!$result) {
        throw new Exception('상태 업데이트에 실패했습니다.');
    }
    
    // 로그 기록
	/**
    $action_text = array(
        'submitted' => '제출 상태로 변경',
        'reviewed' => '심사중으로 변경',
        'accepted' => '채택 (승인)',
        'rejected' => '탈락 (반려)'
    );
    
    $log_sql = "INSERT INTO g5_abstract_submission_log SET
                asl_as_id = {$as_id},
                asl_action = '{$status}',
                asl_description = '{$action_text[$status]}',
                asl_admin_id = '{$member['mb_id']}',
                asl_admin_name = '{$member['mb_name']}',
                asl_reg_date = NOW()";
    
    $log_result = sql_query($log_sql);
    
    if (!$log_result) {
        throw new Exception('로그 기록에 실패했습니다.');
    }
	*/
    
    // 트랜잭션 커밋
    sql_query("COMMIT");
    
    // 성공 메시지
    $status_display = array(
        'submitted' => '제출됨',
        'reviewed' => '심사중',
        'revision_requested' => '수정요청',
        'accepted' => '채택',
        'rejected' => '탈락'
    );
    
    $success_message = "초록 #{$as_id}의 상태가 '{$status_display[$status]}'로 변경되었습니다.";
    
    // 알림 메일 발송 (선택사항)
    if ($status == 'accepted' || $status == 'rejected' || $status == 'revision_requested') {
        send_notification_email($abstract, $status);
    }
    
    alert($success_message, $_SERVER['HTTP_REFERER']);
    
} catch (Exception $e) {
    // 트랜잭션 롤백
    sql_query("ROLLBACK");
    alert($e->getMessage());
}

/**
 * 알림 메일 발송 함수 (선택사항)
 */
function send_notification_email($abstract, $status) {
    // 이메일 발송 로직 (필요시 구현)
    // 예: 초록 제출자에게 심사 결과 알림
    
    /*
    $to_email = $abstract['as_presenter_email'];
    $subject = "[{$abstract['sy_title']}] 초록 심사 결과 안내";
    
    $status_text = ($status == 'accepted') ? '채택' : '탈락';
    $message = "
    안녕하세요.
    
    제출해주신 초록 '{$abstract['as_title_kor']}'의 심사 결과를 안내드립니다.
    
    심사 결과: {$status_text}
    
    감사합니다.
    ";
    
    // 메일 발송 (그누보드 메일 함수 사용)
    if ($to_email) {
        mailer('', $to_email, $subject, $message, 0);
    }
    */
}

/**
 * 상태 변경 권한 확인 함수 (선택사항)
 */
function check_status_change_permission($from_status, $to_status) {
    // 상태 변경 규칙 정의 (필요시)
    $allowed_transitions = array(
        'submitted' => array('reviewed', 'accepted', 'rejected'),
        'reviewed' => array('accepted', 'rejected', 'submitted'),
        'accepted' => array('rejected', 'reviewed'),
        'rejected' => array('accepted', 'reviewed')
    );
    
    if (!isset($allowed_transitions[$from_status])) {
        return false;
    }
    
    return in_array($to_status, $allowed_transitions[$from_status]);
}

/**
 * 통계 업데이트 함수 (선택사항)
 */
function update_conference_statistics($sy_id) {
    // 학술집담회별 통계 업데이트 (필요시)
    $stats_sql = "UPDATE g5_conference SET 
                  sy_total_abstracts = (SELECT COUNT(*) FROM g5_conference_summary WHERE as_sy_id = {$sy_id}),
                  sy_accepted_abstracts = (SELECT COUNT(*) FROM g5_conference_summary WHERE as_sy_id = {$sy_id} AND as_status = 'accepted'),
                  sy_rejected_abstracts = (SELECT COUNT(*) FROM g5_conference_summary WHERE as_sy_id = {$sy_id} AND as_status = 'rejected')
                  WHERE sy_id = {$sy_id}";
    
    sql_query($stats_sql);
}
?>