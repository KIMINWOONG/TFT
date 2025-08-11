
<?php
include_once('./_common.php');

// 관리자 권한 체크
auth_check_menu($auth, $sub_menu, 'r');

// 파라미터 받기
$sy_id = isset($_GET['sy_id']) ? (int)$_GET['sy_id'] : 0;
$sfl = isset($_GET['sfl']) ? clean_xss_tags($_GET['sfl']) : '';
$stx = isset($_GET['stx']) ? clean_xss_tags($_GET['stx']) : '';
$sst = isset($_GET['sst']) ? clean_xss_tags($_GET['sst']) : 'cr_id';
$sod = isset($_GET['sod']) ? clean_xss_tags($_GET['sod']) : 'desc';
$type = isset($_GET['type']) ? clean_xss_tags($_GET['type']) : 'conference'; // conference 또는 symposium

// 테이블명 설정
$table_name = ($type === 'symposium') ? 'g5_symposium' : 'g5_conference';
// 등록 데이터는 모두 g5_conference_registration에서 통합 관리
$registration_table = 'g5_conference_registration';

// SQL 공통 부분 - 회원 조인 조건 개선
$sql_common = " FROM {$registration_table} cr 
                LEFT JOIN {$table_name} c ON cr.cr_sy_id = c.sy_id 
                LEFT JOIN {$g5['member_table']} m ON (cr.cr_mb_id IS NOT NULL AND cr.cr_mb_id != '' AND cr.cr_mb_id = m.mb_id) ";

// 검색 조건
$sql_search = "";
if ($sy_id) {
    $sql_search .= " AND cr.cr_sy_id = '{$sy_id}' ";
}

if ($sfl && $stx) {
    switch($sfl) {
        case 'cr.cr_name_kor':
            $sql_search .= " AND (cr.cr_name_kor LIKE '%{$stx}%' OR cr.cr_nonemb_name LIKE '%{$stx}%') ";
            break;
        case 'cr.cr_email':
            $sql_search .= " AND cr.cr_email LIKE '%{$stx}%' ";
            break;
        case 'cr.cr_hospital_name':
            $sql_search .= " AND cr.cr_hospital_name LIKE '%{$stx}%' ";
            break;
        case 'c.sy_title':
            $sql_search .= " AND c.sy_title LIKE '%{$stx}%' ";
            break;
    }
}

$sql_search = " WHERE 1=1 " . $sql_search;

try {
    // 데이터 조회 - 실제 존재하는 컬럼명 사용
    $sql = "SELECT 
                cr.cr_id as 'ID',
                CASE 
                    WHEN cr.cr_mb_id IS NOT NULL AND cr.cr_mb_id != '' THEN '회원'
                    ELSE '비회원'
                END as '회원 유형',
                CASE 
                    WHEN cr.cr_mb_id IS NOT NULL AND cr.cr_mb_id != '' AND m.mb_name IS NOT NULL AND m.mb_name != '' THEN m.mb_name
                    WHEN cr.cr_name IS NOT NULL AND cr.cr_name != '' THEN cr.cr_name
                    ELSE '이름 없음'
                END as '이름',
                IFNULL(m.mb_license_no, '') as '면허 번호',
                CASE 
                    WHEN cr.cr_mb_id IS NOT NULL AND cr.cr_mb_id != '' AND m.mb_work_name IS NOT NULL AND m.mb_work_name != '' THEN m.mb_work_name
                    WHEN cr.cr_hospital IS NOT NULL AND cr.cr_hospital != '' THEN cr.cr_hospital
                    ELSE '소속 없음'
                END as '소속',
                IFNULL(cr.cr_price, 0) as '결제 금액',
                CASE 
                    WHEN m.mb_hp IS NOT NULL AND m.mb_hp != '' THEN m.mb_hp
                    WHEN cr.cr_phone IS NOT NULL AND cr.cr_phone != '' THEN cr.cr_phone
                    ELSE ''
                END as '전화번호',
                CASE 
                    WHEN cr.cr_mb_id IS NOT NULL AND cr.cr_mb_id != '' THEN m.mb_email
                    ELSE cr.cr_email
                END as '이메일',
                '대한민국' as '국적',
                IFNULL(cr.cr_payment_method, '') as '결제 방법',
                IFNULL(DATE_FORMAT(cr.cr_payment_date, '%Y-%m-%d %H:%i:%s'), '') as '결제 시간',
                CASE cr.cr_payment_status
                    WHEN 'Y' THEN '결제완료'
                    WHEN 'N' THEN '미결제'
                    WHEN 'C' THEN '결제취소'
                    ELSE IFNULL(cr.cr_payment_status, '미결제')
                END as '결제 상태',
                DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i:%s') as '출력 시간',
                IFNULL(cr.cr_memo, '') as '비고',
                CASE cr.cr_type
                    WHEN 'early' THEN '조기등록'
                    WHEN 'regular' THEN '정규등록'
                    WHEN 'onsite' THEN '현장등록'
                    ELSE '일반'
                END as '등록 구분',
                CASE 
                    WHEN m.mb_job_class IS NOT NULL AND m.mb_job_class != '' THEN m.mb_job_class
                    ELSE '일반'
                END as '등록 형태',
                IFNULL(c.sy_title, '') as '행사명',
                IFNULL(DATE_FORMAT(cr.cr_datetime, '%Y-%m-%d %H:%i:%s'), '') as '신청일시'
            {$sql_common} {$sql_search}
            ORDER BY {$sst} {$sod}";
    
    $result = sql_query($sql);
    
    // 파일명 설정
    $filename = ($type === 'symposium') ? 'symposium_registration' : 'conference_registration';
    $filename .= '_' . date('Y-m-d') . '.csv';
    
    // CSV 헤더 출력 - UTF-8 BOM 추가로 한글 깨짐 방지
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="registration_list_' . date('Y-m-d_H-i-s') . '.csv"');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    
    $output = fopen('php://output', 'w');
    
    // UTF-8 BOM 추가
    fwrite($output, "\xEF\xBB\xBF");
    
    $result = sql_query($sql);
    
    if (sql_num_rows($result) > 0) {
        // 첫 번째 행에서 컬럼명 추출
        $first_row = sql_fetch_assoc($result);
        $headers = array_keys($first_row);
        
        // 헤더 출력
        fputcsv($output, $headers);
        
        // 첫 번째 행 출력
        fputcsv($output, array_values($first_row));
        
        // 나머지 행 출력
        while ($row = sql_fetch_assoc($result)) {
            fputcsv($output, array_values($row));
        }
    } else {
        // 데이터가 없을 때 헤더만 출력 - 18개 컬럼
        $headers = array('ID', '회원 유형', '이름', '면허 번호', '소속', '결제 금액', '전화번호', '이메일', '국적', '결제 방법', '결제 시간', '결제 상태', '출력 시간', '비고', '등록 구분', '등록 형태', '행사명', '신청일시');
        fputcsv($output, $headers);
    }
    
    fclose($output);
    exit;
    
} catch (Exception $e) {
    // 오류 발생 시 메시지 출력
    alert('엑셀 다운로드 중 오류가 발생했습니다: ' . $e->getMessage());
    goto_url('./conference_registration_list.php');
}
?>