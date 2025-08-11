<?php
$sub_menu = '100400';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '네이버 웹마스터';
include_once('./admin.head.php');
?>

    <div class="site_wrap">
        <div class="box" id="box1">
            <div class="gu_title">
                <h2>네이버 웹마스터</h2>
                <p>임시주소가 아닌 도메인 연결 후, 진행해주세요.</p>
                <h3><a href="https://searchadvisor.naver.com/" target="_blank">네이버 웹마스터 도구 바로가기</a></h3>
            </div>
            <span>
            <img src="<?php echo G5_ADMIN_URL ?>/img/na1.png" alt="">
        </span>
        </div>
        <div class="box" id="box1">
            <div class="gu_title">
                <h2>메타태그 입력</h2>
                <!--<h3>아래 사진의 붉은 박스 부분을 입력해주세요.</h3>-->
                <!--<div class="input">-->
                <!--    <input type="text" placeholder="입력해주세요">-->
                <!--    <button>입력하기</button>-->
                <!--</div>-->
            </div>
            <span>
            <img src="<?php echo G5_ADMIN_URL ?>/img/na2.png" alt="">
        </span>
        </div>
    </div>

<?php
include_once('./admin.tail.php');
