<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

function get_paging2($write_pages, $cur_page, $total_page, $url, $add="")
{
	global $config;
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#(&amp;)?page=[0-9]*#', '', $url);
	$url .= substr($url, -1) === '?' ? 'page=' : '&amp;page=';

    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="'.$url.'1'.$add.'" class="page_start"><i class="fa-solid fa-angle-left"></i><i class="fa-solid fa-angle-left"></i></a>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1){
		$str .= '<a href="'.$url.($start_page-1).$add.'" class="page_prev"><i class="fa-solid fa-angle-left"></i></a>'.PHP_EOL;
	}

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="'.$url.$k.$add.'" class=""><span>'.$k.'</span><span class="sound_only">페이지</span></a>'.PHP_EOL;
            else
                $str .= '<span class="sound_only">열린</span><span class="pg_current">'.$k.'</span><span class="sound_only">페이지</span>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page){
		$str .= '<a href="'.$url.($end_page+1).$add.'" class="page_next"><i class="fa-solid fa-angle-right"></i></a>'.PHP_EOL;
	}

    if ($cur_page < $total_page) {
        $str .= '<a href="'.$url.$total_page.$add.'" class="page_end"><i class="fa-solid fa-angle-right"></i><i class="fa-solid fa-angle-right"></i></a>'.PHP_EOL;
    }



///////////////////////////////////////////////////////////////////////////
	$mstr = '';
	$write_pages_m=$config['cf_mobile_pages'];
    if ($cur_page > 1) {
        $mstr = '<a href="'.$url.'1'.$add.'" class="page_start"><i class="fa-solid fa-angle-left"></i><i class="fa-solid fa-angle-left"></i></a>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages_m ) ) * $write_pages_m ) + 1;
    $end_page = $start_page + $write_pages_m - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1){
		$mstr .= '<a href="'.$url.($start_page-1).$add.'" class="page_prev"><i class="fa-solid fa-angle-left"></i></a>'.PHP_EOL;
	}

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $mstr .= '<a href="'.$url.$k.$add.'" class=""><span>'.$k.'</span><span class="sound_only">페이지</span></a>'.PHP_EOL;
            else
                $mstr .= '<span class="sound_only">열린</span><span class="pg_current">'.$k.'</span><span class="sound_only">페이지</span>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page){
		$mstr .= '<a href="'.$url.($end_page+1).$add.'" class="page_next"><i class="fa-solid fa-angle-right"></i></a>'.PHP_EOL;
	}

    if ($cur_page < $total_page) {
        $mstr .= '<a href="'.$url.$total_page.$add.'" class="page_end"><i class="fa-solid fa-angle-right"></i><i class="fa-solid fa-angle-right"></i></a>'.PHP_EOL;
    }



    if ($str)
        return "<div class=\"page\">{$str}</div><div class=\"mpage\">{$mstr}</div>";
    else
        return "";
}
$write_pages = get_paging2(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, get_pretty_url($bo_table, '', $qstr.'&amp;page='));

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
//add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/wzappend.css">', 0); // wetoz
?>
<link rel="stylesheet" href="<?=$board_skin_url?>/style.css?ver=<?=time()?>">

<div id="bo_gall"  class="width common">
	<div class="stitle wow fadeInup"  data-wow-duration="1s">
    <h2  class="titleM"><?php echo $sNum?></h2>
  </div>

	<div class="you_big">
		<div class="embed-vimeo">
		<iframe width="100%" height="100%" src="https://player.vimeo.com/video/<?=$list[0]['wr_link1']?>" title="Vimeo video player" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
		</div>
	</div>

		<form name="fboardlist"  id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
		<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
		<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
		<input type="hidden" name="stx" value="<?php echo $stx ?>">
		<input type="hidden" name="spt" value="<?php echo $spt ?>">
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

			<?php if ($is_checkbox) { ?>
			<div id="gall_allchk" class="all_chk chk_box" style="margin-bottom:20px;">
					<input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);" class="selec_chk">
					<label for="chkall">
							<span></span>
							<b class="sound_only">현재 페이지 게시물 </b> 전체선택
					</label>
			</div>
			<?php } ?>


		<div class="youtube">
		<!-- 게시판 카테고리 시작 { -->
		<?php if ($is_category) { ?>
		<nav id="bo_cate" style="margin:20px 0px;">
				<h2><?php echo $board['bo_subject'] ?> 카테고리</h2>
				<ul id="bo_cate_ul">
						<?php echo $category_option ?>
				</ul>
		</nav>
		<?php } ?>
		<!-- } 게시판 카테고리 끝 -->

			<div class="you_wrap">
				<?php for ($i=0; $i<count($list); $i++) {

						$classes = array();

						$classes[] = 'gall_li';
						$classes[] = 'col-gn-'.$bo_gallery_cols;

						if( $i && ($i % $bo_gallery_cols == 0) ){
								$classes[] = 'box_clear';
						}

						if( $wr_id && $wr_id == $list[$i]['wr_id'] ){
								$classes[] = 'gall_now';
						}

						$line_height_style = ($board['bo_gallery_height'] > 0) ? 'line-height:'.$board['bo_gallery_height'].'px' : '';
				 ?>
				<div class="you_box" data-value="<?=$list[$i]['wr_link1']?>">
					<div class="you_thum">
						<img src="https://vumbnail.com/<?=$list[$i]['wr_link1']?>.jpg" alt="">
						<span class="you_thum_bg"><img src="<?php echo G5_THEME_URL ?>/img/you_thum_bg.png" alt=""></span>
						<div class="playbtn_wrap"><div class="playbtn"><i class="fa fa-play" aria-hidden="true"></i></div></div>
						<div class="gall_chk chk_box">
								<?php if ($is_checkbox) { ?>
								<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="selec_chk">
								<label for="chk_wr_id_<?php echo $i ?>">
										<span></span>
										<b class="sound_only"><?php echo $list[$i]['subject'] ?></b>
								</label>

								<?php } ?>
								<span class="sound_only">
										<?php
										if ($wr_id == $list[$i]['wr_id'])
												echo "<span class=\"bo_current\">열람중</span>";
										else
												echo $list[$i]['num'];
										 ?>
								</span>
						</div>
					</div>
					<div class="you_text_wrap">
						<h2 class="s4 you_title">
						<?php if($is_admin){?>
						<a href="<?=$list[$i]['href']?>">
						<?php }else{?>
						<?php }?>
						<?=$list[$i]['wr_subject']?>
						</a>
						</h2>
						<div class="you_text2">
							<p><?=strip_tags($list[$i]['wr_content'])?></p>
						</div>
						<div class="you_text3">

							<a href="javascript:void(0)" class="vimeo_view">간편보기</a>
							<a href="<?php echo $list[$i]['href'] ?>" class="vimeo_view">게시글 보기</a>
							<a href="https://vimeo.com/<?=$list[$i]['wr_link1']?>" target=_blank>비메오</a>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if (count($list) == 0) { echo "<li class=\"empty_list\">게시물이 없습니다.</li>"; } ?>

		<?php echo $write_pages; ?>

			</form>

			</div>
		</div>


</div>


<script>
$(function(){

	$(".you_box .you_thum,.you_box .vimeo_view").on("click", function(){
		vimeo_code=$(this).closest(".you_box").data("value");
		console.log("https://player.vimeo.com/video/"+vimeo_code);
		$(".you_big iframe").attr("src","https://player.vimeo.com/video/"+vimeo_code+"?autoplay=1");

	});
});
</script>

<script>
		// 게시판 검색
		$(".btn_bo_sch").on("click", function() {
				$(".bo_sch_wrap").toggle();
		})
		$('.bo_sch_bg, .bo_sch_cls').click(function(){
				$('.bo_sch_wrap').hide();
		});
</script>


<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>



<!-- 페이지 -->
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
        f.action = "./board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == 'copy')
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
