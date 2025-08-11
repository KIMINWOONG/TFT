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
    <a href="<?php echo G5_THEME_URL ?>/subpage/notice/01.php">이용안내</a>
    <span></span>
    <a href="<?php echo G5_THEME_URL ?>/subpage/notice/02.php">개인정보취급방침</a>
    <span></span>
    <a href="<?php echo G5_THEME_URL ?>/subpage/notice/03.php">이메일무단수집거부</a>
    <span></span>
    <a href="<?php echo G5_THEME_URL ?>/subpage/notice/04.php">환불규정안내</a>
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
        <li><span>대한치과이식임플란트학회</span></li>
        <li><span>대표 : 김성민 </span><span> | </span><span> 사업자등록번호 : 898-82-00058 </span><span> | </span><span> 문의 : <a href="https://pf.kakao.com/_AExomn" target="_blank" style="color:inherit; text-decoration:none; font-size:inherit; font-weight:inherit;">kaid@kaidimplant.or.kr</a></span></li>
        <li><span>주소 : 서울특별시 종로구 대학로 101, 7층 745호 (연건동 28-21) (우)03080</span></li>
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
