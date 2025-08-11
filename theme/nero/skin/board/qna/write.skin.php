<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>
<style>
.cke_sc{display:none;}
</style>
<section id="bo_w" class="width common">

<div class="qna_write common">
    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">

    <div class="width">
       <h2 class="contents_title">QNA 내용 작성 및 수정</h2>
       <form action="">
        <ul style="<?=$w=="r"?"display:none;":"";?>">
            <li>
                <label for=""><span class="required_mark">*</span>카테고리</label>
                 <div class="input_inner2">
                    <div class="items">
                        <div class="item">
                            <input type="radio" id="radio1" name="ca_name" value="회원가입 및 자격" required <?=$write['ca_name']=="회원가입 및 자격"?"checked":"";?>>
                            <label for="radio1" class="margin">회원가입 및 자격</label>
                        </div>

                        <div class="item">
                            <input type="radio" id="radio2" name="ca_name" value="진료 및 상담" required <?=$write['ca_name']=="진료 및 상담"?"checked":"";?>>
                            <label for="radio2"class="margin">진료 및 상담</label>
                        </div>

                        <div class="item">
                            <input type="radio" id="radio3" name="ca_name" value="학술대회 및 집담회" required <?=$write['ca_name']=="학술대회 및 집담회"?"checked":"";?>>
                            <label for="radio3"class="margin">학술대회 및 집담회</label>
                        </div>
                        
                        <div class="item">
                            <input type="radio" id="radio4" name="ca_name" value="기타 문의" required <?=$write['ca_name']=="기타 문의"?"checked":"";?>>
                            <label for="radio4"class="margin2">기타 문의</label>
                        </div>
                    </div>

                    <!--<input type="checkbox" id="secret" name="secret" value="" >
                    <label for="secret"class="">비밀글</label>-->
    <?php
    $option = '';
    $option_hidden = '';
    if ($is_notice || $is_html || $is_secret || $is_mail) {
        $option = '';
        if ($is_notice) {
            $option .= PHP_EOL.'<input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'>'.PHP_EOL.'<label for="notice"><span></span>공지</label>';
        }
        if ($is_html) {
            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" value="html1" name="html">';
            } else {
                $option .= PHP_EOL.'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'.PHP_EOL.'<label for="html"><span></span>html</label>';
            }
        }
        if ($is_secret) {
            if ($is_admin || $is_secret==1) {
                $option .= PHP_EOL.'<input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'>'.PHP_EOL.'<label for="secret"><span></span>비밀글</label>';
            } else {
                $option_hidden .= '<input type="hidden" name="secret" value="secret">';
            }
        }
        if ($is_mail) {
            $option .= PHP_EOL.'<input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.'>'.PHP_EOL.'<label for="mail"><span></span>답변메일받기</label>';
        }
    }
    echo $option_hidden;
    echo $option;
    ?>

                </div>
            </li>
			<?php if($is_name){?>
            <li>
                <label for="">성명</label>
                <div class="input_inner">
                    <input type="text" name="wr_name" id="wr_name" class="frm_input full_input" maxlength="50">
                </div>
            </li>
			<?php }?>
            <li>
                <label for="">휴대전화</label>
                <div class="input_inner number_input">
                    <select name="hp[]" id="mobile_carrier">
							<option value="">선택</option>
							<option value="010">010</option>
							<option value="011">011</option>
							<option value="016">016</option>
							<option value="017">017</option>
							<option value="018">018</option>
							<option value="019">019</option>
						</select>
						<span>-</span>
						<input type="text" name="hp[]" maxlength="4">
						<span>-</span>
						<input type="text" name="hp[]" maxlength="4">
                </div>
            </li>
            <li>
                <label for="">이메일</label>
                <div class="input_inner">
                    <input type="text" name="wr_email" id="wr_email" class="frm_input full_input" maxlength="50"><span class="required_mark">*본인 확인 및 수정에 이용 됩니다.</span>
                </div>
            </li>
			<?php if($is_password){?>
            <li>
                <label for="">비밀번호</label>
                <div class="input_inner">
                    <input type="text" name="wr_password" id="wr_password" class="frm_input full_input" maxlength="50"><span class="required_mark">*본인 확인 및 수정에 이용 됩니다.</span>
                </div>
            </li>
			<?php }?>
        </ul>

        <ul>
            <li>
                <label for="">제목</label>
                <div class="input_inner">
                    <input type="text" name="wr_subject" id="wr_subject" class="frm_input full_input" maxlength="50" required>
                </div>
            </li>
             <li>
                <label for="">내용</label>
                <div class="input_inner">
                    <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                </div>
            </li>
        </ul>

        <ul>
            <li class="stretch">
                <label for="">파일찾기</label>
<div class="input_inner3">
  <!-- 파일 업로드 영역들 생성될 곳 -->
  <div id="fileContainer">
    <div class="file_upload">
      <label for="fileInput_1" class="custom_file_label">파일 선택</label>
      <span class="file_name">선택된 파일이 없습니다</span>
      <input type="file" name="bf_file[]" id="fileInput_1" accept=".jpg,.jpeg,.png,.pdf">
    </div>
  </div>

  <!-- 파일 추가 버튼 -->
  <button type="button" onclick="addFileInput()" class="file_plus">파일 추가</button>

  <div><small>5MB 이하의 jpg, png, PDF 파일 최대 3개까지 업로드 가능합니다.</small></div>
</div>

<script>
  let fileCount = 1; // 이미 1개 있음
  const maxFiles = 3;
  const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
  const maxSize = 5 * 1024 * 1024; // 5MB

  function addFileInput() {
    if (fileCount >= maxFiles) {
      alert("최대 3개의 파일까지만 업로드할 수 있습니다.");
      return;
    }

    fileCount++;

    const fileContainer = document.getElementById("fileContainer");

    // 요소 생성
    const uploadDiv = document.createElement("div");
    uploadDiv.className = "file_upload";

    const label = document.createElement("label");
    const inputId = `fileInput_${fileCount}`;
    label.setAttribute("for", inputId);
    label.className = "custom_file_label";
    label.textContent = "파일 선택";

    const fileNameSpan = document.createElement("span");
    fileNameSpan.className = "file_name";
    fileNameSpan.textContent = "선택된 파일이 없습니다";

    const input = document.createElement("input");
    input.type = "file";
    input.id = inputId;
	input.name = "bf_file[]";
    input.accept = ".jpg,.jpeg,.png,.pdf";

    input.addEventListener("change", function () {
      const file = this.files[0];
      if (!file) {
        fileNameSpan.textContent = "선택된 파일이 없습니다";
        return;
      }

      if (!allowedTypes.includes(file.type)) {
        alert("jpg, png, pdf 형식만 업로드할 수 있습니다.");
        this.value = "";
        fileNameSpan.textContent = "선택된 파일이 없습니다";
        return;
      }

      if (file.size > maxSize) {
        alert("파일 크기는 5MB를 초과할 수 없습니다.");
        this.value = "";
        fileNameSpan.textContent = "선택된 파일이 없습니다";
        return;
      }

      fileNameSpan.textContent = file.name;
    });

    uploadDiv.appendChild(label);
    uploadDiv.appendChild(fileNameSpan);
    uploadDiv.appendChild(input);

    fileContainer.appendChild(uploadDiv);
  }

  // 초기 첫 번째 input에 대한 이벤트 연결
  const firstInput = document.getElementById("fileInput_1");
  const firstFileName = document.querySelector(".file_name");

  firstInput.addEventListener("change", function () {
    const file = this.files[0];
    if (!file) {
      firstFileName.textContent = "선택된 파일이 없습니다";
      return;
    }

    if (!allowedTypes.includes(file.type)) {
      alert("jpg, png, pdf 형식만 업로드할 수 있습니다.");
      this.value = "";
      firstFileName.textContent = "선택된 파일이 없습니다";
      return;
    }

    if (file.size > maxSize) {
      alert("파일 크기는 5MB를 초과할 수 없습니다.");
      this.value = "";
      firstFileName.textContent = "선택된 파일이 없습니다";
      return;
    }

    firstFileName.textContent = file.name;
  });
</script>

            </li>
        </ul>
       </form>

       <div class="btn_wrap">
        <a href="<?php echo get_pretty_url($bo_table); ?>" class="btn_style1">목록보기</a>

        <div>
           <a href="<?php echo get_pretty_url($bo_table); ?>" class="btn_style2">취소</a>
           <button type="submit" id="btn_submit" class="list_btn ">저장하기</button> 
        </div>
       </div>
       </div>


    </div>
	</form>
</div>























    <script>
    $(function(){
    	$(".hidden_file").on("change", function(){
    		$(this).parent().parent().find(".file_name").val($(this).val());
    	});
    });
    <?php if($write_min || $write_max) { ?>
    // 글자수 제한
    var char_min = parseInt(<?php echo $write_min; ?>); // 최소
    var char_max = parseInt(<?php echo $write_max; ?>); // 최대
    check_byte("wr_content", "char_count");

    $(function() {
        $("#wr_content").on("keyup", function() {
            check_byte("wr_content", "char_count");
        });
    });

    <?php } ?>
    function html_auto_br(obj)
    {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        }
        else
            obj.value = "";
    }

    function fwrite_submit(f)
    {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }

        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                    return false;
                }
                else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }

        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
<!-- } 게시물 작성/수정 끝 -->
