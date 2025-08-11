<?php
include_once('./_common.php');

$member_name  = isset($_POST['member_name']) ? trim($_POST['member_name']) : '';
$member_birth = isset($_POST['member_birth']) ? trim($_POST['member_birth']) : '';

set_session('ss_gubun', 'none');
set_session('ss_nonemb_name', $member_name);
set_session('ss_nonemb_birth', $member_birth);

if ($url) {
	$link = urldecode($url);
} else  {
    $link = G5_URL;
}


goto_url($link);