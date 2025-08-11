<div class="lnb">
  <ul class="lnb_map ">
    <li class="home"><a href="<?php echo G5_URL ?>"><img src="<?php echo G5_THEME_IMG_URL ?>/home.png" alt=""></a></li>
    <li> <img src="<?php echo G5_THEME_IMG_URL ?>/arrow2.png" alt=""></li>
    <!-- 1차 메뉴 -->
    <li class="dep">
      <a href="#"><span><?=$tNum?> <img src="<?php echo G5_THEME_IMG_URL ?>/arrow2.png" alt=""></span></a>
			
			</li>
    <!-- 2차 메뉴 -->
    <li class="dep dep1">
      <a href="#">
        <span><?php if (false && $bo_table) { //게시판이 있다면 ?>
          <?=$board[bo_subject]?>
        <?php } else { //그렇지 않다면 ?>
          <?=$sNum?>
        <?php }?>
      
      </span>
      </a>
			
		</li>
  </ul>
</div>
