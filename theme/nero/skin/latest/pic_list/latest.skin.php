<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
$thumb_width = 210;
$thumb_height = 150;
?>

<div class="pic_lt">
    <!-- <h2 class="lat_title"><a href="<?php echo get_pretty_url($bo_table); ?>"><?php echo $bo_subject ?></a></h2> -->
    <ul>
    <?php
    for ($i=0; $i<count($list); $i++) {
    $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], $thumb_width, $thumb_height, false, true);

    if($thumb['src']) {
        $img = $thumb['src'];
    } else {
        $img = G5_THEME_IMG_URL.'/noimg.png';
        $thumb['alt'] = '이미지가 없습니다.';
    }
    $img_content = '<img src="'.$img.'" alt="'.$thumb['alt'].'" >';
    ?>
        <li class="lt_wrap wow fadeInDown" data-wow-duration="1s" data-wow-offset="400">
          <div class="lt_text_top">
            <div class="lt_info">
              <span class="lt_day"><?php echo date("d", strtotime($list[$i]['wr_datetime'])) ?></span>
              <span class="lt_date"><?php echo date("Y.m", strtotime($list[$i]['wr_datetime'])) ?></span>
            </div>
            <div class="lt_text_con">
               <?php



              echo "<a href=\"".$list[$i]['href']."\"> ";
              if ($list[$i]['is_notice'])
                  echo "".$list[$i]['subject']."";
              else
                  echo $list[$i]['subject'];



              echo "</a>";

              if ($list[$i]['comment_cnt'])  echo "
              <span class=\"lt_cmt\">+ ".$list[$i]['wr_comment']."</span>";

              ?>
              <a href="<?php echo $list[$i]['href'] ?>"><?php echo utf8_strcut(strip_tags($list[$i]['wr_content']), 150, '...'); ?></a>
            </div>
          </div>
            <a href="<?php echo $list[$i]['href'] ?>" class="lt_img"><?php echo $img_content; ?></a>
        </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li class="empty_li">게시물이 없습니다.</li>
    <?php }  ?>
    </ul>
    <a href="<?php echo get_pretty_url($bo_table); ?>" class="lt_more">More</a>

</div>
