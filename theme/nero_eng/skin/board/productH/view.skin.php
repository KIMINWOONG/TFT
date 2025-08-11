<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<!-- 게시물 읽기 시작 { -->

<article id="bo_v" style="width:<?php echo $width; ?>"  class="width common">


			<div class="bo_v_topcon">
		    <?php if ($category_name) { ?>
		    <span class="bo_v_cate"><?php echo $view['ca_name']; // 분류 출력 끝 ?></span>
		    <?php } ?>
		    <ul class="btn_bo_user bo_v_com">
		  <li><a href="<?php echo $list_href ?>" class="btn_b01 btn" title="목록"><span class="view_btn">목록</span></a></li>
		        <?php if ($reply_href) { ?><li><a href="<?php echo $reply_href ?>" class="btn_b01 btn" title="답변"><span class="view_btn">답변</span></a></li><?php } ?>
		        <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b01 btn" title="글쓰기"><span class="view_btn">글쓰기</span></a></li><?php } ?>
		        <?php if ($update_href) { ?><li><a href="<?php echo $update_href ?>" class="btn_b01 btn"><span class="view_btn">수정</span></a></li><?php } ?>
		        <?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" class="btn_b01 btn" onclick="del(this.href); return false;"><span class="view_btn">삭제</span></a></li><?php } ?>
		      <?php if($update_href || $delete_href || $copy_href || $move_href || $search_href) { ?>
		      <!-- <li>
		        <button type="button" class="btn_more_opt is_view_btn btn_b01 btn"><i class="fa fa-ellipsis-v" aria-hidden="true"></i><span class="sound_only">게시판 리스트 옵션</span></button>
		        <ul class="more_opt is_view_btn">
		            <?php if ($copy_href) { ?><li><a href="<?php echo $copy_href ?>" onclick="board_move(this.href); return false;">복사<i class="fa fa-files-o" aria-hidden="true"></i></a></li><?php } ?>
		            <?php if ($move_href) { ?><li><a href="<?php echo $move_href ?>" onclick="board_move(this.href); return false;">이동<i class="fa fa-arrows" aria-hidden="true"></i></a></li><?php } ?>
		            <?php if ($search_href) { ?><li><a href="<?php echo $search_href ?>">검색<i class="fa fa-search" aria-hidden="true"></i></a></li><?php } ?>
		        </ul>
		      </li> -->
		      <?php } ?>
		    </ul>
		  </div>
		<!-- 제품소개 갤러리 -->

		<div class="prdt_top">
			<div class="prdt_thum_slide">
				<div class="swiper prdt_Swiper2">
					<div class="prdt_thum_wrap swiper-wrapper">
						<?php
						// 가변 파일
						for ($i=0; $i<count($view['file']); $i++) {
								if (isset($view['file'][$i]['source']) && $view['file'][$i]['source']) {
						 ?>
										<p class="prdt_big swiper-slide"<?=$i==0?'class="on"':'';?>><img src="<?=$view['file'][$i]['path']."/".$view['file'][$i]['file']?>">

										</p>
						<?php
								}
						}
						 ?>
					</div>
					<div class="swiper_arrow">
					<div class="swiper-button-prev2"><i class="fa-solid fa-angle-left"></i></div>
					<div class="swiper-button-next2"><i class="fa-solid fa-angle-right"></i></div>
				 </div>
				</div>
				<div class="swiper prdt_Swiper">
					<div class="prdt_small swiper-wrapper">
	        <?php
	        // 가변 파일
	        for ($i=0; $i<count($view['file']); $i++) {
	            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source']) {
	         ?>
	                <a class="swiper-slide"<?=$i==0?'class="on"':'';?>><img src="<?=$view['file'][$i]['path']."/".$view['file'][$i]['file']?>"></a>
	        <?php
	            }
	        }
	         ?>
				 </div>
				</div>
			</div>
			<div class="prdt_info_wrap">
				<ul class="prdt_info">
					<h2 id="bo_v_title">
							<span class="bo_v_tit">
							<?php
							echo cut_str(get_text($view['wr_subject']), 70); // 글제목 출력
							?></span>
					</h2>
					<li>
						<p>사이즈</p>
						<span><?php echo $view['wr_1']?$view['wr_1']:"-"; ?></span>
					</li>
					<li>
						<p>제품특징</p>
						<span><?php echo $view['wr_2']?$view['wr_2']:"-"; ?></span>
					</li>
					<li>
						<p>제품소개</p>
						<span><?php echo $view['wr_3']?$view['wr_3']:"-"; ?></span>
					</li>
					<li>
						<p>제품기능</p>
						<span><?php echo $view['wr_4']?$view['wr_4']:"-"; ?></span>
					</li>
					<li>
						<p>제품색상</p>
						<span><?php echo $view['wr_5']?$view['wr_5']:"-"; ?></span>
					</li>
				</ul>
				<ul class="prdt_contact">
					<li>
						<a href="#" target="_blank"><i class="xi-kakaotalk"></i>카카오톡 문의</a>
						<a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=qna" ><i class="xi-comment"></i>1:1 문의</a>
					</li>
				</ul>
			</div>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
		<script>
    var swiper = new Swiper(".prdt_Swiper", {
      loop: true,
      spaceBetween: 10,
      slidesPerView: 4,
      freeMode: true,
      watchSlidesProgress: true,
    });
    var swiper2 = new Swiper(".prdt_Swiper2", {
      loop: true,
      spaceBetween: 10,
			navigation: {
			  nextEl: '.swiper-button-next2',
			  prevEl: '.swiper-button-prev2',
			},
      thumbs: {
        swiper: swiper,
      },
    });
  </script>

		<section id="bo_v_atc" class="prdt_detail">
				<h2>제품상세</h2>



				<!-- 본문 내용 시작 { -->
				<div id="bo_v_con"><?php echo get_view_thumbnail($view['content']); ?></div>
				<?php //echo $view['rich_content']; // {이미지:0} 과 같은 코드를 사용할 경우 ?>
				<!-- } 본문 내용 끝 -->

				<?php if ($is_signature) { ?><p><?php echo $signature ?></p><?php } ?>


				<!--  추천 비추천 시작 { -->
				<?php if ( $good_href || $nogood_href) { ?>
				<div id="bo_v_act">
						<?php if ($good_href) { ?>
						<span class="bo_v_act_gng">
								<a href="<?php echo $good_href.'&amp;'.$qstr ?>" id="good_button" class="bo_v_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span class="sound_only">추천</span><strong><?php echo number_format($view['wr_good']) ?></strong></a>
								<b id="bo_v_act_good"></b>
						</span>
						<?php } ?>
						<?php if ($nogood_href) { ?>
						<span class="bo_v_act_gng">
								<a href="<?php echo $nogood_href.'&amp;'.$qstr ?>" id="nogood_button" class="bo_v_nogood"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i><span class="sound_only">비추천</span><strong><?php echo number_format($view['wr_nogood']) ?></strong></a>
								<b id="bo_v_act_nogood"></b>
						</span>
						<?php } ?>
				</div>
				<?php } else {
						if($board['bo_use_good'] || $board['bo_use_nogood']) {
				?>
				<div id="bo_v_act">
						<?php if($board['bo_use_good']) { ?><span class="bo_v_good"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i><span class="sound_only">추천</span><strong><?php echo number_format($view['wr_good']) ?></strong></span><?php } ?>
						<?php if($board['bo_use_nogood']) { ?><span class="bo_v_nogood"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i><span class="sound_only">비추천</span><strong><?php echo number_format($view['wr_nogood']) ?></strong></span><?php } ?>
				</div>
				<?php
						}
				}
				?>
				<!-- }  추천 비추천 끝 -->
		</section>

		<div class="bo_v_file_wrap">
      <?php
      $cnt = 0;
      if ($view['file']['count']) {
          for ($i=0; $i<count($view['file']); $i++) {
              if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
                  $cnt++;
          }
      }
  	?>

      <?php if($cnt) { ?>
      <!-- 첨부파일 시작 { -->
      <section id="bo_v_file">
          <h2>첨부파일</h2>
          <ul>
          <?php
          // 가변 파일
          for ($i=0; $i<count($view['file']); $i++) {
              if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
           ?>
              <li>
                 	<i class="fa fa-folder-open" aria-hidden="true"></i>
                  <a href="<?php echo $view['file'][$i]['href'];  ?>" class="view_file_download">
                      <strong><?php echo $view['file'][$i]['source'] ?></strong> <?php echo $view['file'][$i]['content'] ?> (<?php echo $view['file'][$i]['size'] ?>)
                  </a>
                  <br>
                  <!-- <span class="bo_v_file_cnt"><?php echo $view['file'][$i]['download'] ?>회 다운로드 | DATE : <?php echo $view['file'][$i]['datetime'] ?></span> -->
              </li>
          <?php
              }
          }
           ?>
          </ul>
      </section>
      <!-- } 첨부파일 끝 -->
      <?php } ?>

      <?php if(isset($view['link']) && array_filter($view['link'])) { ?>
      <!-- 관련링크 시작 { -->
      <section id="bo_v_link">
          <h2>관련링크</h2>
          <ul>
          <?php
          // 링크
          $cnt = 0;
          for ($i=1; $i<=count($view['link']); $i++) {
              if ($view['link'][$i]) {
                  $cnt++;
                  $link = cut_str($view['link'][$i], 70);
              ?>
              <li>
                  <i class="fa fa-link" aria-hidden="true"></i>
                  <a href="<?php echo $view['link_href'][$i] ?>" target="_blank">
                      <strong><?php echo $link ?></strong>
                  </a>
                  <br>
                  <!-- <span class="bo_v_link_cnt"><?php echo $view['link_hit'][$i] ?>회 연결</span> -->
              </li>
              <?php
              }
          }
          ?>
          </ul>
      </section>
      <!-- } 관련링크 끝 -->
      <?php } ?>
    </div>

		<div class="btn_list">
			<a href="<?php echo $list_href ?>">목록</a>
		</div>
</article>
<!-- } 게시판 읽기 끝 -->

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if(!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }

        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

        if(confirm(msg)) {
            var href = $(this).attr("href")+"&js=on";
            $(this).attr("href", href);

            return true;
        } else {
            return false;
        }
    });
});
<?php } ?>

function board_move(href)
{
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 추천, 비추천
    $("#good_button, #nogood_button").click(function() {
        var $tx;
        if(this.id == "good_button")
            $tx = $("#bo_v_act_good");
        else
            $tx = $("#bo_v_act_nogood");

        excute_good(this.href, $(this), $tx);
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();
});

function excute_good(href, $el, $tx)
{
    $.post(
        href,
        { js: "on" },
        function(data) {
            if(data.error) {
                alert(data.error);
                return false;
            }

            if(data.count) {
                $el.find("strong").text(number_format(String(data.count)));
                if($tx.attr("id").search("nogood") > -1) {
                    $tx.text("이 글을 비추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                } else {
                    $tx.text("이 글을 추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                }
            }
        }, "json"
    );
}
</script>
<!-- } 게시글 읽기 끝 -->
