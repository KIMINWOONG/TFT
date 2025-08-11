<?php
include "../../../common.php";

$region = isset($_GET['region']) ? clean_xss_tags($_GET['region']) : '';

if (empty($region)) {
    echo json_encode([]);
    exit;
}

// 해당 지역의 병원명 조회
$sql = "SELECT DISTINCT mb_work_name 
        FROM {$g5['member_table']} 
        WHERE mb_work_name != '' 
        AND mb_search_agree = '동의' 
        AND (mb_work_addr1 LIKE '%{$region}%' OR mb_addr1 LIKE '%{$region}%')
        ORDER BY mb_work_name";

$result = sql_query($sql);
$hospitals = array();

while ($row = sql_fetch_array($result)) {
    $hospitals[] = $row['mb_work_name'];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($hospitals);
?>