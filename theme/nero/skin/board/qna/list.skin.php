
<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


$tNum="News & Notice";
	$sNum="Notice";
    $bNum="4";


// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;

if ($is_checkbox) $colspan++;
if ($is_good) $colspan++;
if ($is_nogood) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<div class="qna common">
    <div class="width">
        <h2 class="contents_title">Q&A</h2>

        <div class="board_top_info">
            <div class="total">TOTAL <span><?php echo number_format($total_count) ?></span></div>
            <div class="input_wrap">
				<form name="fsearch" method="get">
				<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
				<input type="hidden" name="sca" value="<?php echo $sca ?>">
				<input type="hidden" name="sop" value="and">
				<label for="sfl" class="sound_only">검색대상</label>
				<select name="sfl" id="sfl">
					<?php echo get_board_sfl_select_options($sfl); ?>
				</select>
				<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
				<div class="sch_bar">
					<input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="sch_input" size="25" maxlength="20" placeholder=" 검색어를 입력해주세요">
					<button type="submit" value="검색" class="sch_btn">
						<img src="<?php echo G5_THEME_IMG_URL ?>/main_icon5.png" alt="" ><span class="sound_only">검색</span></button>
				</div>
				</form>

				<?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="write_btn">글쓰기</a><?php } ?>
            </div>
        </div>

        <table>
            <tr>
				<?php if ($is_checkbox) { ?>
				<th scope="col" class="all_chk chk_box"style="width:2%;">
					<input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);" class="selec_chk">
					<label for="chkall">
						<span></span>
						<b class="sound_only">현재 페이지 게시물  전체선택</b>
					</label>
				</th>
				<?php } ?>
                <th style="width:6%;">No.</th>
                <th style="width:50%;">제목</th>
                <th style="width:7%;">파일</th>
                <th style="width:10%;">작성자</th>
                <th style="width:15%;" class="qa_date">등록일</th>
                <th style="width:8%;"class="qa_hit">조회수</th>
            </tr>
        <?php
        for ($i=0; $i<count($list); $i++) {
        	if ($i%2==0) $lt_class = "even";
        	else $lt_class = "";
			if($list[$i]['icon_reply']){
				$ca_name='<img src="<?php echo G5_THEME_IMG_URL ?>/qnare.png" alt="">';
			}else{
				switch($list[$i]['ca_name']){
					case "회원가입 및 자격":
						$ca_name='<span class="q_login">회원가입 및 자격</span>';
						break;
					case "진료 및 상담":
						$ca_name='<span class="q_course">진료 및 상담</span>';
						break;
					case "학술대회 및 집담회":
						$ca_name='<span class="q_conferen">학술대회 및 집담회</span>';
						break;
					case "기타 문의":
						$ca_name='<span class="q_etc">기타 문의</span>';
						break;
				}
			}
			if($list[$i]['icon_secret']){
				if (($list[$i]['mb_id'] && $list[$i]['mb_id'] === $member['mb_id']) || $is_admin) {
				}else{
					$secret="secret";
				}
			}else{
				$secret="";
			}
			if(strlen($list[$i]['wr_reply'])>0){
				$sql="select * from {$write_table} where wr_num='{$list[$i]['wr_num']}' and wr_reply='' and wr_id=wr_parent";
				$tmp_row=sql_fetch($sql);
				if (strstr($tmp_row['wr_option'], 'secret')){
					$list[$i]['subject']="비밀글입니다.";
				}
			}else{
				if (isset($list[$i]['icon_secret']) && $list[$i]['icon_secret']!=""){
					$list[$i]['subject']="비밀글입니다.";
				}
			}
		?>
            <tr>
				<?php if ($is_checkbox) { ?>
				<td class="td_chk chk_box" style="width:2%;">
					<input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="selec_chk">
					<label for="chk_wr_id_<?php echo $i ?>">
						<span></span>
						<b class="sound_only"><?php echo $list[$i]['subject'] ?></b>
					</label>
				</td>
				<?php } ?>
                <td style="width:6%;"><?=$list[$i]['num']?></td>
                <td class="subject" style="width:50%;">
                    <a href="<?php echo $list[$i]['href'] ?>" class="<?=$secret?>" data-value="<?php echo $list[$i]['wr_id'] ?>">
						<?php if(strlen($list[$i]['wr_reply'])>0){?><img src="<?=G5_THEME_IMG_URL?>/qnare.png"><?php }?>
                        <?php
                            if (isset($list[$i]['icon_secret'])) echo rtrim($list[$i]['icon_secret']);
                         ?>
                        <?=$ca_name?><p><?php echo $list[$i]['subject'] ?></p>
                    </a>

                </td>
                <td style="width:7%;"><?php echo $list[$i]['icon_secret']?"":$list[$i]['icon_file']; ?><!--<img src="<?php echo G5_THEME_IMG_URL ?>/file.png" alt="">--></td>
                <td style="width:10%;"><?php echo $list[$i]['icon_secret']?"":$list[$i]['name']; ?></td>
                <td style="width:15%;" class="qa_date"><?php echo $list[$i]['icon_secret']?"":$list[$i]['datetime']; ?></td>
                <td style="width:8%;"class="qa_hit"><?php echo $list[$i]['icon_secret']?"":$list[$i]['wr_hit']; ?></td>
            </tr>
		<?php
		}
		?>
        </table>

        <!-- 페이징 -->
		<?php echo get_paging_new(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, get_pretty_url($bo_table, '', $qstr.'&amp;page='));?>

        <!-- //페이징 -->

<!-- 비밀글 팝업 -->
  
  <!-- <div id="overlay"></div>


  <div id="popup">
  <form name="passform" id="passform">
  <input type="hidden" name="bo_table" value="<?=$bo_table?>">
  <input type="hidden" name="wr_id" id="confirm_wr_id">
    <h3>회원정보 입력</h3>
    <div class="popup_inner">
		<div class="pop_title">
			<img src="<?php echo G5_THEME_IMG_URL ?>/mem_icon1.png" alt="">
			<div>
				<h5>비밀번호를 입력해주세요.</h5>
			</div>
		 </div>
		 <div class="pop_input_wrap">
			
			<input type="password" name="wr_password" id="wr_password" placeholder="비밀번호를 입력해주세요." required>
		 </div>
		 <div class="txt">
			<p>비밀번호 분실시 고객센터로 문의 주세요</p>
			<h6>T. 02-2273-3875</h6>
		 </div>
		 <div class="pop_btn_wrap">
			<div class="my_pop_close" onclick="closePopup();">취소</div>
			<a href="javascript:void(0)" class="my_pop_ok" onclick="verifyMember();">확인</a>
		 </div>
     </div>
  </div>

  <script>
    function openPopup() {
      document.getElementById("overlay").style.display = "block";
      document.getElementById("popup").style.display = "block";
      document.body.classList.add("modal-open");
    }

    function closePopup() {
      document.getElementById("overlay").style.display = "none";
      document.getElementById("popup").style.display = "none";
      document.body.classList.remove("modal-open");
    }
  </script> -->

<!-- //비밀글 팝업 -->

    </div>
</div>

<script>
$(function(){
	$("a.secret").on("click", function(){
		wr_id=$(this).data("value");
		$.ajax({
			type:"post",
			url:"/bbs/ajax.secret_check.php",
			data:"bo_table=<?=$bo_table?>&wr_id="+wr_id,
			dataType:"json",
			success:function(res){
				if(res.is_password){
					$("#confirm_wr_id").val(wr_id);
					openPopup();
					return false;
				}else{
					//console.log("move");
					
				}
			},
		});

	});
});
function verifyMember(){
	wr_id=document.passform.wr_id.value;
	$.ajax({
		type:"post",
		url:"<?=G5_BBS_URL?>/ajax.check_password.php",
		data:"bo_table=<?=$bo_table?>&wr_id="+wr_id+"&wr_password="+$("#wr_password").val(),
		dataType:"json",
		success:function(res){
			if(res.code=="0000"){
				location.href="/bbs/board.php?bo_table=<?=$bo_table?>&wr_id="+wr_id;
			}else{
				alert(res.msg);
			}
		},
		
	});
}
</script>

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
