<?php
$menu['menu910'] = array(
//    array('910000', '최적화', G5_ADMIN_URL . '#',   ''),
//    array('910800', '세션파일 일괄삭제', G5_ADMIN_URL . '/session_file_delete.php', 'cf_session', 1),
//    array('910900', '캐시파일 일괄삭제', G5_ADMIN_URL . '/cache_file_delete.php',   'cf_cache', 1),
//    array('910910', '캡챠파일 일괄삭제', G5_ADMIN_URL . '/captcha_file_delete.php',   'cf_captcha', 1),
//    array('910920', '썸네일파일 일괄삭제', G5_ADMIN_URL . '/thumbnail_file_delete.php',   'cf_thumbnail', 1),
//    array('910500', 'phpinfo()',        G5_ADMIN_URL . '/phpinfo.php',       'cf_phpinfo')
);

if (version_compare(phpversion(), '5.3.0', '>=') && defined('G5_BROWSCAP_USE') && G5_BROWSCAP_USE) {
//    $menu['menu910'][] = array('910510', 'Browscap 업데이트', G5_ADMIN_URL . '/browscap.php', 'cf_browscap');
//    $menu['menu910'][] = array('910520', '접속로그 변환', G5_ADMIN_URL . '/browscap_convert.php', 'cf_visit_cnvrt');
}

//$menu['menu910'][] = array('910410', 'DB업그레이드', G5_ADMIN_URL . '/dbupgrade.php', 'db_upgrade');
