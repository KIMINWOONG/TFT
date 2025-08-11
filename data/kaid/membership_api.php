<?php
// membership_api.php - 회비관리 백엔드 API
include_once('./_common.php');

// 관리자 권한 체크
if (!$is_admin) {
    http_response_code(403);
    echo json_encode(array('success' => false, 'message' => '관리자만 접근 가능합니다.'));
    exit;
}

$membership_table = G5_TABLE_PREFIX.'membership';

// CORS 헤더 설정 (필요시)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// AJAX 요청 처리
if ($_POST['action'] || $_GET['action']) {
    header('Content-Type: application/json; charset=UTF-8');
    
    $action = $_POST['action'] ?: $_GET['action'];
    
    switch ($action) {
        case 'list':
            echo json_encode(getMembershipList());
            break;
        case 'save':
            echo json_encode(saveMembership());
            break;
        case 'get_detail':
            echo json_encode(getMembershipDetail());
            break;
        case 'delete':
            echo json_encode(deleteMembership());
            break;
        case 'approve':
            echo json_encode(approveMembership());
            break;
        case 'refund':
            echo json_encode(refundMembership());
            break;
        case 'stats':
            echo json_encode(getStatistics());
            break;
        case 'export':
            exportToExcel();
            break;
        case 'bulk_approve':
            echo json_encode(bulkApprove());
            break;
        case 'bulk_delete':
            echo json_encode(bulkDelete());
            break;
        case 'check_expiring':
            echo json_encode(checkExpiringMemberships());
            break;
        case 'auto_generate':
            echo json_encode(autoGenerateMemberships());
            break;
        case 'preview_generation':
            echo json_encode(previewGeneration());
            break;
        default:
            echo json_encode(array('success' => false, 'message' => '잘못된 액션입니다.'));
            break;
    }
    exit;
}

// 회원 정보 조회 (GET 요청)
if ($_GET['get_member']) {
    $member_id = clean_xss_tags($_GET['member_id']);
    $sql = "SELECT mb_id, mb_name, mb_email FROM {$g5['member_table']} WHERE mb_id = '{$member_id}'";
    $member = sql_fetch($sql);
    
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($member ?: array('error' => '회원을 찾을 수 없습니다.'));
    exit;
}

/**
 * 회비 목록 조회
 */
function getMembershipList() {
    global $membership_table;
    
    try {
        $page = (int)$_POST['page'] ?: 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;
        
        $where = "WHERE 1=1";
        
        // 검색 조건
        if ($_POST['member_id']) {
            $member_id = sql_real_escape_string($_POST['member_id']);
            $where .= " AND mb_member_id LIKE '%{$member_id}%'";
        }
        
        if ($_POST['type']) {
            $type = sql_real_escape_string($_POST['type']);
            $where .= " AND mb_type = '{$type}'";
        }
        
        if ($_POST['status']) {
            $status = sql_real_escape_string($_POST['status']);
            $where .= " AND mb_status = '{$status}'";
        }
        
        if ($_POST['year']) {
            $year = sql_real_escape_string($_POST['year']);
            $where .= " AND mb_year = '{$year}'";
        }
        
        // 총 개수 조회
        $count_sql = "SELECT COUNT(*) as cnt FROM `{$membership_table}` $where";
        $count_result = sql_fetch($count_sql);
        $total_count = $count_result['cnt'];
        
        // 목록 조회
        $sql = "SELECT * FROM `{$membership_table}` 
                $where 
                ORDER BY mb_reg_date DESC 
                LIMIT $offset, $per_page";
        
        $result = sql_query($sql);
        $list = array();
        
        while ($row = sql_fetch_array($result)) {
            $list[] = formatMembershipData($row);
        }
        
        return array(
            'success' => true,
            'data' => $list,
            'total_count' => (int)$total_count,
            'page' => $page,
            'per_page' => $per_page
        );
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '데이터 조회 중 오류가 발생했습니다.');
    }
}

/**
 * 회비 데이터 포맷팅
 */
function formatMembershipData($row) {
    // 금액 포맷팅
    $row['mb_amount_formatted'] = number_format($row['mb_amount']);
    
    // 날짜 포맷팅
    $row['mb_reg_date_formatted'] = date('Y-m-d', strtotime($row['mb_reg_date']));
    
    // 유효기간 포맷팅
    if ($row['mb_end_date'] == '9999-12-31' || $row['mb_end_date'] == '0000-00-00') {
        $row['validity_period'] = $row['mb_start_date'] . '<br>~ 무제한';
    } else {
        $row['validity_period'] = $row['mb_start_date'] . '<br>~ ' . $row['mb_end_date'];
    }
    
    // 연체 체크
    $row['is_overdue'] = (strtotime($row['mb_due_date']) < time() && $row['mb_status'] == 'pending');
    
    // 상태 한글화
    $status_map = array(
        'pending' => '납부예정',
        'completed' => '완료',
        'cancelled' => '취소'
    );
    $row['mb_status_text'] = isset($status_map[$row['mb_status']]) ? $status_map[$row['mb_status']] : $row['mb_status'];
    
    // 회비종류 한글화
    $type_map = array(
        'annual' => '연회비',
        'entrance' => '입회비'
    );
    $row['mb_type_text'] = isset($type_map[$row['mb_type']]) ? $type_map[$row['mb_type']] : $row['mb_type'];
    
    return $row;
}

/**
 * 회비 상세 조회
 */
function getMembershipDetail() {
    global $membership_table;
    
    try {
        $mb_id = (int)$_POST['mb_id'];
        
        if ($mb_id <= 0) {
            return array('success' => false, 'message' => '잘못된 요청입니다.');
        }
        
        $sql = "SELECT * FROM `{$membership_table}` WHERE mb_id = '{$mb_id}'";
        $row = sql_fetch($sql);
        
        if ($row) {
            return array('success' => true, 'data' => $row);
        } else {
            return array('success' => false, 'message' => '데이터를 찾을 수 없습니다.');
        }
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '데이터 조회 중 오류가 발생했습니다.');
    }
}

/**
 * 회비 저장/수정
 */
function saveMembership() {
    global $g5, $membership_table;
    
    try {
        $mb_id = (int)$_POST['mb_id'];
        $mb_member_id = clean_xss_tags($_POST['mb_member_id']);
        $mb_type = clean_xss_tags($_POST['mb_type']);
        $mb_content = clean_xss_tags($_POST['mb_content']);
        $mb_amount = (int)$_POST['mb_amount'];
        $mb_year = (int)$_POST['mb_year'];
        $mb_start_date = $_POST['mb_start_date'] ?: '0000-00-00';
        $mb_end_date = $_POST['mb_end_date'] ?: '0000-00-00';
        $mb_due_date = $_POST['mb_due_date'] ?: '0000-00-00';
        $mb_status = clean_xss_tags($_POST['mb_status']);
        $mb_payment_method = clean_xss_tags($_POST['mb_payment_method']);
        $mb_payment_info = clean_xss_tags($_POST['mb_payment_info']);
        $mb_admin_memo = clean_xss_tags($_POST['mb_admin_memo']);
        
        // 필수 항목 체크
        if (!$mb_member_id || !$mb_type || !$mb_content || !$mb_amount) {
            return array('success' => false, 'message' => '필수 항목을 입력해주세요.');
        }
        
        // 회원 존재 여부 체크
        $member_sql = "SELECT mb_id FROM {$g5['member_table']} WHERE mb_id = '{$mb_member_id}'";
        if (!sql_fetch($member_sql)) {
            return array('success' => false, 'message' => '존재하지 않는 회원ID입니다.');
        }
        
        if ($mb_id > 0) {
            // 수정
            $sql = "UPDATE `{$membership_table}` SET 
                    mb_member_id = '{$mb_member_id}',
                    mb_type = '{$mb_type}',
                    mb_content = '{$mb_content}',
                    mb_amount = '{$mb_amount}',
                    mb_year = '{$mb_year}',
                    mb_start_date = '{$mb_start_date}',
                    mb_end_date = '{$mb_end_date}',
                    mb_due_date = '{$mb_due_date}',
                    mb_status = '{$mb_status}',
                    mb_payment_method = '{$mb_payment_method}',
                    mb_payment_info = '{$mb_payment_info}',
                    mb_admin_memo = '{$mb_admin_memo}',
                    mb_update_date = NOW()
                    WHERE mb_id = '{$mb_id}'";
        } else {
            // 신규 등록
            $sql = "INSERT INTO `{$membership_table}` 
                    (mb_member_id, mb_type, mb_content, mb_amount, mb_year, 
                     mb_start_date, mb_end_date, mb_due_date, mb_status, 
                     mb_payment_method, mb_payment_info, mb_admin_memo, mb_reg_date) 
                    VALUES 
                    ('{$mb_member_id}', '{$mb_type}', '{$mb_content}', '{$mb_amount}', '{$mb_year}',
                     '{$mb_start_date}', '{$mb_end_date}', '{$mb_due_date}', '{$mb_status}',
                     '{$mb_payment_method}', '{$mb_payment_info}', '{$mb_admin_memo}', NOW())";
        }
        
        if (sql_query($sql)) {
            return array('success' => true, 'message' => '저장되었습니다.');
        } else {
            return array('success' => false, 'message' => '저장 중 데이터베이스 오류가 발생했습니다.');
        }
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '저장 중 오류가 발생했습니다: ' . $e->getMessage());
    }
}

/**
 * 회비 삭제
 */
function deleteMembership() {
    global $membership_table;
    
    try {
        $mb_id = (int)$_POST['mb_id'];
        
        if ($mb_id <= 0) {
            return array('success' => false, 'message' => '잘못된 요청입니다.');
        }
        
        $sql = "DELETE FROM `{$membership_table}` WHERE mb_id = '{$mb_id}'";
        
        if (sql_query($sql)) {
            return array('success' => true, 'message' => '삭제되었습니다.');
        } else {
            return array('success' => false, 'message' => '삭제 중 오류가 발생했습니다.');
        }
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '삭제 중 오류가 발생했습니다: ' . $e->getMessage());
    }
}

/**
 * 회비 승인
 */
function approveMembership() {
    global $membership_table, $config;
    
    try {
        $mb_id = (int)$_POST['mb_id'];
        
        if ($mb_id <= 0) {
            return array('success' => false, 'message' => '잘못된 요청입니다.');
        }
        
        $sql = "UPDATE `{$membership_table}` SET 
                mb_status = 'completed', 
                mb_approve_date = NOW() 
                WHERE mb_id = '{$mb_id}'";
        
        if (sql_query($sql)) {

			$row=sql_fetch("select * from {$membership_table} where mb_id = '{$mb_id}'");
			$sql="update g5_member set mb_level='3' where mb_id = '{$row['mb_member_id']}' and mb_level<=2";
			sql_query($sql);


            return array('success' => true, 'message' => '승인되었습니다.');
        } else {
            return array('success' => false, 'message' => '승인 처리 중 오류가 발생했습니다.');
        }
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '승인 처리 중 오류가 발생했습니다: ' . $e->getMessage());
    }
}

/**
 * 회비 환불
 */
function refundMembership() {
    global $membership_table, $config;
    
    try {
        $mb_id = (int)$_POST['mb_id'];
        $refund_amount = (int)$_POST['refund_amount'];
        
        if ($mb_id <= 0 || $refund_amount <= 0) {
            return array('success' => false, 'message' => '잘못된 요청입니다.');
        }
        
        $sql = "UPDATE `{$membership_table}` SET 
                mb_is_refund = 1, 
                mb_refund_amount = '{$refund_amount}', 
                mb_refund_date = NOW(),
                mb_status = 'pending'
                WHERE mb_id = '{$mb_id}'";
        
        if (sql_query($sql)) {

			$row=sql_fetch("select * from {$membership_table} where mb_id = '{$mb_id}'");
			$paymentKey = $row['mb_paymentKey'];
			if($paymentKey){

				$url = "https://api.tosspayments.com/v1/payments/{$paymentKey}/cancel";
				$data = ['paymentKey' => $paymentKey];

				$secretKey = $config['cf_pg_secretkey'];///'live_sk_26DlbXAaV0dOLqyG6X1nVqY50Q9R'; 
				$credential = base64_encode($secretKey . ':');


				$curl = curl_init();

				curl_setopt_array($curl, [
				  CURLOPT_URL => $url,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => json_encode([
					'cancelReason' => '관리자 환불'
				  ]),
				  CURLOPT_HTTPHEADER => [
					"Authorization: Basic ".$credential,
					"Content-Type: application/json"
				  ],
				]);

				$response = curl_exec($curl);
				$err = curl_error($curl);

			}
			$sql="update g5_member set mb_level='2' where mb_id = '{$row['mb_member_id']}'";
			sql_query($sql);

            return array('success' => true, 'message' => '환불 처리되었습니다.', 'debug'=>$sql);
        } else {
            return array('success' => false, 'message' => '환불 처리 중 오류가 발생했습니다.');
        }
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '환불 처리 중 오류가 발생했습니다: ' . $e->getMessage());
    }
}

/**
 * 통계 조회
 */
function getStatistics() {
    global $membership_table;
    
    try {
        // 총 회비 금액
        $total_amount_sql = "SELECT SUM(mb_amount) as total_amount FROM `{$membership_table}` WHERE mb_status = 'completed'";
        $row = sql_fetch($total_amount_sql);
        $total_amount = $row['total_amount'] ?: 0;
        
        // 완료 건수
        $completed_sql = "SELECT COUNT(*) as cnt FROM `{$membership_table}` WHERE mb_status = 'completed'";
        $row = sql_fetch($completed_sql);
        $completed_count = $row['cnt'];
        
        // 미납 건수
        $pending_sql = "SELECT COUNT(*) as cnt FROM `{$membership_table}` WHERE mb_status = 'pending'";
        $row = sql_fetch($pending_sql);
        $pending_count = $row['cnt'];
        
        // 연체 건수
        $overdue_sql = "SELECT COUNT(*) as cnt FROM `{$membership_table}` 
                        WHERE mb_status = 'pending' AND mb_due_date < CURDATE()";
        $row = sql_fetch($overdue_sql);
        $overdue_count = $row['cnt'];
        
        return array(
            'success' => true,
            'total_amount' => number_format($total_amount),
            'completed_count' => $completed_count,
            'pending_count' => $pending_count,
            'overdue_count' => $overdue_count
        );
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '통계 조회 중 오류가 발생했습니다: ' . $e->getMessage());
    }
}

/**
 * 엑셀 다운로드
 */
function exportToExcel() {
    global $membership_table;
    
    try {
        $sql = "SELECT 
                    mb_member_id as '회원ID',
                    CASE mb_type 
                        WHEN 'annual' THEN '연회비'
                        WHEN 'entrance' THEN '입회비'
                        ELSE mb_type
                    END as '회비종류',
                    mb_content as '내용',
                    mb_amount as '금액',
                    CONCAT(mb_start_date, ' ~ ', 
                        CASE WHEN mb_end_date = '9999-12-31' THEN '무제한' ELSE mb_end_date END
                    ) as '유효기간',
                    mb_due_date as '납부예정일',
                    CASE mb_status
                        WHEN 'pending' THEN '납부예정'
                        WHEN 'completed' THEN '완료'
                        WHEN 'cancelled' THEN '취소'
                        ELSE mb_status
                    END as '상태',
                    mb_reg_date as '등록일',
                    mb_payment_method as '결제방법',
                    mb_admin_memo as '관리자메모'
                FROM `{$membership_table}` 
                ORDER BY mb_reg_date DESC";
        
        $result = sql_query($sql);
        
        // CSV 헤더 설정
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="membership_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // UTF-8 BOM 추가 (엑셀 한글 깨짐 방지)
        echo "\xEF\xBB\xBF";
        
        $output = fopen('php://output', 'w');
        
        // 헤더 출력
        $first_row = sql_fetch_array($result);
        if ($first_row) {
            fputcsv($output, array_keys($first_row));
            fputcsv($output, $first_row);
            
            // 나머지 데이터 출력
            while ($row = sql_fetch_array($result)) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
        
    } catch (Exception $e) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('success' => false, 'message' => '엑셀 다운로드 중 오류가 발생했습니다: ' . $e->getMessage()));
        exit;
    }
}

/**
 * 일괄 승인
 */
function bulkApprove() {
    global $membership_table;
    
    try {
        $ids = $_POST['ids'];
        if (!is_array($ids) || empty($ids)) {
            return array('success' => false, 'message' => '처리할 항목이 없습니다.');
        }
        
        $id_list = implode(',', array_map('intval', $ids));
        $sql = "UPDATE `{$membership_table}` SET 
                mb_status = 'completed', 
                mb_approve_date = NOW() 
                WHERE mb_id IN ({$id_list})";
        
        if (sql_query($sql)) {
            return array('success' => true, 'message' => count($ids) . '건이 승인되었습니다.');
        } else {
            return array('success' => false, 'message' => '일괄 승인 중 오류가 발생했습니다.');
        }
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '일괄 승인 중 오류가 발생했습니다: ' . $e->getMessage());
    }
}

/**
 * 일괄 삭제
 */
function bulkDelete() {
    global $membership_table;
    
    try {
        $ids = $_POST['ids'];
        if (!is_array($ids) || empty($ids)) {
            return array('success' => false, 'message' => '처리할 항목이 없습니다.');
        }
        
        $id_list = implode(',', array_map('intval', $ids));
        $sql = "DELETE FROM `{$membership_table}` WHERE mb_id IN ({$id_list})";
        
        if (sql_query($sql)) {
            return array('success' => true, 'message' => count($ids) . '건이 삭제되었습니다.');
        } else {
            return array('success' => false, 'message' => '일괄 삭제 중 오류가 발생했습니다.');
        }
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '일괄 삭제 중 오류가 발생했습니다: ' . $e->getMessage());
    }
}

/**
 * 만료 예정 회비 체크 (자동 생성 포함)
 */
function checkExpiringMemberships() {
    global $membership_table, $g5;
    
    try {
        // 한달 후 날짜 계산
        $one_month_later = date('Y-m-d', strtotime('+1 month'));
        $current_year = date('Y');
        $next_year = $current_year + 1;
        
        // 만료 예정인 연회비 조회 (완료 상태만)
        $sql = "SELECT DISTINCT m.mb_member_id, m.mb_id, m.mb_amount, m.mb_end_date, m.mb_year,
                       mem.mb_name, mem.mb_email
                FROM `{$membership_table}` m
                JOIN `{$g5['member_table']}` mem ON m.mb_member_id = mem.mb_id
                WHERE m.mb_auto_create='0' and m.mb_type = 'annual' 
                AND m.mb_status = 'completed'
                AND m.mb_end_date <= '{$one_month_later}'
                AND m.mb_end_date >= CURDATE()
                AND NOT EXISTS (
                    SELECT 1 FROM `{$membership_table}` m2 
                    WHERE m2.mb_member_id = m.mb_member_id 
                    AND m2.mb_type = 'annual' 
                    AND m2.mb_year = '{$next_year}'
                )
                ORDER BY m.mb_end_date ASC";
        
        $result = sql_query($sql);
        $expiring_list = array();
        $auto_generated_count = 0;
        
        while ($row = sql_fetch_array($result)) {
            $row['days_until_expiry'] = ceil((strtotime($row['mb_end_date']) - time()) / (60 * 60 * 24));
            
            // 자동 회비 생성 실행
            $auto_result = autoGenerateSingleMembership($row);
            if ($auto_result['success']) {
                $row['auto_generated'] = true;
                $auto_generated_count++;
                logMembershipAction('AUTO_GENERATE', "회원 {$row['mb_member_id']}의 {$next_year}년 회비 자동 생성");
            } else {
                $row['auto_generated'] = false;
                $row['auto_error'] = $auto_result['message'];
            }
            
            $expiring_list[] = $row;
        }
        
        return array(
            'success' => true,
            'data' => $expiring_list,
            'count' => count($expiring_list),
            'auto_generated_count' => $auto_generated_count,
            'next_year' => $next_year,
            'message' => $auto_generated_count > 0 ? "{$auto_generated_count}건의 회비가 자동 생성되었습니다." : '',
			'debug' => $auto_result
        );
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '만료 예정 회비 체크 중 오류가 발생했습니다: ' . $e->getMessage());
    }
}

/**
 * 단일 회원 회비 자동 생성
 */
function autoGenerateSingleMembership($member_data) {
    global $membership_table;
    
    try {
        $member_id = $member_data['mb_member_id'];
        $current_amount = $member_data['mb_amount'];
        $current_end_date = $member_data['mb_end_date'];
        $current_year = $member_data['mb_year'];
        $next_year = $current_year + 1;
		$mb_id = $member_data['mb_id'];
        
        // 유효기간 계산: 만료일 다음날부터 1년
        $new_start_date = date('Y-m-d', strtotime($current_end_date . ' +1 day'));
        $new_end_date = date('Y-m-d', strtotime($new_start_date . ' +1 year -1 day'));
        
        // 납부예정일: 현재 회비의 만료일
        $new_due_date = $current_end_date;
        
        // 이미 다음년도 회비가 있는지 재확인
        $check_sql = "SELECT mb_id FROM `{$membership_table}` 
                     WHERE mb_member_id = '{$member_id}' 
                     AND mb_type = 'annual' 
                     AND mb_year = '{$next_year}'";
        
        if (sql_fetch($check_sql)) {
			sql_query("update `{$membership_table}` set mb_auto_create='1' where mb_id='{$mb_id}'");
            return array('success' => false, 'message' => '이미 다음년도 회비가 존재합니다.');
        }
        
        // 새 회비 생성
        $insert_sql = "INSERT INTO `{$membership_table}` 
                      (mb_member_id, mb_type, mb_content, mb_amount, mb_year,
                       mb_start_date, mb_end_date, mb_due_date, mb_status,
                       mb_payment_method, mb_admin_memo, mb_reg_date)
                      VALUES 
                      ('{$member_id}', 'annual', '{$next_year}년 연회비', '{$current_amount}', '{$next_year}',
                       '{$new_start_date}', '{$new_end_date}', '{$new_due_date}', 'pending',
                       '', '자동 생성됨 (만료 1개월 전 - 시스템)', NOW())";
        
        if (sql_query($insert_sql)) {
            return array(
                'success' => true, 
                'message' => '자동 생성 완료',
                'data' => array(
                    'member_id' => $member_id,
                    'amount' => $current_amount,
                    'start_date' => $new_start_date,
                    'end_date' => $new_end_date,
                    'due_date' => $new_due_date
                ),
				'debug' => $insert_sql
            );
        } else {
            return array('success' => false, 'message' => '데이터베이스 오류');
        }
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '자동 생성 중 오류: ' . $e->getMessage());
    }
}

/**
 * 회비 자동 생성 미리보기
 */
function previewGeneration() {
    global $membership_table, $g5;
    
    try {
        $member_ids = $_POST['member_ids'];
        if (!is_array($member_ids) || empty($member_ids)) {
            return array('success' => false, 'message' => '선택된 회원이 없습니다.');
        }
        
        $current_year = date('Y');
        $next_year = $current_year + 1;
        $preview_list = array();
        
        foreach ($member_ids as $member_id) {
            $member_id = clean_xss_tags($member_id);
            
            // 현재 연회비 정보 조회
            $sql = "SELECT m.*, mem.mb_name 
                    FROM `{$membership_table}` m
                    JOIN `{$g5['member_table']}` mem ON m.mb_member_id = mem.mb_id
                    WHERE m.mb_member_id = '{$member_id}' 
                    AND m.mb_type = 'annual' 
                    AND m.mb_year = '{$current_year}'
                    AND m.mb_status = 'completed'";
            
            $current_membership = sql_fetch($sql);
            
            if ($current_membership) {
                $preview_item = array(
                    'mb_member_id' => $member_id,
                    'mb_name' => $current_membership['mb_name'],
                    'current_amount' => $current_membership['mb_amount'],
                    'new_content' => $next_year . '년 연회비',
                    'new_amount' => $current_membership['mb_amount'], // 동일 금액
                    'new_start_date' => $next_year . '-01-01',
                    'new_end_date' => $next_year . '-12-31',
                    'new_due_date' => $next_year . '-03-31', // 기본 3월 말
                    'current_end_date' => $current_membership['mb_end_date']
                );
                
                $preview_list[] = $preview_item;
            }
        }
        
        return array(
            'success' => true,
            'data' => $preview_list,
            'next_year' => $next_year
        );
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '미리보기 생성 중 오류가 발생했습니다: ' . $e->getMessage());
    }
}

/**
 * 회비 자동 생성 실행
 */
function autoGenerateMemberships() {
    global $membership_table, $g5;
    
    try {
        $member_ids = $_POST['member_ids'];
        $custom_amount = (int)$_POST['custom_amount'];
        $custom_due_date = $_POST['custom_due_date'];
        
        if (!is_array($member_ids) || empty($member_ids)) {
            return array('success' => false, 'message' => '선택된 회원이 없습니다.');
        }
        
        $current_year = date('Y');
        $next_year = $current_year + 1;
        $success_count = 0;
        $error_list = array();
        
        foreach ($member_ids as $member_id) {
            $member_id = clean_xss_tags($member_id);
            
            // 현재 연회비 정보 조회
            $sql = "SELECT * FROM `{$membership_table}` 
                    WHERE mb_member_id = '{$member_id}' 
                    AND mb_type = 'annual' 
                    AND mb_year = '{$current_year}'
                    AND mb_status = 'completed'";
            
            $current_membership = sql_fetch($sql);
            
            if ($current_membership) {
                // 이미 다음년도 회비가 있는지 체크
                $check_sql = "SELECT mb_id FROM `{$membership_table}` 
                             WHERE mb_member_id = '{$member_id}' 
                             AND mb_type = 'annual' 
                             AND mb_year = '{$next_year}'";
                
                if (!sql_fetch($check_sql)) {
                    $amount = $custom_amount > 0 ? $custom_amount : $current_membership['mb_amount'];
                    $due_date = $custom_due_date ?: ($next_year . '-03-31');
                    
                    // 새 회비 생성
                    $insert_sql = "INSERT INTO `{$membership_table}` 
                                  (mb_member_id, mb_type, mb_content, mb_amount, mb_year,
                                   mb_start_date, mb_end_date, mb_due_date, mb_status,
                                   mb_payment_method, mb_admin_memo, mb_reg_date)
                                  VALUES 
                                  ('{$member_id}', 'annual', '{$next_year}년 연회비', '{$amount}', '{$next_year}',
                                   '{$next_year}-01-01', '{$next_year}-12-31', '{$due_date}', 'pending',
                                   '', '자동 생성됨 (만료 1개월 전)', NOW())";
                    
                    if (sql_query($insert_sql)) {
                        $success_count++;
                    } else {
                        $error_list[] = $member_id . ': 데이터베이스 오류';
                    }
                } else {
                    $error_list[] = $member_id . ': 이미 다음년도 회비 존재';
                }
            } else {
                $error_list[] = $member_id . ': 현재년도 완료된 회비 없음';
            }
        }
        
        return array(
            'success' => true,
            'success_count' => $success_count,
            'error_count' => count($error_list),
            'error_list' => $error_list,
            'message' => "{$success_count}건의 회비가 생성되었습니다."
        );
        
    } catch (Exception $e) {
        return array('success' => false, 'message' => '자동 생성 중 오류가 발생했습니다: ' . $e->getMessage());
    }
}

/**
 * 로그 기록 함수
 */
function logMembershipAction($action, $details = '') {
    $log_file = G5_DATA_PATH . '/log/membership.log';
    $log_dir = dirname($log_file);
    
    // 로그 디렉토리가 없으면 생성
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    
    $log_entry = date('Y-m-d H:i:s') . " [{$_SESSION['ss_mb_id']}] {$action}";
    if ($details) {
        $log_entry .= " - {$details}";
    }
    $log_entry .= "\n";
    
    @file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

/**
 * 에러 응답 함수
 */
function errorResponse($message, $code = 400) {
    http_response_code($code);
    echo json_encode(array(
        'success' => false,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ));
    exit;
}

/**
 * 성공 응답 함수
 */
function successResponse($data = array(), $message = 'Success') {
    echo json_encode(array_merge(array(
        'success' => true,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ), $data));
    exit;
}

// 잘못된 접근 처리
if (!$_POST['action'] && !$_GET['action'] && !$_GET['get_member']) {
    errorResponse('잘못된 접근입니다.', 404);
}
?>