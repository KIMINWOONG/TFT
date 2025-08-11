<?php
// abstract_download.php - 파일 다운로드
require_once './_common.php';

if (!$is_admin) {
    alert("관리자만 접근 가능합니다.", G5_URL);
}

$as_id = (int)$_GET['as_id'];

// 초록 정보 확인
$abstract = sql_fetch("SELECT * FROM g5_conference_summary WHERE as_id = {$as_id}");
if (!$abstract || !$abstract['as_file_path']) {
    alert("파일을 찾을 수 없습니다.");
}

$conference = sql_fetch("SELECT * FROM g5_conference WHERE sy_id='{$abstract['as_sy_id']}'");

$file_path = G5_DATA_PATH . "/summary/".$conference['sy_id']."/".$abstract['as_file_path'];
$file_name = $abstract['as_file_name'];

if (!file_exists($file_path)) {
    alert("파일이 존재하지 않습니다.");
}

// 파일 다운로드
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file_name . '"');
header('Content-Length: ' . filesize($file_path));
header('Cache-Control: must-revalidate');
header('Pragma: public');

readfile($file_path);
exit;
?>