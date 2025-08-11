<?php
include "../../../../common.php";

header('Content-Type: application/json; charset=utf-8');

$mode = $_GET['mode'] ?? '';
$response = array('success' => false);

switch ($mode) {
    case 'detail':
        $ex_id = (int)$_GET['ex_id'];
        
        if (!$ex_id) {
            $response['message'] = '잘못된 요청입니다.';
            break;
        }
        
        // 임원 정보 조회
        $sql = "SELECT * FROM g5_executive WHERE ex_id = $ex_id";
        $executive = sql_fetch($sql);
        
        if (!$executive) {
            $response['message'] = '해당 임원 정보를 찾을 수 없습니다.';
            break;
        }
        
        $response['success'] = true;
        $response['executive'] = $executive;
        break;
        
    case 'list':
        $category = $_GET['category'] ?? 'all';
        $page = (int)($_GET['page'] ?? 1);
        $per_page = 12;
        $offset = ($page - 1) * $per_page;
        
        // 카테고리 필터 처리
        $where_sql = "";
        if ($category != 'all') {
            $where_sql = "WHERE ex_category = '" . sql_real_escape_string($category) . "'";
        }
        
        // 임원 목록 조회
        $sql = "SELECT * FROM g5_executive $where_sql ORDER BY ex_order ASC, ex_id ASC LIMIT $offset, $per_page";
        $result = sql_query($sql);
        
        $executives = array();
        while ($row = sql_fetch_array($result)) {
            $executives[] = $row;
        }
        
        // 전체 개수
        $total_sql = "SELECT COUNT(*) as cnt FROM g5_executive $where_sql";
        $total_result = sql_fetch($total_sql);
        $total_count = $total_result['cnt'];
        $total_page = ceil($total_count / $per_page);
        
        $response['success'] = true;
        $response['executives'] = $executives;
        $response['pagination'] = array(
            'current_page' => $page,
            'total_page' => $total_page,
            'total_count' => $total_count,
            'per_page' => $per_page
        );
        break;
        
    default:
        $response['message'] = '알 수 없는 요청입니다.';
        break;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>