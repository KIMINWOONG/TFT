<?php
include_once('./_common.php');

if (!$is_admin) {
    alert('관리자만 접근 가능합니다.');
    goto_url(G5_URL);
}

$mb_id = isset($_GET['mb_id']) ? (int)$_GET['mb_id'] : 0;

if (!$mb_id) {
    alert('잘못된 접근입니다.');
    goto_url('./adm_members.php');
}

// 썸네일 이미지 삭제
$row = sql_fetch("SELECT mb_thumbnail FROM g5_team_members WHERE mb_id = '{$mb_id}'");
if ($row['mb_thumbnail'] && file_exists(G5_DATA_PATH.'/team/'.$row['mb_thumbnail'])) {
    @unlink(G5_DATA_PATH.'/team/'.$row['mb_thumbnail']);
}

// DB에서 구성원 정보 삭제
$sql = "DELETE FROM g5_team_members WHERE mb_id = '{$mb_id}'";
sql_query($sql);

alert('구성원이 삭제되었습니다.');
goto_url('./team_members.php');
?>