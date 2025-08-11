<?php
include "../../../../common.php";

$paymentKey = $_GET['paymentKey'];
$orderId = $_GET['orderId'];
$amount = $_GET['amount'];

$sql = " select * from {$g5['g5_shop_order_data_table']} where od_id = '$orderId' ";
$row = sql_fetch($sql);

$data = isset($row['dt_data']) ? unserialize(base64_decode($row['dt_data'])) : array();
// 금액 검증
$selectedItems=$data;
$calculatedAmount=$data['amount'];

// 결제 금액과 계산된 금액 비교
if (false && $calculatedAmount != $amount) {
    // 주문 데이터 삭제
    $sql = "delete from {$g5['g5_shop_order_data_table']} where od_id = '$orderId' ";
    sql_query($sql);

	alert("결제 금액이 일치하지 않습니다.",G5_THEME_URL.'/subpage/event/event2_07.php');
    
    //alert("결제 금액이 일치하지 않습니다.\n계산된 금액: " . number_format($calculatedAmount) . "원\n결제 금액: " . number_format($amount) . "원", G5_THEME_URL.'/subpage/mypage/mypage_2.php');
    exit;
}

//이상없을때 결제 승인
$url = 'https://api.tosspayments.com/v1/payments/confirm';
$data = ['paymentKey' => $paymentKey, 'orderId' => $orderId, 'amount' => $amount];

$secretKey = 'test_sk_zXLkKEypNArWmo50nX3lmeaxYG5R'; 
$credential = base64_encode($secretKey . ':');

$curlHandle = curl_init($url);
curl_setopt_array($curlHandle, [
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_HTTPHEADER => [
        'Authorization: Basic ' . $credential,
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($curlHandle);
$httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
$isSuccess = $httpCode == 200;
$responseJson = json_decode($response);

$responseJson = json_decode(json_encode($responseJson), true);

if ($isSuccess) {
/**회비 납부 처리, 결제 데이터 저장***************/

    processPaymentSuccess($data, $responseJson, $selectedItems);

	// 주문번호제거
	set_session('ss_order_id', '');

    echo "<script>
        alert('결제가 성공적으로 완료되었습니다.');
        location.href = '" . G5_THEME_URL . "/subpage/event/event2_08.php';
    </script>";

/*****************/
}else{ //결제 실패
	$sql = "delete from {$g5['g5_shop_order_data_table']} where od_id = '$orderId' ";
	sql_query($sql);

    $errorMessage = isset($responseJson['message']) ? $responseJson['message'] : '결제 처리 중 오류가 발생했습니다.';

    alert("결제 실패: " . $errorMessage, G5_THEME_URL.'/subpage/event/event2_07.php');
}


/**
 * 결제 성공 처리 함수
 */
function processPaymentSuccess($orderData, $paymentResponse, $selectedItems) {
    global $g5, $is_member, $member;
    
    try {
        // 트랜잭션 시작
        sql_query("START TRANSACTION");
        
		// 필수 입력 검증
		if (empty($selectedItems['member_class'])) {
			throw new Exception("회원구분을 선택해주세요.");
		}
		if (empty($selectedItems['name_kor'])) {
			throw new Exception("성명(국문)을 입력해주세요.");
		}
		if (empty($selectedItems['name_eng'])) {
			throw new Exception("성명(영문)을 입력해주세요.");
		}
		if (empty($selectedItems['email'])) {
			throw new Exception("이메일을 입력해주세요.");
		}
		if (!$is_member && empty($selectedItems['password'])) {
			throw new Exception("비밀번호를 입력해주세요.");
		}
		
		// 데이터베이스 저장
		$sql = "INSERT INTO g5_conference_registration SET
				cr_sy_id = '".$selectedItems['sy_id']."',
				cr_reg_type = '".$selectedItems['reg_type']."',
				cr_member_class = '".($selectedItems['member_class'])."',
				cr_title = '".($selectedItems['title'])."',
				cr_name_kor = '".($selectedItems['name_kor'])."',
				cr_name_eng = '".($selectedItems['name_eng'])."',
				cr_license_number = '".($selectedItems['license_number'])."',
				cr_has_no_license = '".(isset($selectedItems['no_license']) ? 1 : 0)."',
				cr_hospital_name = '".($selectedItems['hospital_name'])."',
				cr_department = '".($selectedItems['department'])."',
				cr_work_zip = '".($selectedItems['work_zip'])."',
				cr_work_addr1 = '".($selectedItems['work_addr1'])."',
				cr_work_addr2 = '".($selectedItems['work_addr2'])."',
				cr_work_phone1 = '".($selectedItems['work_phone1'])."',
				cr_work_phone2 = '".($selectedItems['work_phone2'])."',
				cr_work_phone3 = '".($selectedItems['work_phone3'])."',
				cr_mobile_carrier = '".($selectedItems['mobile_carrier'])."',
				cr_mobile1 = '".($selectedItems['mobile1'])."',
				cr_mobile2 = '".($selectedItems['mobile2'])."',
				cr_email = '".($selectedItems['email'])."',
				cr_password = '".($is_member ? '' : password_hash($selectedItems['password'], PASSWORD_DEFAULT))."',
				cr_mb_id = '".($is_member ? $member['mb_id'] : '')."',
				cr_annual_fee_status = '',
				cr_payment_method = '".($selectedItems['payment_method'])."',
				cr_registration_fee = '".$selectedItems['amount']."',
				cr_payment_status = 'completed',
				cr_payment_date = NOW(),
				cr_od_id = '".$orderData['orderId']."',
				cr_reg_date = NOW()";
		
		$result = sql_query($sql);
        
        // 결제 기록 저장
        savePaymentRecord($orderData, $paymentResponse, $selectedItems);
        
        // 주문 데이터 삭제 (처리 완료)
        $orderId = $selectedItems['orderId'];
        sql_query("DELETE FROM {$g5['g5_shop_order_data_table']} WHERE od_id = '$orderId'");
        
        // 트랜잭션 커밋
        sql_query("COMMIT");
        // 결제 완료 로그 기록
        $logMessage = "결제 완료 - 주문번호: {$selectedItems['orderId']}, 금액: " . number_format($selectedItems['amount']) . "원, 항목수: " . count($selectedItems);
        //logPaymentAction('PAYMENT_SUCCESS', $logMessage);
        
    } catch (Exception $e) {
        // 트랜잭션 롤백
        sql_query("ROLLBACK");
        
        // 에러 로그 기록
        //logPaymentAction('PAYMENT_ERROR', "결제 처리 실패: " . $e->getMessage());
        
        // 에러 처리
        alert("결제 처리 중 오류가 발생했습니다: " . $e->getMessage(), G5_THEME_URL.'/subpage/event/event2_07.php');
        exit;
    }
}

/**
 * 연회비 다년 결제시 기간 계산
 */
function calculateExtendedPeriod($currentEndDate, $months) {
    // 현재 만료일에서 추가 개월수만큼 연장
    $baseDate = ($currentEndDate == '0000-00-00' || $currentEndDate < date('Y-m-d')) ? date('Y-m-d') : $currentEndDate;
    
    // 12개월 = 1년, 24개월 = 2년, 36개월 = 3년
    $years = floor($months / 12);
    $remainingMonths = $months % 12;
    
    $newEndDate = date('Y-m-d', strtotime($baseDate . " +{$years} years +{$remainingMonths} months"));
    
    return $newEndDate;
}

/**
 * 결제 정보 생성
 */
function generatePaymentInfo($paymentResponse, $amount, $discount) {
    $info = array();
    
    // 결제 기본 정보
    if (isset($paymentResponse['paymentKey'])) {
        $info[] = "결제키: " . $paymentResponse['paymentKey'];
    }
    
    if (isset($paymentResponse['method'])) {
        $info[] = "결제수단: " . $paymentResponse['method'];
    }
    
    if (isset($paymentResponse['card']['company'])) {
        $info[] = "카드사: " . $paymentResponse['card']['company'];
    }
    
    if (isset($paymentResponse['card']['number'])) {
        $info[] = "카드번호: " . $paymentResponse['card']['number'];
    }
    
    if (isset($paymentResponse['virtualAccount'])) {
        $va = $paymentResponse['virtualAccount'];
        $info[] = "은행: " . $va['bankCode'];
        $info[] = "계좌번호: " . $va['accountNumber'];
    }
    
    // 할인 정보
    if ($discount > 0) {
        $info[] = "할인금액: " . number_format($discount) . "원";
    }
    
    $info[] = "결제금액: " . number_format($amount) . "원";
    $info[] = "결제일시: " . date('Y-m-d H:i:s');
    
    return implode(", ", $info);
}

/**
 * 결제 기록 저장
 */
function savePaymentRecord($orderData, $paymentResponse, $selectedItems) {
    global $g5;
    
   
    $orderId = $orderData['orderId'];
    $customerName = $orderData['customerName'];
    $totalAmount = $orderData['amount'];
    $paymentMethod = isset($paymentResponse['method']) ? $paymentResponse['method'] : $orderData['paymentType'];
    $paymentKey = isset($paymentResponse['paymentKey']) ? $paymentResponse['paymentKey'] : '';
    
    // 상세 정보 JSON으로 저장
    $detailInfo = array(
        'order_data' => $orderData,
        'payment_response' => $paymentResponse,
        'selected_items' => $selectedItems,
        'processed_at' => date('Y-m-d H:i:s')
    );
    
    $detailJson = addslashes(json_encode($detailInfo, JSON_UNESCAPED_UNICODE));
    
    $sql = "INSERT INTO g5_membership_payment_log 
            (mp_order_id, mp_customer_name, mp_amount, mp_payment_method, mp_payment_key, 
             mp_status, mp_detail_info, mp_reg_date) 
            VALUES 
            ('$orderId', '$customerName', '$totalAmount', '$paymentMethod', '$paymentKey', 
             'completed', '$detailJson', NOW())";
    
    if (!sql_query($sql)) {
        throw new Exception("결제 기록 저장 실패");
    }
}

/**
 * 결제 액션 로그 기록
 */
function logPaymentAction($action, $details = '') {
    global $member;
    
    $log_file = G5_DATA_PATH . '/log/membership_payment.log';
    $log_dir = dirname($log_file);
    
    // 로그 디렉토리가 없으면 생성
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    
    $member_id = isset($member['mb_id']) ? $member['mb_id'] : 'anonymous';
    $log_entry = date('Y-m-d H:i:s') . " [{$member_id}] {$action}";
    if ($details) {
        $log_entry .= " - {$details}";
    }
    $log_entry .= "\n";
    
    @file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}
?>
