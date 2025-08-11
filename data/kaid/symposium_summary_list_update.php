<?php
// abstract_list_update.php - 초록 목록 일괄 처리
$sub_menu = "600300";
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'w');

check_demo();

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
}

$act_button = $_POST['act_button'];
$chk = $_POST['chk'];
$as_ids = $_POST['as_id'];

if (!$chk || !is_array($chk)) {
    alert('선택된 항목이 없습니다.');
}

// 선택된 초록 ID 수집
$selected_ids = array();
foreach ($chk as $key) {
    if (isset($as_ids[$key])) {
        $selected_ids[] = (int)$as_ids[$key];
    }
}

if (empty($selected_ids)) {
    alert('처리할 초록이 없습니다.');
}

$ids_str = implode(',', $selected_ids);
$count = count($selected_ids);

// 작업 처리
switch ($act_button) {
    case '선택채택':
        // 승인 처리
        $sql = "UPDATE g5_conference_summary SET 
                as_status = 'accepted',
                as_review_date = NOW(),
                as_update_date = NOW()
                WHERE as_id IN ({$ids_str})";
        
        sql_query($sql);
        
        // 로그 기록
		/**
        foreach ($selected_ids as $as_id) {
            $log_sql = "INSERT INTO g5_abstract_submission_log SET
                        asl_as_id = {$as_id},
                        asl_action = 'accepted',
                        asl_description = '일괄 승인',
                        asl_reg_date = NOW()";
            sql_query($log_sql);
        }
		*/
        
        $message = "{$count}개 초록이 채택되었습니다.";
        break;
        
    case '선택탈락':
        // 반려 처리
        $sql = "UPDATE g5_conference_summary SET 
                as_status = 'rejected',
                as_review_date = NOW(),
                as_update_date = NOW()
                WHERE as_id IN ({$ids_str})";
        
        sql_query($sql);
        
        // 로그 기록
		/**
        foreach ($selected_ids as $as_id) {
            $log_sql = "INSERT INTO g5_abstract_submission_log SET
                        asl_as_id = {$as_id},
                        asl_action = 'rejected',
                        asl_description = '일괄 반려',
                        asl_reg_date = NOW()";
            sql_query($log_sql);
        }
        */

        $message = "{$count}개 초록이 탈락되었습니다.";
        break;
        
    case '선택삭제':
        // 삭제 처리 (저자 정보도 함께 삭제)
        sql_query("DELETE FROM g5_conference_summary_authors WHERE aa_as_id IN ({$ids_str})");
        sql_query("DELETE FROM g5_abstract_submission_log WHERE asl_as_id IN ({$ids_str})");
        sql_query("DELETE FROM g5_conference_summary WHERE as_id IN ({$ids_str})");
        
        $message = "{$count}개 초록이 삭제되었습니다.";
        break;
        
    default:
        alert('올바르지 않은 작업입니다.');
        break;
}

// 기존 쿼리스트링 유지하여 리다이렉트
$query_params = array();
if (isset($_POST['page'])) $query_params['page'] = $_POST['page'];
if (isset($_POST['sfl'])) $query_params['sfl'] = $_POST['sfl'];
if (isset($_POST['stx'])) $query_params['stx'] = $_POST['stx'];
if (isset($_POST['sst'])) $query_params['sst'] = $_POST['sst'];
if (isset($_POST['sod'])) $query_params['sod'] = $_POST['sod'];
if (isset($_POST['sy_id'])) $query_params['sy_id'] = $_POST['sy_id'];
if (isset($_POST['status'])) $query_params['status'] = $_POST['status'];
if (isset($_POST['field'])) $query_params['field'] = $_POST['field'];

$redirect_url = './symposium_summary_list.php';
if (!empty($query_params)) {
    $redirect_url .= '?' . http_build_query($query_params);
}

alert($message, $redirect_url);
?>