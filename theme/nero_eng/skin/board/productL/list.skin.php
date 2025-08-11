<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(!sql_query(" select wr_11 from {$write_table} limit 1 ", false)) {
    sql_query(" ALTER TABLE {$write_table} ADD wr_11 varchar(255) ", true);
}
if(!sql_query(" select wr_12 from {$write_table} limit 1 ", false)) {
    sql_query(" ALTER TABLE {$write_table} ADD wr_12 varchar(255) ", true);
}

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 8;

if ($is_checkbox) $colspan++;
if ($is_good) $colspan++;
if ($is_nogood) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:<?php echo $width; ?>" class="width common">
  <div class="stitle wow fadeInup"  data-wow-duration="1s">
    <h2  class="titleM"><?php echo $sNum?></h2>
  </div>


	<div class="scon" style="padding-top:0px;">
	  <div class="content">


		<!-- 제품소개 갤러리 -->

		<!-- 게시판 카테고리 시작 { -->
    <?php if ($is_category) { ?>
    <nav id="bo_cate">
        <h2><?php echo $board['bo_subject'] ?> 카테고리</h2>
        <ul id="bo_cate_ul">
            <?php echo $category_option ?>
        </ul>
    </nav>
    <?php } ?>
		<!-- } 게시판 카테고리 끝 -->


    <form name="fboardlist" id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">

    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">

    <!-- 게시판 페이지 정보 및 버튼 시작 { -->
    <div id="bo_btn_top">
        <div id="bo_list_total">
            <span>Total <?php echo number_format($total_count) ?>건</span>
            <?php echo $page ?> 페이지
        </div>

        <ul class="btn_bo_user">
          <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin btn" title="관리자"><span class="view_btn">관리자</span></a></li><?php } ?>
            <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01 btn" title="RSS"><i class="fa fa-rss" aria-hidden="true"></i><span class="sound_only">RSS</span></a></li><?php } ?>
            <li>
              <button type="button" class="btn_bo_sch btn_b01 btn" title="게시판 검색"><span class="view_btn">게시판 검색</span></button>
            </li>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b01 btn" title="글쓰기"><span class="view_btn">글쓰기</span></a></li><?php } ?>
          <?php if ($is_admin == 'super' || $is_auth) {  ?>
          <li>
            <button type="button" class="btn_more_opt is_list_btn btn_b01 btn" title="게시판 리스트 옵션"><i class="fa fa-ellipsis-v" aria-hidden="true"></i><span class="sound_only">게시판 리스트 옵션</span></button>
            <?php if ($is_checkbox) { ?>
            <ul class="more_opt is_list_btn">
                <li><button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value"><i class="fa fa-trash-o" aria-hidden="true"></i> 선택삭제</button></li>
                <li><button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value"><i class="fa fa-files-o" aria-hidden="true"></i> 선택복사</button></li>
                <li><button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value"><i class="fa fa-arrows" aria-hidden="true"></i> 선택이동</button></li>
            </ul>
            <?php } ?>
          </li>
          <?php }  ?>
        </ul>
    </div>
    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

		<div class="prdt_list">
			<table>
				<tr class="prdt_top">
          <?php if ($is_checkbox) { ?>
          <th scope="col" class="all_chk chk_box">
          	<input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);" class="selec_chk">
              <label for="chkall">
              	<span></span>
              	<b class="sound_only">현재 페이지 게시물  전체선택</b>
      				</label>
          </th>
          <?php } ?>
          <th class="num">번호</th>
					<th class="pimg">제품사진</th>
					<th class="pname">제품명</th>
          <th class="psize">사이즈</th>
          <th class="pcolor">제품색상</th>
					<th class="pintro">제품소개</th>
				</tr>
        <?php
        for ($i=0; $i<count($list); $i++) {
        	if ($i%2==0) $lt_class = "even";
        	else $lt_class = "";
			//echo print_r($list[$i]['file'][0]);
			if($list[$i]['file'][0]){
				$img=$list[$i]['file'][0]['path']."/".$list[$i]['file'][0]['file'];
			}else{
				$img="";
			}
			$wr_7_arr=explode(",",$list[$i]['wr_7']);
		?>
				<tr class="prdt_table">
            <?php if ($is_checkbox) { ?>
            <td class="td_chk chk_box">
				<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="selec_chk">
            	<label for="chk_wr_id_<?php echo $i ?>">
            		<span></span>
            		<b class="sound_only"><?php echo $list[$i]['subject'] ?></b>
            	</label>
            </td>
            <?php } ?>
            <td class="num"><?php echo $list[$i]['num']; ?></td>
					<td class="prdt_pic_img"><a href="<?php echo $list[$i]['href'] ?>" class="prdt_pic"><?php
          if ($list[$i]['is_notice']) { // 공지사항  ?>
          <?php } else {
              $thumb = get_list_thumbnail($board['bo_table'], $list[$i]['wr_id'], $board['bo_gallery_width'], $board['bo_gallery_height'], false, true);

              if($thumb['src']) {
                  $img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'" >';
              } else {
                  $img_content = '<span class="no_image" style="'.$line_height_style.'">no image</span>';
              }

              echo run_replace('thumb_image_tag', $img_content, $thumb);
          }
           ?></a></td>
					<td class="pname"><a href="<?php echo $list[$i]['href'] ?>"><?php echo $list[$i]['subject'] ?></a></td>
          <td class="psize"><?php echo $list[$i]['wr_1']?$list[$i]['wr_1']:"-"; ?></td>
					<td class="pcolor"><?php echo $list[$i]['wr_5']?$list[$i]['wr_5']:"-"; ?></td>
					<td class="pintro"><a href="<?php echo $list[$i]['href'] ?>"><?php echo $list[$i]['wr_3']?$list[$i]['wr_3']:"-"; ?></a></td>
				</tr>
        <?php } ?>
        <?php if (count($list) == 0) { echo '<tr height=100><td colspan="'.$colspan.'" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>

			</table>
		</div>

		<!-- 페이지 -->
		<?php echo $write_pages; ?>
		<!-- 페이지 -->

	</form>

	  </div>
	</div>





    <script>
    jQuery(function($){
        // 게시판 검색
        $(".btn_bo_sch").on("click", function() {
            $(".bo_sch_wrap").toggle();
        })
        $('.bo_sch_bg, .bo_sch_cls').click(function(){
            $('.bo_sch_wrap').hide();
        });
    });
    </script>
    <!-- } 게시판 검색 끝 -->
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = g5_bbs_url+"/board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == "copy")
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = g5_bbs_url+"/move.php";
    f.submit();
}

// 게시판 리스트 관리자 옵션
jQuery(function($){
    $(".btn_more_opt.is_list_btn").on("click", function(e) {
        e.stopPropagation();
        $(".more_opt.is_list_btn").toggle();
    });
    $(document).on("click", function (e) {
        if(!$(e.target).closest('.is_list_btn').length) {
            $(".more_opt.is_list_btn").hide();
        }
    });
});
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
