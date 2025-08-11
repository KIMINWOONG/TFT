<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<?php if ($is_admin == 'super') {  ?><!-- <div style='float:left; text-align:center;'>RUN TIME : <?php echo get_microtime()-$begin_time; ?><br></div> --><?php }  ?>

<!-- ie6,7에서 사이드뷰가 게시판 목록에서 아래 사이드뷰에 가려지는 현상 수정 -->
<!--[if lte IE 7]>
<script>
$(function() {
    var $sv_use = $(".sv_use");
    var count = $sv_use.length;

    $sv_use.each(function() {
        $(this).css("z-index", count);
        $(this).css("position", "relative");
        count = count - 1;
    });
});
</script>
<![endif]-->
<script>
    feather.replace()
</script>
<script>
	new WOW().init();
</script>
<script src="<?php echo G5_THEME_JS_URL ?>/jarallax.min.js"></script>
<script src="<?php echo G5_THEME_JS_URL ?>/jarallax-video.min.js"></script>
<script type="text/javascript">
      $(".jarallax").jarallax();
</script>
<?php run_event('tail_sub'); ?>


<script>
    
function pop_open() {
  document.getElementById('my_popup').style.display = 'flex';
  document.body.classList.add('popup-open'); // 스크롤 막기
}

function pop_close() {
  document.getElementById('my_popup').style.display = 'none';
  document.body.classList.remove('popup-open'); // 스크롤 복원
}
</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const topBtn = document.querySelector(".top_btn");
    if (topBtn) {
      topBtn.addEventListener("click", function (e) {
        e.preventDefault(); // 링크 기본 동작 방지
        window.scrollTo({
          top: 0,
          behavior: "smooth" // 부드러운 스크롤
        });
      });
    }
  });
</script>


</body>
</html>
<?php echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다.
