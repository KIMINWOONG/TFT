<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

// 그누보드5.4.5.5 버전과 영카트5.4.5.5.1 버전이 통합됨에 따라 그누보드 버전만 표시
// $print_version = defined('G5_YOUNGCART_VER') ? 'YoungCart Version '.G5_YOUNGCART_VER : 'Version '.G5_GNUBOARD_VER;
$print_version = 'Version ' . G5_GNUBOARD_VER;
?>

<script>
	$("#sh_adm_main_wrap > div:nth-of-type(odd)").css("margin-right","2%");
</script>


        </div><!-- #adm_cont_wrap -->
    </div><!-- #adm_cont-->

<!-- 관리자모드 FOOTER -->
<div id="sh_adm_ft">
	<p class="cs"><a href="<?php echo G5_URL ?>/" target="_blank"><span>NEROWEB</span> 1688-8214</a></p>
    <p class="copy">Copyright ⓒ NEROWEB All rights reserved. <?php echo $print_version; ?><br>
    <button type="button" class="scroll_top"><span class="top_img"></span><span class="top_txt">TOP</span></button>
</p>
</div>

</div><!-- #adm_wrapper -->
<script>
    $(".scroll_top").click(function() {
        $("body,html").animate({
            scrollTop: 0
        }, 400);
    })
</script>

<?php
require_once G5_PATH . '/tail.sub.php';
