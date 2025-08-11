<?php
include "../../../../common.php";

$selectedItems = json_decode(stripslashes($_POST['selectedItems']), true);

    try {
        // 트랜잭션 시작
        sql_query("START TRANSACTION");
        
        // 각 선택된 회비 항목 처리
        foreach ($selectedItems as $item) {
            $membershipId = $item['id'];
            $paidAmount = $item['amount'];
            $months = $item['months'];
            $discount = $item['discount'];
            
            // 회비 정보 조회
            $sql = "SELECT * FROM g5_membership WHERE mb_id = '$membershipId'";
            $membership = sql_fetch($sql);
            
            if (!$membership) {
                alert("회비 정보를 찾을 수 없습니다. ID: $membershipId");
            }

			if($membership['mb_payment_method']){
				alert("이미 신청한 회비 내역입니다.");
			}
            
            // 결제 정보 생성
            //$paymentInfo = generatePaymentInfo($paymentResponse, $paidAmount, $discount);
            
            // 연회비 다년 선택시 기간 수정
            if ($membership['mb_type'] == 'annual' && $months > 1) {
                $newEndDate = calculateExtendedPeriod($membership['mb_end_date'], $months);
                
                // 기간 연장 업데이트
                $updateSql = "UPDATE g5_membership SET 
                    mb_end_date = '$newEndDate',
                    mb_amount = '$paidAmount',
                    mb_payment_method = 'bank',
                    mb_payment_info = '$paymentInfo' 
                    WHERE mb_id = '$membershipId'";
            } else {
                // 일반 결제 완료 처리
                $updateSql = "UPDATE g5_membership SET 
                    mb_status = 'completed',
                    mb_amount = '$paidAmount',
                    mb_payment_method = 'bank',
                    mb_payment_info = '$paymentInfo' 
                    WHERE mb_id = '$membershipId'";
            }

            if (!sql_query($updateSql)) {
                alert("회비 상태 업데이트 실패. ID: $membershipId");
            }
        }
        
        // 결제 기록 저장
        //savePaymentRecord($orderData, $paymentResponse, $selectedItems);
        
        // 주문 데이터 삭제 (처리 완료)
        $orderId = $orderData['orderId'];
        sql_query("DELETE FROM {$g5['g5_shop_order_data_table']} WHERE od_id = '$orderId'");
        
        // 트랜잭션 커밋
        sql_query("COMMIT");
        // 결제 완료 로그 기록
        $logMessage = "결제 완료 - 주문번호: {$orderData['orderId']}, 금액: " . number_format($orderData['amount']) . "원, 항목수: " . count($selectedItems);
        //logPaymentAction('PAYMENT_SUCCESS', $logMessage);

		// 주문번호제거
		set_session('ss_order_id', '');

		echo "<script>
			alert('결제신청이 성공적으로 완료되었습니다.');
			location.href = '" . G5_THEME_URL . "/subpage/mypage/mypage_2.php';
		</script>";
        
    } catch (Exception $e) {
        // 트랜잭션 롤백
        sql_query("ROLLBACK");
        
        // 에러 로그 기록
        //logPaymentAction('PAYMENT_ERROR', "결제 처리 실패: " . $e->getMessage());
        
        // 에러 처리
        alert("결제 처리 중 오류가 발생했습니다: " . $e->getMessage(), G5_THEME_URL.'/subpage/mypage/mypage_2.php');
        exit;
    }


function calculateExtendedPeriod($currentEndDate, $months) {
    // 현재 만료일에서 추가 개월수만큼 연장
    $baseDate = ($currentEndDate == '0000-00-00' || $currentEndDate < date('Y-m-d')) ? date('Y-m-d') : $currentEndDate;
    
    // 12개월 = 1년, 24개월 = 2년, 36개월 = 3년
    $years = $months-1;
    $remainingMonths = 0;//$months % 12;
    
    $newEndDate = date('Y-m-d', strtotime($baseDate . " +{$years} years +{$remainingMonths} months"));
    
    return $newEndDate;
}

?>