<?php
include "../../../../common.php";

header('Content-Type: application/json; charset=utf-8');

$mode = $_GET['mode'] ?? '';
$response = array('success' => false);

switch ($mode) {
    case 'detail':
        $co_id = (int)$_GET['co_id'];
        
        if (!$co_id) {
            $response['message'] = '잘못된 요청입니다.';
            break;
        }
        
        // 평의원 정보 조회
        $sql = "SELECT * FROM g5_council WHERE co_id = $co_id";
        $council = sql_fetch($sql);
        
        if (!$council) {
            $response['message'] = '해당 평의원 정보를 찾을 수 없습니다.';
            break;
        }
        
        $response['success'] = true;
        $response['council'] = $council;
        break;
    
    case 'list':
        // 평의원 목록 조회 (AJAX 페이징용)
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 12;
        $category = isset($_GET['category']) ? clean_xss_tags($_GET['category']) : 'all';
        
        // 전체 평의원 수 조회
        $count_sql = "SELECT COUNT(*) as total FROM g5_council";
        $where_sql = "";
        if ($category != 'all') {
            $where_sql = "WHERE co_category = '" . sql_real_escape_string($category) . "'";
            $count_sql .= " " . $where_sql;
        }
        
        $count_result = sql_query($count_sql);
        $count_row = sql_fetch_array($count_result);
        $total_count = $count_row['total'];
        
        // 페이징 계산
        $total_pages = ceil($total_count / $per_page);
        $offset = ($page - 1) * $per_page;
        
        // 평의원 목록 조회
        $sql = "SELECT * FROM g5_council $where_sql ORDER BY co_order ASC, co_id ASC LIMIT $offset, $per_page";
        $result = sql_query($sql);
        
        $councils = array();
        while ($row = sql_fetch_array($result)) {
            $councils[] = $row;
        }
        
        $response['success'] = true;
        $response['data'] = $councils;
        $response['pagination'] = array(
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_count' => $total_count,
            'per_page' => $per_page
        );
        break;
        
    default:
        $response['message'] = '잘못된 요청입니다.';
        break;
}

echo json_encode($response);
?>