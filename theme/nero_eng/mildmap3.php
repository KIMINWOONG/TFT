<div class="lnb">
  <ul class="lnb_map ">

    <!-- 2차 메뉴 -->
    <li class="dep dep2">
     <a href="#">
        <span><?php if (false && $bo_table) { //게시판이 있다면 ?>
          <?=$board[bo_subject]?>
        <?php } else { //그렇지 않다면 ?>
          <?=$sNum?>
        <?php }?>

      </span>
      </a>
			<ul>
				<?php

					if(isset($submenu[$tNum])){
						foreach($submenu[$tNum] as $smenu=>$surl){
				?>
					<li><a href="<?php echo $surl?>" target="<?php echo $submenu_target[$tNum][$smenu]?>"><?php echo $smenu?></a></li>
				<?php
						}
					}
				?>
			</ul>
		</li>
  </ul>
</div>

