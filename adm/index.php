<?php
require_once './_common.php';

@require_once './safe_check.php';
if (function_exists('social_log_file_delete')) {
    social_log_file_delete(86400);      //소셜로그인 디버그 파일 24시간 지난것은 삭제 
}

$g5['title'] = '관리자메인';
require_once './admin.head.php';

$new_member_rows = 4;
/*$new_point_rows = 4;
$new_write_rows = 4;*/

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) ";

if ($is_admin != 'super') {
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";
}

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$new_member_rows} ";
$result = sql_query($sql);

// 오늘 가입회원수
$today = G5_TIME_YMD;
$qq = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where LEFT(mb_datetime, 10)='$today' ");

// 오늘 탈퇴회원수
$today = str_replace('-','',G5_TIME_YMD);
$leave = sql_fetch(" select count(*) as cnt from {$g5['member_table']} where LEFT(mb_leave_date, 10)='$today' ");

$colspan = 6;
?>

<section id="sh_adm_main_wrap">

<?php
require_once './main_new_mb.php'; // 신규가입 회원 현황
require_once './main_visitor_day.php'; // 요일별 방문자 현황
require_once './main_visitor_time.php'; // 시간별 방문자 현황
require_once './main_visitor_url.php'; // 접속 경로별 방문자 현황
//require_once './main_new_revisit.php'; // 신규접속 VS 신규회원 현황
//require_once './main_visitor_os.php'; // 접속 OS별 방문자 현황
//require_once './main_latest.php'; // 접속 OS별 방문자 현황
//require_once './main_access_device.php'; // 접속 OS별 방문자 현황
?>

</section>

<?php
require_once './admin.tail.php';
