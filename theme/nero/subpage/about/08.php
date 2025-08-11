<?php
include "../../../../common.php";

$tNum="학회소개";
$sNum="오시는길/문의";
$bNum="108";
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


include_once(G5_THEME_PATH.'/head.php');
?>

<div class="about_6 common">
    <div class="width">
        <h2 class="contents_title">오시는길/문의</h2>
        <h3>서울대학교치과병원</h3>
        <div class="blue_box">
            <div>
                <b>주소 : </b>
                <p>서울특별시 종로구 대학로 101, 7층 745호 (연건동 28-21) (우)03080 <br>
                SEOUL NATIONAL UNIVERSITY DENTAL HOSPITAL<br>
                101 Daehak-ro, Jongno-gu, Seoul 03080, Korea
                </p>
            </div>
        </div>


        <!-- * 카카오맵 - 지도퍼가기 -->
        <!-- 1. 지도 노드 -->
        <div id="daumRoughmapContainer1750665871863" class="root_daum_roughmap root_daum_roughmap_landing map"></div>

        <!--
            2. 설치 스크립트
            * 지도 퍼가기 서비스를 2개 이상 넣을 경우, 설치 스크립트는 하나만 삽입합니다.
        -->
        <script charset="UTF-8" class="daum_roughmap_loader_script" src="https://ssl.daumcdn.net/dmaps/map_js_init/roughmapLoader.js"></script>

        <!-- 3. 실행 스크립트 -->
        <script charset="UTF-8">
            new daum.roughmap.Lander({
                "timestamp" : "1750665871863",
                "key" : "42qshej4gta",
            }).render();
        </script>

        <div class="email">
            <img src="<?php echo G5_THEME_IMG_URL ?>/contact_img.png" alt="">
            <div class="text">
                <h3>학회 관련 문의</h3>
                <h4><a href="https://pf.kakao.com/_AExomn" target="_blank" style="color:inherit; text-decoration:none;">kaid@kaidimplant.or.kr</a></h4>
            </div>
        </div>
    </div>
</div>



<?php
include_once(G5_THEME_PATH.'/tail.php');
