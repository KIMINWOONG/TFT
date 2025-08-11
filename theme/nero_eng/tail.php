<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/tail.php');
    return;
}
?>

<!-- </div> -->
<!-- all_wrap 끝 -->

<!-- 하단 시작 { -->
<div id="ft">
    <div class="width">
        <div class="foot_service">
    <a href="<?php echo G5_THEME_URL ?>/subpage/notice/01.php">User Guide</a>
    <span></span>
    <a href="<?php echo G5_THEME_URL ?>/subpage/notice/02.php">Privacy Policy</a>
    <span></span>
    <a href="<?php echo G5_THEME_URL ?>/subpage/notice/03.php">Email Collection Policy</a>
    <span></span>
    <a href="<?php echo G5_THEME_URL ?>/subpage/notice/04.php">Refund Policy</a>
</div>
</div>
<div class="foot_menu">
<ul>
      <?php
        foreach($topmenu as $tmenu=>$url){
        ?>
        <li class="dept1">
          <a href="<?=$url?>"  target="<?=$topmenu_target[$tmenu]?>"><?=$tmenu?></a>
          <ul>
            <?php
            	foreach($submenu[$tmenu] as $smenu=>$surl){
            ?>
            <li class="dept2"><a href="<?=$surl?>" target="<?=$submenu_target[$tmenu][$smenu]?>" ><?=$smenu?></a></li>
             <?php
              	} 
              ?>
          </ul>
        </li>
      <?php
      }
      ?>
</ul>
</div>
    <div class="width">
<div class="footer">
    <a href="<?php echo G5_URL ?>"><img src="<?php echo G5_THEME_IMG_URL ?>/flogo.png" alt=""></a>
    <ul>
         <li><span>Tel : 02-2273-3875</span></li>
        <li><span>Address : Room B168, Seoul National University Dental Hospital,
101 Daehak-ro, Jongno-gu, Seoul 03080, South Korea (Yeongeon-dong)</span></li>
        <li><span>Copyright ©2025 Korean Academy of Implant Dentistry All rights reserved</span></li>
    </ul>
    

   
</div>
    </div>
</div>

</div>
</div>
<?php
if(G5_DEVICE_BUTTON_DISPLAY && !G5_IS_MOBILE) { ?>
<?php
}

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->

<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
</script>

<?php
include_once(G5_THEME_PATH."/tail.sub.php");
