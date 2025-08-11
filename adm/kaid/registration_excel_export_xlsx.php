<?php
$sub_menu = "600210";
require_once './_common.php';

// PHPExcel 라이브러리 로드
require_once G5_LIB_PATH . '/PHPExcel.php';

try {
    // 파라미터 받기
    $sy_id = isset($_GET['sy_id']) ? (int)$_GET['sy_id'] : 0;
    $type = isset($_GET['type']) ? $_GET['type'] : 'conference';
    $sfl = isset($_GET['sfl']) ? $_GET['sfl'] : '';
    $stx = isset($_GET['stx']) ? $_GET['stx'] : '';
    $member_type = isset($_GET['member_type']) ? $_GET['member_type'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    
    // 테이블 설정
    $registration_table = 'g5_conference_registration';
    
    // 기본 SQL 구성
    $sql_common = "FROM {$registration_table} cr 
                   LEFT JOIN g5_conference c ON cr.cr_sy_id = c.sy_id 
                   LEFT JOIN g5_symposium s ON cr.cr_sy_id = s.sy_id 
                   LEFT JOIN g5_member m ON (cr.cr_mb_id = m.mb_id AND cr.cr_mb_id IS NOT NULL AND cr.cr_mb_id != '')";
    
    $sql_search = "WHERE 1=1";
    
    if ($sy_id > 0) {
        $sql_search .= " AND cr.cr_sy_id = '{$sy_id}'";
    }
    
    if ($member_type) {
        if ($member_type == 'member') {
            $sql_search .= " AND cr.cr_mb_id IS NOT NULL AND cr.cr_mb_id != ''";
        } else if ($member_type == 'non_member') {
            $sql_search .= " AND (cr.cr_mb_id IS NULL OR cr.cr_mb_id = '')";
        }
    }
    
    if ($status) {
        $sql_search .= " AND cr.cr_status = '{$status}'";
    }
    
    if ($stx && $sfl) {
        $sql_search .= " AND {$sfl} LIKE '%{$stx}%'";
    }
    
    // 먼저 간단한 테스트 쿼리로 데이터 존재 여부 확인
    $test_sql = "SELECT COUNT(*) as total_count FROM {$registration_table}";
    $test_result = sql_query($test_sql);
    $test_row = sql_fetch_array($test_result);
    $total_records = $test_row['total_count'];
    
    // 데이터가 없으면 빈 엑셀 파일 생성
    if ($total_records == 0) {
        // 빈 데이터 메시지를 위한 플래그
        $no_data = true;
        $result = false;
    } else {
        // 데이터 조회 SQL (실제 테이블 구조에 맞게 수정)
        $sql = "SELECT 
                    cr.cr_id,
                    CASE 
                        WHEN cr.cr_mb_id IS NOT NULL AND cr.cr_mb_id != '' THEN '회원'
                        ELSE '비회원'
                    END as member_type,
                    COALESCE(m.mb_name, cr.cr_name_kor, cr.cr_nonemb_name, '이름 없음') as name,
                    COALESCE(cr.cr_license_number, '') as license_no,
                    COALESCE(cr.cr_hospital_name, '소속 없음') as affiliation,
                    COALESCE(cr.cr_registration_fee, 0) as payment_amount,
                    CONCAT(COALESCE(cr.cr_mobile1, ''), COALESCE(cr.cr_mobile2, '')) as phone,
                    COALESCE(cr.cr_email, '') as email,
                    '대한민국' as nationality,
                    COALESCE(cr.cr_payment_method, '') as payment_method,
                    COALESCE(DATE_FORMAT(cr.cr_payment_date, '%Y-%m-%d %H:%i:%s'), '') as payment_time,
                    CASE cr.cr_payment_status
                        WHEN 'Y' THEN '결제완료'
                        WHEN 'N' THEN '미결제'
                        WHEN 'C' THEN '결제취소'
                        ELSE '미결제'
                    END as payment_status,
                    DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') as output_time,
                    COALESCE(cr.cr_admin_memo, '') as notes,
                    CASE cr.cr_reg_type
                        WHEN 'early' THEN '조기등록'
                        WHEN 'regular' THEN '정규등록'
                        WHEN 'onsite' THEN '현장등록'
                        ELSE '일반'
                    END as registration_type,
                    COALESCE(cr.cr_member_class, '일반') as registration_form,
                    COALESCE(s.sy_title, '') as event_name,
                    COALESCE(DATE_FORMAT(cr.cr_reg_date, '%Y-%m-%d %H:%i:%s'), '') as application_date
                {$sql_common} {$sql_search}
                ORDER BY cr.cr_id DESC";
        
        $result = sql_query($sql);
        $no_data = false;
    }
    
    // PHPExcel 객체 생성
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()
        ->setCreator("KAID")
        ->setLastModifiedBy("KAID")
        ->setTitle("등록자 목록")
        ->setSubject("등록자 목록")
        ->setDescription("학술집담회 등록자 목록")
        ->setKeywords("등록자 목록")
        ->setCategory("등록자 관리");
    
    // 활성 시트 설정
    $objPHPExcel->setActiveSheetIndex(0);
    $activeSheet = $objPHPExcel->getActiveSheet();
    $activeSheet->setTitle('등록자 목록');
    
    // 헤더 설정
    $headers = array(
        'A1' => 'ID',
        'B1' => '회원 유형',
        'C1' => '이름',
        'D1' => '면허 번호',
        'E1' => '소속',
        'F1' => '결제 금액',
        'G1' => '전화번호',
        'H1' => '이메일',
        'I1' => '국적',
        'J1' => '결제 방법',
        'K1' => '결제 시간',
        'L1' => '결제 상태',
        'M1' => '출력 시간',
        'N1' => '비고',
        'O1' => '등록 구분',
        'P1' => '등록 형태',
        'Q1' => '행사명',
        'R1' => '신청일시'
    );
    
    // 헤더 스타일 설정
    $headerStyle = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => 'FFFFFF')
        ),
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '366092')
        ),
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );
    
    // 헤더 입력 및 스타일 적용
    foreach ($headers as $cell => $value) {
        $activeSheet->setCellValue($cell, $value);
        $activeSheet->getStyle($cell)->applyFromArray($headerStyle);
    }
    
    // 데이터 입력
    $row = 2;
    $data_count = 0;
    
    if ($no_data || !$result) {
        // 데이터가 없을 때 메시지 표시
        $activeSheet->setCellValue('A' . $row, '데이터가 없습니다.');
        $activeSheet->mergeCells('A' . $row . ':R' . $row);
        $activeSheet->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $row++;
    } else {
        // 데이터 처리
        while ($data = sql_fetch_array($result)) {
            $activeSheet->setCellValue('A' . $row, $data['cr_id']);
            $activeSheet->setCellValue('B' . $row, $data['member_type']);
            $activeSheet->setCellValue('C' . $row, $data['name']);
            $activeSheet->setCellValue('D' . $row, $data['license_no']);
            $activeSheet->setCellValue('E' . $row, $data['affiliation']);
            $activeSheet->setCellValue('F' . $row, $data['payment_amount']);
            $activeSheet->setCellValue('G' . $row, $data['phone']);
            $activeSheet->setCellValue('H' . $row, $data['email']);
            $activeSheet->setCellValue('I' . $row, $data['nationality']);
            $activeSheet->setCellValue('J' . $row, $data['payment_method']);
            $activeSheet->setCellValue('K' . $row, $data['payment_time']);
            $activeSheet->setCellValue('L' . $row, $data['payment_status']);
            $activeSheet->setCellValue('M' . $row, $data['output_time']);
            $activeSheet->setCellValue('N' . $row, $data['notes']);
            $activeSheet->setCellValue('O' . $row, $data['registration_type']);
            $activeSheet->setCellValue('P' . $row, $data['registration_form']);
            $activeSheet->setCellValue('Q' . $row, $data['event_name']);
            $activeSheet->setCellValue('R' . $row, $data['application_date']);
            
            $row++;
            $data_count++;
        }
    }
    
    // 컬럼 너비 자동 조정
    foreach (range('A', 'R') as $column) {
        $activeSheet->getColumnDimension($column)->setAutoSize(true);
    }
    
    // 데이터 영역 테두리 설정
    if ($row > 2) {
        $dataRange = 'A1:R' . ($row - 1);
        $activeSheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }
    
    // 파일명 설정
    $filename = 'registration_list_' . date('Y-m-d_H-i-s') . '.xlsx';
    
    // 헤더 설정
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');
    
    // Excel 파일 출력
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
    
} catch (Exception $e) {
    // 오류 발생 시 메시지 출력
    alert('엑셀 다운로드 중 오류가 발생했습니다: ' . $e->getMessage());
    goto_url('./conference_registration_list.php');
}
?>