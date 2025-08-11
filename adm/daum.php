<?php
$sub_menu = '100400';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '다음 검색 등록';
include_once('./admin.head.php');
?>

    <div class="site_wrap">
        <div class="box" id="box1">
            <div class="gu_title">
                <h2>다음 검색 등록</h2>
                <p>• 임시주소가 아닌 도메인 연결 후, 진행해주세요.</p>
                <h3><a href="https://register.search.daum.net/searchForm.daum?act=insert" target="_blank">다음 검색 등록 바로가기</a></h3>
            </div>
            <span>
            <img src="<?php echo G5_ADMIN_URL ?>/img/da1.png" alt="">
            <img src="<?php echo G5_ADMIN_URL ?>/img/da2.png" alt="">
        </span>
        </div>
    </div>

<?php
include_once('./admin.tail.php');
