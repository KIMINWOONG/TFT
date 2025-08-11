<?php
include "../../../../common.php";

$tNum="회원마당";
$sNum="회원 QNA";
$bNum="303";
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


include_once(G5_THEME_PATH.'/head.php');
?>


<div class="qna_write common">
    <div class="width">
       <h2 class="contents_title">QNA 내용 작성 및 수정</h2>
       <form action="">
        <ul>
            <li>
                <label for=""><span class="required_mark">*</span>카테고리</label>
                 <div class="input_inner2">
                    <div class="items">
                        <div class="item">
                            <input type="radio" id="radio1" name="category" value="회원가입 및 자격">
                            <label for="radio1" class="margin">회원가입 및 자격</label>
                        </div>

                        <div class="item">
                            <input type="radio" id="radio2" name="category" value="진료 및 상담">
                            <label for="radio2"class="margin">진료 및 상담</label>
                        </div>

                        <div class="item">
                            <input type="radio" id="radio3" name="category" value="학술대회 및 집담회">
                            <label for="radio3"class="margin">학술대회 및 집담회</label>
                        </div>
                        
                        <div class="item">
                            <input type="radio" id="radio4" name="category" value="기타 문의">
                            <label for="radio4"class="margin2">기타 문의</label>
                        </div>
                    </div>

                    <input type="checkbox" id="secret" name="secret" value="" >
                    <label for="secret"class="">비밀글</label>
                </div>
            </li>
            <li>
                <label for="">성명</label>
                <div class="input_inner">
                    <input type="text" name="" id="" class="frm_input full_input" maxlength="50">
                </div>
            </li>
            <li>
                <label for="">휴대전화</label>
                <div class="input_inner number_input">
                    <select name="mobile_carrier" id="mobile_carrier">
							<option value="">선택</option>
							<option value="010">010</option>
							<option value="011">011</option>
							<option value="016">016</option>
							<option value="017">017</option>
							<option value="018">018</option>
							<option value="019">019</option>
						</select>
						<span>-</span>
						<input type="text" name="mobile1" maxlength="4">
						<span>-</span>
						<input type="text" name="mobile2" maxlength="4">
                </div>
            </li>
            <li>
                <label for="">이메일</label>
                <div class="input_inner">
                    <input type="text" name="" id="" class="frm_input full_input" maxlength="50"><span class="required_mark">*본인 확인 및 수정에 이용 됩니다.</span>
                </div>
            </li>
            <li>
                <label for="">비밀번호</label>
                <div class="input_inner">
                    <input type="text" name="" id="" class="frm_input full_input" maxlength="50"><span class="required_mark">*본인 확인 및 수정에 이용 됩니다.</span>
                </div>
            </li>
        </ul>

        <ul>
            <li>
                <label for="">제목</label>
                <div class="input_inner">
                    <input type="text" name="" id="" class="frm_input full_input" maxlength="50">
                </div>
            </li>
             <li>
                <label for="">내용</label>
                <div class="input_inner">
                    <!-- Summernote 자리 -->
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
      <input type="file" id="fileInput_1" accept=".jpg,.jpeg,.png,.pdf">
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
        <a href="<?php echo G5_THEME_URL ?>/subpage/member/03.php" class="btn_style1">목록보기</a>

        <div>
           <a href="<?php echo G5_THEME_URL ?>/subpage/member/03.php" class="btn_style2">취소</a>
           <button class="list_btn">저장하기</button> 
        </div>
       </div>


    </div>
</div>


<?php
include_once(G5_THEME_PATH.'/tail.php');
