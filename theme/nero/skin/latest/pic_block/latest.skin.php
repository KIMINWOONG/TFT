<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
$thumb_width = 400;
$thumb_height = 300;
$list_count = (is_array($list) && $list) ? count($list) : 0;
?>

<div class="pic_lt pic_lt_block">
    <ul class="pic_gallery swiper-wrapper">
    <?php
    for ($i=0; $i<$list_count; $i++) {
    $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $thumb_width, $thumb_height, false, true);

    if($thumb['src']) {
        $img = $thumb['src'];
    } else {
        $img = G5_THEME_IMG_URL.'/noimg.png';
        $thumb['alt'] = '이미지가 없습니다.';
    }
    $img_content = '<img src="'.$img.'" alt="'.$thumb['alt'].'" >';
    $wr_href = get_pretty_url($bo_table, $list[$i]['wr_id']);
    ?>
        <li class="galley_li swiper-slide">
            <a href="<?php echo $wr_href; ?>" class="lt_img"><?php echo run_replace('thumb_image_tag', $img_content, $thumb); ?></a>
            <?php
            if ($list[$i]['icon_secret']) echo "<i class=\"fa fa-lock\" aria-hidden=\"true\"></i><span class=\"sound_only\">비밀글</span> ";

            echo "<a href=\"".$wr_href."\"> ";
            if ($list[$i]['is_notice'])
                echo "".$list[$i]['subject']."";
            else
                echo $list[$i]['subject'];
            echo "</a>";

			if ($list[$i]['icon_new']) echo "<span class=\"new_icon\">NEW<span class=\"sound_only\">새글</span></span>";
            if ($list[$i]['icon_hot']) echo "<span class=\"hot_icon\">H<span class=\"sound_only\">인기글</span></span>";

            // if ($list[$i]['link']['count']) { echo "[{$list[$i]['link']['count']}]"; }
            // if ($list[$i]['file']['count']) { echo "<{$list[$i]['file']['count']}>"; }

			// echo $list[$i]['icon_reply']." ";
			// if ($list[$i]['icon_file']) echo " <i class=\"fa fa-download\" aria-hidden=\"true\"></i>" ;
            // if ($list[$i]['icon_link']) echo " <i class=\"fa fa-link\" aria-hidden=\"true\"></i>" ;

            if ($list[$i]['comment_cnt'])  echo "
            <span class=\"lt_cmt\">".$list[$i]['wr_comment']."</span>";

            ?>
            <div class="lt_content">
              <p><?php echo $list[$i]['wr_content'] ?></p>
            </div>

            <div class="lt_info2">
				<span class="lt_nick"><?php echo $list[$i]['name'] ?></span>
            	<span class="lt_date"><?php echo $list[$i]['datetime2'] ?></span>
            </div>
        </li>
    <?php }  ?>
    <?php if ($list_count == 0) { //게시물이 없을 때  ?>
    <li class="empty_li">게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
    <div class="gallery-pagination"></div>
</div>
  <a href="<?php echo get_pretty_url($bo_table); ?>" class="lt_more">More</a>

  <script>
      var swiper = new Swiper(".pic_lt_block", {
        slidesPerView: 4,
        spaceBetween: 20,
        autoplay: {
            delay:3000,
            disableOnInteraction: false,
        },
        pagination: {
        el: ".gallery-pagination",
        },
        loop : true,
        breakpoints: {
          320: {
            slidesPerView: 1,  //브라우저가 1보다 클 때
            spaceBetween: 20,
          },
          768: {
            slidesPerView: 2,  //브라우저가 768보다 클 때
            spaceBetween: 20,
          },
          1024: {
            slidesPerView: 3,  //브라우저가 1024보다 클 때
            spaceBetween: 20,
          },
          1200: {
            slidesPerView: 4,  //브라우저가 1024보다 클 때
            spaceBetween: 20,
          },
        },
      });
    </script>
