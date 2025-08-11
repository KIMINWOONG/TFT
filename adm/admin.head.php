<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

$files = glob(G5_ADMIN_PATH . '/css/admin_extend_*');
if (is_array($files)) {
    foreach ((array) $files as $k => $css_file) {

        $fileinfo = pathinfo($css_file);
        $ext = $fileinfo['extension'];

        if ($ext !== 'css') {
            continue;
        }

        $css_file = str_replace(G5_ADMIN_PATH, G5_ADMIN_URL, $css_file);
        add_stylesheet('<link rel="stylesheet" href="' . $css_file . '">', $k); 
    }
}

require_once G5_PATH . '/adm/admin.head.sub.php';

function print_menu1($key, $no = '')
{
    global $menu;

    $str = print_menu2($key, $no);

    return $str;
}

function print_menu2($key, $no = '')
{
    global $menu, $auth_menu, $is_admin, $auth, $g5, $sub_menu;

    $str = "<ul class='nav_smn'>";
    for ($i = 1; $i < count($menu[$key]); $i++) {
        if (!isset($menu[$key][$i])) {
            continue;
        }

        if ($is_admin != 'super' && (!array_key_exists($menu[$key][$i][0], $auth) || !strstr($auth[$menu[$key][$i][0]], 'r'))) {
            continue;
        }

        $str .= '<li><a href="' . $menu[$key][$i][2] . '" >' . $menu[$key][$i][1] . '</a></li>';

        $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
    }
    $str .= "</ul>";

    return $str;
}

?>

<script>
    var g5_admin_csrf_token_key = "<?php echo (function_exists('admin_csrf_token_key')) ? admin_csrf_token_key() : ''; ?>";
    var tempX = 0;
    var tempY = 0;

    function imageview(id, w, h) {

        menu(id);

        var el_id = document.getElementById(id);

        //submenu = eval(name+".style");
        submenu = el_id.style;
        submenu.left = tempX - (w + 11);
        submenu.top = tempY - (h / 2);

        selectBoxVisible();

        if (el_id.style.display != 'none')
            selectBoxHidden(id);
    }
</script>

<div id="sh_adm_wrap_index">
    
<!-- 관리자모드 HEADER -->
<div id="sh_adm_hd">
    <h1><a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>"><img src="<?php echo G5_ADMIN_URL ?>/img/administrator.png"></a></h1>

        <ul id="sh_adm_nav">
            <?php
            $jj = 1;
            foreach ($amenu as $key => $value) {
                $href1 = $href2 = '';

                if (isset($menu['menu' . $key][0][2]) && $menu['menu' . $key][0][2]) {
                    $href1 = '<a href="' . $menu['menu' . $key][0][2] . '>';
                    $href2 = '</a>';
                } else {
                    continue;
                }
            ?>
                <li>

                <a href="<?php echo $menu['menu' . $key][0][2]?>"><?php echo $menu['menu' . $key][0][1]; ?></a>
                         <?php echo print_menu1('menu' . $key, 1); ?>

                </li>
            <?php
                $jj++;
            }     //end foreach
            ?>

        </ul>
        <ul id="sh_adm_gnb">
            <?php if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
                    <!--<li><a href="--><?php //echo G5_SHOP_URL ?><!--/" class="tnb_shop" target="_blank" title="쇼핑몰 바로가기">쇼핑몰 바로가기</a></li>-->
                <?php } ?>
                <li><a href="<?php echo G5_URL ?>/" target="_blank">홈페이지</a></li>
                <li><a href="<?php echo G5_ADMIN_URL ?>/">관리자메인</a></li>
                <li id="tnb_logout"><a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a></li>
            </ul>
            <script>
        $('#sh_adm_nav > li').hover(function(){
            $('.nav_smn', this).slideDown(50);
            $(this).addClass('active');
        }, function() {
            $('.nav_smn', this).slideUp(50);
            $(this).removeClass('active');
        });
    </script>

</div>

<div id="sh_adm_cont">
        <!-- 관리자모드 SNB -->
		<!-- 퀵메뉴 설정 팝업 스크립트 -->

<div id="sh_adm_snb">
    <div class="nero_logo">
        <img src="<?php echo G5_ADMIN_URL ?>/img/nerologo.png" alt="">
    </div>
    <div class="main_snb hosting">
        <h3>호스팅 관리</h3>
        <ul>
        <?php
     $end_date = date('2025-11-22'); //디데이 날짜
     $d_day = floor(( strtotime(substr($end_date,0,10)) -
                   strtotime(date('Y-m-d')) )/86400);
      ?>            
        	<li><strong>호스팅 만료일</strong> <?php echo $end_date; ?></li>
            <li><strong>남은기간</strong> <strong><?php echo $d_day; ?>일</strong></li>
        </ul>
        <a href="#" target="_blank" class="btn">호스팅 연장하기</a>
        <a href="https://nero-web.co.kr/" target="_blank" class="btn mypage">네로웹 바로가기</a>
    </div>
    
    <div class="main_snb q_menu">
        <h3>퀵메뉴</h3>
        <ul>
            <li><a href="/adm/guide.php">관리자 가이드</a></li>
            <li><a href="/adm/naver.php">네이버 사이트 등록</a></li>
            <li><a href="/adm/daum.php">다음 사이트 등록</a></li>
			        </ul>
    </div>   
     
</div>
<?php
// 접속자 집계(오늘)
$sql = " select vs_count as cnt from {$g5['visit_sum_table']} where vs_date = '".G5_TIME_YMD."' ";
$row = sql_fetch($sql);
$vi_today = isset($row['cnt']) ? $row['cnt'] : 0;

// 접속자 집계(전체)
$sql = " select sum(vs_count) as total from {$g5['visit_sum_table']} ";
$row = sql_fetch($sql);
$vi_sum = isset($row['total']) ? $row['total'] : 0;
?>

<div id="sh_adm_cont_wrap">
            <div id="sh_adm_count">
                <dl><dt>오늘방문자 : </dt><dd><?php echo number_format($vi_today) ?></dd><dt>전체방문자 : </dt><dd><?php echo number_format($vi_sum) ?></dd></dl>
            </div>