<?php
include "../../../../common.php";

$tNum="About the Society";
$sNum="Divisions";
$bNum="105";

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


include_once(G5_THEME_PATH.'/head.php');
?>

<div class="about_5 common">
    <div class="width">
        <h2 class="contents_title">분과 소개</h2>
        <div class="items">
            <a href="#" class="item">
                <img src="<?php echo G5_THEME_IMG_URL ?>/division_img1.png" alt="">
                <h3>구강외과 분과</h3>
            </a>
            <a href="#" class="item">
                <img src="<?php echo G5_THEME_IMG_URL ?>/division_img2.png" alt="">
                <h3>보철 분과</h3>
            </a>
            <a href="#" class="item">
                <img src="<?php echo G5_THEME_IMG_URL ?>/division_img3.png" alt="">
                <h3>치주 분과</h3>
            </a>
            <a href="#" class="item">
                <img src="<?php echo G5_THEME_IMG_URL ?>/division_img4.png" alt="">
                <h3>연구 분과</h3>
            </a>
            <a href="#" class="item">
                <img src="<?php echo G5_THEME_IMG_URL ?>/division_img5.png" alt="">
                <h3>영상 및 AI 분과</h3>
            </a>
            <a href="#" class="item">
                <img src="<?php echo G5_THEME_IMG_URL ?>/division_img6.png" alt="">
                <h3>통합치의학 및 장애인치과 분과</h3>
            </a>
        </div>
    </div>
</div>

<?php
include_once(G5_THEME_PATH.'/tail.php');
